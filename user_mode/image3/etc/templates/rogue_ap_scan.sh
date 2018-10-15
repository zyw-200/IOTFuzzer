#!/bin/sh
echo [$0] ... > /dev/console
if [ $1 = "g" ]; then
ATH_DEV="ath0"
else
ATH_DEV="ath16"
fi
     iwlist $ATH_DEV scanning rogue_scan
