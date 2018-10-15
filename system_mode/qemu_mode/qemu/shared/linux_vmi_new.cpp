/*
    Copyright (C) <2012> <Syracuse System Security (Sycure) Lab>

    DECAF is based on QEMU, a whole-system emulator. You can redistribute
    and modify it under the terms of the GNU GPL, version 3 or later,
    but it is made available WITHOUT ANY WARRANTY. See the top-level
    README file for more details.

    For more information about DECAF and other softwares, see our
    web site at:
    http://sycurelab.ecs.syr.edu/

    If you have any questions about DECAF,please post it on
    http://code.google.com/p/decaf-platform/
*/
/*
* linux_vmi_new.cpp
*
*  Created on: June 26, 2015
* 	Author : Abhishek V B
*/


#ifdef __cplusplus
extern "C" {
#endif /* __cplusplus */
#include "qemu/osdep.h"
#include "cpu.h"
#include "config.h"
#include "hw/hw.h" // AWH
#include "qemu/timer.h"
#include "monitor/monitor.h"

void traverse_pagemap(CPUState *env, int pgd, target_ulong vaddr);


#ifdef __cplusplus
};
#endif /* __cplusplus */

#include <inttypes.h>
#include <string>
#include <list>
#include <set>
#include <algorithm>
#include <assert.h>
#include <errno.h>
#include <string.h>
#include <stdio.h>
#include <tr1/unordered_map>
#include <tr1/unordered_set>
#include <stdlib.h>
#include <unistd.h>
#include <signal.h>
#include <queue>
#include <sys/time.h>
#include <math.h>
#include <glib.h>
#include <mcheck.h>


#include "DECAF_cmds.h"
#include "DECAF_main.h"
#include "DECAF_target.h"
#include "vmi.h"
#include "linux_vmi_new.h"
#include "linux_procinfo.h"
#include "linux_readelf.h"
#include "hookapi.h"
#include "function_map.h"
#include "shared/utils/SimpleCallback.h"
#include "linux_readelf.h"

extern char *mapping_filename;

#include "shared/utils/Output.h"

using namespace std;
using namespace std::tr1;

#define BREAK_IF(x) if(x) break
#define MAX_PARAM_PREFIX_LEN (64 - sizeof(target_ptr))

//Global variable used to read values from the stack
uint32_t call_stack[12];
int monitored = 0;
static int first = 1;

// current linux profile
static ProcInfo OFFSET_PROFILE = {"VMI"};


void print_loaded_modules(CPUState *env)
{

    target_ulong  modules_list, module_size, first_module;

    target_ulong next_module = OFFSET_PROFILE.modules;
	next_module -= OFFSET_PROFILE.module_list;

	first_module = next_module;

	DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_list ,
                              &next_module);

  	next_module -= OFFSET_PROFILE.module_list;
	
	char module_name[MAX_PARAM_PREFIX_LEN];

    //monitor_printf(cur_mon, "%20s     %10s \n", "Module", "Size");


    while(true)
    {
        DECAF_read_mem(env, next_module + OFFSET_PROFILE.module_name,
						 MAX_PARAM_PREFIX_LEN, module_name);

        module_name[MAX_PARAM_PREFIX_LEN - 1] = '\0';

					
        DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_size,
                                &module_size);

        //monitor_printf(cur_mon, "%20s  |  %10lu\n", module_name, module_size);

		DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_list ,
                              &next_module);
			  
        next_module -= OFFSET_PROFILE.module_list;

        if(first_module == next_module)
        {
			//monitor_printf(cur_mon, "done\n");
            break;
        }

    }

}

void print_loaded_modules_old(CPUState *env)
{
	

}


static void traverse_kmod_list(CPUState *env)
{
	target_ulong  modules_list, module_size, first_module, module_base;

    target_ulong next_module = OFFSET_PROFILE.modules;
	next_module -= OFFSET_PROFILE.module_list;

	first_module = next_module;

	DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_list ,
                              &next_module);

  	next_module -= OFFSET_PROFILE.module_list;
	
	char module_name[MAX_PARAM_PREFIX_LEN];

    while(true)
    {
        DECAF_read_mem(env, next_module + OFFSET_PROFILE.module_name,
						 MAX_PARAM_PREFIX_LEN, module_name);

        //module_name[MAX_PARAM_PREFIX_LEN - 1] = '\0';
		module_name[31] = '\0';
			

		if(!VMI_find_kmod_by_name(module_name))
		{
								
	        DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_size,
	                                &module_size);

	        DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_init,
	                                &module_base);

			module *mod = new module();
            strncpy(mod->name, module_name, 31);
            mod->name[31] = '\0';
            mod->size = module_size;
            mod->inode_number = 0;
            mod->symbols_extracted = 1;

			//monitor_printf(default_mon, "kernel module %s base %x\n", module_name, module_base);
            VMI_add_module(mod, module_name);

	        VMI_insert_module(0, module_base , mod);
		}

		DECAF_read_ptr(env, next_module + OFFSET_PROFILE.module_list ,
                              &next_module);
			  
        next_module -= OFFSET_PROFILE.module_list;

        if(first_module == next_module)
        {
            break;
        }

    }
}



//  Wait for kernel's `init_module` to call `trim_init_extable' where we grab module data
static void new_kmod_callback(DECAF_Callback_Params* params)
{
	CPUState *env = params->bb.env;

	target_ulong pc = DECAF_getPC(env);

    if(OFFSET_PROFILE.trim_init_extable != pc)
        return;

	traverse_kmod_list(env);
}

/*
#define pgd_index(address)      (((address) >> PGDIR_SHIFT) & (PTRS_PER_PGD-1))
#define pgd_offset(mm, address) ((mm)->pgd+pgd_index(address))

#define pud_offset_phys(dir, addr)      (pgd_page_paddr(READ_ONCE(*(dir))) + pud_index(addr) * sizeof(pud_t))
#define pud_offset(dir, addr)           ((pud_t *)__va(pud_offset_phys((dir), (addr))))

extern inline pmd_t * pmd_offset(pgd_t * dir, unsigned long address)
{
        pmd_t *ret = (pmd_t *) pgd_page_vaddr(*dir) + ((address >> PMD_SHIFT) & (PTRS_PER_PAGE - 1));
        smp_read_barrier_depends(); 
        return ret;
}


extern inline int pgd_none(pgd_t pgd)           { return !pgd_val(pgd); }
extern inline int pgd_bad(pgd_t pgd)            { return (pgd_val(pgd) & ~_PFN_MASK) != _PAGE_TABLE; }
*/

target_ulong data_obtain(uint8_t* seq)
{
	target_ulong res = 0;
	res += (seq[3]<<24);
	res += (seq[2]<<16);
	res += (seq[1]<<8);
	res += (seq[0]);
	//DECAF_printf("%x,%x,%x,%x\n",seq[0], seq[1], seq[2], seq[3]);
	return res;
}


void traverse_pagemap(CPUState *env, int pgd, target_ulong vaddr)
{
	unordered_map<uint32_t, process *>::iterator iter = process_map.find(pgd);
	process *proc;

	if (iter == process_pid_map.end()) //pid not found
		return NULL;

	proc = iter->second;

	target_ulong next_task, mm, task_pgd;
	uint8_t pud_table_head[4], pmd_table_head[4], pte_table_head[4], physcial_page[4];
	if (DECAF_read_mem(env, proc->EPROC_base_addr + OFFSET_PROFILE.ts_mm, sizeof(target_ptr), &mm) < 0)
		return;

       	if (DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_pgd, &task_pgd) < 0)
		return;
	printf("task_pgd:%x\n", task_pgd);
	target_ulong proc_cr3 = DECAF_get_phys_addr(env, task_pgd);
	target_ulong pgd_table_offset = proc_cr3 + (vaddr >> 21 & 0X3ff) * 4; 
	printf("pgd_table_offset:%x\n", pgd_table_offset);

	cpu_physical_memory_rw(pgd_table_offset, pud_table_head, 4, 0);
	target_ulong pud_head = data_obtain(pud_table_head);
	printf("pud_table_head:%x\n", pud_head);
	target_ulong ptd_head = DECAF_get_phys_addr(env, pud_head);
	printf("pte_table_head:%x\n", ptd_head);
	
	//cpu_physical_memory_rw(pmd_head, pte_table_head, 4, 0);
	//target_ulong ptd_head = data_obtain(pte_table_head);
	//printf("pte_table_head:%x\n", ptd_head);
	target_ulong pte_table_offset = ptd_head + (vaddr >> 12 & 0X3ff) * 4; 
	printf("pte_table_offset:%x\n", pte_table_offset);
	printf("pte_table_offset:%x\n", pte_table_offset + 4);

	cpu_physical_memory_rw(pte_table_offset, physcial_page, 4, 0);
	target_ulong physical = data_obtain(physcial_page);
	printf("physcial_page:%x\n", physical);

}

//  Traverse the task_struct linked list and add all un-added processes
//  This function is called
static void traverse_task_struct_add(CPUState *env)
{

    uint32_t task_pid = 0;
    uint32_t kernel_count = 0; //zyw
    const int MAX_LOOP_COUNT = 10240;	// prevent infinite loop
    target_ulong next_task, mm, proc_cr3, task_pgd, ts_parent_pid, ts_real_parent;
    next_task = OFFSET_PROFILE.init_task_addr;
    
    for (int count = MAX_LOOP_COUNT; count > 0; --count)
    {
        BREAK_IF(DECAF_read_ptr(env, next_task + (OFFSET_PROFILE.ts_tasks + sizeof(target_ptr)),
                                &next_task) < 0);

        next_task -= OFFSET_PROFILE.ts_tasks;


        if(OFFSET_PROFILE.init_task_addr == next_task)
        {
            break;
        }


/*
        BREAK_IF(DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_mm,
                                &mm) < 0);
*/
        DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_mm,
                                &mm);
        if (mm != 0)
        {
/*
            BREAK_IF(DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_pgd,
                                    &task_pgd) < 0);
*/
            DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_pgd,
                                    &task_pgd);
            proc_cr3 = DECAF_get_phys_addr(env, task_pgd);
        }

        else
        {
            // We don't add kernel processed for now.
            proc_cr3 = -1;
            kernel_count++;
            continue;
        }

// zyw
/*
	process * proc = VMI_find_process_by_pgd(proc_cr3);
	if(proc){
	    printf("proc_name:%s,proc_pid:%d,task_pgd:%x,proc_cr3:%x\n",proc->name,proc->pid,task_pgd,proc_cr3);
	}
*/
        //zyw

/*
	char proc_name_1[1000];
	DECAF_read_mem(env, next_task + OFFSET_PROFILE.ts_comm,
		            SIZEOF_COMM, proc_name_1);
	printf("proc_name:%s,proc_cr3:%x\n",proc_name_1,proc_cr3);


	char proc_name[1000];
	DECAF_read_mem(env, next_task + OFFSET_PROFILE.ts_comm,
		            SIZEOF_COMM, proc_name);
	process * proc = VMI_find_process_by_pgd(proc_cr3);
	if(strcmp(proc_name, "httpd") == 0){
	    //printf("\n\n\n\n\nproc_name:%s,proc_cr3:%x,find_result:%s\n\n\n\n\n",proc_name,proc_cr3,proc->name);
	    printf("proc_name:%s,proc_cr3:%x,find_result:%s\n",proc_name,proc_cr3,proc->name);
	}
*/
        if (!VMI_find_process_by_pgd(proc_cr3))
        {

            // get task_pid
            BREAK_IF(DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_tgid,
                                    &task_pid) < 0);

            // get parent task's base address
            BREAK_IF(DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_real_parent,
                                    &ts_real_parent) < 0
                     ||
                     DECAF_read_ptr(env, ts_real_parent + OFFSET_PROFILE.ts_tgid,
                                    &ts_parent_pid) < 0);

            process* pe = new process();
            pe->pid = task_pid;
            pe->parent_pid = ts_parent_pid;
            pe->cr3 = proc_cr3;
            pe->EPROC_base_addr = next_task; // store current task_struct's base address
            BREAK_IF(DECAF_read_mem(env, next_task + OFFSET_PROFILE.ts_comm,
                                    SIZEOF_COMM, pe->name) < 0);
            VMI_create_process(pe);
			pe->modules_extracted = false;
        }
    }
    //zyw
    //printf("\n\n\n\n kernel_count:%d,count:%d\n\n\n", kernel_count,MAX_LOOP_COUNT-count);
}

// Traverse the task_struct linked list and updates the internal DECAF process data structures on process exit
// This is called when the linux system call `proc_exit_connector` is called.
static process *traverse_task_struct_remove(CPUState *env)
{
    set<target_ulong> pids;
    uint32_t task_pid = 0;
    process *right_proc = NULL;
    uint32_t right_pid = 0;

    target_ulong next_task, mm;
    next_task = OFFSET_PROFILE.init_task_addr;

    while(true)
    {
        BREAK_IF(DECAF_read_ptr(env, next_task + (OFFSET_PROFILE.ts_tasks + sizeof(target_ptr)),
                                &next_task) < 0);

        next_task -= OFFSET_PROFILE.ts_tasks;

        if(OFFSET_PROFILE.init_task_addr == next_task)
        {
            break;
        }

        BREAK_IF(DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_mm,
                                &mm) < 0);

        if (mm != 0)
        {
            DECAF_read_ptr(env, next_task + OFFSET_PROFILE.ts_tgid,
                           &task_pid);
            // Collect PIDs of all processes in the task linked list
            pids.insert(task_pid);
        }

    }

    // Compare the collected list with the internal list. We track the Process which is removed and call `VMI_process_remove`
    for(unordered_map < uint32_t, process * >::iterator iter = process_pid_map.begin(); iter != process_pid_map.end(); ++iter)
    {
        if(iter->first != 0 && !pids.count(iter->first))
        {
            right_pid = iter->first;
            right_proc = iter->second;
            break;
        }
    }

    if(right_pid == 0)
		return NULL;
	
	//monitor_printf(default_mon,"process with pid [%08x]  ended\n",right_pid);

    VMI_remove_process(right_pid);
    return right_proc;
}

extern int httpd_pgd;
sigjmp_buf jmpbuf;
//zyw
void helper_tlb_fill(CPUState *cs, target_ulong addr, MMUAccessType access_type, int mmu_idx)
{
	sigsetjmp(jmpbuf, 1);
	printf("return address:%x,%x\n", helper_tlb_fill, __builtin_return_address(0));
	int retaddr = 0;
	target_ulong pgd = DECAF_getPGD(cs);
	int ret = mips_cpu_handle_mmu_fault(cs, addr, access_type, mmu_idx);
	if(pgd == httpd_pgd && addr < 0x70000000)
	{ 
		int a = 0;
		printf("tlb:%x,%x, %x,%x,%x,%x\n", addr & 0xfffff000, addr, access_type, mmu_idx, retaddr,ret);
	}
	if (ret) {
		MIPSCPU *cpu = MIPS_CPU(cs);
		CPUMIPSState *env = &cpu->env;	
		int ret1;
// need call cpu_handle_exception
		printf("exception:%x,%x\n", cs->exception_index, env->error_code);
		cs->can_do_io = 1;
		siglongjmp(jmpbuf, 1);
	}
}


int lib_start_addr = 0;
int stack_start_addr = 0;

// Traverse the memory map for a process
void traverse_mmap(CPUState *env, void *opaque)
{
    process *proc = (process *)opaque;
    target_ulong mm, vma_curr, vma_file, f_dentry, f_inode, mm_mmap, vma_next, vma_prot=NULL;
    set<target_ulong> module_bases;
    unsigned int inode_number;
    target_ulong vma_vm_start = 0, vma_vm_end = 0;
    target_ulong last_vm_start = 0, last_vm_end = 0, mod_vm_start = 0;
    char name[32];	// module file path
    string last_mod_name;
    module *mod = NULL;
	
    if (DECAF_read_mem(env, proc->EPROC_base_addr + OFFSET_PROFILE.ts_mm, sizeof(target_ptr), &mm) < 0)
        return;

    if (DECAF_read_mem(env, mm + OFFSET_PROFILE.mm_mmap, sizeof(target_ptr), &mm_mmap) < 0)
        return;

    // Mark the `modules_extracted` true. This is done because this function calls `VMI_find_module_by_base`
    // and that function calls `traverse_mmap` if `modules_extracted` is false. We don't want to get into
    // an infinite recursion.
    proc->modules_extracted = true;

    if (-1UL == proc->cr3)
        return;


    // starting from the first vm_area, read vm_file. NOTICE vm_area_struct can be null
    if (( vma_curr = mm_mmap) == 0)
        return;
    
    FILE * fp = fopen(mapping_filename, "a+"); 
    int count = 0;
    while(true)
    {
        count ++;
        // read start of curr vma
        if (DECAF_read_mem(env, vma_curr + OFFSET_PROFILE.vma_vm_start, sizeof(target_ptr), &vma_vm_start) < 0)
            goto next;

        // read end of curr vma
        if (DECAF_read_mem(env, vma_curr + OFFSET_PROFILE.vma_vm_end, sizeof(target_ptr), &vma_vm_end) < 0)
            goto next;

    	DECAF_printf("memory %x:%x ", vma_vm_start, vma_vm_end);	

    	if(count == 4) lib_start_addr = vma_vm_start;
    	if(count == 6) stack_start_addr = vma_vm_start;  

//zyw
/*
	//index = (addr >> TARGET_PAGE_BITS) & (CPU_TLB_SIZE - 1);
	int mmu_idx = 2;
	uintptr_t retaddr;
	for(int i = 1; i< (vma_vm_end - vma_vm_start)/4096; i++)
	{
		target_ulong addr =  vma_vm_start + i * 4096;
		printf("addr: %x ", addr);
		helper_tlb_fill(env, addr, 2, mmu_idx);
		int index = (addr >> 12) & (256 - 1);
		//void *p = (void *)((uintptr_t)addr + env->tlb_table[mmu_idx][index].addend);
	}

*/
//	

//zyw obtain the memory area property
        if (DECAF_read_mem(env, vma_curr + OFFSET_PROFILE.vma_vm_page_prot, sizeof(target_ptr), &vma_prot) < 0)      
            goto next;

        DECAF_printf("%x ",vma_prot);
//

        // read the struct* file entry of the curr vma, used to then extract the dentry of the this page
        if (DECAF_read_mem(env, vma_curr + OFFSET_PROFILE.vma_vm_file, sizeof(target_ptr), &vma_file) < 0 || !vma_file)
            goto next;

        // dentry extraction from the struct* file
        if (DECAF_read_mem(env, vma_file + OFFSET_PROFILE.file_dentry, sizeof(target_ptr), &f_dentry) < 0 || !f_dentry)
            goto next;



        // read small names form the dentry
        if (DECAF_read_mem(env, f_dentry + OFFSET_PROFILE.dentry_d_iname, 32, name) < 0)
            goto next;

	    DECAF_printf("%s ", name);

        // inode struct extraction from the struct* file
        if (DECAF_read_mem(env, f_dentry + OFFSET_PROFILE.file_inode, sizeof(target_ptr), &f_inode) < 0 || !f_inode)
            goto next;

        // inode_number extraction
        if (DECAF_read_mem(env, f_inode + OFFSET_PROFILE.inode_ino , sizeof(unsigned int), &inode_number) < 0 || !inode_number)
            goto next;

        name[31] = '\0';	// truncate long string

        // name is invalid, move on the data structure
        if (strlen(name)==0)
            goto next;


        
        if (!strcmp(last_mod_name.c_str(), name))
        {
            // extending the module
            if(last_vm_end == vma_vm_start)
            {
                assert(mod);
                target_ulong new_size = vma_vm_end - mod_vm_start;
                if (mod->size < new_size)
                    mod->size = new_size;
            }
            // This is a special case when the data struct is BEING populated
            goto next;
        }

        char key[32+32];
        //not extending, a different module
        mod_vm_start = vma_vm_start;

        sprintf(key, "%u_%s", inode_number, name);
        mod = VMI_find_module_by_key(key);
        module_bases.insert(vma_vm_start);
        if (!mod)
        {
            mod = new module();
            strncpy(mod->name, name, 31);
            mod->name[31] = '\0';
            mod->size = vma_vm_end - vma_vm_start;
            mod->inode_number = inode_number;
            mod->symbols_extracted = 0;
            VMI_add_module(mod, key);
        }

        if(VMI_find_module_by_base(proc->cr3, vma_vm_start) != mod)
        {
            VMI_insert_module(proc->pid, mod_vm_start , mod);
        }

next:
        DECAF_printf("\n");
        if (DECAF_read_mem(env, vma_curr + OFFSET_PROFILE.vma_vm_next, sizeof(target_ptr), &vma_next) < 0)
            break;

        if (vma_next == NULL)
        {
            break;
        }

        vma_curr = vma_next;
        last_mod_name = name;
        if (mod != NULL)
        {
            last_vm_start = vma_vm_start;
            last_vm_end = vma_vm_end;
        }
    }
   
    fclose(fp);

    unordered_map<uint32_t, module *>::iterator iter = proc->module_list.begin();
    set<target_ulong> bases_to_remove;
    for(; iter!=proc->module_list.end(); iter++)
    {
        //DEBUG-only
        //monitor_printf(default_mon,"module %s base %08x \n",iter->second->name,iter->first);
        if (module_bases.find(iter->first) == module_bases.end())
            bases_to_remove.insert(iter->first);
    }

    set<target_ulong>::iterator iter2;
    for (iter2=bases_to_remove.begin(); iter2!=bases_to_remove.end(); iter2++)
    {
        VMI_remove_module(proc->pid, *iter2);
    }
}

//zyw
// Traverse the memory map for a process
void traverse_mmap_new(CPUState *env, void *opaque, FILE *fp)
{
    process *proc = (process *)opaque;
    target_ulong mm, vma_curr, vma_file, f_dentry, f_inode, mm_mmap, vma_next, vma_prot, vma_flags=NULL;
    set<target_ulong> module_bases;
    unsigned int inode_number;
    target_ulong vma_vm_start = 0, vma_vm_end = 0;
    target_ulong last_vm_start = 0, last_vm_end = 0, mod_vm_start = 0;
    char name[32];  // module file path
    string last_mod_name;
    module *mod = NULL;

    if (DECAF_read_ptr(env, proc->EPROC_base_addr + OFFSET_PROFILE.ts_mm, &mm) < 0)
        return;
    
    if (DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_mmap, &mm_mmap) < 0)
        return;

    // Mark the `modules_extracted` true. This is done because this function calls `VMI_find_module_by_base`
    // and that function calls `traverse_mmap` if `modules_extracted` is false. We don't want to get into
    // an infinite recursion.

    proc->modules_extracted = true;

    if (-1UL == proc->cr3)
        return;


    // starting from the first vm_area, read vm_file. NOTICE vm_area_struct can be null
    if (( vma_curr = mm_mmap) == 0)
        return;
    
    int count = 0;
    while(true)
    {
        count ++;
        // read start of curr vma
        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_start,  &vma_vm_start) < 0)
            goto next;

        // read end of curr vma
        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_end,  &vma_vm_end) < 0)
            goto next;

        DECAF_printf("memory %x:%x ", vma_vm_start, vma_vm_end);    

        if(count == 4) lib_start_addr = vma_vm_start;
        if(count == 6) stack_start_addr = vma_vm_start;  

//zyw
/*
    //index = (addr >> TARGET_PAGE_BITS) & (CPU_TLB_SIZE - 1);
    int mmu_idx = 2;
    uintptr_t retaddr;
    for(int i = 1; i< (vma_vm_end - vma_vm_start)/4096; i++)
    {
        target_ulong addr =  vma_vm_start + i * 4096;
        printf("addr: %x ", addr);
        helper_tlb_fill(env, addr, 2, mmu_idx);
        int index = (addr >> 12) & (256 - 1);
        //void *p = (void *)((uintptr_t)addr + env->tlb_table[mmu_idx][index].addend);
    }

*/
//  

//zyw obtain the memory area property
        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_page_prot, &vma_prot) < 0)      
            goto next;

        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_flags, &vma_flags) < 0)      
            goto next;

        DECAF_printf("%x ",vma_prot);
        DECAF_printf("%x ", vma_flags);
        fprintf(fp, "%x:", vma_vm_start);
        fprintf(fp, "%x:", vma_vm_end);
        fprintf(fp, "%x:", vma_flags);
        fprintf(fp, "%x\n", vma_prot);

//
         
        

        // read the struct* file entry of the curr vma, used to then extract the dentry of the this page
        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_file, &vma_file) < 0 || !vma_file)
            goto next;

        // dentry extraction from the struct* file
        if (DECAF_read_ptr(env, vma_file + OFFSET_PROFILE.file_dentry, &f_dentry) < 0 || !f_dentry)
            goto next;



        // read small names form the dentry
        if (DECAF_read_mem(env, f_dentry + OFFSET_PROFILE.dentry_d_iname, 32, name) < 0)
            goto next;

        DECAF_printf("%s ", name);

        // inode struct extraction from the struct* file
        if (DECAF_read_ptr(env, f_dentry + OFFSET_PROFILE.file_inode, &f_inode) < 0 || !f_inode)
            goto next;

        // inode_number extraction
        if (DECAF_read_ptr(env, f_inode + OFFSET_PROFILE.inode_ino, &inode_number) < 0 || !inode_number)
            goto next;

        name[31] = '\0';    // truncate long string

        // name is invalid, move on the data structure
        if (strlen(name)==0)
            goto next;


        
        if (!strcmp(last_mod_name.c_str(), name))
        {
            // extending the module
            if(last_vm_end == vma_vm_start)
            {
                assert(mod);
                target_ulong new_size = vma_vm_end - mod_vm_start;
                if (mod->size < new_size)
                    mod->size = new_size;
            }
            // This is a special case when the data struct is BEING populated
            goto next;
        }

        char key[32+32];
        //not extending, a different module
        mod_vm_start = vma_vm_start;

        sprintf(key, "%u_%s", inode_number, name);
        mod = VMI_find_module_by_key(key);
        module_bases.insert(vma_vm_start);
        if (!mod)
        {
            mod = new module();
            strncpy(mod->name, name, 31);
            mod->name[31] = '\0';
            mod->size = vma_vm_end - vma_vm_start;
            mod->inode_number = inode_number;
            mod->symbols_extracted = 0;
            VMI_add_module(mod, key);
        }

        if(VMI_find_module_by_base(proc->cr3, vma_vm_start) != mod)
        {
            VMI_insert_module(proc->pid, mod_vm_start , mod);
        }

next:
        DECAF_printf("\n");
        if (DECAF_read_ptr(env, vma_curr + OFFSET_PROFILE.vma_vm_next, &vma_next) < 0)
            break;

        if (vma_next == NULL)
        {
            break;
        }

        vma_curr = vma_next;
        last_mod_name = name;
        if (mod != NULL)
        {
            last_vm_start = vma_vm_start;
            last_vm_end = vma_vm_end;
        }
    }
   
    fclose(fp);

    unordered_map<uint32_t, module *>::iterator iter = proc->module_list.begin();
    set<target_ulong> bases_to_remove;
    for(; iter!=proc->module_list.end(); iter++)
    {
        //DEBUG-only
        //monitor_printf(default_mon,"module %s base %08x \n",iter->second->name,iter->first);
        if (module_bases.find(iter->first) == module_bases.end())
            bases_to_remove.insert(iter->first);
    }

    set<target_ulong>::iterator iter2;
    for (iter2=bases_to_remove.begin(); iter2!=bases_to_remove.end(); iter2++)
    {
        VMI_remove_module(proc->pid, *iter2);
    }
}


//New process callback function
static int countt = 0;
static void new_proc_callback(DECAF_Callback_Params* params)
{
    CPUState *env = params->bb.env;
    target_ulong pc = DECAF_getPC(env);
    if(env == NULL) DECAF_printf("no env\n");
/*
    if(0x402424 == pc){ //0x402434
	DECAF_printf("into hewdig\n");
    }

    if(0x40a0b8 == pc){ //0x402434
	CPUArchState *cpu = env->env_ptr;
	target_ulong s3 = cpu->active_tc.gpr[19];//fd
	char *tmpBuf = (char*)malloc(25);
	memset(tmpBuf, 0, 25);
	DECAF_read_mem(env, s3, 25, tmpBuf);
	DECAF_printf("before execv:%x, %s\n", s3, tmpBuf);
    }		  
*/
    if(OFFSET_PROFILE.proc_exec_connector != pc)
        return;
    traverse_task_struct_add(env);
}

//Process exit callback function
static void proc_end_callback(DECAF_Callback_Params *params)
{

    CPUState *env = params->bb.env;

    target_ulong pc = DECAF_getPC(env);

    if(OFFSET_PROFILE.proc_exit_connector != pc)
        return;
    traverse_task_struct_remove(env);
}

// Callback corresponding to `vma_link`,`vma_adjust` & `remove_vma`
// This marks the `modules_extracted` for the process `false`
void VMA_update_func_callback(DECAF_Callback_Params *params)
{
    CPUState *env = params->bb.env;

    target_ulong pc = DECAF_getPC(env);

    if(!(pc == OFFSET_PROFILE.vma_link) && !(pc == OFFSET_PROFILE.vma_adjust) && !(pc == OFFSET_PROFILE.remove_vma))
        return;

    uint32_t pgd =  DECAF_getPGD(env);
    process *proc = NULL;

    proc = VMI_find_process_by_pgd(pgd);

    if(proc)
        proc->modules_extracted = false;
}

// TLB miss callback
// This callback is only used for updating modules when users have registered for either a
// module loaded/unloaded callback.
void Linux_tlb_call_back(DECAF_Callback_Params *temp)
{
    CPUState *ourenv = temp->tx.env;
    uint32_t pgd = -1;
    process *proc = NULL;

    // Check too see if any callbacks are registered
    if(!VMI_is_MoudleExtract_Required())
    {
        return;
    }
	
    // The first time we register for some VMA related callbacks
    if(first)
    {
        monitor_printf(cur_mon,"Registered for VMA update callbacks!\n");
        DECAF_registerOptimizedBlockBeginCallback(&VMA_update_func_callback, NULL, OFFSET_PROFILE.vma_adjust, OCB_CONST);
        DECAF_registerOptimizedBlockBeginCallback(&VMA_update_func_callback, NULL, OFFSET_PROFILE.vma_link, OCB_CONST);
        DECAF_registerOptimizedBlockBeginCallback(&VMA_update_func_callback, NULL, OFFSET_PROFILE.remove_vma, OCB_CONST);
        first = 0;
    }

    pgd = DECAF_getPGD(ourenv);
    proc = VMI_find_process_by_pgd(pgd);

    // Traverse memory map for a process if required.
    if (proc && !proc->modules_extracted)
    {
        traverse_mmap(ourenv, proc);
    }
}

//zyw
target_ulong mips_get_cur_pid(CPUState *env, char *proc_name)
{
//not use tulInitTaskAddr
	ProcInfo offset_profile = {"VMI"};
	target_ulong pid = 0;
	target_ulong current_task_addr;
	target_ulong _thread_info = DECAF_getESP(env) & ~ (guestOS_THREAD_SIZE - 1);
	//target_ulong _thread_info = ((CPUMIPSState *)env->env_ptr)->active_tc.gpr[28];
	//target_ulong tulInitTaskAddr = findTaskStructFromThreadInfo(env, _thread_info, &offset_profile, 0);
	DECAF_read_ptr(env, _thread_info + OFFSET_PROFILE.ti_task, &current_task_addr);
	//printf("thread_info_addr:%x, task_addr:%x, ts_pid:%x\n",_thread_info,current_task_addr,OFFSET_PROFILE.ts_pid);
	DECAF_read_ptr(env, current_task_addr + OFFSET_PROFILE.ts_pid, &pid);
	DECAF_read_mem(env, current_task_addr + OFFSET_PROFILE.ts_comm, SIZEOF_COMM, proc_name);
	return pid;
}
	
//zyw
/*
target_ulong mips_get_cur_pgd(CPUState *env)
{
	ProcInfo offset_profile = {"VMI"};
	target_ulong mm, task_pgd, proc_cr3;
	target_ulong _thread_info = DECAF_getESP(env) & ~ (guestOS_THREAD_SIZE - 1);	
	target_ulong tulInitTaskAddr = findTaskStructFromThreadInfo(env, _thread_info, &offset_profile, 0);
	DECAF_read_ptr(env, tulInitTaskAddr + OFFSET_PROFILE.ts_mm,
                                &mm);
	DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_pgd,
                                    &task_pgd);
	return task_pgd;
	
}



target_ulong mips_get_cur_cr3(CPUState *env)
{
	ProcInfo offset_profile = {"VMI"};
	target_ulong mm, task_pgd, proc_cr3;
	target_ulong _thread_info = DECAF_getESP(env) & ~ (guestOS_THREAD_SIZE - 1);	
	target_ulong tulInitTaskAddr = findTaskStructFromThreadInfo(env, _thread_info, &offset_profile, 0);
	DECAF_read_ptr(env, tulInitTaskAddr + OFFSET_PROFILE.ts_mm,
                                &mm);
	DECAF_read_ptr(env, mm + OFFSET_PROFILE.mm_pgd,
                                    &task_pgd);
	proc_cr3 = DECAF_get_phys_addr(env, task_pgd);
	return proc_cr3;
	
}
*/

// to see whether this is a Linux or not,
// the trick is to check the init_thread_info, init_task
int find_linux(CPUState *env, uintptr_t insn_handle)
{
    target_ulong _thread_info = DECAF_getESP(env) & ~ (guestOS_THREAD_SIZE - 1);
    static target_ulong _last_thread_info = 0;

// if current address is tested before, save time and do not try it again
    if (_thread_info == _last_thread_info || _thread_info <= 0x80000000)
        return 0;
// first time run
    if (_last_thread_info == 0)
    {
// memset(&OFFSET_PROFILE.init_task_addr, -1, sizeof(ProcInfo) - sizeof(OFFSET_PROFILE.strName));
    }

    _last_thread_info = _thread_info;



    if(0 != load_proc_info(env, _thread_info, OFFSET_PROFILE))
    {
        return 0;
    }

    //monitor_printf(cur_mon, "swapper task @ [%08x] \n", OFFSET_PROFILE.init_task_addr);

    VMI_guest_kernel_base = 0xc0000000;

    return (1);
}



// when we know this is a linux
void linux_vmi_init()
{ 	
	DECAF_registerOptimizedBlockBeginCallback(&new_proc_callback, NULL, OFFSET_PROFILE.proc_exec_connector, OCB_CONST);
	DECAF_registerOptimizedBlockBeginCallback(&new_kmod_callback, NULL, OFFSET_PROFILE.trim_init_extable, OCB_CONST);
	DECAF_registerOptimizedBlockBeginCallback(&proc_end_callback, NULL, OFFSET_PROFILE.proc_exit_connector, OCB_CONST);
    DECAF_register_callback(DECAF_TLB_EXEC_CB, Linux_tlb_call_back, NULL);

	process *kernel_proc = new process();
	kernel_proc->cr3 = 0;
	strcpy(kernel_proc->name, "<kernel>");
	kernel_proc->pid = 0;
	VMI_create_process(kernel_proc);
}

/*
gpa_t mips_get_cur_pgd(CPUState *env)
{
    const target_ulong MIPS_KERNEL_BASE = 0x80000000;
    gpa_t pgd = 0;
    if(0 == OFFSET_PROFILE.mips_pgd_current)
    {
        //monitor_printf(cur_mon, "Error\nmips_get_cur_pgd: read pgd before procinfo is populated.\n");
        return 0;
    }

    DECAF_read_ptr(env,
                   OFFSET_PROFILE.mips_pgd_current,
                   &pgd);
    pgd &= ~MIPS_KERNEL_BASE;
    return pgd;
}
*/

//ldl_phys first argument need to be env->as

static struct {
    target_ulong pgd_current_p;
    int softshift;
} linux_pte_info = {0};



gpa_t mips_get_cur_pgd(CPUState *env){

    if (unlikely(linux_pte_info.pgd_current_p == 0)) {
        int i;
        uint32_t lui_ins, lw_ins, srl_ins;
        uint32_t address;
        uint32_t ebase;

        /*
         * The exact TLB refill code varies depeing on the kernel version
         * and configuration. Examins the TLB handler to extract
         * pgd_current_p and the shift required to convert in memory PTE
         * to TLB format
         */
        static struct {
            struct {
                uint32_t off;
                uint32_t op;
                uint32_t mask;
            } lui, lw, srl;
        } handlers[] = {
            /* 2.6.29+ */
            {
                {0x00, 0x3c1b0000, 0xffff0000}, /* 0x3c1b803f : lui k1,%hi(pgd_current_p) */
                {0x08, 0x8f7b0000, 0xffff0000}, /* 0x8f7b3000 : lw  k1,%lo(k1) */
                {0x34, 0x001ad182, 0xffffffff}  /* 0x001ad182 : srl k0,k0,0x6 */
            },
            /* 3.4+ */
            {
                {0x00, 0x3c1b0000, 0xffff0000}, /* 0x3c1b803f : lui k1,%hi(pgd_current_p) */
                {0x08, 0x8f7b0000, 0xffff0000}, /* 0x8f7b3000 : lw  k1,%lo(k1) */
                {0x34, 0x001ad142, 0xffffffff}  /* 0x001ad182 : srl k0,k0,0x5 */
            }
        };
 	CPUArchState *cpu = (CPUArchState *)(env->env_ptr);
	ebase = cpu->CP0_EBase - 0x80000000;
        /* Match the kernel TLB refill exception handler against known code */
        for (i = 0; i < sizeof(handlers)/sizeof(handlers[0]); i++) {
            lui_ins = ldl_phys(env->as, ebase + handlers[i].lui.off);
            lw_ins = ldl_phys(env->as, ebase + handlers[i].lw.off);
            srl_ins = ldl_phys(env->as, ebase + handlers[i].srl.off);
            if (((lui_ins & handlers[i].lui.mask) == handlers[i].lui.op) &&
                ((lw_ins & handlers[i].lw.mask) == handlers[i].lw.op) &&
                ((srl_ins & handlers[i].srl.mask) == handlers[i].srl.op))
                break;
        }
        if (i >= sizeof(handlers)/sizeof(handlers[0])) {
            //for (i = 0; i < 0x80; i+= 4)
                //printf("0x%08x: 0x%08x\n", ebase + i, ldl_phys(env->as, ebase + i));
//zyw
            cpu_abort(env, "TLBMiss handler signature not recognized\n");
	    //return 0xffffffff;
//
	
        }
        address = (lui_ins & 0xffff) << 16;
        address += (((int32_t)(lw_ins & 0xffff)) << 16) >> 16;
        if (address >= 0x80000000 && address < 0xa0000000)
            address -= 0x80000000;
        else if (address >= 0xa0000000 && address <= 0xc0000000)
            address -= 0xa0000000;
        else
            cpu_abort(env, "pgd_current_p not in KSEG0/KSEG1\n");

        linux_pte_info.pgd_current_p = address;
        linux_pte_info.softshift = (srl_ins >> 6) & 0x1f;
    }

    /* Get pgd_current */
    //return ldl_phys(env->as, linux_pte_info.pgd_current_p);
    return ldl_phys(env->as, linux_pte_info.pgd_current_p) - 0x80000000; //zyw
}
