#include<stdio.h> //printf
#include<string.h>    //strlen
#include<sys/socket.h>    //socket
#include<arpa/inet.h> //inet_addr
#include <stdlib.h>
#include <unistd.h>
#include <fcntl.h>


int make_socket_noblocking(int sock)
{
	int flags;
	if ((flags = fcntl(sock, F_GETFL, NULL)) < 0) {
		return -1;
	}
	if (fcntl(sock, F_SETFL, flags | O_NONBLOCK) == -1) {
		return -1;
	} 
	return 0;
}


int main(int argc , char *argv[])
{
	int sock;
	struct sockaddr_in server;
	char message[1000] , server_reply[2000];

	//Create socket
	while(1){
		sock = socket(AF_INET , SOCK_STREAM , 0);
		if (sock == -1)
		{
			printf("Could not create socket");
		}
		puts("Socket created");

		server.sin_addr.s_addr = inet_addr("192.168.0.1");
		server.sin_family = AF_INET;
		server.sin_port = htons( 80 );
		if(make_socket_noblocking(sock) == -1){
			printf("socket non-blocking set error\n");
		}

		//Connect to remote server
		while (connect(sock, (struct sockaddr *)&server , sizeof(server)) < 0)
		{	
			//printf("connect failed\n");
		}

		puts("Connected\n");

		//keep communicating with server

		memset(message, 0, 1000);
		strcpy(message, "st");
		 
		//Send some data

		if(send(sock , message , strlen(message) , 0) < 0)
		{
		    puts("Send failed");
		    return 1;
		}
		close(sock); //dont forget zyw
/*
		if(recv(sock , server_reply , 2000 , 0) < 0)
		{
		    puts("recv failed");
		}
*/

	}

	
/*	 
	//Receive a reply from the server
	
	 
	puts("Server reply :");
	puts(server_reply);


	close(sock);
*/

	return 0;
}
