#!/bin/sh
#
rm -f /tmp/config1
awk 'BEGIN { FS = " " }; { print $1}' /etc/default-config >  /tmp/config
while read line
do
	len=${#line}
	if [ $len -eq 0 ]; then
		echo  >> /tmp/config1
	else
		echo $line > /tmp/tline
		grep "configVersion" /tmp/tline > /tmp/tout
		g_num=$?
		if [ $g_num -eq 0 ]; then
			grep "$line" /etc/default-config >> /tmp/config1
		else
			grep "$line " /var/config > /tmp/tout
			g_num=$?
			size=`/usr/bin/wc -l /tmp/tout | awk '{print $1}'`
			if [ $g_num -eq 0 ]; then
				if [ $size -eq 1 ]; then
					grep "$line " /var/config >> /tmp/config1
				else
					echo "$line t" >> /tmp/config1
				fi
			else
				grep "$line " /etc/default-config > /tmp/tout
				size=`/usr/bin/wc -l /tmp/tout | awk '{print $1}'`
				if [ $size -eq 1 ]; then
					grep "$line " /etc/default-config >> /tmp/config1
				else
					echo "$line t" >> /tmp/config1
				fi
			fi
		fi
	fi
done < /tmp/config
