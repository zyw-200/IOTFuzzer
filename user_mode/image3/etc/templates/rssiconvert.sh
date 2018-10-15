#!/bin/sh
if [ ! $# == 1 ]; then
	echo "0"
	exit
 fi
 if [ "$1" -le 0 ] || [ "$1" -gt 100  ] ; then
	echo "0"
	exit
fi

#expr  $1 * 94 /100

 if [ "$1" -ge 100  ] ; then
	dbmvalue=42
elif [ "$1" -ge 90  ] ; then
	dbmvalue=36
elif [ "$1" -ge 80  ] ; then
	dbmvalue=27
elif [ "$1" -ge 70  ] ; then
	dbmvalue=24
elif [ "$1" -ge 60  ] ; then
	dbmvalue=21
elif [ "$1" -ge 50  ] ; then
	dbmvalue=18
elif [ "$1" -ge 40  ] ; then
	dbmvalue=15
elif [ "$1" -ge 30  ] ; then
	dbmvalue=12
elif [ "$1" -ge 20  ] ; then
	dbmvalue=9
elif [ "$1" -ge 10  ] ; then
	dbmvalue=6
elif [ "$1" -ge 5  ] ; then
	dbmvalue=4
elif [ "$1" -ge 1  ] ; then
	dbmvalue=1
else
	dbmvalue=0
fi	
echo $dbmvalue
#elif [ "$1" -gt 85  ] ; then
#	$dbmvalue=expr 1.33*$1-84
#elif [ "$1" -gt 5  ] ; then
#	$dbmvalue=expr 5*tmprssi/16+3.3
#elif [ "$1" -ge 1  ] ; then
#	$dbmvalue= $1
#else
#	$dbmvalue=0
#fi
#echo $dbmvalue
exit 0


