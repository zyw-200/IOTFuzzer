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



static struct timeval tv_start, tv_end, tv_poll, tv_fork, tv_next, tv_api, old_tv_api;
static char old_api[100];
static float sec_delta = 0.0;

//basic stub for plugins

char * VMI_find_process_name_by_pgd(uint32_t pgd);

static plugin_interface_t callbacktests_interface;
static int bVerboseTest = 0;
static int enableTimer = 1;
static int afl_start = 0;
static int afl_fork = 0;
static char *current_data = NULL;
static int rest_len = 0;
static int network_read_block = 0;
static int open_block = 0;
static int recv_block = 0;
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


int pid_exist(int pid){
	for(int i=0; i<pid_index; i++){
		if (pid == httpd_pid[i]){
			return 1;
		}
	}
	return 0;
}

void print_pid(){
	for(int i=0; i<pid_index; i++){
		DECAF_printf("%d ",httpd_pid[i]);
	}
}

void pid_add(int pid){
	httpd_pid[pid_index] = pid;
	pid_index ++;
}


typedef struct _callbacktest_t
{
  char name[64];
  DECAF_callback_type_t cbtype;
  OCB_t ocbtype;
  gva_t from;
  gva_t to;
  DECAF_Handle handle;
  struct timeval tick;
  struct timeval tock;
  int count;
  double elapsedtime;
}callbacktest_t;
#define CALLBACKTESTS_TEST_COUNT 100

/*
static callbacktest_t callbacktests[CALLBACKTESTS_TEST_COUNT] = {
    {"Block Begin Single", DECAF_BLOCK_BEGIN_CB, OCB_CONST, 0x7C90d9b0, 0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0}, //0x7C90d580 is NtOpenFile 0x7C90d9b0 is NtReadFile
    {"Block Begin Page", DECAF_BLOCK_BEGIN_CB, OCB_PAGE, 0x7C90d9b0, 0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0}, //0x7C90d090 is NtCreateFile
    {"Block Begin All", DECAF_BLOCK_BEGIN_CB, OCB_ALL, 0, 0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
    {"Block End From Page", DECAF_BLOCK_END_CB, OCB_PAGE, 0x7C90d9b0, INV_ADDR, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
    {"Block End To Page", DECAF_BLOCK_END_CB, OCB_PAGE, INV_ADDR, 0x7C90d9b0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
    {"Block End All", DECAF_BLOCK_END_CB, OCB_PAGE, INV_ADDR, INV_ADDR, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
    {"Insn Begin", DECAF_INSN_BEGIN_CB, OCB_ALL, 0, 0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
    {"Insn End", DECAF_INSN_END_CB, OCB_ALL, 0, 0, DECAF_NULL_HANDLE, {0, 0}, {0, 0}, 0, 0.0},
};
*/

static callbacktest_pid[100] = {0};
static callbacktest_t callbacktests[100] = {};

int search_callbacktest_pid(int pid){
	for(int i=0; i<100; i ++){
		if (callbacktest_pid[i] == pid){
			return i;
		}
	}
	return -1;
}
		

	


static int curTest = 0; 
static DECAF_Handle processbegin_handle = DECAF_NULL_HANDLE;
static DECAF_Handle removeproc_handle = DECAF_NULL_HANDLE;

static char targetname[100][512];// 10 program, such as httpd, hedwig.cig
static int target_index = 0;

int target_exist(char *name){
	for(int i=0; i<target_index; i++){
		if (strcmp(targetname[i], name) == 0){
			return i;
		}
	}
	return -1;
}

static uint32_t targetpid;
static uint32_t targetcr3;

static void runTests(int);

static void callbacktests_printSummary(void);
static void callbacktests_resetTests(void);


static void callbacktests_printSummary(void)
{
  int i = 0;
  DECAF_printf("******* SUMMARY *******\n");
  DECAF_printf("%+30s\t%12s\t%10s\n", "Test", "Count", "Time");
  for (i = 0; i < CALLBACKTESTS_TEST_COUNT; i++)
  {
    DECAF_printf("%-30s\t%12u\t%.5f\n", callbacktests[i].name, callbacktests[i].count, callbacktests[i].elapsedtime);
  }
}

static void callbacktests_resetTests(void)
{
  int i = 0;
  for (i = 0; i < CALLBACKTESTS_TEST_COUNT; i++)
  {
    callbacktests[i].tick.tv_sec = 0;
    callbacktests[i].tick.tv_usec = 0;
    callbacktests[i].tock.tv_sec = 0;
    callbacktests[i].tock.tv_usec = 0;
    callbacktests[i].handle = 0;
    callbacktests[i].count = 0;
    callbacktests[i].elapsedtime = 0.0;
  }
}

static void callbacktests_loadmainmodule_callback(VMI_Callback_Params* params)
{
  char procname[64];
  uint32_t pid;
  if (params == NULL)
  {
    return;
  }

  //DECAF_printf("Process with pid = %d and cr3 = %u was just created\n", params->lmm.pid, params->lmm.cr3);

  VMI_find_process_by_cr3_c(params->cp.cr3, procname, 64, &pid);
  //DECAF_printf("process with pid=%d, name=%s\n",pid, procname);
  //in find_process pid is set to 1 if the process is not found
  // otherwise the number of elements in the module list is returned
  if (pid == (uint32_t)(-1))
  {
    return;
  }
  int index = target_exist(procname);
  if (index != -1 && run_test == 0)
  {
    //run_test = 1;
/*
    if(mkfifo("myfifo",0666) < 0){
	DECAF_printf("MKFIFO ERROR\n");
    }
*/
    DECAF_printf("\nProcname:%s,targetname:%s,targetpid:%d\n",targetname[index],procname,pid);
    targetpid = pid;
    targetcr3 = params->cp.cr3;
    DECAF_printf("cr3:%x\n",targetcr3);
    runTests(index);
  }
}

static void callbacktests_removeproc_callback(VMI_Callback_Params* params)
{
  double elapsedtime;

  if (params == NULL)
  {
    return;
  }
  int index = search_callbacktest_pid(params->rp.pid);
  if (index != -1)
  {
    if (index >= CALLBACKTESTS_TEST_COUNT)
    {
      return;
    }

    if (callbacktests[index].handle == DECAF_NULL_HANDLE)
    {
      return;
    }

    //unregister the callback FIRST before getting the time of day - so
    // we don't get any unnecessary callbacks (although we shouldn't
    // since the guest should be paused.... right?)
    DECAF_printf("unregister handle %x\n", callbacktests[index].handle);
    DECAF_unregister_callback(callbacktests[index].cbtype, callbacktests[index].handle);
    callbacktests[index].handle = DECAF_NULL_HANDLE;
    DECAF_printf("Callback Count = %u\n", callbacktests[index].count);

    gettimeofday(&callbacktests[index].tock, NULL);

    elapsedtime = (double)callbacktests[index].tock.tv_sec + ((double)callbacktests[index].tock.tv_usec / 1000000.0);
    elapsedtime -= ((double)callbacktests[index].tick.tv_sec + ((double)callbacktests[index].tick.tv_usec / 1000000.0));
    DECAF_printf("Process [%s] with pid [%d] ended at %u:%u\n", callbacktests[index].name, params->rp.pid, callbacktests[index].tock.tv_sec, callbacktests[index].tock.tv_usec);
    DECAF_printf("  Elapsed time = %0.6f seconds\n\n", elapsedtime);

    callbacktests[index].elapsedtime = elapsedtime;


    targetpid = (uint32_t)(-1);
    targetcr3 = 0;
  }
}

static int count = 0;
static int poll = 0;
static int api_time = 0;

static void callbacktests_genericcallback(DECAF_Callback_Params* param)
{
  if (curTest >= CALLBACKTESTS_TEST_COUNT)
  {
    return;
  }



  //LOK: Setup to do a more comprehensive test that does something with the parameters
  if (1 || bVerboseTest)
  //if (0)
  {

    switch(callbacktests[curTest].cbtype)
    {
      case (DECAF_BLOCK_BEGIN_CB):
      {
// libuClibc-0.9.30.1.so is stripped, so no symbol was loaded
	CPUArchState *cpu = param->bb.env->env_ptr;
	//if(param->bb.tb->pc > 0x80000000 && afl_start == 0){
	if(param->bb.tb->pc > 0x80000000){

		char program[50];
		target_ulong tmp_sp = cpu->active_tc.gpr[28];

		if(tmp_sp != kernel_sp && tmp_sp > 0x80000000){
			//In kernel mode, it used to find the following user-mode process. 
	 		kernel_sp = tmp_sp;
			int pid = mips_get_cur_pid(param->bb.env, program);
			if(pid != current_pid){
				current_pid = pid;
				strcpy(current_program,program);

				if(target_exist(program) != -1 && pid!=0){ //httpd sometimes pid equals 0
					if(!pid_exist(pid)){
						pid_add(pid);
					}
					
				}	
			}
		}

		if(param->bb.tb->pc == 	0x805510f0){ //egrep ' (panic|log_store)$' /proc/kallsyms

			DECAF_printf("kernel panic\n");
			doneWork(32);
		}
	}
	else if(param->bb.tb->pc < 0x80000000){
		
		char modname[512];
		char functionname[512];
		int index = target_exist(current_program);
		if(pid_exist(current_pid) || index != -1){
			//target_ulong cr3 = DECAF_getPGD(param->bb.env) ;
			//DECAF_printf("cr3:%x\n",cr3);

			CPUArchState *tmp_cpu = param->bb.env->env_ptr;
			if (0 == funcmap_get_name_c(param->bb.tb->pc, targetcr3, &modname,
							&functionname)) {
				if(strcmp(functionname, "__libc_accept") == 0 || strcmp(functionname, "accept") == 0  || strcmp(functionname, "spawn") == 0 )
				{
					
					recv_block = 1;	
				}
				//key api
				if(strcmp(functionname, "__libc_open") == 0 || strcmp(functionname, "fork") == 0 || strcmp(functionname, "_exit") == 0 ){
					//DECAF_printf("current pid:%d, %s\n", current_pid, functionname);
				}
/*
				if( strcmp(functionname, "__libc_fcntl") == 0 ){
					target_ulong a0 = cpu->active_tc.gpr[4];//fd
					DECAF_printf("%s:fd:%d\n", functionname, a0);
				}
*/


				if(strcmp(functionname, "execve") == 0 || strcmp(functionname, "system") == 0){
					
					target_ulong a0 = cpu->active_tc.gpr[4];//fd
					char tmpBuf[50];
					memset(tmpBuf, 0, 50);
					DECAF_read_mem(param->bb.env, a0, 50, tmpBuf);
					DECAF_printf("program:%s, current pid:%d,%s,file:%s,pc:%x, a0:%x\n",targetname[index], current_pid, functionname, tmpBuf, param->bb.tb->pc, a0);
					//print_pid();					
					start_debug = 1;
				}
				if(strcmp(functionname, "poll") == 0)
				{	
					gettimeofday(&tv_poll,NULL);
					DECAF_printf("poll time:%d,%d\n", tv_poll.tv_sec, tv_poll.tv_usec);
					//DECAF_printf("current pid:%d, %s\n", current_pid, functionname);
					if(afl_fork == 1){
/*
						poll ++;
						if(poll == 3){
							doneWork(0);
						}
*/
					}
				}

				else if(strcmp(functionname, "read") == 0){
					if(afl_start == 0){	
						afl_start = 1;
						gettimeofday(&tv_fork,NULL);
						DECAF_printf("fork time:%d,%d\n", tv_fork.tv_sec, tv_fork.tv_usec);
						startForkserver(cpu, enableTimer);
					}				
				}
				else if(strcmp(functionname, "__libc_read")==0 || strcmp(functionname, "__libc_recv")==0 || strcmp(functionname, "__libc_recvfrom")==0 || strcmp(functionname, "__libc_recvmsg")==0) {
					//gettimeofday(&tv_end,NULL);
					//DECAF_printf("end time:%d,%d\n", tv_end.tv_sec, tv_end.tv_usec);
					target_ulong a0 = cpu->active_tc.gpr[4];//fd
					target_ulong a2 = cpu->active_tc.gpr[6];//nbytes	
					if(a0 == current_fd){
						network_read_block = 1;
					}
				}
/* calculate the slowest api 
				if (afl_start == 1 && api_time == 0){
					gettimeofday(&old_tv_api,NULL);
					gettimeofday(&tv_api,NULL);
					sec_delta = (tv_api.tv_sec - old_tv_api.tv_sec) + ((float)(tv_api.tv_usec - old_tv_api.tv_usec))/1000000;
					memset(old_api, 0, 100);
					strcpy(old_api, functionname);
					old_tv_api.tv_sec = tv_api.tv_sec;
					old_tv_api.tv_usec = tv_api.tv_usec;					
					api_time = 1;	
				}
				else if(afl_start == 1 && api_time == 1){
					gettimeofday(&tv_api,NULL);
					float sec = (tv_api.tv_sec - old_tv_api.tv_sec) + ((float)(tv_api.tv_usec - old_tv_api.tv_usec))/1000000;
					if(strcmp(old_api, "__libc_open") == 0){	
						DECAF_printf("time:%s,%f\n",old_api, sec);
					}
					if(sec > sec_delta){
						sec_delta = sec;
						DECAF_printf("long time:%s,%f\n",old_api, sec);
					}
					memset(old_api, 0, 100);
					strcpy(old_api, functionname);
					old_tv_api.tv_sec = tv_api.tv_sec;
					old_tv_api.tv_usec = tv_api.tv_usec;
				}
*/	

				if (afl_start == 1){
					//gettimeofday(&tv_api,NULL);
					//DECAF_printf("%s\n", functionname);
					//DECAF_printf("%s:%d\n", functionname, tv_api.tv_usec);
					//DECAF_printf("%s:%x\n", functionname, param->bb.tb->pc);
				}
					

			}
			else if(param->bb.tb->pc < 0x500000){
				if(recv_block == 1){
					recv_block = 0;
					target_ulong v0 = cpu->active_tc.gpr[2];//return value (fd)
					if (v0!=0 && v0!=0xffffffff){
/*
						if(afl_start == 0){	
							afl_start = 1;
							startForkserver(cpu, enableTimer);
						}
*/
						current_fd = v0;
						
					}
				}

				else if(network_read_block == 1){

					network_read_block = 0;
	//0x4072f8 after read
					CPUArchState *cpu = param->bb.env->env_ptr;
					target_ulong pc = cpu->active_tc.PC;
					target_ulong a0 = cpu->active_tc.gpr[4];//fd
					target_ulong a1 = cpu->active_tc.gpr[5];//buf
					target_ulong a2 = cpu->active_tc.gpr[6];//nbytes
					target_ulong v0 = cpu->active_tc.gpr[2];//return value (read)
	//real network data
					char *buf1 = (char *)malloc(v0);
					DECAF_printf("a2:%d, v0:%d\n", a2, v0);
					DECAF_read_mem(param->bb.env, a1, v0, buf1);
					DECAF_printf("network is %s\n", buf1);
					free(buf1);
	//read network data end 
	//aflcall

					if(afl_start == 1 && afl_fork == 0){
						
						afl_fork = 1;	
		
						char * buf;
						buf = (char *)malloc(4096);
						memset(buf, 0, 4096);
						u_long bufsz = 4096;
						char filename[500];
						ulong sz = getWork(cpu, buf, bufsz);
						startWork(cpu, 0x400000L, 0x500000L);
						current_data = buf;
						rest_len = sz; 
						DECAF_printf("rest_len:%d\, current data len:%d\n", rest_len, strlen(current_data));


					}
					DECAF_printf("current data is %s\n", current_data);
	//read network data end 
					DECAF_printf("current_data:%x, restlen:%d, a1:%x, a2:%d, v0:%d\n", current_data, rest_len, a1, a2, v0);
					if(rest_len != 0){// rest_len is the length of virtual buffer
						if (rest_len + 1 >= a2){// a2 is the maximum recv length for once
							DECAF_write_mem(param->bb.env, a1, a2, current_data);
							cpu->active_tc.gpr[2] = a2;//need to modify v0
							current_data += a2;
							rest_len -= a2;
						}
						else{

							DECAF_write_mem(param->bb.env, a1, rest_len, current_data);
							cpu->active_tc.gpr[2] = rest_len;//need to modify v0
							current_data += rest_len;
							rest_len = 0;
						}	
						char *buf2 = (char *)malloc(a2);
						DECAF_read_mem(param->bb.env, a1, a2, buf2);
						DECAF_printf("current network is %s\nlen:%d\n", buf2, strlen(buf2));
						free(buf2);

					}
					
					else{// if input data's data is too little, it will end up with donework
						DECAF_printf("		donework done:%d,  parent pid:%d\n",getpid(), getppid());
						free(buf); //current_data 
						//doneWork(0);
					}

				}

				else if(start_debug == 1){
					start_debug = 0;
					DECAF_printf("next ins:%x,pid:%d\n", param->bb.tb->pc, current_pid);
					//gettimeofday(&tv_next,NULL);
					//DECAF_printf("next ins time:%d,%d\n", tv_next.tv_sec, tv_next.tv_usec);
					
				}
/*
				else if(afl_fork == 1){

					DECAF_printf("next instruction:%x\n", param->bb.tb->pc);
					gettimeofday(&tv_next,NULL);
					DECAF_printf("next ins time:%d,%d\n", tv_next.tv_sec, tv_next.tv_usec);

				}

				if(param->bb.tb->pc == 0x402230){
					DECAF_printf("hedwig.cgi end\n");
					doneWork(0);
				}
*/

			}
		}
	}	
        break;
      }
      case (DECAF_BLOCK_END_CB):
      {
        break;

      }
      case (DECAF_INSN_BEGIN_CB):
      {
        //do nothing yet?
	CPUState *env = param->ib.env;
	DECAF_printf("pc:%x\n", ((CPUArchState *)env->env_ptr)->active_tc.PC);
	break;
      }
      case (DECAF_INSN_END_CB):
      {
        //do nothing yet?
	CPUState *env = param->ie.env;
	target_ulong pc = 0;
	CPUArchState *env_ptr = (CPUArchState *)env->env_ptr;
	if(env_ptr)
	{
		TCState *tc = &(env_ptr->active_tc);
		if(tc)
		{
			pc = tc->PC;
			DECAF_printf("pc:%x\n", pc);
		}
	}
	break;
      }
    }
  }

  //TODO: Add support for ONLY tracking target process and not ALL processes
  callbacktests[curTest].count++;
}

static void runTests(int index)
{
  if (curTest >= CALLBACKTESTS_TEST_COUNT)
  {
    DECAF_printf("process is too many\n");
    return;
  }

  DECAF_printf("Process [%s] with pid [%d] started at %u:%u\n", targetname[index], targetpid, callbacktests[curTest].tick.tv_sec, callbacktests[curTest].tick.tv_usec);
  DECAF_printf("Registering for callback\n");
  switch(callbacktests[curTest].cbtype)
  {
    case (DECAF_BLOCK_BEGIN_CB):
    {
      char callback_function[50];
      sprintf(callback_function, "callbacktests_genericcallback_%d", targetpid);
      callbacktests[curTest].handle = DECAF_registerOptimizedBlockBeginCallback(&callbacktests_genericcallback, NULL, callbacktests[curTest].from, callbacktests[curTest].ocbtype);
      DECAF_printf("register callback %x\n", callbacktests[curTest].handle);
      //callbacktests[curTest].handle = DECAF_registerOptimizedBlockBeginCallback(&callback_function, NULL, callbacktests[curTest].from, callbacktests[curTest].ocbtype);
      callbacktest_pid[curTest] = targetpid;
      curTest ++;
      break;
    }
    case (DECAF_BLOCK_END_CB):
    {
      DECAF_printf("from and to: %d,%d\n",callbacktests[curTest].from, callbacktests[curTest].to);
      callbacktests[curTest].handle = DECAF_registerOptimizedBlockEndCallback(&callbacktests_genericcallback, NULL, callbacktests[curTest].from, callbacktests[curTest].to);
      break;
    }
    default:
    case (DECAF_INSN_BEGIN_CB):
    case (DECAF_INSN_END_CB):
    {
      callbacktests[curTest].handle = DECAF_register_callback(callbacktests[curTest].cbtype, &callbacktests_genericcallback, NULL);
    }
    
  }

  if (callbacktests[curTest - 1].handle == DECAF_NULL_HANDLE)
  {
    DECAF_printf("Could not register the event\n");
    return;
  }

  callbacktests[curTest].count = 0;
  DECAF_printf("Callback Registered\n");
}

void do_callbacktests(Monitor* mon, const QDict* qdict)
{
  if ((qdict != NULL) && (qdict_haskey(qdict, "procname")))
  {
 
    strncpy(targetname[target_index], qdict_get_str(qdict, "procname"), 512);
    targetname[target_index][511] = '\0';
    target_index++;
  }
  curTest = 0;
  callbacktests_resetTests();
}

static int callbacktests_init(void)
{
  DECAF_output_init(NULL);
  DECAF_printf("Hello World\n");
  //register for process create and process remove events

  for(int i=0; i<100; i++)
  {
	strcpy(callbacktests[i].name, "Block Begin All");
	callbacktests[i].cbtype = DECAF_BLOCK_BEGIN_CB;
	callbacktests[i].ocbtype = OCB_ALL;
	callbacktests[i].from = 0;
	callbacktests[i].to = 0;
	callbacktests[i].handle = DECAF_NULL_HANDLE;
	callbacktests[i].tick.tv_sec = 0;
	callbacktests[i].tick.tv_usec = 0;
	callbacktests[i].tock.tv_sec = 0;
	callbacktests[i].tock.tv_usec = 0;
	callbacktests[i].count = 0;
	callbacktests[i].elapsedtime = 0.0;
	
	targetname[i][0] = '\0';
  } 


  processbegin_handle = VMI_register_callback(VMI_CREATEPROC_CB, &callbacktests_loadmainmodule_callback, NULL);
  removeproc_handle = VMI_register_callback(VMI_REMOVEPROC_CB, &callbacktests_removeproc_callback, NULL);
  if ((processbegin_handle == DECAF_NULL_HANDLE) || (removeproc_handle == DECAF_NULL_HANDLE))
  {
    DECAF_printf("Could not register for the create or remove proc events\n");
  }

  targetcr3 = 0;
  targetpid = (uint32_t)(-1);

  do_callbacktests(NULL, NULL);
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

  curTest = 0;
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
  
  //initialize the plugin
  callbacktests_init();
  return (&callbacktests_interface);
}

