#include <stdlib.h>
#include <unistd.h>
#include<stdio.h>
#include<string.h>    //strlen
#include<sys/socket.h>
#include<arpa/inet.h> //inet_addr

int main(int argc, char * argv[]){
	int socket_desc , client_sock , c , read_size;
	struct sockaddr_in server , client;
	char client_message[2000];
	//printf("start\n");
	//Receive a message from client
	//memset(client_message, 0, 2000);
	//printf("argv,%s\n", argv[1]);
	FILE *fp = fopen(argv[1], "r");

	if(fp==NULL){ 
		printf("file open er\n");
		strcpy(client_message, "st12345");
	}
	else{
		fgets(client_message, 2000, fp);
		fclose(fp);
	}

	if(strncmp(client_message, "st", 2) == 0){
		int child_pid = fork();
		if(child_pid == 0){
			int a = 3;
			int b = 4;
			char * argv1[] = {"busybox", "ping", "192.168.0.2", NULL};
			char * argv2[] = {"busybox", "ls", "/FILE_LOAD", NULL};
			char buf[10];
			if(strstr(client_message, "st1") == NULL)
				return 0;
			if(strstr(client_message, "st12") == NULL)
				return 0;
			if(strstr(client_message, "st123") != NULL)
				strcpy(buf, client_message);

				if(strlen(buf) >10){		
					exit(32);
				}
		}
		else{
			int status;
			waitpid(child_pid, &status, 0);
			printf("status:%d\n",status);			
			if(status!=0)
			{
				exit(32);
			} 
		}

	}

	return 0;
}
