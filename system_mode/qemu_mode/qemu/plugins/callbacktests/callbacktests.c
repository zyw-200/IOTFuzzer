/*
Copyright (C) <2012> <Syracuse System Security (Sycure) Lab>
This is a plugin of DECAF. You can redistribute and modify it
under the terms of BSD license but it is made available
WITHOUT ANY WARRANTY. See the top-level COPYING file for more details.

For more information about DECAF and other softwares, see our
web site at:
http://sycurelab.ecs.syr.edu/

If you have any questions about DECAF,please post it on
http://code.google.com/p/decaf-platform/
*/
/**
 * @author Lok Yan
 * @date Oct 18 2012
 */
#include "qemu/osdep.h"
#include "cpu.h"

#include "DECAF_types.h"
#include "DECAF_main.h"
#include "DECAF_callback.h"
#include "vmi_callback.h"
#include "utils/Output.h"
#include "vmi_c_wrapper.h"
#include "afl-qemu-cpu-inl.h"


//http socket
#include <stdio.h>
#include <string.h>
#include <stdlib.h>
#include <unistd.h>
#include <fcntl.h>

#include <netinet/tcp.h>
#include <sys/socket.h>
#include <sys/types.h>
#include <netinet/in.h>
#include <netdb.h>
#include "zyw_config.h"
#include <sys/time.h>


static struct timeval tv_api;
static char old_api[100];
static float sec_delta = 0.0;

//basic stub for plugins

char * VMI_find_process_name_by_pgd(uint32_t pgd);

static plugin_interface_t callbacktests_interface;
static int bVerboseTest = 0;
static int enableTimer = 0;
static int afl_begin = 0;
static int afl_fork = 0;
//static char *current_data = NULL;
static int rest_len = 0;
static int network_read_block = 0;
static int open_block = 0;
static int accept_block = 0;
static int current_fd = 0;
static int write_fd = 0;
static int start_debug = 0;
static int tmp_pc = 0;
static int httpd_pid[100]; //httpd will fork itself
static int current_pid = 0;
static char current_program[50];
static int pid_index = 0;
static target_ulong kernel_sp = 0;
static int run_test = 0;
static int http_request = 0;
static int main_start = 0;


static DECAF_Handle processbegin_handle = DECAF_NULL_HANDLE;
static DECAF_Handle removeproc_handle = DECAF_NULL_HANDLE;
static DECAF_Handle block_begin_handle = DECAF_NULL_HANDLE;
static DECAF_Handle block_end_handle = DECAF_NULL_HANDLE;
static DECAF_Handle mem_write_cb_handle = DECAF_NULL_HANDLE;
static DECAF_Handle insn_begin_handle = DECAF_NULL_HANDLE;
#define PRO_MAX_NUM 10
static char targetname[PRO_MAX_NUM][512];// 10 program, such as httpd, hedwig.cig
static uint32_t targetcr3[PRO_MAX_NUM];// 10 program, such as httpd, hedwig.cig
static uint32_t targetpid[PRO_MAX_NUM];// 10 program, such as httpd, hedwig.cig
static int target_main_address[PRO_MAX_NUM];

static int target_index = 0; //the number of target program

//zyw
extern int helper_flag;
extern int helper_ASID[2];

extern void traverse_pagemap(CPUState *env, int pgd, target_ulong vaddr);

int target_exist(char *name){
	for(int i=0; i<target_index; i++){
		if (strcmp(targetname[i], name) == 0){
			return i;
		}
	}
	return -1;
}

int targetpid_exist(uint32_t pid){
	for(int i=0; i<target_index; i++){
		if (pid == targetpid[i]){
			return i;
		}
	}
	return -1;
}

int targetcr3_exist(uint32_t cr3){
	for(int i=0; i<target_index; i++){
		if (cr3 == targetcr3[i]){
			return i;
		}
	}
	return -1;
}

static void runTests(void);

static void callbacktests_printSummary(void);
static void callbacktests_resetTests(void);


static int count = 0;
static int poll = 0;
static int api_time = 0;


static plugin_interface_t keylogger_interface;

DECAF_Handle handle_ins_end_cb = DECAF_NULL_HANDLE;
DECAF_Handle handle_block_end_cb = DECAF_NULL_HANDLE;
DECAF_Handle handle_block_begin_cb = DECAF_NULL_HANDLE;
FILE * keylogger_log=DECAF_NULL_HANDLE;

#define MAX_STACK_SIZE 5000
char modname_t[512];
char func_name_t[512];

extern uint32_t sys_call_ret_stack[2][MAX_STACK_SIZE];
extern uint32_t sys_call_entry_stack[2][MAX_STACK_SIZE];
extern uint32_t cr3_stack[2][MAX_STACK_SIZE];
extern uint32_t stack_top[2];

extern uint32_t saved_stack[2][MAX_STACK_SIZE];
extern uint32_t saved_stack_top[2];

void check_call(DECAF_Callback_Params *param, int index)
{
	//DECAF_printf("check_call:%d\n", index);
	CPUState *env=param->be.env;
	CPUArchState *mips_env = env->env_ptr;
	if(env == NULL)
	return;
	target_ulong pc = param->be.next_pc;
	target_ulong cr3 = DECAF_getPGD(env) ;
	if(stack_top[index] == MAX_STACK_SIZE)
	{
     //if the stack reaches to the max size, we ignore the data from stack bottom to MAX_STACK_SIZE/10
		memcpy(sys_call_ret_stack[index],&sys_call_ret_stack[index][MAX_STACK_SIZE/10],MAX_STACK_SIZE-MAX_STACK_SIZE/10);
		memcpy(sys_call_entry_stack[index],&sys_call_entry_stack[index][MAX_STACK_SIZE/10],MAX_STACK_SIZE-MAX_STACK_SIZE/10);
		memcpy(cr3_stack,&cr3_stack[index][MAX_STACK_SIZE/10],MAX_STACK_SIZE-MAX_STACK_SIZE/10);
		stack_top[index] = MAX_STACK_SIZE-MAX_STACK_SIZE/10;
		return;
	}

	//DECAF_read_mem(env,mips_env->active_tc.gpr[28],4,&sys_call_ret_stack[stack_top]);
	sys_call_entry_stack[index][stack_top[index]] = pc;
	cr3_stack[index][stack_top[index]] = cr3;
	stack_top[index]++;




}


void check_ret(DECAF_Callback_Params *param, int index)
{
	//DECAF_printf("check_ret:%d\n", index);
	if(!stack_top[index])
		return;
	if(stack_top[index] > 0){
		if(param->be.next_pc == sys_call_entry_stack[index][stack_top[index]-1])
		{
			stack_top[index]--;
		}
		else if(param->be.next_pc > 0x80000000){
			DECAF_printf("jump into kernel, maybe not overflow\n");
		} 
		else{
			DECAF_printf("stack overflow(%d):%x, %x, %d\n", index, param->be.next_pc, sys_call_entry_stack[index][stack_top[index]-1], stack_top[index] - 1);

			for(int i=0;i< stack_top[index];i++){
				DECAF_printf("%d:%x\n",i,sys_call_entry_stack[index][i]);
				//sleep(10000);
			}
			//DECAF_printf("checke ret done work\n");
			endWork(32);
			//doneWork(32);
		}
	}
	
}



void stopWork(){
	stopTrace();
	DECAF_printf("time is up\n");
	afl_fork = 0;
	targetpid[1]=0;
	targetcr3[1]=0;	
	//endWork(0);
}


//int do_block_begin_flag = 0;
int after = 0;
extern int fcntl_start;
int in_httpd = 0;
extern int program_start;		
extern int afl_user_fork;
extern void store_page(uint32_t virt_addr,  uintptr_t addr, int in_httpd);
extern void store_addr(uint32_t virt_addr, uint32_t phys_addr, uintptr_t host_addr, int type, unsigned long value);
extern void cpu_physical_memory_rw(hwaddr addr, uint8_t *buf,int len, int is_write);

extern int lib_start_addr;

static void do_block_begin(DECAF_Callback_Params* param)
{
/*
	if(param->bb.tb->pc == 	0x805510f0){ //egrep ' (panic|log_store)$' /proc/kallsyms
		DECAF_printf("kernel panic\n");
		doneWork(32);
	}
*/
	CPUArchState *cpu = param->bb.env->env_ptr;
	target_ulong pc = param->bb.tb->pc;
	target_ulong pgd = DECAF_getPGD(param->bb.env);
	int index = targetcr3_exist(pgd);
	char cur_process[512];
	int pid;
	VMI_find_process_by_cr3_c(pgd, cur_process, 512, &pid);
	if(index == -1)
	{
		int index = target_exist(cur_process);
		if(index != -1)
		{
			targetcr3[index] = pgd;
			targetpid[index] = pid;
			in_httpd = 1;
		}
		else{
			//if(helper_flag == 1) DECAF_printf("pid:%d,proc:%s\n", pid, cur_process);
			in_httpd = 0;
			return;
		}
	}
	else{
		index = target_exist(cur_process); //sometimes the pgd of child program is the same as parent program. need to recalculate the index
		if(index == -1) {
			in_httpd = 0;
			return; // cur_process is null, pid is 0
		}
	}
	in_httpd = 1;


	helper_ASID[index] = cpu->CP0_EntryHi & cpu->CP0_EntryHi_ASID_mask; //zyw
	//if(pc < 0x70000000 && after == 1){ DECAF_printf("pc:%x, cur_proc:%s, pgd:%x\n", pc, cur_process, pgd); }//after = 0;}

	char modname[512];
	char functionname[512];

	if(pc >= 0x80000000 && kernel_stack_count == 0)
	{
		kernel_stack_count = 1;
		kernel_origpt = cpu->active_tc.gpr[29];
		//printf("sizeof CPUArchState:%d\n", sizeof(CPUArchState));
		DECAF_printf("%s kernel stack:%x\n",cur_process, kernel_origpt);
	}
	//else if(pc < 0x70000000 && user_stack_count == 0) //need change 0x40a218
	else if(pc ==0x4023e0 && user_stack_count == 0) //need change 0x40a218
	{
		DECAF_printf("%s user pc:%x\n",cur_process, pc);
		user_stack_count = 1;
		DECAF_printf("lib start addr:%x\n", lib_start_addr);


	}
/*
	//if((pc-lib_start_addr) == transit_pc && only_transit_for_one_time == 0) 
 	if(pc == transit_pc && only_transit_for_one_time == 0) 
	{
		DECAF_printf("fp is:%x\n", cpu->active_tc.gpr[30]);
		only_transit_for_one_time = 1;
		DECAF_printf("transit at recvfrom buf:%x, len:%d, actual len:%d\n", cpu->active_tc.gpr[5], cpu->active_tc.gpr[6], cpu->active_tc.gpr[2]);
		FILE * fp = fopen(mapping_filename, "a+");
		for(int i=0;i<32;i++) {
			fprintf(fp, "%x\n", cpu->active_tc.gpr[i]);
		}
		fprintf(fp, "%x\n", cpu->active_tc.PC);
		fprintf(fp ,"%x\n", cpu->CP0_Status);
		fprintf(fp ,"%x\n", cpu->CP0_Cause);
		fprintf(fp ,"%x\n", cpu->CP0_EPC);
		printf("user pc:%x,%x, stack:%x, pgd:%x, qemu pid:%x\n", pc, cpu->active_tc.PC, cpu->active_tc.gpr[29], pgd, getpid());

		//print_mapped_page(fp); // ###3old version print memory mapping to file





		//int index = (addr >> 12) & (256 - 1);
		for(int i=2; i<=2; i++)
		{
			for(int j =0; j <= 255; j++)
			{//CPUTLBEntry
				uintptr_t t = cpu->tlb_table[i][j].addend;
				target_ulong read = cpu->tlb_table[i][j].addr_read;
				target_ulong write = cpu->tlb_table[i][j].addr_write;
				target_ulong code = cpu->tlb_table[i][j].addr_code;
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

		pause_stateTransit_wait(param->bb.env);

	}
*/
	if(pc > 0x50000000)	
		return;

}

int stack_monitor = 0;

static void do_block_end(DECAF_Callback_Params* param){	
/*
	CPUArchState *cpu = param->be.env->env_ptr;
	target_ulong pc = param->be.cur_pc;
	if(pc > 0x80000000)
		return;


	target_ulong bk_pc = param->be.tb->pc;
	target_ulong pgd = DECAF_getPGD(param->be.env);
	int index = targetcr3_exist(pgd);
	char cur_process[512];
	int pid;
	VMI_find_process_by_cr3_c(pgd, cur_process, 512, &pid);
	if(index == -1)
	{
		index = target_exist(cur_process);
		if(index != -1)
		{
			targetcr3[index] = pgd;
			targetpid[index] = pid;
		}
		else{
			return;
		}
	}
	else{
		index = target_exist(cur_process); //sometimes the pgd of child program is the same as parent program. need to recalculate the index
		if(index == -1) return; // cur_process is null, pid is 0
	}
		

	if(pc < 0x70000000) printf("pc is:%lx\n", pc);
		//zyw main
	if(bk_pc == 0x4023e0)
	{
		DECAF_printf("start monitor\n");
		stack_monitor = 1;
	}
	if(stack_monitor!=1)
		return;


//NEED CHANGE
	index =  1;
	if(index == 1){

		if(pc >= 0x419470 && pc <= 0x41991c) //.MIPS.stubs for hedwig.cgi(cgibin)
			return;
	}
	if(index == 0){
		if(pc >= 0x411910 && pc <= 0x411f7c)//.MIPS.stubs for httpd
			return;
	}

	if(pc > 0x50000000) return;

	//DECAF_printf("into block_begin:%x\n", pc);


	unsigned char insn_buf[4];
	int is_call = 0, is_ret = 0;

	DECAF_read_mem(param->be.env,pc - 4 ,sizeof(char)*4,insn_buf);
	if(insn_buf[0] == 9 && (insn_buf[1]&7) == 0 && (insn_buf[2]&31) == 0 && (insn_buf[3]&252) == 0){ //jalr
		param->be.next_pc = param->be.cur_pc + 4;
		int jump_reg = (insn_buf[3] * 8) + (insn_buf[2]/32);
		int next_reg = insn_buf[1]/8;			
		
		int jump_addr = cpu->active_tc.gpr[jump_reg];
		if(jump_addr > 0x80000000)
			return;
		
		int jump_value = ((CPUArchState *)param->be.env->env_ptr)->active_tc.gpr[25];
		//DECAF_printf("jalr ins:%x, next pc:%x, jalr reg:%d, jalr next reg:%d\n",param->be.cur_pc, param->be.next_pc, jump_reg, next_reg);
		if(next_reg == 31){
			is_call = 1;
		}
	}else if((insn_buf[3] & 252) == 12){ //jal
		param->be.next_pc = param->be.cur_pc + 4;
		int jump_addr = insn_buf[0] + insn_buf[1]*256 + insn_buf[2]* 256*256;
		if(jump_addr > 0x80000000)
			//DECAF_printf("jal:%x\n", jump_addr);
			return;
		//DECAF_printf("jal ins:%x, next pc:%x\n",param->be.cur_pc, param->be.next_pc);
		is_call = 1;
	}else if((insn_buf[0] & 63) == 8 && insn_buf[1] == 0 && (insn_buf[2]&31) == 0 && (insn_buf[3]&252) == 0){
		int reg = (insn_buf[3] *8) + (insn_buf[2]/32);
		if(reg == 31){ 
			//jr $ra, not jr other(such as jr $t9, jump at the end of function)
			int jump_addr = cpu->active_tc.gpr[reg];
			if(jump_addr > 0x80000000)
				return;	
			//DECAF_printf("jr ins:%x, next pc:%x, jr reg:%d\n",param->be.cur_pc, param->be.next_pc, reg);	
			is_ret = 1;
		}
		else if(reg == 25){
			//jr $ra happens in lib function
			//if(param->be.cur_pc < 0x70000000){
			//DECAF_printf("jr ins:%x, next pc:%x, jr reg:%d\n",param->be.cur_pc, param->be.next_pc, reg);
			//}




			//if(stack_top > 0){
			//	stack_top --;
			//}

		}	
	}else if((insn_buf[3] == 0x04) && (insn_buf[2] == 0x11)){ //bal;
		param->be.next_pc = param->be.cur_pc + 4;
		int offset = insn_buf[1] * 1024 + insn_buf[0] * 4;
		int jump_addr =  param->be.cur_pc + offset;
		if(jump_addr > 0x80000000)
			//DECAF_printf("jal:%x\n", jump_addr);
			return;
		if(offset <= 4) //bal the next pc
			return;
		//DECAF_printf("bal ins:%x, next pc:%x, jmp pc:%x\n",param->be.cur_pc, param->be.next_pc, offset);
		is_call = 1;	
	}

	//if (is_call)
	//check_call(param, index);
	//else if (is_ret)
	//check_ret(param, index);
*/
}

static void do_insn_begin(DECAF_Callback_Params* param){
/*
	CPUState * cpu = param->ib.env;
	CPUArchState * env = cpu->env_ptr;
	if(env->current_tc >= 0X80109C10 && env->current_tc <= 0X80109C80 && user_stack_count){
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
		printf("insn:%x, k1:%x, calk1:%x, sp:%x, gp:%x, v0:%x, v1:%x\n",env->current_tc, env->active_tc.gpr[28], k1, env->active_tc.gpr[29], env->active_tc.gpr[28],v0, v1);
	}
*/
}

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
			
extern double full_store_page_time;
struct timeval store_page_start;
struct timeval store_page_end;

extern int sem_addr;

static void fuzz_mem_write(DECAF_Callback_Params *dcp)
{

	if(afl_user_fork == 1)
	{
		uint32_t virt_addr = dcp->mw.vaddr;
		uint32_t phys_addr = dcp->mw.paddr; 
		uintptr_t host_addr = dcp->mw.haddr; //64bit
		int dt = dcp->mw.dt;
		unsigned long value = dcp->mw.value;
		int caller_pos = dcp->mw.caller_pos;
		uintptr_t page_host_addr = host_addr & 0xfffffffffffff000;
		int dl = data_length(value);

//brk problem
		/*
		if(sem_addr!= 0 && virt_addr == sem_addr)
		{
			printf("$$$$$$$$$$$$$write to sem for the mem:%x, %x\n",virt_addr, value);
		}
		*/
//

		if ((virt_addr & 0xfff) + dl > 0x1000)
	    { 
	        printf("cross page:lx\n", virt_addr);
	        exit(32);
	    } 
	    //if(value == 0) printf("kernel store:%lx\n", phys_addr);
	    //if((phys_addr & 0xfff000) == 0x6e9000) { printf("meet physica:%x\n", phys_addr);}
	    //if((phys_addr & 0xfff000) == 0x72d000) { printf("meet physica:%x\n", phys_addr);}


	    //zyw very important call_pos 1 exec.c virt_addr = 0
	     /*
	    if(caller_pos!=1 && virt_addr<0x80000000 &&  in_httpd) 
	    {
	    	//printf("not store physical:%x\n", phys_addr);
	        return;// test program and in user space
	    }
	   
	    if(caller_pos==1 && phys_addr > 0xe000000 && in_httpd) 
	    {
	        return;// 
	    }
	    */

// memory consistence
#ifdef SNAPSHOT_SYNC
	    if(in_httpd && (virt_addr<0x80000000))
	    {

	    	int ifexist = if_physical_exist(phys_addr & 0xfffff000);
	    	if(ifexist)
	    	{
	    		return;
	    	}
	    	add_physical_page(phys_addr & 0xfffff000);
	    }
#endif

//
	    gettimeofday(&store_page_start, NULL);
		store_page(virt_addr & 0xfffff000, page_host_addr, in_httpd);
		gettimeofday(&store_page_end, NULL);
    	full_store_page_time += (double)store_page_end.tv_sec - store_page_start.tv_sec + (store_page_end.tv_usec - store_page_start.tv_usec)/1000000.0;

	}


}

void do_callbacktests(Monitor* mon, const QDict* qdict)
{
  if ((qdict != NULL) && (qdict_haskey(qdict, "procname")))
  {
 
    strncpy(targetname[target_index], qdict_get_str(qdict, "procname"), 512);
    targetname[target_index][511] = '\0';
    target_index++;
  }
}

//zyw
extern uint32_t httpd_pgd;
extern int pgd_changed;
int first_pgd = 0;

static void callbacktests_loadmainmodule_callback(VMI_Callback_Params* params)
{
	char procname[64];
	uint32_t pid;
	if (params == NULL)
	{
		return;
	}

	VMI_find_process_by_cr3_c(params->cp.cr3, procname, 64, &pid);

	if (pid == (uint32_t)(-1))
	{
		return;
	}
	int index = target_exist(procname);
	if (index != -1)
	{
		helper_flag = 1;
		gettimeofday(&tv_api, NULL);
		DECAF_printf("\nProcname:%s/%d,pid:%d, cr3:%x start, time:%d,%d\n",procname, index, pid, params->cp.cr3, tv_api.tv_sec, tv_api.tv_usec);
		char modname[512];
		char functionname[512];
		target_ulong base;
		//print_mapping(modname, params->cp.cr3, &base);// obtain mapping
		targetpid[index] = pid;
		targetcr3[index] = params->cp.cr3;

		if(strcmp(procname,program_analysis) == 0 && first_pgd == 0)  
		{ 
			if(first_or_new_pgd == 0) 
				first_pgd = 1;
#ifdef multiple_process
			else 
				first_or_new_pgd--;
#endif
			httpd_pgd = targetcr3[index]; 
			pgd_changed = 1;

			if(program_start == 0){
				program_start = 1;
				printf("program start:%s,%x\n", procname, httpd_pgd);
			}
		}
	}
}

static void callbacktests_removeproc_callback(VMI_Callback_Params* params)
{

  	char procname[64];
	uint32_t pid;

	if (params == NULL)
	{
		return;
	}
	VMI_find_process_by_cr3_c(params->rp.cr3, procname, 64, &pid);
	
	if (pid == (uint32_t)(-1))
	{
		return;
	}
	int index = target_exist(procname);
	if (index != -1)
	{
		stack_top[index] = 0; //??????????????????????????? http end
		gettimeofday(&tv_api, NULL);
		DECAF_printf("\nProcname:%s/%d,pid:%d, cr3:%x end, time:%d,%d\n",procname, index, pid, params->rp.cr3,  tv_api.tv_sec, tv_api.tv_usec);
		//targetpid[index] = 0;
		//targetcr3[index] = 0;
		kernel_stack_count = 0;
		user_stack_count = 0;
		/*
		if(strcmp(procname, "hedwig.cgi")==0) 
		//if(strcmp(procname, "single_httpd_sa")==0)//NEED CHANGE
		{
			//stopTrace();
			afl_fork = 0;
			//network_read_block = 1; //change before/after create snapshot.
//zyw ?
			targetpid[1]=0;
			targetcr3[1]=0;		
		
			struct itimerval tick;
			memset(&tick, 0, sizeof(tick));    
			tick.it_value.tv_sec = 0;  // sec  
			tick.it_value.tv_usec = 0; // micro sec
			int ret = setitimer(ITIMER_REAL, &tick, NULL);  
			if(ret) DECAF_printf("cancel timer failed\n");
			DECAF_printf("hedwig end work\n");
			//endWork(0);
			//doneWork(0);//NEED CHANGE
		}
		*/
	}

	

	//unregister the callback FIRST before getting the time of day - so
	// we don't get any unnecessary callbacks (although we shouldn't
	// since the guest should be paused.... right?)
/*
	DECAF_printf("unregister handle %x\n", callbacktests[index].handle);
	DECAF_unregister_callback(callbacktests[index].cbtype, callbacktests[index].handle);
	callbacktests[index].handle = DECAF_NULL_HANDLE;
	DECAF_printf("Callback Count = %u\n", callbacktests[index].count);

	gettimeofday(&callbacktests[index].tock, NULL);

	elapsedtime = (double)callbacktests[index].tock.tv_sec + ((double)callbacktests[index].tock.tv_usec / 1000000.0);
	elapsedtime -= ((double)callbacktests[index].tick.tv_sec + ((double)callbacktests[index].tick.tv_usec / 1000000.0));
	DECAF_printf("Process [%s] with pid [%d] ended at %u:%u\n", callbacktests[index].name, params->rp.pid, callbacktests[index].tock.tv_sec, callbacktests[index].tock.tv_usec);
	DECAF_printf("  Elapsed time = %0.6f seconds\n\n", elapsedtime);
*/
}

static int callbacktests_init(void)
{
	DECAF_output_init(NULL);
	DECAF_printf("Hello World\n");

	target_main_address[0] = 0x40a218; //httpd
	target_main_address[1] = 0x4023e0; //hedwig.cgi
	stack_top[0] = 0;
	stack_top[1] = 0;

	processbegin_handle = VMI_register_callback(VMI_CREATEPROC_CB, &callbacktests_loadmainmodule_callback, NULL);
	removeproc_handle = VMI_register_callback(VMI_REMOVEPROC_CB, &callbacktests_removeproc_callback, NULL);
	block_begin_handle = DECAF_registerOptimizedBlockBeginCallback(&do_block_begin, NULL, INV_ADDR, OCB_ALL);
	block_end_handle = DECAF_registerOptimizedBlockEndCallback(&do_block_end, NULL, INV_ADDR, INV_ADDR);
	mem_write_cb_handle = DECAF_register_callback(DECAF_MEM_WRITE_CB,fuzz_mem_write,NULL);
	insn_begin_handle = DECAF_register_callback(DECAF_INSN_BEGIN_CB,do_insn_begin,NULL);				
	for(int i = 0; i < PRO_MAX_NUM; i++){	
		targetcr3[i] = 0;
		targetpid[i] = 0;
		targetname[i][0] = '\0';
	}
  	return (0);
}


static void callbacktests_cleanup(void)
{
  VMI_Callback_Params params;

  DECAF_printf("Bye world\n");

  if (processbegin_handle != DECAF_NULL_HANDLE)
  {
    VMI_unregister_callback(VMI_CREATEPROC_CB, processbegin_handle);
    processbegin_handle = DECAF_NULL_HANDLE;
  }

  if (removeproc_handle != DECAF_NULL_HANDLE)
  {
    VMI_unregister_callback(VMI_REMOVEPROC_CB, removeproc_handle);
    removeproc_handle = DECAF_NULL_HANDLE;
  }

  //make one final call to removeproc to finish any currently running tests
  if (targetpid != (uint32_t)(-1))
  {
    params.rp.pid = targetpid;
    callbacktests_removeproc_callback(&params);
  }
}

#ifdef __cplusplus
extern "C"
{
#endif

static mon_cmd_t callbacktests_term_cmds[] = {
  #include "plugin_cmds.h"
  {NULL, NULL, },
};

#ifdef __cplusplus
}
#endif

plugin_interface_t* init_plugin(void)
{
  callbacktests_interface.mon_cmds = callbacktests_term_cmds;
  callbacktests_interface.plugin_cleanup = &callbacktests_cleanup;
  signal(SIGALRM, stopWork);
  //initialize the plugin
  callbacktests_init();
  return (&callbacktests_interface);
}

