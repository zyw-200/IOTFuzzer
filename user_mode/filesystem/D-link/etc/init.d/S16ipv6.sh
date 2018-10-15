#!/bin/sh
echo 1 > /proc/sys/net/ipv6/conf/all/forwarding
echo 2 > /proc/sys/net/ipv6/conf/default/accept_dad
echo 1 > /proc/sys/net/ipv6/conf/default/disable_ipv6
