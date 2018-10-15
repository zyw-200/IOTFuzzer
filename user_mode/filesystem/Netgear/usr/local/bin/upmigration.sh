#!/bin/sh

if [ -z $1 ] || [ ! -f $1 ] || [ -z $2 ] || [ ! -f $2 ]; then
        logger -s "Usage: $0 <migration-file> <config-file>"
        exit 1
fi

while read -r line
do
	diff_name=$(echo "$line" | cut -d ' ' -f1)
	diff_value=$(echo "$line" | cut -d ' ' -f2)

	grep $diff_name $2 >> /dev/null

	if [ $? == 0 ]
	then
	        while read -r line
	        do
         	        var_name=$(echo "$line" | cut -d ' ' -f1)
        	        var_value=$(echo "$line" | cut -d ' ' -f2)

			if [ ${#line} -gt 0 ] && [ $diff_name == $var_name ]
	                then
				sed -i "s,$var_name $var_value,$diff_name $diff_value,g" $2
        	        fi

	        done < "$2"
	else
		echo "$line" >> $2
	fi

done < "$1"

