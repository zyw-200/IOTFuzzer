#include <stdio.h>
#include <stdlib.h>
#include <unistd.h>

void main(int argc, char **argv){
	int a = 3;
	int b = 4;
	char * argv1[] = {"busybox", "ping", "192.168.0.2", NULL};
	char * argv2[] = {"busybox", "ls", "/FILE_LOAD", NULL};
        char buf[10];
	printf("arg is %s\n", argv[1]);
	execv("/bin/busybox",argv1);
	if(strcmp(argv[1], "st1") != 0)
		return;
	if(strcmp(argv[1], "st12") != 0)
		return;
	if(strcmp(argv[1], "st123") != 0)
	strcpy(buf, argv[1]);
	return;
}
