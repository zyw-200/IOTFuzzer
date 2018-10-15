

AFL="./afl-fuzz -m none -t 800000+ -Q -i /inputs -o ./outputs -S fuzzer1"

#busybox bzip2 -d /home/zyw/tmp/afl_user_mode/image/outputs/.cur_input -c
#busybox bzip2 -d outputs/fuzzer1/.cur_input -c
#crash 0x459368

#../../TriforceAFL_new/FILE_LOAD/single_httpd_sample_user_mode_x86 @@
#gdb -q --args \
#${AFL} \
#gdb -q --args \
#chroot . ./afl-qemu-trace  ./sbin/httpd -f /var/run/httpd.conf 
#gdb -q --args \
#./sbin/httpd -f /var/run/httpd.conf

#gdb -q --args \
#./htdocs/cgibin
#gdb -q --args \
#chroot . \
#gdb -q --args \
chroot . \
${AFL} \
/htdocs/web/hedwig.cgi
#/bin/busybox bzip2 -d @@ -c 
#/htdocs/web/hedwig.cgi
#/htdocs/web/hedwig.cgi
#./sample @@
#/usr/sbin/xmldb @@
#/htdocs/web/hedwig.cgi
#/sbin/httpd -f /var/run/httpd.conf
#/usr/sbin/dnsmasq
#./single_httpd_sample_user_mode @@