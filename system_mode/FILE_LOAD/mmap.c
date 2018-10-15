#include <sys/mman.h>
#include <sys/types.h>
#include <fcntl.h>
#include <unistd.h>
#include <sys/stat.h>
#include <assert.h>
#include <stdio.h>

size_t getFilesize(const char* filename) {
    struct stat st;
    stat(filename, &st);
    return st.st_size;
}

int main(int argc, char** argv) {
    int filesize = getFilesize("/home/zyw/experiment/TriforceAFL_new/firmadyne/mem_file");
    //Open file
    int fd = open("/home/zyw/experiment/TriforceAFL_new/firmadyne/mem_file", O_RDWR, 0);
    assert(fd != -1);
    //Execute mmap
    //void* mmappedData = mmap(NULL, filesize, PROT_READ, MAP_PRIVATE | MAP_POPULATE, fd, 0);
    char* mmappedData = mmap(NULL, filesize, PROT_READ|PROT_WRITE, MAP_SHARED, fd, 0);
    printf("start address:%x\n", mmappedData);
    assert(mmappedData != MAP_FAILED);
    //Write the mmapped data to stdout (= FD #1)
    printf("filesize:%x\n",filesize);

/*
    for(int i=0;i<filesize;i++){
	int *p = (int *)(mmappedData + i);
	printf("%x",*p);
	//write(1,p,1);
    }

    while(1){
	printf("%s\n", mmappedData);
	sleep(2);
    }
*/
    write(1, mmappedData, filesize);
    printf("finish\n");
    //Cleanup
    int rc = munmap(mmappedData, filesize);
    assert(rc == 0);
    close(fd);
}
