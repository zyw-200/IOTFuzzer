#!/bin/sh
echo [$0] ... > /dev/console
if [ $1 = "g" ]; then
iwlist ath0 compare 
else
iwlist ath16 compare 
fi
