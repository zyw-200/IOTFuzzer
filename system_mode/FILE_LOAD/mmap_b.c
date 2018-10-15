 //进程B的代码
    #include <sys/mman.h>  
    #include <sys/stat.h>  
    #include <fcntl.h>  
    #include <stdio.h>  
    #include <stdlib.h>  
    #include <unistd.h>  
    #include <error.h>  
      
    #define BUF_SIZE 100  
      
    int main(int argc, char **argv)  
    {  
        int fd, nread, i;  
        struct stat sb;  
        char *mapped, buf[BUF_SIZE];  
      
        for (i = 0; i < BUF_SIZE; i++) {  
            buf[i] = '#';  
        }  
      
        /* 打开文件 */  
        if ((fd = open(argv[1], O_RDWR)) < 0) {  
            perror("open");  
        }  
      
        /* 获取文件的属性 */  
        if ((fstat(fd, &sb)) == -1) {  
            perror("fstat");  
        }  
      
        /* 私有文件映射将无法修改文件 */  
	printf("size:%d\n",sb.st_size);
        if ((mapped = (char *)mmap(NULL, sb.st_size, PROT_READ |   
                        PROT_WRITE, MAP_PRIVATE, fd, 0)) == (void *)-1) {  
            perror("mmap");  
        }  
      
        /* 映射完后, 关闭文件也可以操纵内存 */  
        close(fd);  
      
        /* 修改一个字符 */  
        mapped[20] = '9';  
       	printf("over\n");
        return 0;  
    }  
