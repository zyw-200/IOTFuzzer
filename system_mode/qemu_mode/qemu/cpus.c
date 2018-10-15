/*
 * QEMU System Emulator
 *
 * Copyright (c) 2003-2008 Fabrice Bellard
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL
 * THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

/* Needed early for CONFIG_BSD etc. */
#include "qemu/osdep.h"
#include "qemu-common.h"
#include "qemu/config-file.h"
#include "cpu.h"
#include "monitor/monitor.h"
#include "qapi/qmp/qerror.h"
#include "qemu/error-report.h"
#include "sysemu/sysemu.h"
#include "sysemu/block-backend.h"
#include "exec/gdbstub.h"
#include "sysemu/dma.h"
#include "sysemu/hw_accel.h"
#include "sysemu/kvm.h"
#include "sysemu/hax.h"
#include "qmp-commands.h"
#include "exec/exec-all.h"

#include "qemu/thread.h"
#include "sysemu/cpus.h"
#include "sysemu/qtest.h"
#include "qemu/main-loop.h"
#include "qemu/bitmap.h"
#include "qemu/seqlock.h"
#include "tcg.h"
#include "qapi-event.h"
#include "hw/nmi.h"
#include "sysemu/replay.h"
#include "hw/boards.h"

#include "afl.h"//zyw
#include "function_map.h"
extern char *write_pipename;
extern char *init_read_pipename;
extern char *mapping_filename;
extern char* orig_data;
extern int print_start;
extern int print_index;
extern int print_pc[1000];

#ifdef CONFIG_LINUX

#include <sys/prctl.h>

#ifndef PR_MCE_KILL
#define PR_MCE_KILL 33
#endif

#ifndef PR_MCE_KILL_SET
#define PR_MCE_KILL_SET 1
#endif

#ifndef PR_MCE_KILL_EARLY
#define PR_MCE_KILL_EARLY 1
#endif

#endif /* CONFIG_LINUX */

//zyw define
#define SNAPSHOT_SYNC
#define PRE_MAPPING

int64_t max_delay;
int64_t max_advance;

/* vcpu throttling controls */
static QEMUTimer *throttle_timer;
static unsigned int throttle_percentage;

//zyw
static int afl_qemuloop_pipe[2];        /* to notify mainloop to become forkserver */
static CPUState *restart_cpu = NULL;    /* cpu to restart */
static int after_Notification = 0;

int afl_wants_cpu_to_stop = 0; //zyw

int flagg = 0;

int kernel_stack_count = 0;
int user_stack_count = 0;
int kernel_origpt = 0;
int user_origpt = 0;
int user_forkpt;
int user_stack[1000];

//zyw
int curr_state_pc;
int handle_addr_or_state = 0;
extern int print_debug;
int full_store_count = 0;

//int kenerl[0x10000000];

#define CPU_THROTTLE_PCT_MIN 1
#define CPU_THROTTLE_PCT_MAX 99
#define CPU_THROTTLE_TIMESLICE_NS 10000000


bool cpu_is_stopped(CPUState *cpu)
{
    return cpu->stopped || !runstate_is_running();
}

static bool cpu_thread_is_idle(CPUState *cpu)
{
    if (cpu->stop || cpu->queued_work_first) {
        return false;
    }
    if (cpu_is_stopped(cpu)) {
        return true;
    }
    if (!cpu->halted || cpu_has_work(cpu) ||
        kvm_halt_in_kernel()) {
        return false;
    }
    return true;
}

static bool all_cpu_threads_idle(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        if (!cpu_thread_is_idle(cpu)) {
            return false;
        }
    }
    return true;
}

/***********************************************************/
/* guest cycle counter */

/* Protected by TimersState seqlock */

static bool icount_sleep = true;
static int64_t vm_clock_warp_start = -1;
/* Conversion factor from emulated instructions to virtual clock ticks.  */
static int icount_time_shift;
/* Arbitrarily pick 1MIPS as the minimum allowable speed.  */
#define MAX_ICOUNT_SHIFT 10

static QEMUTimer *icount_rt_timer;
static QEMUTimer *icount_vm_timer;
static QEMUTimer *icount_warp_timer;

typedef struct TimersState {
    /* Protected by BQL.  */
    int64_t cpu_ticks_prev;
    int64_t cpu_ticks_offset;

    /* cpu_clock_offset can be read out of BQL, so protect it with
     * this lock.
     */
    QemuSeqLock vm_clock_seqlock;
    int64_t cpu_clock_offset;
    int32_t cpu_ticks_enabled;
    int64_t dummy;

    /* Compensate for varying guest execution speed.  */
    int64_t qemu_icount_bias;
    /* Only written by TCG thread */
    int64_t qemu_icount;
} TimersState;

static TimersState timers_state;
bool mttcg_enabled;

/*
 * We default to false if we know other options have been enabled
 * which are currently incompatible with MTTCG. Otherwise when each
 * guest (target) has been updated to support:
 *   - atomic instructions
 *   - memory ordering primitives (barriers)
 * they can set the appropriate CONFIG flags in ${target}-softmmu.mak
 *
 * Once a guest architecture has been converted to the new primitives
 * there are two remaining limitations to check.
 *
 * - The guest can't be oversized (e.g. 64 bit guest on 32 bit host)
 * - The host must have a stronger memory order than the guest
 *
 * It may be possible in future to support strong guests on weak hosts
 * but that will require tagging all load/stores in a guest with their
 * implicit memory order requirements which would likely slow things
 * down a lot.
 */

static bool check_tcg_memory_orders_compatible(void)
{
#if defined(TCG_GUEST_DEFAULT_MO) && defined(TCG_TARGET_DEFAULT_MO)
    return (TCG_GUEST_DEFAULT_MO & ~TCG_TARGET_DEFAULT_MO) == 0;
#else
    return false;
#endif
}

static bool default_mttcg_enabled(void)
{
    if (use_icount || TCG_OVERSIZED_GUEST) {
        return false;
    } else {
#ifdef TARGET_SUPPORTS_MTTCG
        return check_tcg_memory_orders_compatible();
#else
        return false;
#endif
    }
}

void qemu_tcg_configure(QemuOpts *opts, Error **errp)
{
    const char *t = qemu_opt_get(opts, "thread");
    if (t) {
        if (strcmp(t, "multi") == 0) {
            if (TCG_OVERSIZED_GUEST) {
                error_setg(errp, "No MTTCG when guest word size > hosts");
            } else if (use_icount) {
                error_setg(errp, "No MTTCG when icount is enabled");
            } else {
#ifndef TARGET_SUPPORTS_MTTCG
                error_report("Guest not yet converted to MTTCG - "
                             "you may get unexpected results");
#endif
                if (!check_tcg_memory_orders_compatible()) {
                    error_report("Guest expects a stronger memory ordering "
                                 "than the host provides");
                    error_printf("This may cause strange/hard to debug errors\n");
                }
                mttcg_enabled = true;
            }
        } else if (strcmp(t, "single") == 0) {
            mttcg_enabled = false;
        } else {
            error_setg(errp, "Invalid 'thread' setting %s", t);
        }
    } else {
        mttcg_enabled = default_mttcg_enabled();
    }
}

/* The current number of executed instructions is based on what we
 * originally budgeted minus the current state of the decrementing
 * icount counters in extra/u16.low.
 */
static int64_t cpu_get_icount_executed(CPUState *cpu)
{
    return cpu->icount_budget - (cpu->icount_decr.u16.low + cpu->icount_extra);
}

/*
 * Update the global shared timer_state.qemu_icount to take into
 * account executed instructions. This is done by the TCG vCPU
 * thread so the main-loop can see time has moved forward.
 */
void cpu_update_icount(CPUState *cpu)
{
    int64_t executed = cpu_get_icount_executed(cpu);
    cpu->icount_budget -= executed;

#ifdef CONFIG_ATOMIC64
    atomic_set__nocheck(&timers_state.qemu_icount,
                        atomic_read__nocheck(&timers_state.qemu_icount) +
                        executed);
#else /* FIXME: we need 64bit atomics to do this safely */
    timers_state.qemu_icount += executed;
#endif
}

int64_t cpu_get_icount_raw(void)
{
    CPUState *cpu = current_cpu;

    if (cpu && cpu->running) {
        if (!cpu->can_do_io) {
            fprintf(stderr, "Bad icount read\n");
            exit(1);
        }
        /* Take into account what has run */
        cpu_update_icount(cpu);
    }
#ifdef CONFIG_ATOMIC64
    return atomic_read__nocheck(&timers_state.qemu_icount);
#else /* FIXME: we need 64bit atomics to do this safely */
    return timers_state.qemu_icount;
#endif
}

/* Return the virtual CPU time, based on the instruction counter.  */
static int64_t cpu_get_icount_locked(void)
{
    int64_t icount = cpu_get_icount_raw();
    return timers_state.qemu_icount_bias + cpu_icount_to_ns(icount);
}

int64_t cpu_get_icount(void)
{
    int64_t icount;
    unsigned start;

    do {
        start = seqlock_read_begin(&timers_state.vm_clock_seqlock);
        icount = cpu_get_icount_locked();
    } while (seqlock_read_retry(&timers_state.vm_clock_seqlock, start));

    return icount;
}

int64_t cpu_icount_to_ns(int64_t icount)
{
    return icount << icount_time_shift;
}

/* return the time elapsed in VM between vm_start and vm_stop.  Unless
 * icount is active, cpu_get_ticks() uses units of the host CPU cycle
 * counter.
 *
 * Caller must hold the BQL
 */
int64_t cpu_get_ticks(void)
{
    int64_t ticks;

    if (use_icount) {
        return cpu_get_icount();
    }

    ticks = timers_state.cpu_ticks_offset;
    if (timers_state.cpu_ticks_enabled) {
        ticks += cpu_get_host_ticks();
    }

    if (timers_state.cpu_ticks_prev > ticks) {
        /* Note: non increasing ticks may happen if the host uses
           software suspend */
        timers_state.cpu_ticks_offset += timers_state.cpu_ticks_prev - ticks;
        ticks = timers_state.cpu_ticks_prev;
    }

    timers_state.cpu_ticks_prev = ticks;
    return ticks;
}

static int64_t cpu_get_clock_locked(void)
{
    int64_t time;

    time = timers_state.cpu_clock_offset;
    if (timers_state.cpu_ticks_enabled) {
        time += get_clock();
    }

    return time;
}

/* Return the monotonic time elapsed in VM, i.e.,
 * the time between vm_start and vm_stop
 */
int64_t cpu_get_clock(void)
{
    int64_t ti;
    unsigned start;

    do {
        start = seqlock_read_begin(&timers_state.vm_clock_seqlock);
        ti = cpu_get_clock_locked();
    } while (seqlock_read_retry(&timers_state.vm_clock_seqlock, start));

    return ti;
}

/* enable cpu_get_ticks()
 * Caller must hold BQL which serves as mutex for vm_clock_seqlock.
 */
void cpu_enable_ticks(void)
{
    /* Here, the really thing protected by seqlock is cpu_clock_offset. */
    seqlock_write_begin(&timers_state.vm_clock_seqlock);
    if (!timers_state.cpu_ticks_enabled) {
        timers_state.cpu_ticks_offset -= cpu_get_host_ticks();
        timers_state.cpu_clock_offset -= get_clock();
        timers_state.cpu_ticks_enabled = 1;
    }
    seqlock_write_end(&timers_state.vm_clock_seqlock);
}

/* disable cpu_get_ticks() : the clock is stopped. You must not call
 * cpu_get_ticks() after that.
 * Caller must hold BQL which serves as mutex for vm_clock_seqlock.
 */
void cpu_disable_ticks(void)
{
    /* Here, the really thing protected by seqlock is cpu_clock_offset. */
    seqlock_write_begin(&timers_state.vm_clock_seqlock);
    if (timers_state.cpu_ticks_enabled) {
        timers_state.cpu_ticks_offset += cpu_get_host_ticks();
        timers_state.cpu_clock_offset = cpu_get_clock_locked();
        timers_state.cpu_ticks_enabled = 0;
    }
    seqlock_write_end(&timers_state.vm_clock_seqlock);
}

/* Correlation between real and virtual time is always going to be
   fairly approximate, so ignore small variation.
   When the guest is idle real and virtual time will be aligned in
   the IO wait loop.  */
#define ICOUNT_WOBBLE (NANOSECONDS_PER_SECOND / 10)

static void icount_adjust(void)
{
    int64_t cur_time;
    int64_t cur_icount;
    int64_t delta;

    /* Protected by TimersState mutex.  */
    static int64_t last_delta;

    /* If the VM is not running, then do nothing.  */
    if (!runstate_is_running()) {
        return;
    }

    seqlock_write_begin(&timers_state.vm_clock_seqlock);
    cur_time = cpu_get_clock_locked();
    cur_icount = cpu_get_icount_locked();

    delta = cur_icount - cur_time;
    /* FIXME: This is a very crude algorithm, somewhat prone to oscillation.  */
    if (delta > 0
        && last_delta + ICOUNT_WOBBLE < delta * 2
        && icount_time_shift > 0) {
        /* The guest is getting too far ahead.  Slow time down.  */
        icount_time_shift--;
    }
    if (delta < 0
        && last_delta - ICOUNT_WOBBLE > delta * 2
        && icount_time_shift < MAX_ICOUNT_SHIFT) {
        /* The guest is getting too far behind.  Speed time up.  */
        icount_time_shift++;
    }
    last_delta = delta;
    timers_state.qemu_icount_bias = cur_icount
                              - (timers_state.qemu_icount << icount_time_shift);
    seqlock_write_end(&timers_state.vm_clock_seqlock);
}

static void icount_adjust_rt(void *opaque)
{
    timer_mod(icount_rt_timer,
              qemu_clock_get_ms(QEMU_CLOCK_VIRTUAL_RT) + 1000);
    icount_adjust();
}

static void icount_adjust_vm(void *opaque)
{
    timer_mod(icount_vm_timer,
                   qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL) +
                   NANOSECONDS_PER_SECOND / 10);
    icount_adjust();
}

static int64_t qemu_icount_round(int64_t count)
{
    return (count + (1 << icount_time_shift) - 1) >> icount_time_shift;
}

static void icount_warp_rt(void)
{
    unsigned seq;
    int64_t warp_start;

    /* The icount_warp_timer is rescheduled soon after vm_clock_warp_start
     * changes from -1 to another value, so the race here is okay.
     */
    do {
        seq = seqlock_read_begin(&timers_state.vm_clock_seqlock);
        warp_start = vm_clock_warp_start;
    } while (seqlock_read_retry(&timers_state.vm_clock_seqlock, seq));

    if (warp_start == -1) {
        return;
    }

    seqlock_write_begin(&timers_state.vm_clock_seqlock);
    if (runstate_is_running()) {
        int64_t clock = REPLAY_CLOCK(REPLAY_CLOCK_VIRTUAL_RT,
                                     cpu_get_clock_locked());
        int64_t warp_delta;

        warp_delta = clock - vm_clock_warp_start;
        if (use_icount == 2) {
            /*
             * In adaptive mode, do not let QEMU_CLOCK_VIRTUAL run too
             * far ahead of real time.
             */
            int64_t cur_icount = cpu_get_icount_locked();
            int64_t delta = clock - cur_icount;
            warp_delta = MIN(warp_delta, delta);
        }
        timers_state.qemu_icount_bias += warp_delta;
    }
    vm_clock_warp_start = -1;
    seqlock_write_end(&timers_state.vm_clock_seqlock);

    if (qemu_clock_expired(QEMU_CLOCK_VIRTUAL)) {
        qemu_clock_notify(QEMU_CLOCK_VIRTUAL);
    }
}

static void icount_timer_cb(void *opaque)
{
    /* No need for a checkpoint because the timer already synchronizes
     * with CHECKPOINT_CLOCK_VIRTUAL_RT.
     */
    icount_warp_rt();
}

void qtest_clock_warp(int64_t dest)
{
    int64_t clock = qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL);
    AioContext *aio_context;
    assert(qtest_enabled());
    aio_context = qemu_get_aio_context();
    while (clock < dest) {
        int64_t deadline = qemu_clock_deadline_ns_all(QEMU_CLOCK_VIRTUAL);
        int64_t warp = qemu_soonest_timeout(dest - clock, deadline);

        seqlock_write_begin(&timers_state.vm_clock_seqlock);
        timers_state.qemu_icount_bias += warp;
        seqlock_write_end(&timers_state.vm_clock_seqlock);

        qemu_clock_run_timers(QEMU_CLOCK_VIRTUAL);
        timerlist_run_timers(aio_context->tlg.tl[QEMU_CLOCK_VIRTUAL]);
        clock = qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL);
    }
    qemu_clock_notify(QEMU_CLOCK_VIRTUAL);
}

void qemu_start_warp_timer(void)
{
    int64_t clock;
    int64_t deadline;

    if (!use_icount) {
        return;
    }

    /* Nothing to do if the VM is stopped: QEMU_CLOCK_VIRTUAL timers
     * do not fire, so computing the deadline does not make sense.
     */
    if (!runstate_is_running()) {
        return;
    }

    /* warp clock deterministically in record/replay mode */
    if (!replay_checkpoint(CHECKPOINT_CLOCK_WARP_START)) {
        return;
    }

    if (!all_cpu_threads_idle()) {
        return;
    }

    if (qtest_enabled()) {
        /* When testing, qtest commands advance icount.  */
        return;
    }

    /* We want to use the earliest deadline from ALL vm_clocks */
    clock = qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL_RT);
    deadline = qemu_clock_deadline_ns_all(QEMU_CLOCK_VIRTUAL);
    if (deadline < 0) {
        static bool notified;
        if (!icount_sleep && !notified) {
            warn_report("icount sleep disabled and no active timers");
            notified = true;
        }
        return;
    }

    if (deadline > 0) {
        /*
         * Ensure QEMU_CLOCK_VIRTUAL proceeds even when the virtual CPU goes to
         * sleep.  Otherwise, the CPU might be waiting for a future timer
         * interrupt to wake it up, but the interrupt never comes because
         * the vCPU isn't running any insns and thus doesn't advance the
         * QEMU_CLOCK_VIRTUAL.
         */
        if (!icount_sleep) {
            /*
             * We never let VCPUs sleep in no sleep icount mode.
             * If there is a pending QEMU_CLOCK_VIRTUAL timer we just advance
             * to the next QEMU_CLOCK_VIRTUAL event and notify it.
             * It is useful when we want a deterministic execution time,
             * isolated from host latencies.
             */
            seqlock_write_begin(&timers_state.vm_clock_seqlock);
            timers_state.qemu_icount_bias += deadline;
            seqlock_write_end(&timers_state.vm_clock_seqlock);
            qemu_clock_notify(QEMU_CLOCK_VIRTUAL);
        } else {
            /*
             * We do stop VCPUs and only advance QEMU_CLOCK_VIRTUAL after some
             * "real" time, (related to the time left until the next event) has
             * passed. The QEMU_CLOCK_VIRTUAL_RT clock will do this.
             * This avoids that the warps are visible externally; for example,
             * you will not be sending network packets continuously instead of
             * every 100ms.
             */
            seqlock_write_begin(&timers_state.vm_clock_seqlock);
            if (vm_clock_warp_start == -1 || vm_clock_warp_start > clock) {
                vm_clock_warp_start = clock;
            }
            seqlock_write_end(&timers_state.vm_clock_seqlock);
            timer_mod_anticipate(icount_warp_timer, clock + deadline);
        }
    } else if (deadline == 0) {
        qemu_clock_notify(QEMU_CLOCK_VIRTUAL);
    }
}

static void qemu_account_warp_timer(void)
{
    if (!use_icount || !icount_sleep) {
        return;
    }

    /* Nothing to do if the VM is stopped: QEMU_CLOCK_VIRTUAL timers
     * do not fire, so computing the deadline does not make sense.
     */
    if (!runstate_is_running()) {
        return;
    }

    /* warp clock deterministically in record/replay mode */
    if (!replay_checkpoint(CHECKPOINT_CLOCK_WARP_ACCOUNT)) {
        return;
    }

    timer_del(icount_warp_timer);
    icount_warp_rt();
}

static bool icount_state_needed(void *opaque)
{
    return use_icount;
}

/*
 * This is a subsection for icount migration.
 */
static const VMStateDescription icount_vmstate_timers = {
    .name = "timer/icount",
    .version_id = 1,
    .minimum_version_id = 1,
    .needed = icount_state_needed,
    .fields = (VMStateField[]) {
        VMSTATE_INT64(qemu_icount_bias, TimersState),
        VMSTATE_INT64(qemu_icount, TimersState),
        VMSTATE_END_OF_LIST()
    }
};

static const VMStateDescription vmstate_timers = {
    .name = "timer",
    .version_id = 2,
    .minimum_version_id = 1,
    .fields = (VMStateField[]) {
        VMSTATE_INT64(cpu_ticks_offset, TimersState),
        VMSTATE_INT64(dummy, TimersState),
        VMSTATE_INT64_V(cpu_clock_offset, TimersState, 2),
        VMSTATE_END_OF_LIST()
    },
    .subsections = (const VMStateDescription*[]) {
        &icount_vmstate_timers,
        NULL
    }
};

static void cpu_throttle_thread(CPUState *cpu, run_on_cpu_data opaque)
{
    double pct;
    double throttle_ratio;
    long sleeptime_ns;

    if (!cpu_throttle_get_percentage()) {
        return;
    }

    pct = (double)cpu_throttle_get_percentage()/100;
    throttle_ratio = pct / (1 - pct);
    sleeptime_ns = (long)(throttle_ratio * CPU_THROTTLE_TIMESLICE_NS);

    qemu_mutex_unlock_iothread();
    g_usleep(sleeptime_ns / 1000); /* Convert ns to us for usleep call */
    qemu_mutex_lock_iothread();
    atomic_set(&cpu->throttle_thread_scheduled, 0);
}

static void cpu_throttle_timer_tick(void *opaque)
{
    CPUState *cpu;
    double pct;

    /* Stop the timer if needed */
    if (!cpu_throttle_get_percentage()) {
        return;
    }
    CPU_FOREACH(cpu) {
        if (!atomic_xchg(&cpu->throttle_thread_scheduled, 1)) {
            async_run_on_cpu(cpu, cpu_throttle_thread,
                             RUN_ON_CPU_NULL);
        }
    }

    pct = (double)cpu_throttle_get_percentage()/100;
    timer_mod(throttle_timer, qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL_RT) +
                                   CPU_THROTTLE_TIMESLICE_NS / (1-pct));
}

void cpu_throttle_set(int new_throttle_pct)
{
    /* Ensure throttle percentage is within valid range */
    new_throttle_pct = MIN(new_throttle_pct, CPU_THROTTLE_PCT_MAX);
    new_throttle_pct = MAX(new_throttle_pct, CPU_THROTTLE_PCT_MIN);

    atomic_set(&throttle_percentage, new_throttle_pct);

    timer_mod(throttle_timer, qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL_RT) +
                                       CPU_THROTTLE_TIMESLICE_NS);
}

void cpu_throttle_stop(void)
{
    atomic_set(&throttle_percentage, 0);
}

bool cpu_throttle_active(void)
{
    return (cpu_throttle_get_percentage() != 0);
}

int cpu_throttle_get_percentage(void)
{
    return atomic_read(&throttle_percentage);
}

void cpu_ticks_init(void)
{
    seqlock_init(&timers_state.vm_clock_seqlock);
    vmstate_register(NULL, 0, &vmstate_timers, &timers_state);
    throttle_timer = timer_new_ns(QEMU_CLOCK_VIRTUAL_RT,
                                           cpu_throttle_timer_tick, NULL);
}

void configure_icount(QemuOpts *opts, Error **errp)
{
    const char *option;
    char *rem_str = NULL;

    option = qemu_opt_get(opts, "shift");
    if (!option) {
        if (qemu_opt_get(opts, "align") != NULL) {
            error_setg(errp, "Please specify shift option when using align");
        }
        return;
    }

    icount_sleep = qemu_opt_get_bool(opts, "sleep", true);
    if (icount_sleep) {
        icount_warp_timer = timer_new_ns(QEMU_CLOCK_VIRTUAL_RT,
                                         icount_timer_cb, NULL);
    }

    icount_align_option = qemu_opt_get_bool(opts, "align", false);

    if (icount_align_option && !icount_sleep) {
        error_setg(errp, "align=on and sleep=off are incompatible");
    }
    if (strcmp(option, "auto") != 0) {
        errno = 0;
        icount_time_shift = strtol(option, &rem_str, 0);
        if (errno != 0 || *rem_str != '\0' || !strlen(option)) {
            error_setg(errp, "icount: Invalid shift value");
        }
        use_icount = 1;
        return;
    } else if (icount_align_option) {
        error_setg(errp, "shift=auto and align=on are incompatible");
    } else if (!icount_sleep) {
        error_setg(errp, "shift=auto and sleep=off are incompatible");
    }

    use_icount = 2;

    /* 125MIPS seems a reasonable initial guess at the guest speed.
       It will be corrected fairly quickly anyway.  */
    icount_time_shift = 3;

    /* Have both realtime and virtual time triggers for speed adjustment.
       The realtime trigger catches emulated time passing too slowly,
       the virtual time trigger catches emulated time passing too fast.
       Realtime triggers occur even when idle, so use them less frequently
       than VM triggers.  */
    icount_rt_timer = timer_new_ms(QEMU_CLOCK_VIRTUAL_RT,
                                   icount_adjust_rt, NULL);
    timer_mod(icount_rt_timer,
                   qemu_clock_get_ms(QEMU_CLOCK_VIRTUAL_RT) + 1000);
    icount_vm_timer = timer_new_ns(QEMU_CLOCK_VIRTUAL,
                                        icount_adjust_vm, NULL);
    timer_mod(icount_vm_timer,
                   qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL) +
                   NANOSECONDS_PER_SECOND / 10);
}

/***********************************************************/
/* TCG vCPU kick timer
 *
 * The kick timer is responsible for moving single threaded vCPU
 * emulation on to the next vCPU. If more than one vCPU is running a
 * timer event with force a cpu->exit so the next vCPU can get
 * scheduled.
 *
 * The timer is removed if all vCPUs are idle and restarted again once
 * idleness is complete.
 */

static QEMUTimer *tcg_kick_vcpu_timer;
static CPUState *tcg_current_rr_cpu;

#define TCG_KICK_PERIOD (NANOSECONDS_PER_SECOND / 10)

static inline int64_t qemu_tcg_next_kick(void)
{
    return qemu_clock_get_ns(QEMU_CLOCK_VIRTUAL) + TCG_KICK_PERIOD;
}

/* Kick the currently round-robin scheduled vCPU */
static void qemu_cpu_kick_rr_cpu(void)
{
    CPUState *cpu;
    do {
        cpu = atomic_mb_read(&tcg_current_rr_cpu);
        if (cpu) {
            cpu_exit(cpu);
        }
    } while (cpu != atomic_mb_read(&tcg_current_rr_cpu));
}

static void do_nothing(CPUState *cpu, run_on_cpu_data unused)
{
}

void qemu_timer_notify_cb(void *opaque, QEMUClockType type)
{
    if (!use_icount || type != QEMU_CLOCK_VIRTUAL) {
        qemu_notify_event();
        return;
    }

    if (!qemu_in_vcpu_thread() && first_cpu) {
        /* qemu_cpu_kick is not enough to kick a halted CPU out of
         * qemu_tcg_wait_io_event.  async_run_on_cpu, instead,
         * causes cpu_thread_is_idle to return false.  This way,
         * handle_icount_deadline can run.
         */
        async_run_on_cpu(first_cpu, do_nothing, RUN_ON_CPU_NULL);
    }
}

static void kick_tcg_thread(void *opaque)
{
    timer_mod(tcg_kick_vcpu_timer, qemu_tcg_next_kick());
    qemu_cpu_kick_rr_cpu();
}

static void start_tcg_kick_timer(void)
{
    if (!mttcg_enabled && !tcg_kick_vcpu_timer && CPU_NEXT(first_cpu)) {
        tcg_kick_vcpu_timer = timer_new_ns(QEMU_CLOCK_VIRTUAL,
                                           kick_tcg_thread, NULL);
        timer_mod(tcg_kick_vcpu_timer, qemu_tcg_next_kick());
    }
}

static void stop_tcg_kick_timer(void)
{
    if (tcg_kick_vcpu_timer) {
        timer_del(tcg_kick_vcpu_timer);
        tcg_kick_vcpu_timer = NULL;
    }
}

/***********************************************************/
void hw_error(const char *fmt, ...)
{
    va_list ap;
    CPUState *cpu;

    va_start(ap, fmt);
    fprintf(stderr, "qemu: hardware error: ");
    vfprintf(stderr, fmt, ap);
    fprintf(stderr, "\n");
    CPU_FOREACH(cpu) {
        fprintf(stderr, "CPU #%d:\n", cpu->cpu_index);
        cpu_dump_state(cpu, stderr, fprintf, CPU_DUMP_FPU);
    }
    va_end(ap);
    abort();
}

void cpu_synchronize_all_states(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        cpu_synchronize_state(cpu);
    }
}

void cpu_synchronize_all_post_reset(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        cpu_synchronize_post_reset(cpu);
    }
}

void cpu_synchronize_all_post_init(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        cpu_synchronize_post_init(cpu);
    }
}

void cpu_synchronize_all_pre_loadvm(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        cpu_synchronize_pre_loadvm(cpu);
    }
}

static int do_vm_stop(RunState state)
{
    int ret = 0;

    if (runstate_is_running()) {
        cpu_disable_ticks();
        pause_all_vcpus();
        runstate_set(state);
        vm_state_notify(0, state);
        qapi_event_send_stop(&error_abort);
    }

    bdrv_drain_all();
    replay_disable_events();
    ret = bdrv_flush_all();

    return ret;
}

static bool cpu_can_run(CPUState *cpu)
{
    if (cpu->stop) {
        return false;
    }
    if (cpu_is_stopped(cpu)) {
        return false;
    }
    return true;
}

static void cpu_handle_guest_debug(CPUState *cpu)
{
    gdb_set_stop_cpu(cpu);
    qemu_system_debug_request();
    cpu->stopped = true;
}

#ifdef CONFIG_LINUX
static void sigbus_reraise(void)
{
    sigset_t set;
    struct sigaction action;

    memset(&action, 0, sizeof(action));
    action.sa_handler = SIG_DFL;
    if (!sigaction(SIGBUS, &action, NULL)) {
        raise(SIGBUS);
        sigemptyset(&set);
        sigaddset(&set, SIGBUS);
        pthread_sigmask(SIG_UNBLOCK, &set, NULL);
    }
    perror("Failed to re-raise SIGBUS!\n");
    abort();
}

static void sigbus_handler(int n, siginfo_t *siginfo, void *ctx)
{
    if (siginfo->si_code != BUS_MCEERR_AO && siginfo->si_code != BUS_MCEERR_AR) {
        sigbus_reraise();
    }

    if (current_cpu) {
        /* Called asynchronously in VCPU thread.  */
        if (kvm_on_sigbus_vcpu(current_cpu, siginfo->si_code, siginfo->si_addr)) {
            sigbus_reraise();
        }
    } else {
        /* Called synchronously (via signalfd) in main thread.  */
        if (kvm_on_sigbus(siginfo->si_code, siginfo->si_addr)) {
            sigbus_reraise();
        }
    }
}

static void qemu_init_sigbus(void)
{
    struct sigaction action;

    memset(&action, 0, sizeof(action));
    action.sa_flags = SA_SIGINFO;
    action.sa_sigaction = sigbus_handler;
    sigaction(SIGBUS, &action, NULL);

    prctl(PR_MCE_KILL, PR_MCE_KILL_SET, PR_MCE_KILL_EARLY, 0, 0);
}
#else /* !CONFIG_LINUX */
static void qemu_init_sigbus(void)
{
}
#endif /* !CONFIG_LINUX */

static QemuMutex qemu_global_mutex;

static QemuThread io_thread;

/* cpu creation */
static QemuCond qemu_cpu_cond;
/* system init */
static QemuCond qemu_pause_cond;

void qemu_init_cpu_loop(void)
{
    qemu_init_sigbus();
    qemu_cond_init(&qemu_cpu_cond);
    qemu_cond_init(&qemu_pause_cond);
    qemu_mutex_init(&qemu_global_mutex);

    qemu_thread_get_self(&io_thread);
}

void run_on_cpu(CPUState *cpu, run_on_cpu_func func, run_on_cpu_data data)
{
    do_run_on_cpu(cpu, func, data, &qemu_global_mutex);
}

static void qemu_kvm_destroy_vcpu(CPUState *cpu)
{
    if (kvm_destroy_vcpu(cpu) < 0) {
        error_report("kvm_destroy_vcpu failed");
        exit(EXIT_FAILURE);
    }
}

static void qemu_tcg_destroy_vcpu(CPUState *cpu)
{
}

static void qemu_wait_io_event_common(CPUState *cpu)
{
    atomic_mb_set(&cpu->thread_kicked, false);
    if (cpu->stop) {
        cpu->stop = false;
        cpu->stopped = true;
        qemu_cond_broadcast(&qemu_pause_cond);
    }
    process_queued_cpu_work(cpu);
}

static bool qemu_tcg_should_sleep(CPUState *cpu)
{
    if (mttcg_enabled) {
        return cpu_thread_is_idle(cpu);
    } else {
        return all_cpu_threads_idle();
    }
}

//zywzyw 

static void qemu_tcg_wait_io_event(CPUState *cpu)
{
    while (afl_wants_cpu_to_stop || qemu_tcg_should_sleep(cpu)) {
        stop_tcg_kick_timer();
        qemu_cond_wait(cpu->halt_cond, &qemu_global_mutex);
    }
    start_tcg_kick_timer();
    qemu_wait_io_event_common(cpu);
}

static void qemu_kvm_wait_io_event(CPUState *cpu)
{
    while (cpu_thread_is_idle(cpu)) {
        qemu_cond_wait(cpu->halt_cond, &qemu_global_mutex);
    }

    qemu_wait_io_event_common(cpu);
}

static void *qemu_kvm_cpu_thread_fn(void *arg)
{
    CPUState *cpu = arg;
    int r;

    rcu_register_thread();

    qemu_mutex_lock_iothread();
    qemu_thread_get_self(cpu->thread);
    cpu->thread_id = qemu_get_thread_id();
    cpu->can_do_io = 1;
    current_cpu = cpu;

    r = kvm_init_vcpu(cpu);
    if (r < 0) {
        fprintf(stderr, "kvm_init_vcpu failed: %s\n", strerror(-r));
        exit(1);
    }

    kvm_init_cpu_signals(cpu);

    /* signal CPU creation */
    cpu->created = true;
    qemu_cond_signal(&qemu_cpu_cond);

    do {
        if (cpu_can_run(cpu)) {
            r = kvm_cpu_exec(cpu);
            if (r == EXCP_DEBUG) {
                cpu_handle_guest_debug(cpu);
            }
        }
        qemu_kvm_wait_io_event(cpu);
    } while (!cpu->unplug || cpu_can_run(cpu));

    qemu_kvm_destroy_vcpu(cpu);
    cpu->created = false;
    qemu_cond_signal(&qemu_cpu_cond);
    qemu_mutex_unlock_iothread();
    return NULL;
}

static void *qemu_dummy_cpu_thread_fn(void *arg)
{
#ifdef _WIN32
    fprintf(stderr, "qtest is not supported under Windows\n");
    exit(1);
#else
    CPUState *cpu = arg;
    sigset_t waitset;
    int r;

    rcu_register_thread();

    qemu_mutex_lock_iothread();
    qemu_thread_get_self(cpu->thread);
    cpu->thread_id = qemu_get_thread_id();
    cpu->can_do_io = 1;
    current_cpu = cpu;

    sigemptyset(&waitset);
    sigaddset(&waitset, SIG_IPI);

    /* signal CPU creation */
    cpu->created = true;
    qemu_cond_signal(&qemu_cpu_cond);

    while (1) {
        qemu_mutex_unlock_iothread();
        do {
            int sig;
            r = sigwait(&waitset, &sig);
        } while (r == -1 && (errno == EAGAIN || errno == EINTR));
        if (r == -1) {
            perror("sigwait");
            exit(1);
        }
        qemu_mutex_lock_iothread();
        qemu_wait_io_event_common(cpu);
    }

    return NULL;
#endif
}

static int64_t tcg_get_icount_limit(void)
{
    int64_t deadline;

    if (replay_mode != REPLAY_MODE_PLAY) {
        deadline = qemu_clock_deadline_ns_all(QEMU_CLOCK_VIRTUAL);

        /* Maintain prior (possibly buggy) behaviour where if no deadline
         * was set (as there is no QEMU_CLOCK_VIRTUAL timer) or it is more than
         * INT32_MAX nanoseconds ahead, we still use INT32_MAX
         * nanoseconds.
         */
        if ((deadline < 0) || (deadline > INT32_MAX)) {
            deadline = INT32_MAX;
        }

        return qemu_icount_round(deadline);
    } else {
        return replay_get_instructions();
    }
}

static void handle_icount_deadline(void)
{
    assert(qemu_in_vcpu_thread()); 
    if (use_icount) {
        int64_t deadline =
            qemu_clock_deadline_ns_all(QEMU_CLOCK_VIRTUAL);

        if (deadline == 0) {
            /* Wake up other AioContexts.  */
            qemu_clock_notify(QEMU_CLOCK_VIRTUAL);
            qemu_clock_run_timers(QEMU_CLOCK_VIRTUAL);
        }
    }
}

static void prepare_icount_for_run(CPUState *cpu)
{
    if (use_icount) {
        int insns_left;

        /* These should always be cleared by process_icount_data after
         * each vCPU execution. However u16.high can be raised
         * asynchronously by cpu_exit/cpu_interrupt/tcg_handle_interrupt
         */
        g_assert(cpu->icount_decr.u16.low == 0);
        g_assert(cpu->icount_extra == 0);

        cpu->icount_budget = tcg_get_icount_limit();
        insns_left = MIN(0xffff, cpu->icount_budget);
        cpu->icount_decr.u16.low = insns_left;
        cpu->icount_extra = cpu->icount_budget - insns_left;
    }
}

static void process_icount_data(CPUState *cpu)
{
    if (use_icount) {
        /* Account for executed instructions */
        cpu_update_icount(cpu);

        /* Reset the counters */
        cpu->icount_decr.u16.low = 0;
        cpu->icount_extra = 0;
        cpu->icount_budget = 0;

        replay_account_executed_instructions();
    }
}


static int tcg_cpu_exec(CPUState *cpu)
{
    int ret;
#ifdef CONFIG_PROFILER
    int64_t ti;
#endif

#ifdef CONFIG_PROFILER
    ti = profile_getclock();
#endif
    qemu_mutex_unlock_iothread();
    cpu_exec_start(cpu);
    ret = cpu_exec(cpu);
    cpu_exec_end(cpu);
    CPUArchState *env = cpu->env_ptr;
    //if(user_stack_count) printf("after cpu_exec_end:%x\n", env->active_tc.PC);
    qemu_mutex_lock_iothread();
#ifdef CONFIG_PROFILER
    tcg_time += profile_getclock() - ti;
#endif
    return ret;
}

/* Destroy any remaining vCPUs which have been unplugged and have
 * finished running
 */
static void deal_with_unplugged_cpus(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        if (cpu->unplug && !cpu_can_run(cpu)) {
            qemu_tcg_destroy_vcpu(cpu);
            cpu->created = false;
            qemu_cond_signal(&qemu_cpu_cond);
            break;
        }
    }
}

/* Single-threaded TCG
 *
 * In the single-threaded case each vCPU is simulated in turn. If
 * there is more than a single vCPU we create a simple timer to kick
 * the vCPU and ensure we don't get stuck in a tight loop in one vCPU.
 * This is done explicitly rather than relying on side-effects
 * elsewhere.
 */

static void qemu_tcg_init_vcpu(CPUState *cpu);

static void *qemu_tcg_rr_cpu_thread_fn(void *arg)
{
    CPUState *cpu = arg;


    rcu_register_thread();

    qemu_mutex_lock_iothread();
    qemu_thread_get_self(cpu->thread);

    CPU_FOREACH(cpu) {
        cpu->thread_id = qemu_get_thread_id();
        cpu->created = true;
        cpu->can_do_io = 1;
    }
    qemu_cond_signal(&qemu_cpu_cond);

    /* wait for initial kick-off after machine start */

    while (first_cpu->stopped) {
        qemu_cond_wait(first_cpu->halt_cond, &qemu_global_mutex);

        /* process any pending work */
        CPU_FOREACH(cpu) {
            current_cpu = cpu;
            qemu_wait_io_event_common(cpu);
        }
    }
    start_tcg_kick_timer();

    cpu = first_cpu;

    /* process any pending work */
    cpu->exit_request = 1;
label:  
     //while (!afl_wants_cpu_to_stop) {
   while (1) {
        /* Account partial waits to QEMU_CLOCK_VIRTUAL.  */
        qemu_account_warp_timer();

        /* Run the timers here.  This is much more efficient than
         * waking up the I/O thread and waiting for completion.
         */
        handle_icount_deadline(); //zyw
	
        if (!cpu) {
            cpu = first_cpu;
        }
	
        while (cpu && !cpu->queued_work_first && !cpu->exit_request ) {
            atomic_mb_set(&tcg_current_rr_cpu, cpu);
            current_cpu = cpu;
            qemu_clock_enable(QEMU_CLOCK_VIRTUAL,
                              (cpu->singlestep_enabled & SSTEP_NOTIMER) == 0);
            if (cpu_can_run(cpu)) {
                int r;
                prepare_icount_for_run(cpu);
                r = tcg_cpu_exec(cpu);
                process_icount_data(cpu);
                if (r == EXCP_DEBUG) {
                    cpu_handle_guest_debug(cpu);
                    break;
                } else if (r == EXCP_ATOMIC) {
                    qemu_mutex_unlock_iothread();
                    cpu_exec_step_atomic(cpu);
                    qemu_mutex_lock_iothread();
                    break;
                }
            } else if (cpu->stop) {
                if (cpu->unplug) {
                    cpu = CPU_NEXT(cpu);
                }
                break;
            }
            cpu = CPU_NEXT(cpu);
        } /* while (cpu && !cpu->exit_request).. */	
 	
        /* Does not need atomic_mb_set because a spurious wakeup is okay.  */
        atomic_set(&tcg_current_rr_cpu, NULL);
        if (cpu && cpu->exit_request) {
            atomic_mb_set(&cpu->exit_request, 0);
        }
        qemu_tcg_wait_io_event(cpu ? cpu : QTAILQ_FIRST(&cpus));
        deal_with_unplugged_cpus();

    }
    /*
    if(afl_wants_cpu_to_stop){	
	flagg = 1;
	afl_wants_cpu_to_stop = 0;
	//sleep(1);
	//goto label;	
	//qemu_cond_signal(&qemu_cpu_cond);
	//qemu_mutex_unlock_iothread();
	if(write(afl_qemuloop_pipe[1], "FORK", 4) != 4)
	    DECAF_printf("write afl_qemuloop_pip");	
	afl_qemuloop_pipe[1] = -1;
	restart_cpu = first_cpu;
	cpu_disable_ticks();
	if (!cpu) {
            cpu = first_cpu;
        }
        atomic_mb_set(&cpu->exit_request, 0);
        qemu_tcg_wait_io_event(cpu ? cpu : QTAILQ_FIRST(&cpus));
	//deal_with_unplugged_cpus();
	qemu_mutex_unlock_iothread();
	//sleep(1);
	//qemu_mutex_lock_iothread();
	


    }
    */
    printf("out of loop\n");
    return NULL;
}

static void *qemu_hax_cpu_thread_fn(void *arg)
{
    CPUState *cpu = arg;
    int r;

    qemu_mutex_lock_iothread();
    qemu_thread_get_self(cpu->thread);

    cpu->thread_id = qemu_get_thread_id();
    cpu->created = true;
    cpu->halted = 0;
    current_cpu = cpu;

    hax_init_vcpu(cpu);
    qemu_cond_signal(&qemu_cpu_cond);

    while (1) {
        if (cpu_can_run(cpu)) {
            r = hax_smp_cpu_exec(cpu);
            if (r == EXCP_DEBUG) {
                cpu_handle_guest_debug(cpu);
            }
        }

        while (cpu_thread_is_idle(cpu)) {
            qemu_cond_wait(cpu->halt_cond, &qemu_global_mutex);
        }
#ifdef _WIN32
        SleepEx(0, TRUE);
#endif
        qemu_wait_io_event_common(cpu);
    }
    return NULL;
}

#ifdef _WIN32
static void CALLBACK dummy_apc_func(ULONG_PTR unused)
{
}
#endif

/* Multi-threaded TCG
 *
 * In the multi-threaded case each vCPU has its own thread. The TLS
 * variable current_cpu can be used deep in the code to find the
 * current CPUState for a given thread.
 */

//zyw
extern int httpd_pgd;
int stop = 0;
//zyw
int afl_user_fork = 0;
int read_type = -1;
extern void *memfile_start;

static void *qemu_tcg_cpu_thread_fn(void *arg)
{
    CPUState *cpu = arg;

    g_assert(!use_icount);

    rcu_register_thread();

    qemu_mutex_lock_iothread();
    qemu_thread_get_self(cpu->thread);

    cpu->thread_id = qemu_get_thread_id();
    cpu->created = true;
    cpu->can_do_io = 1;
    current_cpu = cpu;
    qemu_cond_signal(&qemu_cpu_cond);

    /* process any pending work */
    cpu->exit_request = 1;
//zywz
    while (1) { //zyw
   // while (!afl_wants_cpu_to_stop) {
        //!afl_wants_cpu_to_stop &&
        CPUArchState * env = (CPUArchState *)cpu->env_ptr;
        if (!afl_wants_cpu_to_stop && cpu_can_run(cpu)) {
            int r;
            r = tcg_cpu_exec(cpu);
            switch (r) {
            case EXCP_DEBUG:
                cpu_handle_guest_debug(cpu);
                break;
            case EXCP_HALTED:
                /* during start-up the vCPU is reset and the thread is
                 * kicked several times. If we don't ensure we go back
                 * to sleep in the halted state we won't cleanly
                 * start-up when the vCPU is enabled.
                 *
                 * cpu->halted should ensure we sleep in wait_io_event
                 */
                g_assert(cpu->halted);
                break;
            case EXCP_ATOMIC:
                qemu_mutex_unlock_iothread();
                cpu_exec_step_atomic(cpu);
                qemu_mutex_lock_iothread();
            default:
                /* Ignore everything else? */
                break;
            }
        } else if (cpu->unplug) {
            qemu_tcg_destroy_vcpu(cpu);
            cpu->created = false;
            qemu_cond_signal(&qemu_cpu_cond);
            qemu_mutex_unlock_iothread();
            return NULL;
        } 
        atomic_mb_set(&cpu->exit_request, 0);
        qemu_tcg_wait_io_event(cpu ? cpu : QTAILQ_FIRST(&cpus));

    }
//zyw

/*
    if(afl_wants_cpu_to_stop) {
    	flagg = 1;
    	afl_wants_cpu_to_stop = 0;
    	//sleep(1);
    	//goto label;	
    	//qemu_cond_signal(&qemu_cpu_cond);
    	//qemu_mutex_unlock_iothread();
    	if(write(afl_qemuloop_pipe[1], "FORK", 4) != 4)
    	    DECAF_printf("write afl_qemuloop_pip");	
    	afl_qemuloop_pipe[1] = -1;
    	printf("afl_wants_cpu_to_stop,first cpu:%x\n",first_cpu);
    	restart_cpu = first_cpu;
    	first_cpu = NULL;
    	cpu_disable_ticks();
        qemu_tcg_wait_io_event(cpu ? cpu : QTAILQ_FIRST(&cpus));
    	//qemu_mutex_unlock_iothread();
    	//sleep(1);
    }
//zyw
    return NULL;
*/
}

static void qemu_cpu_kick_thread(CPUState *cpu)
{
#ifndef _WIN32
    int err;

    if (cpu->thread_kicked) {
        return;
    }
    cpu->thread_kicked = true;
    err = pthread_kill(cpu->thread->thread, SIG_IPI);
    if (err) {
        fprintf(stderr, "qemu:%s: %s", __func__, strerror(err));
        exit(1);
    }
#else /* _WIN32 */
    if (!qemu_cpu_is_self(cpu)) {
        if (!QueueUserAPC(dummy_apc_func, cpu->hThread, 0)) {
            fprintf(stderr, "%s: QueueUserAPC failed with error %lu\n",
                    __func__, GetLastError());
            exit(1);
        }
    }
#endif
}

void qemu_cpu_kick(CPUState *cpu)
{
    qemu_cond_broadcast(cpu->halt_cond);
    if (tcg_enabled()) {
        cpu_exit(cpu);
        /* NOP unless doing single-thread RR */
        qemu_cpu_kick_rr_cpu();
    } else {
        if (hax_enabled()) {
            /*
             * FIXME: race condition with the exit_request check in
             * hax_vcpu_hax_exec
             */
            cpu->exit_request = 1;
        }
        qemu_cpu_kick_thread(cpu);
    }
}

void qemu_cpu_kick_self(void)
{
    assert(current_cpu);
    qemu_cpu_kick_thread(current_cpu);
}

bool qemu_cpu_is_self(CPUState *cpu)
{
    return qemu_thread_is_self(cpu->thread);
}

bool qemu_in_vcpu_thread(void)
{
    return current_cpu && qemu_cpu_is_self(current_cpu);
}

static __thread bool iothread_locked = false;

bool qemu_mutex_iothread_locked(void)
{
    return iothread_locked;
}

void qemu_mutex_lock_iothread(void)
{
    g_assert(!qemu_mutex_iothread_locked());
    qemu_mutex_lock(&qemu_global_mutex);
    iothread_locked = true;
}

void qemu_mutex_unlock_iothread(void)
{
    g_assert(qemu_mutex_iothread_locked());
    iothread_locked = false;
    qemu_mutex_unlock(&qemu_global_mutex);
}

static bool all_vcpus_paused(void)
{
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        if (!cpu->stopped) {
            return false;
        }
    }

    return true;
}

void pause_all_vcpus(void)
{
    CPUState *cpu;

    qemu_clock_enable(QEMU_CLOCK_VIRTUAL, false);
    CPU_FOREACH(cpu) {
        cpu->stop = true;
        qemu_cpu_kick(cpu);
    }

    if (qemu_in_vcpu_thread()) {
        cpu_stop_current();
    }

    while (!all_vcpus_paused()) {
        qemu_cond_wait(&qemu_pause_cond, &qemu_global_mutex);
        CPU_FOREACH(cpu) {
            qemu_cpu_kick(cpu);
        }
    }
}

void cpu_resume(CPUState *cpu)
{
    cpu->stop = false;
    cpu->stopped = false;
    qemu_cpu_kick(cpu);
}

void resume_all_vcpus(void)
{
    CPUState *cpu;

    qemu_clock_enable(QEMU_CLOCK_VIRTUAL, true);
    CPU_FOREACH(cpu) {
        cpu_resume(cpu);
    }
}

void cpu_remove(CPUState *cpu)
{
    cpu->stop = true;
    cpu->unplug = true;
    qemu_cpu_kick(cpu);
}

void cpu_remove_sync(CPUState *cpu)
{
    cpu_remove(cpu);
    while (cpu->created) {
        qemu_cond_wait(&qemu_cpu_cond, &qemu_global_mutex);
    }
}

/* For temporary buffers for forming a name */
#define VCPU_THREAD_NAME_SIZE 16

static QemuThread *single_tcg_cpu_thread;

static void qemu_tcg_init_vcpu(CPUState *cpu)
{
    char thread_name[VCPU_THREAD_NAME_SIZE];
    static QemuCond *single_tcg_halt_cond;
    //static QemuThread *single_tcg_cpu_thread;

    if (qemu_tcg_mttcg_enabled() || !single_tcg_cpu_thread) {
        cpu->thread = g_malloc0(sizeof(QemuThread));
        cpu->halt_cond = g_malloc0(sizeof(QemuCond));
        qemu_cond_init(cpu->halt_cond);
	
        if (1) { //zyw
        //if (qemu_tcg_mttcg_enabled()) {
            /* create a thread per vCPU with TCG (MTTCG) */
            parallel_cpus = true;
            snprintf(thread_name, VCPU_THREAD_NAME_SIZE, "CPU %d/TCG",
                 cpu->cpu_index);
            qemu_thread_create(cpu->thread, thread_name, qemu_tcg_cpu_thread_fn,
                               cpu, QEMU_THREAD_JOINABLE);

        } else {
            /* share a single thread for all cpus with TCG */
            snprintf(thread_name, VCPU_THREAD_NAME_SIZE, "ALL CPUs/TCG");
            qemu_thread_create(cpu->thread, thread_name,
                               qemu_tcg_rr_cpu_thread_fn,
                               cpu, QEMU_THREAD_JOINABLE);
	    
            single_tcg_halt_cond = cpu->halt_cond;
            single_tcg_cpu_thread = cpu->thread;
        }
#ifdef _WIN32
        cpu->hThread = qemu_thread_get_handle(cpu->thread);
#endif
        while (!cpu->created) {
            qemu_cond_wait(&qemu_cpu_cond, &qemu_global_mutex);
        }
    } else {
        /* For non-MTTCG cases we share the thread */
        cpu->thread = single_tcg_cpu_thread;
        cpu->halt_cond = single_tcg_halt_cond;
    }
}

static void qemu_hax_start_vcpu(CPUState *cpu)
{
    char thread_name[VCPU_THREAD_NAME_SIZE];

    cpu->thread = g_malloc0(sizeof(QemuThread));
    cpu->halt_cond = g_malloc0(sizeof(QemuCond));
    qemu_cond_init(cpu->halt_cond);

    snprintf(thread_name, VCPU_THREAD_NAME_SIZE, "CPU %d/HAX",
             cpu->cpu_index);
    qemu_thread_create(cpu->thread, thread_name, qemu_hax_cpu_thread_fn,
                       cpu, QEMU_THREAD_JOINABLE);
#ifdef _WIN32
    cpu->hThread = qemu_thread_get_handle(cpu->thread);
#endif
    while (!cpu->created) {
        qemu_cond_wait(&qemu_cpu_cond, &qemu_global_mutex);
    }
}

static void qemu_kvm_start_vcpu(CPUState *cpu)
{
    char thread_name[VCPU_THREAD_NAME_SIZE];

    cpu->thread = g_malloc0(sizeof(QemuThread));
    cpu->halt_cond = g_malloc0(sizeof(QemuCond));
    qemu_cond_init(cpu->halt_cond);
    snprintf(thread_name, VCPU_THREAD_NAME_SIZE, "CPU %d/KVM",
             cpu->cpu_index);
    qemu_thread_create(cpu->thread, thread_name, qemu_kvm_cpu_thread_fn,
                       cpu, QEMU_THREAD_JOINABLE);
    while (!cpu->created) {
        qemu_cond_wait(&qemu_cpu_cond, &qemu_global_mutex);
    }
}

static void qemu_dummy_start_vcpu(CPUState *cpu)
{
    char thread_name[VCPU_THREAD_NAME_SIZE];

    cpu->thread = g_malloc0(sizeof(QemuThread));
    cpu->halt_cond = g_malloc0(sizeof(QemuCond));
    qemu_cond_init(cpu->halt_cond);
    snprintf(thread_name, VCPU_THREAD_NAME_SIZE, "CPU %d/DUMMY",
             cpu->cpu_index);
    qemu_thread_create(cpu->thread, thread_name, qemu_dummy_cpu_thread_fn, cpu,
                       QEMU_THREAD_JOINABLE);
    while (!cpu->created) {
        qemu_cond_wait(&qemu_cpu_cond, &qemu_global_mutex);
    }
}



//zyw
typedef struct CPUSHSTATE{
	target_ulong regs[32];
	target_ulong PC;
	target_ulong CP0_Status;
        target_ulong CP0_EPC;
        target_ulong CP0_Cause;
} CPUSHSTATE;

typedef struct MISSING_PAGE{
	target_ulong addr;
	int prot; 
    int mmu_idx;
} MISSING_PAGE;


static int pipe_read_fd = -1;
static int pipe_write_fd = -1; 


void loadCPUShState(CPUSHSTATE *state, CPUArchState *env)
{
	for(int i=0; i<32; i++)
	{
		env->active_tc.gpr[i] = state->regs[i];
	}
	env->active_tc.PC = state->PC;
	env->CP0_Status = state->CP0_Status;
	env->CP0_Cause = state->CP0_Cause;
	env->CP0_EPC = state->CP0_EPC;	
	
}

void storeCPUShState(CPUSHSTATE *state, CPUArchState *env)
{
	for(int i=0; i<32; i++)
	{
		state->regs[i] = env->active_tc.gpr[i];
	}
	state->PC = env->active_tc.PC;
	state->CP0_Status = env->CP0_Status; 
	state->CP0_Cause = env->CP0_Cause; 
	state->CP0_EPC = env->CP0_EPC; 
}

/*
int write_mapping(int gva, int hva)  
{  
    const char *fifo_name_full = "/home/zyw/tmp/afl_user_mode/image/pipe/full_cpu_state";  
    const char *fifo_name_user = "/home/zyw/tmp/afl_user_mode/image/pipe/user_cpu_state";  
    int pipe_fd = -1;  
    int res = 0;  
    const int open_mode_full = O_WRONLY;
    const int open_mode_user = O_RDONLY;    
    int bytes_sent = 0;  
    char buffer[PIPE_BUF + 1];  
    if(access(fifo_name_full, F_OK) == -1)  
    {  
        res = mkfifo(fifo_name_full, 0777);  
        if(res != 0)  
        {  
            fprintf(stderr, "Could not create fifo %s\n", fifo_name_full);  
            exit(EXIT_FAILURE);  
        }  
    } 

    pipe_fd = open(fifo_name_full, open_mode_full);    
    if(pipe_fd != -1)  
    {  
	int bytes_read = 0;  
	write(pipe_fd, &gva, sizeof(int));
	res = write(pipe_fd, &hva, 2*sizeof(int));
	if(res == -1)  
	{  
		fprintf(stderr, "Write error on pipe\n");  
		exit(EXIT_FAILURE);  
	}  
	printf("write mapping %x,%x ok\n", gva, hva);
        close(pipe_fd);   
    }  
    else{
	printf("write state failure\n");  
        exit(EXIT_FAILURE);  
    }
    return 1;
}  
*/


int write_addr(uintptr_t ori_addr, uintptr_t addr)  
{  
    int res = 0;  
    if(pipe_write_fd == -1){
        const char *fifo_name_full = write_pipename;  
        const int open_mode_full = O_WRONLY;  
        if(access(fifo_name_full, F_OK) == -1)  
        {  
            res = mkfifo(fifo_name_full, 0777);  
            if(res != 0)  
            {  
                printf("Could not create fifo %s\n", fifo_name_full);  
                exit(EXIT_FAILURE);  
            }  
        } 
        pipe_write_fd = open(fifo_name_full, open_mode_full); 
        printf("open pipe write:%d\n", pipe_write_fd);   
    }
   
    if(pipe_write_fd != -1)  
    {  
    	int bytes_read = 0;  
    	res = write(pipe_write_fd, &addr, sizeof(uintptr_t));  
    	if(res == -1)  
    	{  
    		printf("Write addr error on pipe\n");  
    		exit(EXIT_FAILURE);  
    	}  
        //printf("write addr ok:%lx, %lx\n",ori_addr, addr);
    }  
    else{
        printf("write addr failure\n");  
        exit(EXIT_FAILURE);  
    }
    return 1;
}  



/*
typedef struct STORE_ADDR{
	uintptr_t virt_addr;
	uintptr_t phys_addr;
	uintptr_t host_addr;
	int data;
	struct STORE_ADDR *next;
} STORE_ADDR;

STORE_ADDR *data_pt[0x1000];

int data_length(unsigned long value)
{
	int data_len = 0; //byte
	if((value & 0xffffff00) == 0)
	{
		data_len = 1;
	}
	else if((value & 0xffff0000) == 0)
	{
		data_len = 2;
	}
	else
	{
		data_len = 4;
	}
	return data_len;	
}

//addr is host virtual addr
void store_addr(uint32_t virt_addr, uint32_t phys_addr, uintptr_t host_addr, int type, unsigned long value)
{

	if (type == 1) //handle_addr
	{
		//printf("store addr:%lx\n", virt_addr);
		return; 
	}

	int data_len = data_length(value);
	int data = 0;
	cpu_physical_memory_rw(phys_addr, &data,  data_len, 0);
	
	int addr_exist = 0;
	int index = (host_addr >> 12) & 0xfff;
	STORE_ADDR * tmp_p = data_pt[index];
	STORE_ADDR * last_p;
	if(tmp_p == NULL)
	{
		STORE_ADDR * st = malloc(sizeof(STORE_ADDR));
		st->virt_addr = virt_addr;
		st->host_addr = host_addr;
		st->phys_addr = phys_addr;
		//printf("store addr:%lx, %lx, %lx, %lx\n", virt_addr, data, value, data_len);
		st->data = data;
		st->next = NULL;  
		data_pt[index] = st;
		return;
	}
	while(tmp_p!= NULL)
	{
		last_p = tmp_p;
		if(virt_addr == tmp_p->virt_addr){
			addr_exist = 1;			
			return;
		}
		tmp_p = tmp_p->next;
	}
	if(addr_exist == 0){
		STORE_ADDR * st = malloc(sizeof(STORE_ADDR));
		st->virt_addr = virt_addr;
		st->host_addr = host_addr;
		st->phys_addr = phys_addr;
		printf("store addr:%lx, %lx, %lx, %lx\n", virt_addr, data, value, data_len);
		st->data = data;
		st->next = NULL;
		last_p->next = st;
	}
}	

void restore_addr()
{
	printf("restore addr\n");
	for(int i=0; i<0x1000; i++)
	{
		STORE_ADDR * tmp_p = data_pt[i];	
		while(tmp_p){
			uintptr_t virt_addr = tmp_p-> virt_addr;			
			uintptr_t phys_addr = tmp_p-> phys_addr;
			uintptr_t host_addr = tmp_p-> host_addr;
			int data = tmp_p-> data;
			int data_len = data_length(data);
			cpu_physical_memory_rw(phys_addr, &data,  data_len, 1);
			printf("restore addr:%lx, %lx, %lx\n", virt_addr, data, data_len);
			tmp_p = tmp_p->next;
		}
	}
}
*/


extern int exception;
extern TranslationBlock *tb_find(CPUState *cpu,
                                        TranslationBlock *last_tb,
                                        int tb_exit);
extern bool cpu_handle_exception(CPUState *cpu, int *ret);
extern bool cpu_handle_interrupt(CPUState *cpu,
                                        TranslationBlock **last_tb);
extern void cpu_loop_exec_tb(CPUState *cpu, TranslationBlock *tb,
                                    TranslationBlock **last_tb, int *tb_exit);
extern ram_addr_t qemu_ram_addr_from_host_nofail(void *ptr);


typedef struct STORE_PAGE{
	uintptr_t prev_addr;
	uintptr_t curr_addr;
	struct STORE_PAGE * page;
} STORE_PAGE;


STORE_PAGE *pt[0x1000];



void store_page(uint32_t virt_addr, uintptr_t addr, int in_httpd)
{ 
    full_store_count += 1; 
	int page_exist = 0;
	int index = (addr >> 12) & 0xfff;
	STORE_PAGE * tmp_p = pt[index];
	STORE_PAGE * last_p;
	if(tmp_p == NULL)
	{	
		STORE_PAGE * page = malloc(sizeof(STORE_PAGE));
		void * dst = malloc(0x1000);
		memset(dst, 0, 0x1000);
		void * src = addr;
		memcpy(dst, src, 0x1000);
        //full_store_count += 1; 
        uint32_t phys_addr = qemu_ram_addr_from_host_nofail(addr);
        //show_store_phys();
		//printf("index1:%x, copy finished from %lx(%lx) to %lx, int_httpd:%x\n",  virt_addr, src, phys_addr, dst, in_httpd);
		page->prev_addr = src;
		page->curr_addr = dst;
		page->page = NULL;  
		pt[index] = page;
		return;
	}
	while(tmp_p!= NULL)
	{
		last_p = tmp_p;
		//printf("store page:%lx,%lx\n", addr, tmp_p->prev_addr);
		if(addr == tmp_p->prev_addr){
			page_exist = 1;			
			return;
		}
		//printf("into list\n");
		tmp_p = tmp_p->page;
	}
	if(page_exist == 0){
		STORE_PAGE * page = malloc(sizeof(STORE_PAGE));
		void * dst = malloc(0x1000);
		memset(dst, 0, 0x1000);
		void * src = addr;
		memcpy(dst, src, 0x1000);
        //full_store_count += 1; 
        uint32_t phys_addr = qemu_ram_addr_from_host_nofail(addr);
		//printf("index2:%x, copy finished from %lx(%lx) to %lx, in_httpd:%x\n", virt_addr , src, phys_addr, dst, in_httpd);
		page->prev_addr = src;
		page->curr_addr = dst;
		page->page = NULL;  
		last_p->page = page;
	}

}

//remember to free page 
void restore_page()
{
	//DECAF_printf("restore page\n");
	for(int i=0; i<0x1000; i++)
	{
		STORE_PAGE * tmp_p = pt[i];	
		STORE_PAGE * last = NULL;
		while(tmp_p){
			last = tmp_p;
			uintptr_t dst = tmp_p-> prev_addr;
			uintptr_t src = tmp_p-> curr_addr;
            if(dst && src)
            {
                char tmp[0x1000];
                memcpy(tmp, src, 0x1000);
                memcpy(dst, tmp, 0x1000);
                //memcpy(dst, src, 0x1000);
                //printf("restore from %lx to %lx\n", src, dst);
                free(src);
                tmp_p = tmp_p->page;
                free(last);
            }
            else
            {
                printf("restore page error:%lx,%lx\n", pt[i], tmp_p);
                exit(32);
            }
            
		}
		pt[i] = NULL;
	}
}



int open_read_pipe()
{
    const char *fifo_name_user = init_read_pipename;
    const int open_mode_user = O_RDONLY | O_NONBLOCK; 
    int res = 0;  
    if(access(fifo_name_user, F_OK) == -1)  
    {  
        res = mkfifo(fifo_name_user, 0777);  
        if(res != 0)  
        {  
            fprintf(stderr, "Could not create fifo %s\n", fifo_name_user);  
            exit(EXIT_FAILURE);  
        }  
    } 
    pipe_read_fd = open(fifo_name_user, open_mode_user);
    if(pipe_read_fd != -1)  
    {
	return pipe_read_fd;
    }
    return -1;
}  



typedef struct addrPair{
	uint32_t virt_addr;
	uintptr_t host_addr;

} addrPair;

typedef struct 
{
  double handle_state_time;
  double handle_addr_time;
  double handle_syscall_time;
  double store_page_time;
  double restore_page_time;
  int user_syscall_count;
  int user_store_count;
} USER_MODE_TIME;

int is_loop_over = 1;
double full_store_page_time = 0.0;
extern double time_interval_total;
extern int syscall_count;
extern int print_loop_times;
extern int print_loop_count;


struct timeval loop_begin;
struct timeval loop_end;
struct timeval restore_begin;
struct timeval restore_end;
struct timeval store_begin;
struct timeval store_end;

//zyw fix the all the pipe read error: no data, data is wrong;
int read_content(int pipe_fd, char *buf, int total_len)
{
    int rest_len = total_len;
    int read_len = 0;
    int read_len_once = 0;
    do
    {
        //printf("read_len:%x, rest_len:%x\n", read_len, rest_len);
        read_len_once = read(pipe_fd, buf + read_len, rest_len);
        if(read_len_once == -1)
        {
            continue;
        }
        rest_len -= read_len_once;
        read_len += read_len_once;
    }
    while(rest_len!=0);
    return read_len;

}

r4k_tlb_t saved_tlb[MIPS_TLB_MAX];

void store_tlb(CPUMIPSState * env)
{
    for(int i =0; i<MIPS_TLB_MAX; i++)
    {
        saved_tlb[i] = env->tlb->mmu.r4k.tlb[i];
    }
}

void reload_tlb(CPUMIPSState * env)
{
    for(int i =0; i<MIPS_TLB_MAX; i++)
    {
        env->tlb->mmu.r4k.tlb[i] = saved_tlb[i];
    }
}


int first_time = 0;

int read_state(CPUArchState * env, MISSING_PAGE *page)  
{  	
     int type;
     char buf[1024]; 
     memset(buf, 0, 1024);
     int res = 0;   
     if(pipe_read_fd != -1)
     {
    	if(read_type == -1)
    	{
    		res = read_content(pipe_read_fd, &type, sizeof(int));
    		if(res == -1)  
    		{
                printf("after fix the pipe usage error, will not step into here");	
    			return -1;
    		
    		}
    		else if(res == 0)
    		{
                printf("after fix the pipe usage error, will not step into here");  
    			return -1;
    		}
    		else{
    			//printf("res is %x, type is %x\n", res, type);
    			read_type = type;
    			return -1;
    		}
    	}
    	switch (read_type){
    		case 0:
    		{
    			res = read_content(pipe_read_fd, page, sizeof(MISSING_PAGE));
                int data = 0;
    		    //printf("read addr:%x\n", page->addr);
    			read_type = -1;
    			return 0;
    		}
    		case 1:
    		{	
    			CPUSHSTATE cpustate;
    			res = read_content(pipe_read_fd, &cpustate, sizeof(CPUSHSTATE));
    			loadCPUShState(&cpustate, env);
    			//printf("read state:%x\n", cpustate.PC);
    			read_type = -1;	
    			return 1;
    		}
    		case 2:
    		{
    			target_ulong cmd;
                USER_MODE_TIME user_mode_time;
    			res = read_content(pipe_read_fd, &cmd, sizeof(target_ulong));
                res = read_content(pipe_read_fd, &user_mode_time, sizeof(USER_MODE_TIME));
    			if(cmd == 0x10 && is_loop_over) {
                    is_loop_over = 0;
    				afl_user_fork = 1;
                    gettimeofday(&loop_begin, NULL);
                    if(print_debug)
                    {
                       printf("cmd is %x #######################################\n", cmd);
                    }
                    print_loop_count++;
                    //sleep(5);

                    if(first_time == 0)
                    {
                        first_time = 1;
                        store_tlb(env);
                    }
                    else
                    {
                       reload_tlb(env);
                    }

    			}
    			else if(cmd == 0x20  && !is_loop_over) 
    			{	

                    double handle_state_time = user_mode_time.handle_state_time;
                    double handle_addr_time = user_mode_time.handle_addr_time;
                    double handle_syscall_time = user_mode_time.handle_syscall_time;
                    double user_store_page_time = user_mode_time.store_page_time;
                    double user_restore_page_time = user_mode_time.restore_page_time;
                    int user_syscall_count = user_mode_time.user_syscall_count;
                    int user_store_count = user_mode_time.user_store_count;

                    gettimeofday(&loop_end, NULL);
                    double total_loop_time =  (double)loop_end.tv_sec - loop_begin.tv_sec + (loop_end.tv_usec - loop_begin.tv_usec)/1000000.0;
                    gettimeofday(&restore_begin, NULL);
    				restore_page(); //restore_addr();
                    gettimeofday(&restore_end, NULL);
                    double restore_time = (double)restore_end.tv_sec - restore_begin.tv_sec + (restore_end.tv_usec - restore_begin.tv_usec)/1000000.0;
                    if(print_debug)
                    {
                        printf("----------------------------------------------------------\n");
                    }
                    if(print_loop_count == print_loop_times)
                    {
                        print_loop_count = 0;
                        //printf("total loop time:%fs,syacall execute:%fs, restore page time:%fs\n", total_loop_time, time_interval_total, restore_time);
                        DECAF_printf("%f:%f:%f:%f:%f:%f:%f:%f:%f:%d:%d:%f:%d:%d\n", total_loop_time, time_interval_total, full_store_page_time, restore_time, user_store_page_time, user_restore_page_time, handle_state_time - time_interval_total, handle_addr_time, total_loop_time  - full_store_page_time - restore_time - handle_state_time - handle_addr_time - user_restore_page_time- user_store_page_time, syscall_count, user_syscall_count, handle_syscall_time, full_store_count, user_store_count);


                    }
                    time_interval_total = 0.0;
                    full_store_page_time = 0.0;
                    full_store_count = 0;
                    syscall_count = 0;
                    close(pipe_write_fd);
                    pipe_write_fd = -1;     
                    is_loop_over = 1;

    			}
                else
                {
                    printf("one loop not over, cmd:%x, is_loop_over:%d\n", cmd, is_loop_over);
                    exit(32);
                }
    			read_type = -1;
    			return -1;
    		}
    		default:
    		{
    			printf("############error############ cmd is:%x\n", read_type);
    			read_type = -1;
    			exit(32);
    		}
    	}
    }  
    else{
	printf("pipe fd not right\n");    
        exit(EXIT_FAILURE);
    }  
} 


int write_state(CPUArchState * env)  
{  
    int res = 0;  
    CPUSHSTATE cpustate;
    storeCPUShState(&cpustate, env);
    if(pipe_write_fd == -1)
    {
        const char *fifo_name_full = write_pipename;  
        const int open_mode_full = O_WRONLY;
        if(access(fifo_name_full, F_OK) == -1)  
        {  
            res = mkfifo(fifo_name_full, 0777);  
            if(res != 0)  
            {  
                fprintf(stderr, "Could not create fifo %s\n", fifo_name_full);  
                exit(EXIT_FAILURE);  
            }  
        } 
        pipe_write_fd = open(fifo_name_full, open_mode_full);    
    }
    if(pipe_write_fd != -1)  
    {  
    	int bytes_read = 0;  
    	res = write(pipe_write_fd, &cpustate, sizeof(cpustate));  
    	if(res == -1)  
    	{  
    		fprintf(stderr, "Write error on pipe\n");  
    		exit(EXIT_FAILURE);  
    	}  
    	//printf("write state ok:%x\n", cpustate.PC);
    }  
    else{
	    printf("write state failure\n");  
        exit(EXIT_FAILURE);  
    }
    return 1;
} 

#include <sys/ipc.h>
#include <sys/shm.h>


//snapshot consistence
#ifdef SNAPSHOT_SYNC
char *phys_addr_stored_bitmap; 


int add_physical_page(int phys_addr)
{
    int value = phys_addr >> 12;
    int index = value >> 3;
    int position = value & 0x07;

    phys_addr_stored_bitmap[index] |=  1 << position;
}

//if not exist,add it and return -1; if exist, return index;
int if_physical_exist(int phys_addr) //phys_addr <= 0x7ffff000
{   
    int value = phys_addr >> 12;
    int index = value >> 3;
    int position = value & 0x07;
    return (phys_addr_stored_bitmap[index] & (1 << position)) !=0; 

}
#endif 

void show_store_phys()
{

}

target_ulong pause_stateTransit_wait(CPUState *cpu)
{	
    CPUArchState * env = cpu->env_ptr;
    FILE * fp = fopen(mapping_filename, "a+");
    printf("map file:%s\n", mapping_filename);
    for(int i=0;i<32;i++) {
        fprintf(fp, "%x\n", env->active_tc.gpr[i]);
    }
    fprintf(fp, "%x\n", env->active_tc.PC);
    fprintf(fp ,"%x\n", env->CP0_Status);
    fprintf(fp ,"%x\n", env->CP0_Cause);
    fprintf(fp ,"%x\n", env->CP0_EPC);
    printf("user pc:%x, stack:%x,  qemu pid:%x\n", env->active_tc.PC, env->active_tc.gpr[29], getpid());

    //print_mapped_page(fp); // ###3old version print memory mapping to file




#ifdef PRE_MAPPING
    char modname[512];
    char functionname[512];
    target_ulong base;
    target_ulong pgd = DECAF_getPGD(cpu);
    printf("print_mapping for %x\n", pgd);
    print_mapping(modname, pgd, &base, fp);// obtain mapping
#else
    for(int i=2; i<=2; i++)
    {
        for(int j =0; j <= 255; j++)
        {//CPUTLBEntry
            uintptr_t t = env->tlb_table[i][j].addend;
            target_ulong read = env->tlb_table[i][j].addr_read;
            target_ulong write = env->tlb_table[i][j].addr_write;
            target_ulong code = env->tlb_table[i][j].addr_code;
            if(t!=-1)
            {
                DECAF_printf("i:%d,j:%d,%lx,%lx,%lx,%lx\n", i,j,read,write,code,t);
                if(write!=-1)
                {
                    fprintf(fp, "%lx:", write);
                    fprintf(fp, "%lx:", write + t);
                    fprintf(fp, "%d\n", 1);
                }
                else
                {
                    fprintf(fp, "%lx:", read);
                    fprintf(fp, "%lx:", read + t);
                    fprintf(fp, "%d\n", 0);
                }
            }
        }
    }
    fclose(fp);
#endif


	printf("stop\n\n");
	//vm_stop(RUN_STATE_RESTORE_VM);

//memory for snapshot consistency
#ifdef SNAPSHOT_SYNC
    int shmem_id = shmget(0, 8192, IPC_CREAT|IPC_EXCL); //0xfffffff(7 bit)  orig:131072
    printf("share mem id:%d\n", shmem_id);
    void * shmem_start = shmat(shmem_id, NULL,  1); 
    memset(shmem_start, 0, 8192);
    phys_addr_stored_bitmap = (char *)shmem_start;
#endif
    afl_wants_cpu_to_stop = 1;
	//vm_stop(RUN_STATE_PAUSED);

}	

		


target_ulong endWork(target_ulong status)
{
	DECAF_printf("endWork\n");
	if(status == 0){
		if(write(afl_qemuloop_pipe[1], "END", 4) != 4)
		{
		    perror("write afl_qemuloop_pip");
		}
	}
	else if(status == 32){
		if(write(afl_qemuloop_pipe[1], "CRA", 4) != 4)
		{
		    perror("write afl_qemuloop_pip");
		}
	}
	//sleep(1);
	//qemu_mutex_unlock_iothread();
	return 0;

}

CPUArchState backup_cpu;
CPUArchState transit_cpu;

void storeCPUState(CPUArchState *env, CPUArchState *saved_env)
{
	for(int i=0; i<32; i++)
	{
		saved_env->active_tc.gpr[i] = env->active_tc.gpr[i];
	}
	saved_env->active_tc.PC = env->active_tc.PC;
}

void loadCPUState(CPUArchState *env, CPUArchState *saved_env)
{
	for(int i=0; i<32; i++)
	{
		env->active_tc.gpr[i] =saved_env->active_tc.gpr[i];
	}
	env->active_tc.PC = saved_env->active_tc.PC;
	printf("pc is %x\n",env->active_tc.PC);
}


char * trim(char *str)
{
  char *end;

  // ltrim
  while (isspace(*str)) {
    str++;
  }

  if (*str == 0) // only spaces and zyw '\'
    return str;

  // rtrim
  end = str + strlen(str) - 1;
  while (end > str && isspace(*end)) {
    end--;
  }

  // null terminator
  *(end+1) = 0;

  return str;
}


/*
typedef enum MMUAccessType {
    MMU_DATA_LOAD  = 0,
    MMU_DATA_STORE = 1,
    MMU_INST_FETCH = 2
} MMUAccessType;
*/


void print_cpu_state(CPUArchState *env)
{
	for(int i = 0; i< 32; i++)
	{
		printf("%x ",env->active_tc.gpr[i]);
	}
	printf("%x\n", env->active_tc.PC);
}

/*
uintptr_t search_hva_gva_pair(target_ulong gva)
{
	for(int i = 0; i < pair_index; i++)
	{
		if(stackaddr_pair[i].virt_addr == gva)
		{
			return stackaddr_pair[i].host_addr;
		}
	}
	return -1;
}
*/



int mutex = 1;
int switch_start = 0;
static void handlePiperead(void *ctx)
{
	CPUMIPSState *env = first_cpu->env_ptr;
	MISSING_PAGE page;
	int res = read_state(first_cpu->env_ptr, &page);
	
// read addr
	if (qemu_mutex_iothread_locked()) {
	    qemu_mutex_unlock_iothread();
	}
	if(res == -1)
	{
		qemu_mutex_lock_iothread();
		return;  //zyw exit if in child process
	}
	if(res == 0)
	{
		handle_addr_or_state = 1;
		int status, child_pid;
        CPUArchState *env = first_cpu->env_ptr;
		target_ulong addr = page.addr;
		MMUAccessType access_type = page.prot;        
        int mmu_idx = page.mmu_idx; 
//zyw 09.29
        /*  
        if(mmu_idx == -1){
            mmu_idx = cpu_mmu_index(first_cpu, true); //zyw previously assigned with 2 directly which cause physical page finding error;
            if(mmu_idx == 3) mmu_idx = 0; // zyw if equals 3 error, assigned with 0 kernel mode
        }
        */

        current_cpu = first_cpu;
		target_ulong pgd = DECAF_getPGD(first_cpu);

        //rcu_read_lock();

        first_cpu->interrupt_request = 0;// need???????????????
		int ret = mips_cpu_handle_mmu_fault(first_cpu, addr, access_type, mmu_idx);
		int index = (addr >> 12) & (256 - 1);
		target_ulong labelpc;
        target_ulong orig_pc = env->active_tc.PC;
        void *t = env->tlb_table[mmu_idx][index].addend;
		while(ret == 1)
		{
			if (sigsetjmp(first_cpu->jmp_env, 0) != 0) {
				//printf("long jmp here:%d\n", first_cpu->exception_index);
				first_cpu->can_do_io = 1;
				tb_lock_reset();
				if (qemu_mutex_iothread_locked()) {
				    qemu_mutex_unlock_iothread();
				}
			}
			int ret_excep;
		    //printf("step into kernel to handle address:%x,%x, exception:%d, interrupt:%d\n", addr, pgd, first_cpu->exception_index, first_cpu->interrupt_request);
			while (!cpu_handle_exception(first_cpu, &ret_excep)) {
				TranslationBlock *last_tb = NULL;
				int tb_exit = 0; 
				while (!cpu_handle_interrupt(first_cpu, &last_tb)) {
                     if(env->active_tc.PC < 0x80000000) {
                        labelpc = env->active_tc.PC;     
                        if(orig_pc == labelpc)
                        {
                            goto label;
                        }                      
                    }
				    //printf("kernel :%x\n", env->active_tc.PC);
				    TranslationBlock *tb = tb_find(first_cpu, last_tb, tb_exit); 
				    cpu_loop_exec_tb(first_cpu, tb, &last_tb, &tb_exit);
				   //align_clocks(&sc, first_cpu);
				}
		    }
            //printf("handler addr out of exception loop:%d\n", ret_excep);
            siglongjmp(first_cpu->jmp_env, 0);

label:			
			ret = mips_cpu_handle_mmu_fault(first_cpu, addr, access_type, mmu_idx);
			t = env->tlb_table[mmu_idx][index].addend;
            //printf("t is:%lx,%x, arg:%x,%x,%x\n", t, ret,  mmu_idx, index, env->active_tc.gpr[29]);
		}
        if((uintptr_t)t & 0xffffffff00000000 == 0xffffffff00000000) {printf("addend not aligned:%lx\n",t); exit(32);} 
		void *p = (void *)((uintptr_t)addr + t); //addend will be modified?  so ,use t here
        //int data = 0;
        //cpu_physical_memory_rw(0x6e96f8, &data, 2, 0);
		//printf("write addr is:%lx, %lx, %lx, %lx, arg: %x,%x,%x\n",addr, env->tlb_table[mmu_idx][index].addend, p, t, mmu_idx, index, env->active_tc.gpr[29]);
        /* which cause error
		stackaddr_pair[pair_index].virt_addr = addr & 0xfffff000;
		stackaddr_pair[pair_index].host_addr = (uintptr_t)p & 0xfffffffffffff000;
		pair_index ++;
        */
        //rcu_read_unlock();

		write_addr(addr, p);
		handle_addr_or_state = 0;
		qemu_mutex_lock_iothread();		
	}
	else{
        switch_start = 1;
		handle_addr_or_state = 2;
		qemu_mutex_lock_iothread();
		//vm_stop(RUN_STATE_PAUSED);// zyw add temporarily to change current_run_state in vl.c from RUN_STATE_RUNNING to RUN_STATE_PAUSED; previous vm_stop is not enough.
		//first_cpu->exception_index = 17;	
        CPUArchState *env = first_cpu->env_ptr;
	    //vm_start();
        afl_wants_cpu_to_stop = 0;
        qemu_cond_broadcast(first_cpu->halt_cond);
        //qemu_cond_wait(cpu->halt_cond, )

/*
		int ret_excep;
		CPUArchState *env = first_cpu->env_ptr;
		printf("error code:%x\n", env->error_code);
		print_cpu_state(env);

		if (sigsetjmp(first_cpu->jmp_env, 0) != 0) {
			printf("state raise exception\n");
			first_cpu->can_do_io = 1;
			tb_lock_reset();
			if (qemu_mutex_iothread_locked()) {
			    qemu_mutex_unlock_iothread();
			}
		}
		//if(user_stack_count)  printf("tb find2:%x, %x,%x,%x\n",first_cpu, 0x80000180, 6, first_cpu->tb_jmp_cache[6]);
exception:
		while (!cpu_handle_exception(first_cpu, &ret_excep)) {
			printf("into exception\n");
			TranslationBlock *last_tb = NULL;
			int tb_exit = 0;
			while (!cpu_handle_interrupt(first_cpu, &last_tb)) {
			    target_ulong k1 = 0;
			    DECAF_read_mem(first_cpu, 0x80728250, 4, &k1);
			    target_ulong v0 = 0;
			    DECAF_read_mem(first_cpu, ( (k1 - 0xb0) | 0x1fff) ^ 0x1fff, 4, &v0);
			    target_ulong a0 = 0;
			    DECAF_read_mem(first_cpu, v0 + 0xa4, 4, &a0);
			    target_ulong v1 = 0;
			    DECAF_read_mem(first_cpu, a0 + 0x34, 4, &v1);
			    //printf("before find:%x, k1:%x, sp:%x, gp:%x, v0:%x, a0:%x, v1:%x\n",env->active_tc.PC, env->active_tc.gpr[27], env->active_tc.gpr[29], env->active_tc.gpr[28],  v0, a0, v1);
			    //printf("before find:%x\n",env->active_tc.PC); 
			    printf("find:%x, sp:%x, gp:%x,v0:%x, a0:%x, v1:%x\n",env->active_tc.PC, env->active_tc.gpr[29], env->active_tc.gpr[28], v0, a0 + 0x34, v1);
			    if(env->active_tc.PC < 0X80000000){
				printf("end pc:%x\n", env->active_tc.PC);
				write_state(env);
				qemu_mutex_lock_iothread();
				return;  //zyw 
			    }
			    TranslationBlock *tb = tb_find(first_cpu, last_tb, tb_exit);
			    cpu_loop_exec_tb(first_cpu, tb, &last_tb, &tb_exit);
			}
			
	    	}
		printf("out of exception:%x\n", ret_excep);
		goto exception;	
		//qemu_mutex_lock_iothread();
*/
	}

}



void qemu_init_vcpu(CPUState *cpu)
{
//zyw
    if(pipe(afl_qemuloop_pipe) == -1) {
        perror("qemuloop pipe");
        exit(1);
    }

    int res = open_read_pipe();
    if(res != -1){
	printf("read pipe open\n");
	qemu_set_fd_handler(pipe_read_fd, handlePiperead, NULL, NULL);
    }
    else{
	printf("open read pipe error\n");
    }
 
    cpu->nr_cores = smp_cores;
    cpu->nr_threads = smp_threads;
    cpu->stopped = true;

    if (!cpu->as) {
        /* If the target cpu hasn't set up any address spaces itself,
         * give it the default one.
         */
        AddressSpace *as = address_space_init_shareable(cpu->memory,
                                                        "cpu-memory");
        cpu->num_ases = 1;
        cpu_address_space_init(cpu, as, 0);
    }

    if (kvm_enabled()) {
        qemu_kvm_start_vcpu(cpu);
    } else if (hax_enabled()) {
        qemu_hax_start_vcpu(cpu);
    } else if (tcg_enabled()) {
        qemu_tcg_init_vcpu(cpu);
    } else {
        qemu_dummy_start_vcpu(cpu);
    }
}

void cpu_stop_current(void)
{
    if (current_cpu) {
        current_cpu->stop = false;
        current_cpu->stopped = true;
        cpu_exit(current_cpu);
        qemu_cond_broadcast(&qemu_pause_cond);
    }
}

int vm_stop(RunState state)
{
    if (qemu_in_vcpu_thread()) {
        qemu_system_vmstop_request_prepare();
        qemu_system_vmstop_request(state);
        /*
         * FIXME: should not return to device code in case
         * vm_stop() has been requested.
         */
        cpu_stop_current();
        return 0;
    }

    return do_vm_stop(state);
}

/**
 * Prepare for (re)starting the VM.
 * Returns -1 if the vCPUs are not to be restarted (e.g. if they are already
 * running or in case of an error condition), 0 otherwise.
 */
int vm_prepare_start(void)
{
    RunState requested;
    int res = 0;
    qemu_vmstop_requested(&requested);
    if (runstate_is_running() && requested == RUN_STATE__MAX) {
        return -1;
    }

    /* Ensure that a STOP/RESUME pair of events is emitted if a
     * vmstop request was pending.  The BLOCK_IO_ERROR event, for
     * example, according to documentation is always followed by
     * the STOP event.
     */
    if (runstate_is_running()) {
        qapi_event_send_stop(&error_abort);
        res = -1;
    } else {
        replay_enable_events();
        cpu_enable_ticks();
        runstate_set(RUN_STATE_RUNNING);
        vm_state_notify(1, RUN_STATE_RUNNING);
    }
    /* We are sending this now, but the CPUs will be resumed shortly later */
    qapi_event_send_resume(&error_abort);
    return res;
}

void vm_start(void)
{
    if (!vm_prepare_start()) {
        resume_all_vcpus();
    }
}

/* does a state transition even if the VM is already stopped,
   current state is forgotten forever */
int vm_stop_force_state(RunState state)
{
    if (runstate_is_running()) {
        return vm_stop(state);
    } else {
        runstate_set(state);

        bdrv_drain_all();
        /* Make sure to return an error if the flush in a previous vm_stop()
         * failed. */
        return bdrv_flush_all();
    }
}

void list_cpus(FILE *f, fprintf_function cpu_fprintf, const char *optarg)
{
    /* XXX: implement xxx_cpu_list for targets that still miss it */
#if defined(cpu_list)
    cpu_list(f, cpu_fprintf);
#endif
}

CpuInfoList *qmp_query_cpus(Error **errp)
{
    MachineState *ms = MACHINE(qdev_get_machine());
    MachineClass *mc = MACHINE_GET_CLASS(ms);
    CpuInfoList *head = NULL, *cur_item = NULL;
    CPUState *cpu;

    CPU_FOREACH(cpu) {
        CpuInfoList *info;
#if defined(TARGET_I386)
        X86CPU *x86_cpu = X86_CPU(cpu);
        CPUX86State *env = &x86_cpu->env;
#elif defined(TARGET_PPC)
        PowerPCCPU *ppc_cpu = POWERPC_CPU(cpu);
        CPUPPCState *env = &ppc_cpu->env;
#elif defined(TARGET_SPARC)
        SPARCCPU *sparc_cpu = SPARC_CPU(cpu);
        CPUSPARCState *env = &sparc_cpu->env;
#elif defined(TARGET_MIPS)
        MIPSCPU *mips_cpu = MIPS_CPU(cpu);
        CPUMIPSState *env = &mips_cpu->env;
#elif defined(TARGET_TRICORE)
        TriCoreCPU *tricore_cpu = TRICORE_CPU(cpu);
        CPUTriCoreState *env = &tricore_cpu->env;
#endif

        cpu_synchronize_state(cpu);

        info = g_malloc0(sizeof(*info));
        info->value = g_malloc0(sizeof(*info->value));
        info->value->CPU = cpu->cpu_index;
        info->value->current = (cpu == first_cpu);
        info->value->halted = cpu->halted;
        info->value->qom_path = object_get_canonical_path(OBJECT(cpu));
        info->value->thread_id = cpu->thread_id;
#if defined(TARGET_I386)
        info->value->arch = CPU_INFO_ARCH_X86;
        info->value->u.x86.pc = env->eip + env->segs[R_CS].base;
#elif defined(TARGET_PPC)
        info->value->arch = CPU_INFO_ARCH_PPC;
        info->value->u.ppc.nip = env->nip;
#elif defined(TARGET_SPARC)
        info->value->arch = CPU_INFO_ARCH_SPARC;
        info->value->u.q_sparc.pc = env->pc;
        info->value->u.q_sparc.npc = env->npc;
#elif defined(TARGET_MIPS)
        info->value->arch = CPU_INFO_ARCH_MIPS;
        info->value->u.q_mips.PC = env->active_tc.PC;
#elif defined(TARGET_TRICORE)
        info->value->arch = CPU_INFO_ARCH_TRICORE;
        info->value->u.tricore.PC = env->PC;
#else
        info->value->arch = CPU_INFO_ARCH_OTHER;
#endif
        info->value->has_props = !!mc->cpu_index_to_instance_props;
        if (info->value->has_props) {
            CpuInstanceProperties *props;
            props = g_malloc0(sizeof(*props));
            *props = mc->cpu_index_to_instance_props(ms, cpu->cpu_index);
            info->value->props = props;
        }

        /* XXX: waiting for the qapi to support GSList */
        if (!cur_item) {
            head = cur_item = info;
        } else {
            cur_item->next = info;
            cur_item = info;
        }
    }

    return head;
}

void qmp_memsave(int64_t addr, int64_t size, const char *filename,
                 bool has_cpu, int64_t cpu_index, Error **errp)
{
    FILE *f;
    uint32_t l;
    CPUState *cpu;
    uint8_t buf[1024];
    int64_t orig_addr = addr, orig_size = size;

    if (!has_cpu) {
        cpu_index = 0;
    }

    cpu = qemu_get_cpu(cpu_index);
    if (cpu == NULL) {
        error_setg(errp, QERR_INVALID_PARAMETER_VALUE, "cpu-index",
                   "a CPU number");
        return;
    }

    f = fopen(filename, "wb");
    if (!f) {
        error_setg_file_open(errp, errno, filename);
        return;
    }

    while (size != 0) {
        l = sizeof(buf);
        if (l > size)
            l = size;
        if (cpu_memory_rw_debug(cpu, addr, buf, l, 0) != 0) {
            error_setg(errp, "Invalid addr 0x%016" PRIx64 "/size %" PRId64
                             " specified", orig_addr, orig_size);
            goto exit;
        }
        if (fwrite(buf, 1, l, f) != l) {
            error_setg(errp, QERR_IO_ERROR);
            goto exit;
        }
        addr += l;
        size -= l;
    }

exit:
    fclose(f);
}

void qmp_pmemsave(int64_t addr, int64_t size, const char *filename,
                  Error **errp)
{
    FILE *f;
    uint32_t l;
    uint8_t buf[1024];

    f = fopen(filename, "wb");
    if (!f) {
        error_setg_file_open(errp, errno, filename);
        return;
    }

    while (size != 0) {
        l = sizeof(buf);
        if (l > size)
            l = size;
        cpu_physical_memory_read(addr, buf, l);
        if (fwrite(buf, 1, l, f) != l) {
            error_setg(errp, QERR_IO_ERROR);
            goto exit;
        }
        addr += l;
        size -= l;
    }

exit:
    fclose(f);
}

void qmp_inject_nmi(Error **errp)
{
    nmi_monitor_handle(monitor_get_cpu_index(), errp);
}

void dump_drift_info(FILE *f, fprintf_function cpu_fprintf)
{
    if (!use_icount) {
        return;
    }

    cpu_fprintf(f, "Host - Guest clock  %"PRIi64" ms\n",
                (cpu_get_clock() - cpu_get_icount())/SCALE_MS);
    if (icount_align_option) {
        cpu_fprintf(f, "Max guest delay     %"PRIi64" ms\n", -max_delay/SCALE_MS);
        cpu_fprintf(f, "Max guest advance   %"PRIi64" ms\n", max_advance/SCALE_MS);
    } else {
        cpu_fprintf(f, "Max guest delay     NA\n");
        cpu_fprintf(f, "Max guest advance   NA\n");
    }
}
