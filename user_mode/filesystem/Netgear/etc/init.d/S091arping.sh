#!/bin/sh
mgmt_vlan=`cat /var/config | grep 'managementVlanID' | cut -d: -f3 | awk '{ print $2}'`
mgmt_vlan_first3=`echo $mgmt_vlan | cut -c1-3`
ifconfig | grep brvlan$mgmt_vlan_first3 > /dev/null 2>&1

if [  $? == 0 ]; then

interface=brvlan$mgmt_vlan

else

interface=brtrunk
fi

# Here we are deriving IPV6 address from mac address. assigning this newly formed address and the address which is present in var/config to the interface

mac=`ifconfig "$interface" | grep HWaddr | cut -dH -f2 | awk '{ print $2 }'`
var_conf_ipv6adr=`cat /var/config | grep Ipv6:ipAddr | awk '{ print $2 }'`

/usr/bin/set_ipv6_addr $mac $interface $var_conf_ipv6adr
        


sleep 1
ncecho 'Starting ARPING...          '

X=0
while [ "$X" -lt "5" ];
do
arping -U `ifconfig "$interface"  | grep 'inet addr:'| cut -d: -f2 | awk '{ print $1}'` -I "$interface" -c 1 > /dev/null 2>&1 &
sleep 1
X=`expr $X + 1`
done

cecho green '[DONE]'

