#include <stdlib.h>
#include <unistd.h>
#include<stdio.h>
#include<string.h>    //strlen
#include<sys/socket.h>
#include<arpa/inet.h> //inet_addr

int main(){
	int socket_desc , client_sock , c , read_size;
	struct sockaddr_in server , client;
	char client_message[2000];

	//Create socket
	socket_desc = socket(AF_INET , SOCK_STREAM , 0);
	if (socket_desc == -1)
	{
		printf("Could not create socket");
	}
	puts("Socket created");

	//Prepare the sockaddr_in structure
	server.sin_family = AF_INET;
	server.sin_addr.s_addr = INADDR_ANY;
	server.sin_port = htons(8888);

	//Bind
	if(bind(socket_desc,(struct sockaddr *)&server , sizeof(server)) < 0)
	{
		//print the error message
		//perror("bind failed. Error");
		return 1;
	}
	//puts("bind done");

	//Listen
	listen(socket_desc , 3);
	
	

	//Accept and incoming connection
	//puts("Waiting for incoming connections...");

	c = sizeof(struct sockaddr_in);
	

	//accept connection from an incoming client
	client_sock = accept(socket_desc, (struct sockaddr *)&client, (socklen_t*)&c);
	
	if (client_sock < 0)
	{
		//perror("accept failed");
		return 1;
	}
	puts("Connection accepted");

	//Receive a message from client
	memset(client_message, 0, 2000);

	if( (read_size = read(client_sock , client_message , sizeof(client_message))) > 0 )
	{
		if(strncmp(client_message, "st", 2) == 0){
			int child_pid = fork();
			if(child_pid == 0){
				int a = 3;
				int b = 4;
				char * argv1[] = {"busybox", "ping", "192.168.0.2", NULL};
				char * argv2[] = {"busybox", "ls", "/FILE_LOAD", NULL};
				char buf[10];
				if(strcmp(client_message, "st1") != 0)
					return;
				if(strcmp(client_message, "st12") != 0)
					return;
				if(strcmp(client_message, "st123") != 0)
					strcpy(buf, client_message);
					return;
			}
			else{
				int status;
				waitpid(child_pid, &status, 0);
			}
		
		}
	}
/*
	else if(read_size == 0)
	{
		puts("Client disconnected");
		fflush(stdout);
	}
	else if(read_size == -1)
	{
		perror("recv failed");
	}
*/
	sleep(1);
	return 0;
}
