#include <stdlib.h>
#include <unistd.h>
#include<stdio.h>
#include<string.h>    //strlen
#include<sys/socket.h>
#include<arpa/inet.h> //inet_addr
#include <sys/types.h>


// /opt/cross/mipsel-linux-musl/bin/mipsel-linux-musl-gcc -static single_httpd_sample_user_mode.c
// mv a.out /home/zyw/tmp/afl/image/sample
//  mv a.out /home/zyw/tmp/triforceafl_new/FILE_LOAD/sample

static int getWork(char *filename, char * ptr, int sz)
{
    int retsz;
    FILE *fp;
    unsigned char ch;

    fp = fopen(filename, "rb");
    if(!fp) {
     perror(filename);
     printf("aflFile open failed\n");
    }
    retsz = 0;
    while(retsz < sz) {
        if(fread(&ch, 1, 1, fp) == 0)
            break;

	*ptr = ch;
        retsz ++;
        ptr ++;
    }
    fclose(fp);
    return retsz;
}


int main(int argc, char * argv[]){
	int socket_desc , client_sock , c , read_size;
	printf("argv[1]:%s\n", argv[1]);
	struct sockaddr_in server , client;
	char client_message[2000];

	//getWork(argv[1], client_message, 2000);
    strcpy(client_message, argv[1]);//"st12345"
	//printf("%s\n", client_message);
	getpid();
	printf("%s\n", client_message);
	if(strncmp(client_message, "st", 2) == 0){
		int a = 3;
		int b = 4;
		char * argv1[] = {"busybox", "ping", "192.168.0.2", NULL};
		char * argv2[] = {"busybox", "ls", "/FILE_LOAD", NULL};
		char buf[100];
		if(strstr(client_message, "st1") == NULL)
			return 0;
		if(strstr(client_message, "st12") == NULL)
			return 0;
		if(strstr(client_message, "st123") != NULL){
			strcpy(buf, client_message);
			time(NULL);
			if(strlen(buf) >10){	
				//printf("crash\n");	
				exit(32);
			}
			else{
				exit(0);
			}	
		}
	}
	return 0;
}
