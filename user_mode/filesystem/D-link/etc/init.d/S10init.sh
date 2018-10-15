#!/bin/sh
mount -t proc none /proc
mount -t ramfs ramfs /var
mount -t sysfs sysfs /sys
mount -t usbfs usbfs /proc/bus/usb
