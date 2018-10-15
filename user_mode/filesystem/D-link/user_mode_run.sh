

AFL="./afl-fuzz -m none -t 800000+ -Q -i ./inputs -o ./outputs "



#../../TriforceAFL_new/FILE_LOAD/single_httpd_sample_user_mode_x86 @@
#gdb -q --args \
#${AFL} \
#gdb -q --args \
#chroot . ./afl-qemu-trace  ./sbin/httpd -f /var/run/httpd.conf 
#gdb -q --args \
#./sbin/httpd -f /var/run/httpd.conf
#gdb -q --args \
#./htdocs/cgibin
#./gdb-static -q --args \
chroot . \
${AFL} \
/sbin/httpd -f /var/run/httpd.conf
#/htdocs/web/hedwig.cgi
#/usr/sbin/dnsmasq
#/usr/sbin/xmldb @@
#./htdocs/web/hedwig.cgi @@
#./sbin/httpd -f /var/run/httpd.conf
#./single_httpd_sample_user_mode @@
