#include <stdio.h>
#include <sys/types.h>
#include <unistd.h>

int main(int argc, char **argv){
	int a = 3;
	int b = 4;
	int pid = fork();
	if(pid == 0){
		char **p = argv + 1;
		printf("start ********************%s\n",p[0]);
		execv(p[0], p);

	}
	else{
		return 0;
	}
}
