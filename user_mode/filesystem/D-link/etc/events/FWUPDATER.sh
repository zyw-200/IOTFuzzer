#!/bin/sh
echo "[$0] ..."
cp -f /bin/busybox /var
fwupdater -i /var/firmware.seama
/var/busybox echo 1 > /proc/driver/system_reset
/var/busybox echo 1 > /proc/system_reset
