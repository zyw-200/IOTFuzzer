#!/bin/sh
echo [$0] ... > /dev/console

LAN_RX_BYTES=`rgdb -i -g /runtime/stats/lan/rx/bytes_c`
LAN_RX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/lan/rx/bytes_reduce`
LAN_RX_BYTES_SHOW=`rgdb -i -g /runtime/stats/lan/rx/bytes`

LAN_RX_PACKETS=`rgdb -i -g /runtime/stats/lan/rx/packets_c`
LAN_RX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/lan/rx/packets_reduce`
LAN_RX_PACKETS_SHOW=`rgdb -i -g /runtime/stats/lan/rx/packets`

LAN_RX_DROP=`rgdb -i -g /runtime/stats/lan/rx/drop_c`
LAN_RX_DROP_REDUCE=`rgdb -i -g /runtime/stats/lan/rx/drop_reduce`
LAN_RX_DROP_SHOW=`rgdb -i -g /runtime/stats/lan/rx/drop`

LAN_TX_BYTES=`rgdb -i -g /runtime/stats/lan/tx/bytes_c`
LAN_TX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/lan/tx/bytes_reduce`
LAN_TX_BYTES_SHOW=`rgdb -i -g /runtime/stats/lan/tx/bytes`

LAN_TX_PACKETS=`rgdb -i -g /runtime/stats/lan/tx/packets_c`
LAN_TX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/lan/tx/packets_reduce`
LAN_TX_PACKETS_SHOW=`rgdb -i -g /runtime/stats/lan/tx/packets`

LAN_TX_DROP=`rgdb -i -g /runtime/stats/lan/tx/drop_c`
LAN_TX_DROP_REDUCE=`rgdb -i -g /runtime/stats/lan/tx/drop_reduce`
LAN_TX_DROP_SHOW=`rgdb -i -g /runtime/stats/lan/tx/drop`

WLAN_RX_BYTES=`rgdb -i -g /runtime/stats/wireless/rx/bytes_c`
WLAN_RX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/wireless/rx/bytes_reduce`
WLAN_RX_BYTES_SHOW=`rgdb -i -g /runtime/stats/wireless/rx/bytes`

WLAN_RX_PACKETS=`rgdb -i -g /runtime/stats/wireless/rx/packets_c`
WLAN_RX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/wireless/rx/packets_reduce`
WLAN_RX_PACKETS_SHOW=`rgdb -i -g /runtime/stats/wireless/rx/packets`

WLAN_RX_DROP=`rgdb -i -g /runtime/stats/wireless/rx/drop_c`
WLAN_RX_DROP_REDUCE=`rgdb -i -g /runtime/stats/wireless/rx/drop_reduce`
WLAN_RX_DROP_SHOW=`rgdb -i -g /runtime/stats/wireless/rx/drop`

WLAN_TX_BYTES=`rgdb -i -g /runtime/stats/wireless/tx/bytes_c`
WLAN_TX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/wireless/tx/bytes_reduce`
WLAN_TX_BYTES_SHOW=`rgdb -i -g /runtime/stats/wireless/tx/bytes`

WLAN_TX_PACKETS=`rgdb -i -g /runtime/stats/wireless/tx/packets_c`
WLAN_TX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/wireless/tx/packets_reduce`
WLAN_TX_PACKETS_SHOW=`rgdb -i -g /runtime/stats/wireless/tx/packets`

WLAN_TX_DROP=`rgdb -i -g /runtime/stats/wireless/tx/drop_c`
WLAN_TX_DROP_REDUCE=`rgdb -i -g /runtime/stats/wireless/tx/drop_reduce`
WLAN_TX_DROP_SHOW=`rgdb -i -g /runtime/stats/wireless/tx/drop`

LAN_RX_BYTES_SHOW=`expr $LAN_RX_BYTES - $LAN_RX_BYTES_REDUCE`
rgdb -i -s "/runtime/stats/lan/rx/bytes" "$LAN_RX_BYTES_SHOW"

LAN_RX_PACKETS_SHOW=`expr $LAN_RX_PACKETS - $LAN_RX_PACKETS_REDUCE`
rgdb -i -s "/runtime/stats/lan/rx/packets" "$LAN_RX_PACKETS_SHOW"

LAN_RX_DROP_SHOW=`expr $LAN_RX_DROP - $LAN_RX_DROP_REDUCE`
rgdb -i -s "/runtime/stats/lan/rx/drop" "$LAN_RX_DROP_SHOW"

LAN_TX_BYTES_SHOW=`expr $LAN_TX_BYTES - $LAN_TX_BYTES_REDUCE`
rgdb -i -s "/runtime/stats/lan/tx/bytes" "$LAN_TX_BYTES_SHOW"

LAN_TX_PACKETS_SHOW=`expr $LAN_TX_PACKETS - $LAN_TX_PACKETS_REDUCE`
rgdb -i -s "/runtime/stats/lan/tx/packets" "$LAN_TX_PACKETS_SHOW"

LAN_TX_DROP_SHOW=`expr $LAN_TX_DROP - $LAN_TX_DROP_REDUCE`
rgdb -i -s "/runtime/stats/lan/tx/drop" "$LAN_TX_DROP_SHOW"

WLAN_RX_BYTES_SHOW=`expr $WLAN_RX_BYTES - $WLAN_RX_BYTES_REDUCE`
rgdb -i -s "/runtime/stats/wireless/rx/bytes" "$WLAN_RX_BYTES_SHOW"

WLAN_RX_PACKETS_SHOW=`expr $WLAN_RX_PACKETS - $WLAN_RX_PACKETS_REDUCE`
rgdb -i -s "/runtime/stats/wireless/rx/packets" "$WLAN_RX_PACKETS_SHOW"

WLAN_RX_DROP_SHOW=`expr $WLAN_RX_DROP - $WLAN_RX_DROP_REDUCE`
rgdb -i -s "/runtime/stats/wireless/rx/drop" "$WLAN_RX_DROP_SHOW"

WLAN_TX_BYTES_SHOW=`expr $WLAN_TX_BYTES - $WLAN_TX_BYTES_REDUCE`
rgdb -i -s "/runtime/stats/wireless/tx/bytes" "$WLAN_TX_BYTES_SHOW"

WLAN_TX_PACKETS_SHOW=`expr $WLAN_TX_PACKETS - $WLAN_TX_PACKETS_REDUCE`
rgdb -i -s "/runtime/stats/wireless/tx/packets" "$WLAN_TX_PACKETS_SHOW"

WLAN_TX_DROP_SHOW=`expr $WLAN_TX_DROP - $WLAN_TX_DROP_REDUCE`
rgdb -i -s "/runtime/stats/wireless/tx/drop" "$WLAN_TX_DROP_SHOW"


