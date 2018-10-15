/*
 *  emulator main execution loop
 *
 *  Copyright (c) 2003-2005 Fabrice Bellard
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, see <http://www.gnu.org/licenses/>.
 */
#include "qemu/osdep.h"
#include "cpu.h"
#include "trace.h"
#include "disas/disas.h"
#include "exec/exec-all.h"
#include "tcg.h"
#include "qemu/atomic.h"
#include "sysemu/qtest.h"
#include "qemu/timer.h"
#include "exec/address-spaces.h"
#include "qemu/rcu.h"
#include "exec/tb-hash.h"
#include "exec/log.h"
#include "qemu/main-loop.h"
#if defined(TARGET_I386) && !defined(CONFIG_USER_ONLY)
#include "hw/i386/apic.h"
#endif
#include "sysemu/cpus.h"
#include "sysemu/replay.h"

extern int afl_wants_cpu_to_stop; //zyw

//#include "../../afl-qemu-cpu-inl.h"

/* -icount align implementation. */

typedef struct SyncClocks {
    int64_t diff_clk;
    int64_t last_cpu_icount;
    int64_t realtime_clock;
} SyncClocks;

#if !defined(CONFIG_USER_ONLY)
/* Allow the guest to have a max 3ms advance.
 * The difference between the 2 clocks could therefore
 * oscillate around 0.
 */
#define VM_CLOCK_ADVANCE 3000000
#define THRESHOLD_REDUCE 1.5
#define MAX_DELAY_PRINT_RATE 2000000000LL
#define MAX_NB_PRINTS 100

static void align_clocks(SyncClocks *sc, const CPUState *cpu)
{
    int64_t cpu_icount;

    if (!icount_align_option) {
        return;
    }

    cpu_icount = cpu->icount_extra + cpu->icount_decr.u16.low;
    sc->diff_clk += cpu_icount_to_ns(sc->last_cpu_icount - cpu_icount);
    sc->last_cpu_icount = cpu_icount;

    if (sc->diff_clk > VM_CLOCK_ADVANCE) {
#ifndef _WIN32
        struct timespec sleep_delay, rem_delay;
        sleep_delay.tv_sec = sc->diff_clk / 1000000000LL;
        sleep_delay.tv_nsec = sc->diff_clk % 1000000000LL;
        if (nanosleep(&sleep_delay, &rem_delay) < 0) {
            sc->diff_clk = rem_delay.tv_sec * 1000000000LL + rem_delay.tv_nsec;
        } else {
            sc->diff_clk = 0;
        }
#else
        Sleep(sc->diff_clk / SCALE_MS);
        sc->diff_clk = 0;
#endif
    }
}

static void print_delay(const SyncClocks *sc)
{
    static float threshold_delay;
    static int64_t last_realtime_clock;
    static int nb_prints;

    if (icount_align_option &&
        sc->realtime_clock - last_realtime_clock >= MAX_DELAY_PRINT_RATE &&
        nb_prints < MAX_NB_PRINTS) {
        if ((-sc->diff_clk / (float)1000000000LL > threshold_delay) ||
            (-sc->diff_clk / (float)1000000000LL <
             (threshold_delay - THRESHOLD_REDUCE))) {
            threshold_delay = (-sc->diff_clk / 1000000000LL) + 1;
            printf("Warning: The guest is now late by %.1f to %.1f seconds\n",
                   threshold_delay - 1,
                   threshold_delay);
            nb_prints++;
            last_realtime_clock = sc->realtime_clock;
        }
    }
}

static void init_delay_params(SyncClocks *sc,
                              const CPUState *cpu)
{
    if (!icount_align_option) {
        return;
    }
    sc->realtime_clock = qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL_RT);
    sc->diff_clk = qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL) - sc->realtime_clock;
    sc->last_cpu_icount = cpu->icount_extra + cpu->icount_decr.u16.low;
    if (sc->diff_clk < max_delay) {
        max_delay = sc->diff_clk;
    }
    if (sc->diff_clk > max_advance) {
        max_advance = sc->diff_clk;
    }

    /* Print every 2s max if the guest is late. We limit the number
       of printed messages to NB_PRINT_MAX(currently 100) */
    print_delay(sc);
}
#else
static void align_clocks(SyncClocks *sc, const CPUState *cpu)
{
}

static void init_delay_params(SyncClocks *sc, const CPUState *cpu)
{
}
#endif /* CONFIG USER ONLY */

/* Execute a TB, and fix up the CPU state afterwards if necessary */
static int next_output = 1;
extern int user_stack_count;
extern int httpd_pgd;
extern int lib_start_addr;
extern int afl_user_fork;
extern int handle_addr_or_state;
int program_start = 0;

static inline tcg_target_ulong cpu_tb_exec(CPUState *cpu, TranslationBlock *itb)
{
    CPUArchState *env = cpu->env_ptr;
    uintptr_t ret;
    TranslationBlock *last_tb;
    int tb_exit;
    uint8_t *tb_ptr = itb->tc_ptr;

    qemu_log_mask_and_addr(CPU_LOG_EXEC, itb->pc,
                           "Trace %p [%d: " TARGET_FMT_lx "] %s\n",
                           itb->tc_ptr, cpu->cpu_index, itb->pc,
                           lookup_symbol(itb->pc));

#if defined(DEBUG_DISAS)
    if (qemu_loglevel_mask(CPU_LOG_TB_CPU)
        && qemu_log_in_addr_range(itb->pc)) {
        qemu_log_lock();
#if defined(TARGET_I386)
        log_cpu_state(cpu, CPU_DUMP_CCOP);
#else
        log_cpu_state(cpu, 0);
#endif
        qemu_log_unlock();
    }
#endif /* DEBUG_DISAS */

    cpu->can_do_io = !use_icount;
    target_ulong pc = env->active_tc.PC; //zyw mips

/* tmp    
if(next_output)
{	
	if(aflStart){
		char cur_process[512];
		  int pid;
		VMI_find_process_by_cr3_c(pgd, cur_process, 512, &pid);
		//if(strcmp(cur_process, "hedwig.cgi") == 0){// NEED CHANGE
			//DECAF_printf("print_pc is %x\n", pc);
			AFL_QEMU_CPU_SNIPPET2(env, pc);
		//}
	}
}
else
{
	next_output = 1;
}

*/

//zywzyw
    /*
    target_ulong pgd = DECAF_getPGD(cpu);
    if(afl_user_fork && itb->pc<0x80000000 && pgd == httpd_pgd)
    {
        printf("run pc:%lx\n", env->active_tc.PC);
    }
    */
/*
	if(itb->pc<0x80000000 && itb->pc> 0x70000000&& user_stack_count & pgd == httpd_pgd ) {
        	target_ulong pc = itb->pc - lib_start_addr;
		if(pc< 0x7000) printf("pc:%x\n", pc);

		if(itb->pc - lib_start_addr == 0x3E3C){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
		}
		if(itb->pc - lib_start_addr == 0x3E48){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
			exit(0);
		}

		if(itb->pc - lib_start_addr == 0x2218){
			printf("v0:%x, s0:%x,fp:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[16], env->active_tc.gpr[30]);
			exit(0);
		}
		if(itb->pc - lib_start_addr == 0x21d0){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
		}
		if(itb->pc - lib_start_addr == 0x21dc){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
		}
		if(itb->pc - lib_start_addr == 0x21e8){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
		}
		if(itb->pc - lib_start_addr == 0x21f8){
			printf("v0:%x, v1:%x\n", env->active_tc.gpr[2], env->active_tc.gpr[3]);
		}
	}
*/
//
    ret = tcg_qemu_tb_exec(env, tb_ptr);

    cpu->can_do_io = 1;
    
    last_tb = (TranslationBlock *)(ret & ~TB_EXIT_MASK);
    tb_exit = ret & TB_EXIT_MASK;
    trace_exec_tb_exit(last_tb, tb_exit);

    if (tb_exit > TB_EXIT_IDX1) {
        /* We didn't start executing this TB (eg because the instruction
         * counter hit zero); we must restore the guest PC to the address
         * of the start of the TB.
         */
        CPUClass *cc = CPU_GET_CLASS(cpu);
        qemu_log_mask_and_addr(CPU_LOG_EXEC, last_tb->pc,
                               "Stopped execution of TB chain before %p ["
                               TARGET_FMT_lx "] %s\n",
                               last_tb->tc_ptr, last_tb->pc,
                               lookup_symbol(last_tb->pc));
        if (cc->synchronize_from_tb) {
            cc->synchronize_from_tb(cpu, last_tb);
        } else {
            assert(cc->set_pc);
            cc->set_pc(cpu, last_tb->pc);
        }
    }
//zyw fix

    else
    {	

/*tmp
	if(ret == 0) //zyw solve multiple path problem
	{
		next_output = 0;
	}
*/

/*
	if(next_output)
	{	
		if(aflStart){
			target_ulong pgd = DECAF_getPGD(cpu);
			char cur_process[512];
			int pid;
			VMI_find_process_by_cr3_c(pgd, cur_process, 512, &pid);
			//if(strcmp(cur_process, "hedwig.cgi") == 0){// NEED CHANGE
			DECAF_printf("print_pc is %x\n", pc);
				AFL_QEMU_CPU_SNIPPET2(env, pc);
			//}
		}
	}
	else
	{
		next_output = 1;
	}
	if(ret == 0) //zyw solve multiple path problem
	{
		next_output = 0;
	}
*/
    }
/*
    if(afl_wants_cpu_to_stop){
	DECAF_printf("cpu-exec, exit_request\n");
	cpu->exit_request = 1;
	
    }
*/
//zyw
    return ret;
}

#ifndef CONFIG_USER_ONLY
/* Execute the code without caching the generated code. An interpreter
   could be used if available. */
static void cpu_exec_nocache(CPUState *cpu, int max_cycles,
                             TranslationBlock *orig_tb, bool ignore_icount)
{
    TranslationBlock *tb;

    /* Should never happen.
       We only end up here when an existing TB is too long.  */
    if (max_cycles > CF_COUNT_MASK)
        max_cycles = CF_COUNT_MASK;

    tb_lock();
    tb = tb_gen_code(cpu, orig_tb->pc, orig_tb->cs_base, orig_tb->flags,
                     max_cycles | CF_NOCACHE
                         | (ignore_icount ? CF_IGNORE_ICOUNT : 0));
    tb->orig_tb = orig_tb;
    tb_unlock();

    /* execute the generated code */
    trace_exec_tb_nocache(tb, tb->pc);
    cpu_tb_exec(cpu, tb);

    tb_lock();
    tb_phys_invalidate(tb, -1);
    tb_free(tb);
    tb_unlock();
}
#endif

static void cpu_exec_step(CPUState *cpu)
{
    CPUClass *cc = CPU_GET_CLASS(cpu);
    CPUArchState *env = (CPUArchState *)cpu->env_ptr;
    TranslationBlock *tb;
    target_ulong cs_base, pc;
    uint32_t flags;

    cpu_get_tb_cpu_state(env, &pc, &cs_base, &flags);
    if (sigsetjmp(cpu->jmp_env, 0) == 0) {
        mmap_lock();
        tb_lock();
        tb = tb_gen_code(cpu, pc, cs_base, flags,
                         1 | CF_NOCACHE | CF_IGNORE_ICOUNT);
        tb->orig_tb = NULL;
        tb_unlock();
        mmap_unlock();

        cc->cpu_exec_enter(cpu);
        /* execute the generated code */
        trace_exec_tb_nocache(tb, pc);
        cpu_tb_exec(cpu, tb);
        cc->cpu_exec_exit(cpu);

        tb_lock();
        tb_phys_invalidate(tb, -1);
        tb_free(tb);
        tb_unlock();
    } else {
        /* We may have exited due to another problem here, so we need
         * to reset any tb_locks we may have taken but didn't release.
         * The mmap_lock is dropped by tb_gen_code if it runs out of
         * memory.
         */
#ifndef CONFIG_SOFTMMU
        tcg_debug_assert(!have_mmap_lock());
#endif
        tb_lock_reset();
    }
}

void cpu_exec_step_atomic(CPUState *cpu)
{
    start_exclusive();

    /* Since we got here, we know that parallel_cpus must be true.  */
    parallel_cpus = false;
    cpu_exec_step(cpu);
    parallel_cpus = true;

    end_exclusive();
}

struct tb_desc {
    target_ulong pc;
    target_ulong cs_base;
    CPUArchState *env;
    tb_page_addr_t phys_page1;
    uint32_t flags;
    uint32_t trace_vcpu_dstate;
};

static bool tb_cmp(const void *p, const void *d)
{
    const TranslationBlock *tb = p;
    const struct tb_desc *desc = d;

    if (tb->pc == desc->pc &&
        tb->page_addr[0] == desc->phys_page1 &&
        tb->cs_base == desc->cs_base &&
        tb->flags == desc->flags &&
        tb->trace_vcpu_dstate == desc->trace_vcpu_dstate &&
        !atomic_read(&tb->invalid)) {
        /* check next page if needed */
        if (tb->page_addr[1] == -1) {
            return true;
        } else {
            tb_page_addr_t phys_page2;
            target_ulong virt_page2;

            virt_page2 = (desc->pc & TARGET_PAGE_MASK) + TARGET_PAGE_SIZE;
            phys_page2 = get_page_addr_code(desc->env, virt_page2);
            if (tb->page_addr[1] == phys_page2) {
                return true;
            }
        }
    }
    return false;
}

TranslationBlock *tb_htable_lookup(CPUState *cpu, target_ulong pc,
                                   target_ulong cs_base, uint32_t flags)
{
    tb_page_addr_t phys_pc;
    struct tb_desc desc;
    uint32_t h;

    desc.env = (CPUArchState *)cpu->env_ptr;
    desc.cs_base = cs_base;
    desc.flags = flags;
    desc.trace_vcpu_dstate = *cpu->trace_dstate;
    desc.pc = pc;
    phys_pc = get_page_addr_code(desc.env, pc);
//zyw    
    //printf("phys_pc is:%x,%x\n", pc, phys_pc);
    desc.phys_page1 = phys_pc & TARGET_PAGE_MASK;
    h = tb_hash_func(phys_pc, pc, flags, *cpu->trace_dstate);
    return qht_lookup(&tcg_ctx.tb_ctx.htable, tb_cmp, &desc, h);
}

//static inline
TranslationBlock *tb_find(CPUState *cpu,
                                        TranslationBlock *last_tb,
                                        int tb_exit)
{
    CPUArchState *env = (CPUArchState *)cpu->env_ptr;
    TranslationBlock *tb;
    target_ulong cs_base, pc;
    uint32_t flags;
    bool have_tb_lock = false;

    /* we record a subset of the CPU state. It will
       always be the same before a given translated block
       is executed. */
    cpu_get_tb_cpu_state(env, &pc, &cs_base, &flags);
    tb = atomic_rcu_read(&cpu->tb_jmp_cache[tb_jmp_cache_hash_func(pc)]);
    if (unlikely(!tb || tb->pc != pc || tb->cs_base != cs_base ||
                 tb->flags != flags ||
                 tb->trace_vcpu_dstate != *cpu->trace_dstate)) {
        tb = tb_htable_lookup(cpu, pc, cs_base, flags);
        if (!tb) {

            /* mmap_lock is needed by tb_gen_code, and mmap_lock must be
             * taken outside tb_lock. As system emulation is currently
             * single threaded the locks are NOPs.
             */
            mmap_lock();
            tb_lock();
            have_tb_lock = true;

            /* There's a chance that our desired tb has been translated while
             * taking the locks so we check again inside the lock.
             */
            tb = tb_htable_lookup(cpu, pc, cs_base, flags);
            if (!tb) {
                /* if no translated code available, then translate it now */
                //if(afl_user_fork) DECAF_printf("???????????????need regenerate code\n");
                tb = tb_gen_code(cpu, pc, cs_base, flags, 0);
                //AFL_QEMU_CPU_SNIPPET1; //zyw
            }

            mmap_unlock();
        }

        /* We add the TB in the virtual pc hash table for the fast lookup */
        atomic_set(&cpu->tb_jmp_cache[tb_jmp_cache_hash_func(pc)], tb);
    }

#ifdef NOPE_NOT_NEVER 

#ifndef CONFIG_USER_ONLY
    /* We don't take care of direct jumps when address mapping changes in
     * system emulation. So it's not safe to make a direct jump to a TB
     * spanning two pages because the mapping for the second page can change.
     */
    if (tb->page_addr[1] != -1) {
        last_tb = NULL;
    }
#endif
//zyw


    /* See if we can patch the calling TB. */
    if (last_tb && !qemu_loglevel_mask(CPU_LOG_TB_NOCHAIN)) {
        if (!have_tb_lock) {
            tb_lock();
            have_tb_lock = true;
        }
        if (!tb->invalid) {
            tb_add_jump(last_tb, tb_exit, tb);
        }
    }
#endif
//zyw
    if (have_tb_lock) {
        tb_unlock();
    }
    return tb;
}


TranslationBlock *afl_tb_find(CPUState *cpu,
                                        TranslationBlock *last_tb,
                                        int tb_exit)
{
	tb_find(cpu, last_tb, tb_exit);
}


static inline bool cpu_handle_halt(CPUState *cpu)
{
    if (cpu->halted) {
#if defined(TARGET_I386) && !defined(CONFIG_USER_ONLY)
        if ((cpu->interrupt_request & CPU_INTERRUPT_POLL)
            && replay_interrupt()) {
            X86CPU *x86_cpu = X86_CPU(cpu);
            qemu_mutex_lock_iothread();
            apic_poll_irq(x86_cpu->apic_state);
            cpu_reset_interrupt(cpu, CPU_INTERRUPT_POLL);
            qemu_mutex_unlock_iothread();
        }
#endif
        if (!cpu_has_work(cpu)) {
            return true;
        }

        cpu->halted = 0;
    }

    return false;
}

static inline void cpu_handle_debug_exception(CPUState *cpu)
{
    CPUClass *cc = CPU_GET_CLASS(cpu);
    CPUWatchpoint *wp;

    if (!cpu->watchpoint_hit) {
        QTAILQ_FOREACH(wp, &cpu->watchpoints, entry) {
            wp->flags &= ~BP_WATCHPOINT_HIT;
        }
    }

    cc->debug_excp_handler(cpu);
}


int count = 0;
//static inline 
bool cpu_handle_exception(CPUState *cpu, int *ret)
{
    //if(afl_user_fork) printf("exception:%d\n", cpu->exception_index);
    if (cpu->exception_index >= 0) {
        if (cpu->exception_index >= EXCP_INTERRUPT) {
            /* exit request from the cpu execution loop */
            *ret = cpu->exception_index;
            if (*ret == EXCP_DEBUG) {
                cpu_handle_debug_exception(cpu);
            }
            cpu->exception_index = -1;
            return true;
        } else {
#if defined(CONFIG_USER_ONLY)
            /* if user mode only, we simulate a fake exception
               which will be handled outside the cpu execution
               loop */
#if defined(TARGET_I386)
            CPUClass *cc = CPU_GET_CLASS(cpu);
            cc->do_interrupt(cpu);
#endif
            *ret = cpu->exception_index;
            cpu->exception_index = -1;
            return true;
#else
            if (replay_exception()) {
                CPUClass *cc = CPU_GET_CLASS(cpu);
                qemu_mutex_lock_iothread();
                cc->do_interrupt(cpu);
                qemu_mutex_unlock_iothread();
                cpu->exception_index = -1;
            } else if (!replay_has_interrupt()) {
                /* give a chance to iothread in replay mode */
                *ret = EXCP_INTERRUPT;
                return true;
            }
#endif
        }
#ifndef CONFIG_USER_ONLY
    } else if (replay_has_exception()
               && cpu->icount_decr.u16.low + cpu->icount_extra == 0) {
        /* try to cause an exception pending in the log */
        cpu_exec_nocache(cpu, 1, tb_find(cpu, NULL, 0), true);
        *ret = -1;
        return true;
#endif
    }

    return false;
}

extern int flagg;
extern int exception;
//static inline
bool cpu_handle_interrupt(CPUState *cpu,
                                        TranslationBlock **last_tb)
{
   
    CPUClass *cc = CPU_GET_CLASS(cpu);
    if (unlikely(atomic_read(&cpu->interrupt_request))) {
        int interrupt_request;
        qemu_mutex_lock_iothread();
        interrupt_request = cpu->interrupt_request;
        if (unlikely(cpu->singlestep_enabled & SSTEP_NOIRQ)) {
            /* Mask out external interrupts for this step. */
            interrupt_request &= ~CPU_INTERRUPT_SSTEP_MASK;
        }
        if (interrupt_request & CPU_INTERRUPT_DEBUG) {
            cpu->interrupt_request &= ~CPU_INTERRUPT_DEBUG;
            cpu->exception_index = EXCP_DEBUG;
            qemu_mutex_unlock_iothread();
            return true;
        }
        if (replay_mode == REPLAY_MODE_PLAY && !replay_has_interrupt()) {
            /* Do nothing */
        } else if (interrupt_request & CPU_INTERRUPT_HALT) {
            replay_interrupt();
            cpu->interrupt_request &= ~CPU_INTERRUPT_HALT;
            cpu->halted = 1;
            cpu->exception_index = EXCP_HLT;
            qemu_mutex_unlock_iothread();
            return true;
        }
#if defined(TARGET_I386)
        else if (interrupt_request & CPU_INTERRUPT_INIT) {
            X86CPU *x86_cpu = X86_CPU(cpu);
            CPUArchState *env = &x86_cpu->env;
            replay_interrupt();
            cpu_svm_check_intercept_param(env, SVM_EXIT_INIT, 0, 0);
            do_cpu_init(x86_cpu);
            cpu->exception_index = EXCP_HALTED;
            qemu_mutex_unlock_iothread();
            return true;
        }
#else
        else if (interrupt_request & CPU_INTERRUPT_RESET) {
            replay_interrupt();
            cpu_reset(cpu);
            qemu_mutex_unlock_iothread();
            return true;
        }
#endif
        /* The target hook has 3 exit conditions:
           False when the interrupt isn't processed,
           True when it is, and we should restart on a new TB,
           and via longjmp via cpu_loop_exit.  */
        else {
            if (cc->cpu_exec_interrupt(cpu, interrupt_request)) {
                replay_interrupt();
                *last_tb = NULL;
            }
            /* The target hook may have updated the 'cpu->interrupt_request';
             * reload the 'interrupt_request' value */
            interrupt_request = cpu->interrupt_request;
        }
        if (interrupt_request & CPU_INTERRUPT_EXITTB) {
            cpu->interrupt_request &= ~CPU_INTERRUPT_EXITTB;
            /* ensure that no TB jump will be modified as
               the program flow was changed */
            *last_tb = NULL;
        }

        /* If we exit via cpu_loop_exit/longjmp it is reset in cpu_exec */
        qemu_mutex_unlock_iothread();
    }

    /* Finally, check if we need to exit to the main loop.  */
    if (unlikely(atomic_read(&cpu->exit_request)
        || (use_icount && cpu->icount_decr.u16.low + cpu->icount_extra == 0))) {
        atomic_set(&cpu->exit_request, 0);
        cpu->exception_index = EXCP_INTERRUPT;
        return true;
    }

    return false;
}



//static inline 
void cpu_loop_exec_tb(CPUState *cpu, TranslationBlock *tb,
                                    TranslationBlock **last_tb, int *tb_exit)
{
    uintptr_t ret;
    int32_t insns_left;
    trace_exec_tb(tb, tb->pc);
    CPUArchState *env = cpu->env_ptr;
    ret = cpu_tb_exec(cpu, tb);
    tb = (TranslationBlock *)(ret & ~TB_EXIT_MASK);
    *tb_exit = ret & TB_EXIT_MASK;
    if (*tb_exit != TB_EXIT_REQUESTED) {
        *last_tb = tb;
        return;
    }
    *last_tb = NULL;
    
    insns_left = atomic_read(&cpu->icount_decr.u32);
    atomic_set(&cpu->icount_decr.u16.high, 0);
    if (insns_left < 0) {
        /* Something asked us to stop executing chained TBs; just
         * continue round the main loop. Whatever requested the exit
         * will also have set something else (eg exit_request or
         * interrupt_request) which we will handle next time around
         * the loop.  But we need to ensure the zeroing of icount_decr
         * comes before the next read of cpu->exit_request
         * or cpu->interrupt_request.
         */
        smp_mb();
        return;
    }
    /* Instruction counter expired.  */
    assert(use_icount);
#ifndef CONFIG_USER_ONLY
    /* Ensure global icount has gone forward */
    cpu_update_icount(cpu);
    /* Refill decrementer and continue execution.  */
    insns_left = MIN(0xffff, cpu->icount_budget);
    cpu->icount_decr.u16.low = insns_left;
    cpu->icount_extra = cpu->icount_budget - insns_left;
    if (!cpu->icount_extra) {
        /* Execute any remaining instructions, then let the main loop
         * handle the next event.
         */
        if (insns_left > 0) {
            cpu_exec_nocache(cpu, insns_left, tb, false);
        }
    }
#endif
}

extern int flagg;
extern int helper_flag;
int exception;//zyw
//zyw
int counts = 0;
extern int stack_start_addr;
extern int curr_state_pc;
extern int stop;


extern uintptr_t search_hva_gva_pair(target_ulong gva);
extern int switch_start;
int other_exception = 0;
/* main execution loop */
struct timeval syscall_begin;
struct timeval syscall_end;
double time_interval = 0.0;
double time_interval_total = 0.0;
int syscall_count = 0;
int into_syscall = 0;
extern int print_debug;
int only_transit_for_one_time = 0;

int sem_addr = 0; //semaphore for mm
extern int transit_pc;
extern char* program_analysis;

int cpu_exec(CPUState *cpu)
{
    CPUArchState * env = cpu->env_ptr;
    if(handle_addr_or_state == 2)
    {
        cpu->exit_request = 0; //need???????????
    }

    CPUClass *cc = CPU_GET_CLASS(cpu);
    int ret;
    SyncClocks sc = { 0 };

    /* replay_interrupt may need current_cpu */
    current_cpu = cpu;

    if (cpu_handle_halt(cpu)) {
        return EXCP_HALTED;
    }
    rcu_read_lock();

    cc->cpu_exec_enter(cpu);
    /* Calculate difference between guest clock and host clock.
     * This delay includes the delay of the last cycle, so
     * what we have to do is sleep until it is 0. As for the
     * advance/delay we gain here, we try to fix it next time.
     */
    init_delay_params(&sc, cpu);

    /* prepare setjmp context for exception handling */
    if (sigsetjmp(cpu->jmp_env, 0) != 0) {
#if defined(__clang__) || !QEMU_GNUC_PREREQ(4, 6)
        /* Some compilers wrongly smash all local variables after
         * siglongjmp. There were bug reports for gcc 4.5.0 and clang.
         * Reload essential local variables here for those compilers.
         * Newer versions of gcc would complain about this code (-Wclobbered). */
        cpu = current_cpu;
        cc = CPU_GET_CLASS(cpu);
#else /* buggy compiler */
        /* Assert that the compiler does not smash local variables. */
        g_assert(cpu == current_cpu);
        g_assert(cc == CPU_GET_CLASS(cpu));
#endif /* buggy compiler */
        if(handle_addr_or_state ==2)
        {
            //printf("longjmp:%d\n",  cpu->exception_index);
        }

        cpu->can_do_io = 1;
        tb_lock_reset();
        if (qemu_mutex_iothread_locked()) {
            qemu_mutex_unlock_iothread();
        }
    }
    if(afl_user_fork)
    {
        target_ulong pgd = DECAF_getPGD(cpu);
        if(pgd == httpd_pgd && handle_addr_or_state ==2 && into_syscall == 0) {
            if(cpu->exception_index == 17)  {
                counts=1;
                into_syscall = env->active_tc.gpr[2];
                gettimeofday(&syscall_begin, NULL);
                if(strcmp(program_analysis, "dnsmasq") == 0 && into_syscall == 4006) {}
                else if(print_debug)
                {
                    printf("----------------------------------------------------------\n");
                    printf("sys num:%d pc:%x ra:%x, a0:%x, sp:%x, epc:%x, status:%x, cause:%x\n", 
                        into_syscall, env->active_tc.PC, env->active_tc.gpr[31], env->active_tc.gpr[4], env->active_tc.gpr[29], env->CP0_EPC, env->CP0_Status, env->CP0_Cause);
                }
                curr_state_pc = env->active_tc.PC;

                if(into_syscall == 4106)
                {
                    char file_name[100];
                    printf("stat:%x, ", env->active_tc.gpr[4]);
                    DECAF_read_mem(cpu, env->active_tc.gpr[4], 100, file_name);
                    printf("%s\n", file_name);
                }
                if(into_syscall == 4005)
                {
                    char file_name[100];
                    printf("open:%x, ", env->active_tc.gpr[4]);
                    DECAF_read_mem(cpu, env->active_tc.gpr[4], 100, file_name);
                    printf("%s\n", file_name);
                }
                if(into_syscall == 4213)
                {
                    char file_name[100];
                    printf("stat64:%x, ", env->active_tc.gpr[4]);
                    DECAF_read_mem(cpu, env->active_tc.gpr[4], 100, file_name);
                    printf("%s\n", file_name);
                }
                
                
            }
        }
        else if(pgd == httpd_pgd && handle_addr_or_state ==2 && into_syscall == 4045)
        {
            printf("cpu exception int brk:%d\n", cpu->exception_index);
            other_exception = 1;
        } 
    } 
    
    /* if an exception is pending, we execute it here */
    while (!cpu_handle_exception(cpu, &ret)) {
        TranslationBlock *last_tb = NULL;
        int tb_exit = 0;
        while (!cpu_handle_interrupt(cpu, &last_tb)) { 
//zywzyw
            if(afl_user_fork)
            {
                target_ulong new_pgd = DECAF_getPGD(cpu);
        	    if(env->active_tc.PC ==  curr_state_pc + 4  && new_pgd == httpd_pgd && handle_addr_or_state == 2 && into_syscall){ //user_stack_count
                    if(strcmp(program_analysis, "dnsmasq") == 0 && into_syscall == 4006) {}
                    else if(print_debug)
                    {
                        
                        printf("end pc:%x, ret:%x, err:%x, sp:%x, exit:%x\n", env->active_tc.PC, env->active_tc.gpr[2], env->active_tc.gpr[7], env->active_tc.gpr[29], ret);
                    }
        			//if(env->active_tc.gpr[7]!=0)  env->active_tc.gpr[2]=0xffffffff;  //NEED MODIFY for http accept, zywzyw
        			//vm_stop(RUN_STATE_PAUSED);
                    afl_wants_cpu_to_stop = 1;

                    handle_addr_or_state = 0;
                    gettimeofday(&syscall_end, NULL);
                    time_interval = (double)syscall_end.tv_sec - syscall_begin.tv_sec + (syscall_end.tv_usec - syscall_begin.tv_usec)/1000000.0;
                    time_interval_total += time_interval;
                    if(strcmp(program_analysis, "dnsmasq") == 0 && into_syscall == 4006) {}
                    else if(print_debug)
                    {
                        
                        printf("syscall execute:%f\n", time_interval);
                    }
                    syscall_count++;
                    into_syscall = 0;
                    write_state(env);

        			//return;
        	    }
            }
            
            if(handle_addr_or_state == 2 && env->active_tc.PC < 0x80000000)
            {
                target_ulong new_pgd = DECAF_getPGD(cpu);
                if(new_pgd == httpd_pgd){
                    //printf("handle state pc:%x\n", env->active_tc.PC);
                }
            }
            if(handle_addr_or_state == 0 && switch_start == 1)
            {
                //printf("other programs\n");
            } 
            if(env->active_tc.PC == transit_pc && only_transit_for_one_time == 0 && program_start)
            {
                target_ulong new_pgd = DECAF_getPGD(cpu);
                DECAF_printf("transit at recvfrom  pgd:%x, buf:%x, len:%d, actual len:%d\n", new_pgd, env->active_tc.gpr[5], env->active_tc.gpr[6], env->active_tc.gpr[2]);
                only_transit_for_one_time = 1;
                pause_stateTransit_wait(cpu);
                //vm_stop(RUN_STATE_PAUSED);

            }
            if(afl_wants_cpu_to_stop)
            {
                goto end;
            }  

            TranslationBlock *tb = tb_find(cpu, last_tb, tb_exit); 
            cpu_loop_exec_tb(cpu, tb, &last_tb, &tb_exit);
            /* Try to align the host and virtual clocks
               if the guest is in advance */
            align_clocks(&sc, cpu);
        }
    }
    if(handle_addr_or_state == 2)
    {
        printf("exception not handle:%d, pc:%x\n", cpu->exception_index, env->active_tc.PC);
    }
end:   
    cc->cpu_exec_exit(cpu);
    rcu_read_unlock();	
    return ret;
}
