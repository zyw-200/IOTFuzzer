
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
/********************************************************************
** function_map.cpp
** Author: Heng Yin <heyin@syr.edu>
**
**
** used to map eip to function name.  this file uses the fact
** that TEMU knows module information for loaded modules.
** using this, and the print_funcs_on command, we can print
** every library call that is made within the program.
**
*/
#include "qemu/osdep.h"
#include "cpu.h"

#include <inttypes.h>
#include <map>
#include <vector>
#include <list>
#include <string>
#include <iostream>
#include <fstream>
#include <iomanip>
#include <cassert>
#include <errno.h>
#include <stdlib.h>
#include <string.h>

#include "DECAF_main.h"
#include "DECAF_vm_compress.h"
#include "hw/hw.h" // AWH
#include "shared/vmi.h"
#include "function_map.h"
#include "shared/hookapi.h"
#include "shared/utils/Output.h"

using namespace std;

//zyw
extern int fla;

// Map ``module name" -> "function name" -> offset
map<string, map<string, uint32_t> > map_function_offset;

// Map "module name" -> "offset" -> "function name"
map<string, map<uint32_t, string> > map_offset_function;
target_ulong funcmap_get_pc(const char *module_name, const char *function_name, target_ulong cr3) __attribute__((optimize("O0")));
target_ulong funcmap_get_pc(const char *module_name, const char *function_name, target_ulong cr3)
{

	target_ulong base;
	module *mod = VMI_find_module_by_name(module_name, cr3, &base);
	if(!mod){
		printf("In function_get_pc, no module was found\n");
		return 0;
	}

	/* AVB: For Linux, we do not extract exported symbols regularly when the modules are discovered
	 * Instead we just extract the `inode number' belonging to the module and do the extraction
	 * when there is a request for a mapping from pc-->function_name or function_name-->pc.
	 * This function does symbol extraction of the module specified in `mod' if not already done
	 * 
	 * For Windows though, this function just returns.
	 */

	VMI_extract_symbols(mod,base);
	char key[64];
	sprintf(key, "%u_%s", mod->inode_number, mod->name);
	printf("function_map:%s,%s,%x\n",function_name, mod->name, base);
	
	map<string, map<string, uint32_t> >::iterator iter = map_function_offset.find(key);
	if(iter == map_function_offset.end())
		return 0;

	map<string, uint32_t>::iterator iter2 = iter->second.find(function_name);
	if(iter2 == iter->second.end())
		return 0;

	return iter2->second + base;
}

int funcmap_get_name(target_ulong pc, target_ulong cr3, string &mod_name, string &func_name)
{
	target_ulong base;
	//if (fla == 1) DECAF_printf("1111\n");
	module *mod = VMI_find_module_by_pc(pc, cr3, &base);
	if(!mod)
		return -1;
	//if (fla == 1) DECAF_printf("2222\n");
	/* AVB: For Linux, we do not extract exported symbols regularly when the modules are discovered
	 * Instead we just extract the `inode number' belonging to the module and do the extraction
	 * when there is a request for a mapping from pc-->function_name or function_name-->pc.
	 * This function does symbol extraction of the module specified in `mod' if not already done
	 * 
	 * For Windows though, this function just returns.
	 */
	//printf("mod %s\n", mod->name);
	//if (fla == 1) DECAF_printf("modname:%s\n", mod->name);
	VMI_extract_symbols(mod,base);
	//if (fla == 1) DECAF_printf("3333\n");
	char key[64];
	sprintf(key, "%u_%s", mod->inode_number, mod->name);
	//if (fla == 1) DECAF_printf("4444\n");
	map<string, map<uint32_t, string> >::iterator iter = map_offset_function.find(key);
	if (iter == map_offset_function.end())
		return -1;
	
	map<uint32_t, string>::iterator iter2 = iter->second.find(pc - base);
	if (iter2 == iter->second.end())
		//printf("out2\n");
		return -1;
	//if (fla == 1) DECAF_printf("5555\n");
	mod_name = mod->name;
	func_name = iter2->second;
	return 0;
}

int funcmap_get_name_c(target_ulong pc, target_ulong cr3, char *mod_name, char *func_name)
{
	string mod, func;
	int ret = funcmap_get_name(pc, cr3, mod, func);
	if(ret == 0) {
		//we assume the caller has allocated enough space for mod_name and func_name
		strncpy(mod_name, mod.c_str(), 512);
		strncpy(func_name, func.c_str(), 512);
	}

	return ret;
}




#define BOUNDED_STR(len) "%" #len "s"
#define BOUNDED_QUOTED(len) "%" #len "[^\"]"
#define BOUNDED_STR_x(len) BOUNDED_STR(len)
#define BOUNDED_QUOTED_x(len) BOUNDED_QUOTED(len)
#define BSTR BOUNDED_STR_x(511)
#define BQUOT BOUNDED_QUOTED_x(511)


void parse_function(const char *message)
{
	char module[512];
	char fname[512];
	uint32_t offset;

	if (sscanf(message, " F " BSTR " " BSTR " %x ", module, fname, &offset) != 3)
		return;

	funcmap_insert_function(module, fname, offset, 0);
}
// void funcmap_insert_function(const char *module, const char *fname, uint32_t offset) __attribute__((optimize("O0")));
void funcmap_insert_function(const char *module, const char *fname, uint32_t offset, uint32_t inode_number)
{
	// cout << module << fname << offset << endl;
	
	char key[64];
	sprintf(key, "%u_%s", inode_number, module);
	
	map<string, map<string, uint32_t> >::iterator iter = map_function_offset.find(key);
	if (iter == map_function_offset.end()) {
		map<string, uint32_t> func_offset;
		func_offset[fname] = offset;
		map_function_offset[key] = func_offset;
	} else {
		iter->second.insert(pair<string, uint32_t>(string(fname), offset));
	}

	map<string, map<uint32_t, string> >::iterator iter2 = map_offset_function.find(key);
	if (iter2 == map_offset_function.end()) {
		map<uint32_t, string> offset_func;
		offset_func[offset] = fname;
		map_offset_function[key] = offset_func;
	} else
		iter2->second.insert(pair<uint32_t, string>(offset, fname));

}

static void function_map_save(QEMUFile * f, void *opaque)
{
 /* Nothing here if we are loading from guest.log */ //Aravind
}

static int function_map_load(QEMUFile * f, void *opaque, int version_id)
{
	//Aravind start
  /* Loading the entries from guest.log.
   * This only works if TEMU_loadvm has executed (guest.log is generated).
   * Ideal would be to serialize, compress and checkpoint the maps in the image and then restore.
   */
  FILE *fp = fopen("guest.log", "r");
  char line[1024] = {'\0'};
  while( fgets (line, 1024, fp)) {
	  if(line[0] != 'F')
		  continue;
	  parse_function(line);
  }
  fclose(fp);
  return 0;
	//end
}


void function_map_init()
{
  //zyw?
  //register_savevm(NULL, "funmap", 0, 1,
		  //function_map_save, function_map_load, NULL);
}

void function_map_cleanup()
{
  map_function_offset.clear();
  map_offset_function.clear();
  //zyw?
  //unregister_savevm(NULL, "funmap", 0);
}
