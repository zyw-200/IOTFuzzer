#include <stdlib.h>
#include <unistd.h>

// /opt/cross/mipsel-linux-musl/bin/mipsel-linux-musl-gcc -static

int global_value_initial = 32;
int global_value_uninitial;

static int static_value = 42;

int main(int argc, char *argv[])
{
	global_value_uninitial = 90;
	int start = strtol(argv[1], NULL, 16); 
	int count = 10;
	for(int i = 0; i<10; i++)
	{
		malloc(140);
	}
}