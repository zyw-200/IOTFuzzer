/*
   american fuzzy lop - high-performance binary-only instrumentation
   -----------------------------------------------------------------

   Written by Andrew Griffiths <agriffiths@google.com> and
              Michal Zalewski <lcamtuf@google.com>

   Idea & design very much by Andrew Griffiths.

   Copyright 2015, 2016, 2017 Google Inc. All rights reserved.

   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at:

     http://www.apache.org/licenses/LICENSE-2.0

   This code is a shim patched into the separately-distributed source
   code of QEMU 2.10.0. It leverages the built-in QEMU tracing functionality
   to implement AFL-style instrumentation and to take care of the remaining
   parts of the AFL fork server logic.

   The resulting QEMU binary is essentially a standalone instrumentation
   tool; for an example of how to leverage it for other purposes, you can
   have a look at afl-showmap.c.

 */

#include <sys/shm.h>
#include "../../config.h"
#include "zyw_config.h"
/***************************
 * VARIOUS AUXILIARY STUFF *
 ***************************/

/* A snippet patched into tb_find_slow to inform the parent process that
   we have hit a new block that hasn't been translated yet, and to tell
   it to translate within its own context, too (this avoids translation
   overhead in the next forked-off copy). */

#define AFL_QEMU_CPU_SNIPPET1 do { \
    afl_request_tsl(pc, cs_base, flags); \
  } while (0)

/* This snippet kicks in when the instruction pointer is positioned at
   _start and does the usual forkserver stuff, not very different from
   regular instrumentation injected via afl-as.h. */

int is_fork = 0;
int forked = 0;

// && is_fork
#define AFL_QEMU_CPU_SNIPPET2 do { \
    if(itb->pc == afl_entry_point && !forked) { \
      printf("afl_entry_point:%lx\n", afl_entry_point);\
      afl_setup(); \
      afl_forkserver(cpu); \
      forked = 1;\
    } \
    afl_maybe_log(itb->pc); \
  } while (0)

/* We use one additional file descriptor to relay "needs translation"
   messages between the child and the fork server. */

#define TSL_FD (FORKSRV_FD - 1)

/* This is equivalent to afl-as.h: */

static unsigned char *afl_area_ptr;

/* Exported variables populated by the code patched into elfload.c: */

//zywconfig
//#define FEED_INPUT
#ifdef SNAPSHOT_SYNC
char *phys_addr_stored_bitmap;
int syn_shmem_id = 73335017; 
target_ulong not_gencode_pc = 0 ;//0x1000// 0x56000 //0x51000
#endif
//afl_entry_point = 0x400470;// brk
//afl_entry_point = 0x40e054;// httpd d-link after open //0x4072f8; //httpd dlink after read
//afl_entry_point = 0x409528; // 0x40959c; //cgibin 
//afl_entry_point = 0x409480;// main 0x43126c; //after brk //0x4053b0; //tplink dropbear
//afl_entry_point = 0x402260; //xmldb
//afl_entry_point = 0x502dfc; //httpd tplink after recv
//afl_entry_point = 0x40da28 // 0x414208; //dnsmasq
//afl_entry_point = 0x525698; 0x502dfc; //httpd tplink after read
//afl_entry_point = 0x40b088; // 0x40c000; //jjhttpd Trendnet after read
//afl_entry_point = 0x40ced0; //lighttpd Netgear new after read
//afl_entry_point = 0x400670;// sample
abi_ulong afl_entry_point = 0x400120;   /* ELF entry point (_start) */
          afl_start_code,  /* .text start pointer      */
          afl_end_code;    /* .text end pointer        */

/* Set in the child process in forkserver mode: */

static unsigned char afl_fork_child;
unsigned int afl_forksrv_pid;

/* Instrumentation ratio: */

static unsigned int afl_inst_rms = MAP_SIZE;

/* Function declarations. */

static void afl_setup(void);
static void afl_forkserver(CPUState*);
static inline void afl_maybe_log(abi_ulong);

static void afl_wait_tsl(CPUState*, int);
static void afl_request_tsl(target_ulong, target_ulong, uint64_t);
void afl_request_map(target_ulong vaddr, uintptr_t paddr);

target_ulong startTrace(target_ulong start, target_ulong end)
{
    afl_start_code = start;
    afl_end_code   = end;
    return 0;
}

/* Data structure passed around by the translate handlers: */

int mem_file;

struct afl_tsl {
  target_ulong pc;
  target_ulong cs_base;
  uint64_t flags;
};

struct addrPair {
  target_ulong vaddr;
  uintptr_t paddr;
};

/* Some forward decls: */

TranslationBlock *tb_htable_lookup(CPUState*, target_ulong, target_ulong, uint32_t);
static inline TranslationBlock *tb_find(CPUState*, TranslationBlock*, int);
void * snapshot_shmem_start;
void * snapshot_shmem_pt;
int snapshot_shmem_id;


/*************************
 * ACTUAL IMPLEMENTATION *
 *************************/

/* Set up SHM region and initialize other stuff. */


static void afl_setup(void) {


  char *id_str = getenv(SHM_ENV_VAR),
       *inst_r = getenv("AFL_INST_RATIO");

  int shm_id;

  if (inst_r) {

    unsigned int r;

    r = atoi(inst_r);

    if (r > 100) r = 100;
    if (!r) r = 1;

    afl_inst_rms = MAP_SIZE * r / 100;

  }

  if (id_str) {

    shm_id = atoi(id_str);
    printf("guest:%lx,%x\n", guest_base + 0x80000000, SHM_RDONLY);
//SHMAT
    afl_area_ptr = shmat(shm_id, NULL , 0); //MAP SIZE 0X10000
    //afl_area_ptr = shmat(shm_id, 0x80000000 , 0); //MAP SIZE 0X10000
    
    printf("afl_area_ptr:%lx\n", afl_area_ptr);

    if (afl_area_ptr == (void*)-1) exit(1);

    /* With AFL_INST_RATIO set to a low value, we want to touch the bitmap
       so that the parent doesn't give up on us. */

    if (inst_r) afl_area_ptr[0] = 1;


  }

  if (getenv("AFL_INST_LIBS")) {

    afl_start_code = 0;
    afl_end_code   = (abi_ulong)-1;

  }



  /* pthread_atfork() seems somewhat broken in util/rcu.c, and I'm
     not entirely sure what is the cause. This disables that
     behaviour, and seems to work alright? */

  rcu_disable_atfork();

}



int write_aflcmd(int cmd, USER_MODE_TIME *user_mode_time)  
{  
    const char *fifo_name_user = "./pipe/user_cpu_state";  
    int pipe_fd = -1;  
    int res = 0;  
    const int open_mode_user = O_WRONLY;  
  
    if(access(fifo_name_user, F_OK) == -1)  
    {  
        res = mkfifo(fifo_name_user, 0777);  
        if(res != 0)  
        { 
            fprintf(stderr, "Could not create fifo %s\n", fifo_name_user);  
            exit(EXIT_FAILURE);  
        }  
    } 

    pipe_fd = open(fifo_name_user, open_mode_user);    
    if(pipe_fd != -1)  
    { 
    	int type = 2; 
    	res = write(pipe_fd, &type, sizeof(int));  
    	if(res == -1)  
    	{  
    		fprintf(stderr, "Write type on pipe\n");  
    		exit(EXIT_FAILURE);  
    	}
    	res = write(pipe_fd, &cmd, sizeof(int));  
    	if(res == -1)  
    	{  
    		fprintf(stderr, "Write error on pipe\n");  
    		exit(EXIT_FAILURE);  
    	}
      res = write(pipe_fd, user_mode_time, sizeof(USER_MODE_TIME));  
      if(res == -1)  
      {  
        fprintf(stderr, "Write error on pipe\n");  
        exit(EXIT_FAILURE);  
      }
    	printf("write cmd ok:%x\n", cmd);  
      close(pipe_fd);   
    }  
    else  
        exit(EXIT_FAILURE);  
  
    return 1;  
}  


//zyw
extern int pipe_read_fd;
extern int pipe_write_fd;
target_ulong feed_input_vaddr = 0;
/* Fork server logic, invoked once we hit _start. */


void stopWork(){
  printf("stop work with crash\n");
  int cmd = 0x20;// restore page
  USER_MODE_TIME user_mode_time;
  write_aflcmd(cmd, &user_mode_time); sleep(1000);
  is_fork = 0;
  restore_page();  //zyw
  exit(32);
}

void stopWork_normal(){
  printf("stop work with exception\n");
  int cmd = 0x20;// restore page
  USER_MODE_TIME user_mode_time;
  write_aflcmd(cmd, &user_mode_time); sleep(1000);
  is_fork = 0;
  restore_page();  //zyw
  exit(0);
}


size_t virtual_to_physical(size_t addr, int pid)
{
    char filename[100];
    sprintf(filename, "/proc/%d/pagemap", pid);
    int fd = open(filename, O_RDONLY);
    if(fd < 0)
    {
        printf("%s failed!\n", filename);
        return 0;
    }

    size_t pagesize = getpagesize();
    size_t offset = (addr / pagesize) * sizeof(uint64_t);
    if(lseek(fd, offset, SEEK_SET) < 0)
    {
        printf("lseek() failed!\n");
        close(fd);
        return 0;
    }
    uint64_t info;
    if(read(fd, &info, sizeof(uint64_t)) != sizeof(uint64_t))
    {
        printf("read() failed!\n");
        close(fd);
        return 0;
    }
    if((info & (((uint64_t)1) << 63)) == 0)
    {
        printf("page is not present!\n");
        close(fd);
        return 0;
    }
    size_t frame = info & ((((uint64_t)1) << 55) - 1);
    size_t phy = frame * pagesize + addr % pagesize;
    close(fd);
    return phy;
}


struct timeval child_start1;
struct timeval child_start2;

static void afl_forkserver(CPUState *cpu) {


  CPUArchState *env = cpu->env_ptr;
  static unsigned char tmp[4];
  printf("start afl_forkserver\n");
  if (!afl_area_ptr) return;

  /* Tell the parent that we're alive. If the parent doesn't want
     to talk, assume that we're not running in forkserver mode. */

  if (write(FORKSRV_FD + 1, tmp, 4) != 4) return;

  afl_forksrv_pid = getpid();

  /* All right, let's await orders... */



  while (1) {

    pid_t child_pid;
    int status, t_fd[2];

    /* Whoops, parent dead? */

    if (read(FORKSRV_FD, tmp, 4) != 4) exit(2);

    /* Establish a channel with child to grab translation commands. We'll
       read from t_fd[0], child will write to TSL_FD. */

    if (pipe(t_fd) || dup2(t_fd[1], TSL_FD) < 0) exit(3);
    close(t_fd[1]); 
          
    is_fork = 1;
    printf("****************************new loop****************************\n");
    //restore_page();
 /*    
    for(int i=0; i<addr_index; i++)
    {
      target_munmap(parent_map_addr[i], 1024*4);
    }
    addr_index = 0;
*/
    signal(SIGALRM, stopWork);
/*
    if(pipe_read_fd!=-1) close(pipe_read_fd);
    pipe_read_fd = -1;
*/

    child_pid = fork();
    if (child_pid < 0) exit(4);

    if (!child_pid) {

      /* Child process. Close descriptors and run free. */
	    gettimeofday(&child_start1, NULL);

      int cmd = 0x10;// start mem write callback
      USER_MODE_TIME user_mode_time;
      write_aflcmd(cmd,  &user_mode_time);
//SHMAT
      //snapshot_shmem_start = shmat(snapshot_shmem_id, guest_base + 0x182000000 ,  1); //zyw 
      //snapshot_shmem_start = shmat(snapshot_shmem_id, 0x80200000 ,  0); //zyw 
      //snapshot_shmem_start = shmat(snapshot_shmem_id, NULL,  1); //zyw 

      printf("page storage memory addr:%lx,%lx\n", snapshot_shmem_id, snapshot_shmem_start);
      //memset(snapshot_shmem_start, 0, 1024*1024*16); // oh, it takes lots of time here !!!!!!!!!!!!, the most time cost in user mode; memset 16M

      //snapshot_shmem_pt = snapshot_shmem_start + 8 * 0x1000;
      afl_fork_child = 1;




      
#ifdef FEED_INPUT
      if(env->active_tc.PC == 0x400670) 
      {
          char buf[2000];
          char orig_buf[2000];
          cpu_memory_rw_debug(cpu, env->active_tc.gpr[4], orig_buf, 2000, 0);
          //int len = getWork(env, buf, 2000); // big mistake here
          int len = getWork(buf, 2000);
          printf("??????????????????%x,%s,%s\n", env->active_tc.gpr[4], buf, orig_buf);
          store_page(env->active_tc.gpr[4] & 0xfffff000); //don't forget & 0xfffff000
          //printf("after store_page\n");
          int write_len = strlen(orig_buf);
          if(len > write_len) write_len = len;
          cpu_memory_rw_debug(cpu, env->active_tc.gpr[4], buf, write_len, 1);
      }
#endif



      gettimeofday(&child_start2, NULL);
      double loop_start_time  =  (double)child_start2.tv_sec - child_start1.tv_sec + (child_start2.tv_usec - child_start1.tv_usec)/1000000.0;
      printf("loop start time:%f\n", loop_start_time);

/* feed input
      char buf[5000];
      int tmp_len = 0x400; // origin buf len we want to read

      uintptr_t env_ptr =  feed_input_vaddr;
      cpu_memory_rw_debug(NULL, env_ptr, buf, tmp_len, 0);

      printf("#############env is %x, %lx, %s\n", guest_base, env_ptr, buf);
      char new_buf[2500];
      getWork(new_buf, 2500);
      if(strlen(new_buf) > 2000)
      {
        sleep(100);
        printf("string too long");
      }
      printf("#############new data is %s, len:%d\n", new_buf, strlen(new_buf));
      int len = search_http_and_replace(buf, 5000, tmp_len, "HTTP_COOKIE=", new_buf, 2500);
      printf("#############new data is %s\n", buf);
      cpu_memory_rw_debug(NULL, env_ptr, buf, tmp_len, 1); //not len because len may less than tmp_len, connot cover the old content
*/
      close(FORKSRV_FD);
      close(FORKSRV_FD + 1);
      close(t_fd[0]);

//timer
      struct itimerval tick;
      memset(&tick, 0, sizeof(tick));    
      tick.it_value.tv_sec = 0;  // sec  //set to 5
      tick.it_value.tv_usec = 0; // micro sec
      int ret = setitimer(ITIMER_REAL, &tick, NULL);  

      return;

    }

    /* Parent. */

    close(TSL_FD);
    if (write(FORKSRV_FD + 1, &child_pid, 4) != 4) exit(5);
    /* Collect translation requests until child dies and closes the pipe. */
//zywzyw    
    afl_wait_tsl(cpu, t_fd[0]);	
    /* Get and relay exit status to parent. */
    if (waitpid(child_pid, &status, 0) < 0) exit(6);
    printf("child process end:%x\n", child_pid);
    if (write(FORKSRV_FD + 1, &status, 4) != 4) exit(7);

  }

}

/* The equivalent of the tuple logging routine from afl-as.h. */

static inline void afl_maybe_log(abi_ulong cur_loc) {

  static __thread abi_ulong prev_loc;

  /* Optimize for cur_loc > afl_end_code, which is the most likely case on
     Linux systems. */
  if (cur_loc > afl_end_code || cur_loc < afl_start_code || !afl_area_ptr)
    return;

  /* Looks like QEMU always maps to fixed locations, so ASAN is not a
     concern. Phew. But instruction addresses may be aligned. Let's mangle
     the value to get something quasi-uniform. */


  cur_loc  = (cur_loc >> 4) ^ (cur_loc << 8);
  cur_loc &= MAP_SIZE - 1;

  /* Implement probabilistic instrumentation by looking at scrambled block
     address. This keeps the instrumented locations stable across runs. */

  if (cur_loc >= afl_inst_rms) return;

  afl_area_ptr[cur_loc ^ prev_loc]++;
  prev_loc = cur_loc >> 1;

}





/* This is the other side of the same channel. Since timeouts are handled by
   afl-fuzz simply killing the child, we can just wait until the pipe breaks. */

uintptr_t parent_map_addr[0x1000];//NEED CHANGE
int addr_index = 0;


static void afl_wait_tsl(CPUState *cpu, int fd) {
	while (is_fork) {
		int type;
		/* Broken pipe means it's time to return to the fork server routine. */
		int res = read(fd, &type, sizeof(int));
		if(res == 0 || res == -1){
			return; 
		}
		switch(type)
		{
			case 1:
			{
				struct afl_tsl t;
				read(fd, &t, sizeof(struct afl_tsl));
				TranslationBlock *tb;
				tb = tb_htable_lookup(cpu, t.pc, t.cs_base, t.flags);
				if(!tb) {
					mmap_lock();
					tb_lock();
//zywconfig
          //if(t.pc < 0x70000000)
          //if(t.pc > 0x77026000 && t.pc < 0x77154000){}
          //else if(t.pc > 0x76f1d000 && t.pc < 0x76fbc000){}
          //else if(t.pc!= not_gencode_pc)
          if(t.pc!= not_gencode_pc)
          {
            printf("gen code:%x\n", t.pc);
					  tb_gen_code(cpu, t.pc, t.cs_base, t.flags, 0);
          }
					mmap_unlock();
					tb_unlock();
				}
				break;
			}
			case 2:
			{
				struct addrPair p;
				read(fd, &p, sizeof(struct addrPair));
				//printf("##########accept mmap request:%lx,%lx\n",p.vaddr, p.paddr);
				int res = target_mmap(p.vaddr, 1024*4, PROT_READ|PROT_WRITE, MAP_SHARED, mem_file, p.paddr);
        parent_map_addr[addr_index++] = p.vaddr; 
				break;
			}
			case 3:
			{
				printf("big error\n"); sleep(10000);
				target_ulong tmp_addr;
				read(fd, &tmp_addr, sizeof(target_ulong));
				store_page(tmp_addr);
				break;
			}
			default:
			{
				printf("afl_wait_tsl error:%x\n", type);
				sleep(10000);
			}
		}
	}
	close(fd);

}


/* This code is invoked whenever QEMU decides that it doesn't have a
   translation of a particular block and needs to compute it. When this happens,
   we tell the parent to mirror the operation, so that the next fork() has a
   cached copy. */

static void afl_request_tsl(target_ulong pc, target_ulong cb, uint64_t flags) {

  struct afl_tsl t;

  if (!afl_fork_child) return;

  t.pc      = pc;
  t.cs_base = cb;
  t.flags   = flags;

  int type = 1;
  if (write(TSL_FD, &type, sizeof(int)) != sizeof(int))
    return;
  if (write(TSL_FD, &t, sizeof(struct afl_tsl)) != sizeof(struct afl_tsl))
    return;
  //printf("afl_request_tsl:%lx,%lx\n", pc, cb);

}


void afl_request_map(target_ulong vaddr, uintptr_t paddr) {

  struct addrPair p;

  if (!afl_fork_child) return;

  p.vaddr = vaddr;
  p.paddr = paddr;

  
  int type = 2;
  if (write(TSL_FD, &type, sizeof(int)) != sizeof(int))
    return;

  if (write(TSL_FD, &p, sizeof(struct addrPair)) != sizeof(struct addrPair))
    return;
  //printf("afl_request_map:%lx,%lx\n", vaddr, paddr);

}

void afl_request_storeaddr(target_ulong vaddr) {


  if (!afl_fork_child) return;
  int type = 3;
  if (write(TSL_FD, &type, sizeof(int)) != sizeof(int))
    return;
	
  if (write(TSL_FD, &vaddr, sizeof(target_ulong)) != sizeof(target_ulong))
    return;
  //printf("request store addr:%x\n", vaddr);

}

