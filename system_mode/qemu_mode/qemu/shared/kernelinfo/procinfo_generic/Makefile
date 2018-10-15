obj-m	:= procinfo.o
KDIR	:= /lib/modules/$(shell uname -r)/build
PWD	:= $(shell pwd)

CROSS_COMPILE:=/opt/cross/mipseb-linux-musl/bin/mipseb-linux-musl-
ARCH:=mips
CC:=$(CROSS_COMPILE)gcc
LD:=$(CROSS_COMPILE)ld
 
default:
	$(MAKE) -C $(KDIR) SUBDIRS=$(PWD) modules

clean:
	$(MAKE) -C $(KDIR) SUBDIRS=$(PWD) clean
	rm -rf Module.markers modules.order Module.symvers

