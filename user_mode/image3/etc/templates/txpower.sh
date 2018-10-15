#!/bin/sh
echo [$0] ... > /dev/console
TXPOWER_NODE=`rgdb -g /wlan/inf:1/txpower`
TXPOWER_RUNTIME=`rgdb -i -g /runtime/stats/wlan/inf:1/txpower`
if [ "$TXPOWER_NODE" = "2" ]; then
if [ "$TXPOWER_RUNTIME" -gt "3" ]; then
a=`expr \( $TXPOWER_RUNTIME - "3" \)`
iwconfig ath0 txpower $a
else
iwconfig ath0 txpower 1
fi
elif [ "$TXPOWER_NODE" = "3" ]; then
if [ "$TXPOWER_RUNTIME" -gt "6" ]; then
b=`expr \( $TXPOWER_RUNTIME - "6" \)`
iwconfig ath0 txpower $b
else
iwconfig ath0 txpower 1
fi
elif [ "$TXPOWER_NODE" = "4" ]; then
if [ "$TXPOWER_RUNTIME" -gt "9" ]; then
c=`expr \( $TXPOWER_RUNTIME - "9" \)`
iwconfig ath0 txpower $c
else
iwconfig ath0 txpower 1
fi
else 
##iwconfig ath0 txpower $TXPOWER_RUNTIME
echo 100% Tx power ... > /dev/console
fi
