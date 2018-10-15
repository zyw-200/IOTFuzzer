#!/bin/sh

echo "****** insmod usb-sorage module ******"
insmod /lib/modules/$(uname -r)/kernel/drivers/usb/storage/usb-storage.ko
echo "****** insmod usb-sorage module finish ******"

usbmount2 &
sleep 1
echo /usr/hotplug > /proc/sys/kernel/hotplug
