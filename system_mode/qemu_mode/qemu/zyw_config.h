#ifndef _zyw_config_H 
#define _zyw_config_H

//int transit_pc = 0x400120; //busybox bzip2
//int transit_pc = 0x400470; //brk 
//int transit_pc = 0x400670; //sample single_user_mode
//int transit_pc = 0x402260; //xmldb main
//int transit_pc = 0x402260; //xmldb
//int transit_pc = 0x502dfc; //xmldb 
//int transit_pc = 0x4023e0;  //0x409528; main 0x4023e0 //after getenv  0x40959c; after brk //cgibin  0xdb70;//
//int transit_pc = 0x40e054; //httpd d-link after open // 0x4072f8; //httpd d-link after read
//int transit_pc = 0x414208; /// 0x40d790; //skip the brk //0x414208; main //dnsmasq
//int transit_pc = 0x43126c; //0x43126c; //after brk // 0x405560; //dropbear
int transit_pc = 0x525698; //0x502dfc; //httpd tplink after read
//int transit_pc = 0x40b088; //jjhttpd Trendnet after read
//int transit_pc = 0x40ced0; //lighttpd Netgear new after read

//char *program_analysis = "busybox";
//char *program_analysis = "brk";
//char *program_analysis = "sample";
//char *program_analysis = "xmldbc";
//char *program_analysis = "hedwig.cgi"; ///htdocs/cgibin
//char *program_analysis = "httpd"; //sbin/httpd
//char *program_analysis = "dnsmasq"; // /usr/sbin/dnsmasq
//char *program_analysis = "dropbear"; // /usr/bin/dropbear
char *program_analysis = "httpd"; // /usr/bin/httpd
//char *program_analysis = "jjhttpd"; // sbin/jjhttpd after dnsmasq error
//char *program_analysis = "lighttpd"; // sbin/lighttpd

//#define multiple_process //Trendnet jjhttpd Netgear lighttpd
int first_or_new_pgd = 0; //0 first 1 new;

int print_debug = 1;
int print_pc_times = 0;
int print_loop_times = 10;
int print_loop_count = 0;

char *mapping_filename = "/home/zyw/tmp/afl_user_mode/image7/mapping_table";
//zyw
char *init_read_pipename = "/home/zyw/tmp/afl_user_mode/image7/pipe/user_cpu_state";
char *write_pipename = "/home/zyw/tmp/afl_user_mode/image7/pipe/full_cpu_state";
#define SNAPSHOT_SYNC
#endif
