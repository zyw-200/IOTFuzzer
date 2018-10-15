#include <fcntl.h>
#include <stdio.h>
#include <stdint.h>
#include <stdlib.h>
#include <unistd.h>

size_t virtual_to_physical(unsigned long addr, int pid)
{
    char filename[100];
    sprintf(filename, "/proc/%d/pagemap", pid);
    int fd = open(filename, O_RDONLY);
    if(fd < 0)
    {
        perror("cause"); 
        printf("%s failed!\n", filename);
        return 0;
    }

    size_t pagesize = getpagesize();
    size_t offset = (addr / pagesize) * sizeof(uint64_t);
    if(lseek(fd, offset, SEEK_SET) < 0)
    {
        printf("lseek() failed!\n");
        close(fd);
        return 0;
    }
    uint64_t info;
    if(read(fd, &info, sizeof(uint64_t)) != sizeof(uint64_t))
    {
        printf("read() failed!\n");
        close(fd);
        return 0;
    }
    if((info & (((uint64_t)1) << 63)) == 0)
    {
        printf("page is not present!\n");
        close(fd);
        return 0;
    }
    size_t frame = info & ((((uint64_t)1) << 55) - 1);
    size_t phy = frame * pagesize + addr % pagesize;
    close(fd);
    return phy;
}

int main(int argc, char **argv)
{
	int pid = atoi(argv[2]);
	unsigned long addr = (unsigned long)strtol(argv[1], NULL, 16);
	unsigned long phys_addr = virtual_to_physical(addr,pid);
    printf("%lx;%lx\n", addr, phys_addr);
}