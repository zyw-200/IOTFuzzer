#!/bin/sh
#show all env
set 
#new_domain_name=abc.com.tld4.wireless.com. 
#new_domain_name_servers="2001::61 2001::60"
resolv_conf=/var/etc/resolv.conf
#echo -n "" > ${resolv_conf}
if [ "${new_domain_name}" != "" ] ;then
#        echo "search ${new_domain_name}" >> ${resolv_conf}
fi
for domain_name_server in ${new_domain_name_servers} ;
do 
#        echo "nameserver ${domain_name_server}" >> ${resolv_conf}
done
