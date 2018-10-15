
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


 * linux_vmi_new.h
 *
 *  Created on: June 26, 2015
 *      Author: Abhishek V B
 */


#ifndef LINUX_VMI_H_
#define LINUX_VMI_H_
#include <inttypes.h>

#ifdef __cplusplus
extern "C" {
#endif

#define guestOS_THREAD_SIZE 8192

#define SIZEOF_COMM 16

int find_linux(CPUState *env, uintptr_t insn_handle);
void linux_vmi_init();
//gpa_t mips_get_cur_pgd(CPUState *env);
//zyw
target_ulong mips_get_cur_pid(CPUState *env, char *proc_name);
target_ulong mips_get_cur_pgd(CPUState *env);
target_ulong mips_get_cur_cr3(CPUState *env);

void traverse_mmap(CPUState * env,void *opaque);

#ifdef __cplusplus
};
#endif
#endif /* RECON_H_ */


