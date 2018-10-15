#!/bin/sh
echo [$0] ... > /dev/console

rgdb -i -s "/runtime/stats/lan/rx/bytes" "0"
rgdb -i -s "/runtime/stats/lan/rx/packets" "0"
rgdb -i -s "/runtime/stats/lan/rx/drop" "0"
rgdb -i -s "/runtime/stats/lan/tx/bytes" "0"
rgdb -i -s "/runtime/stats/lan/tx/packets" "0"
rgdb -i -s "/runtime/stats/lan/tx/drop" "0"
rgdb -i -s "/runtime/stats/wireless/rx/bytes" "0"
rgdb -i -s "/runtime/stats/wireless/rx/packets" "0"
rgdb -i -s "/runtime/stats/wireless/rx/drop" "0"
rgdb -i -s "/runtime/stats/wireless/tx/bytes" "0"
rgdb -i -s "/runtime/stats/wireless/tx/packets" "0"
rgdb -i -s "/runtime/stats/wireless/tx/drop" "0"

LAN_RX_BYTES=`rgdb -i -g /runtime/stats/lan/rx/bytes_c`
rgdb -i -s "/runtime/stats/lan/rx/bytes_reduce" "$LAN_RX_BYTES"

LAN_RX_PACKETS=`rgdb -i -g /runtime/stats/lan/rx/packets_c`
rgdb -i -s "/runtime/stats/lan/rx/packets_reduce" "$LAN_RX_PACKETS"

LAN_RX_DROP=`rgdb -i -g /runtime/stats/lan/rx/drop_c`
rgdb -i -s "/runtime/stats/lan/rx/drop_reduce" "$LAN_RX_DROP"

LAN_TX_BYTES=`rgdb -i -g /runtime/stats/lan/tx/bytes_c`
rgdb -i -s "/runtime/stats/lan/tx/bytes_reduce" "$LAN_TX_BYTES"

LAN_TX_PACKETS=`rgdb -i -g /runtime/stats/lan/tx/packets_c`
rgdb -i -s "/runtime/stats/lan/tx/packets_reduce" "$LAN_TX_PACKETS"

LAN_TX_DROP=`rgdb -i -g /runtime/stats/lan/tx/drop_c`
rgdb -i -s "/runtime/stats/lan/tx/drop_reduce" "$LAN_TX_DROP"

WLAN_RX_BYTES=`rgdb -i -g /runtime/stats/wireless/rx/bytes_c`
rgdb -i -s "/runtime/stats/wireless/rx/bytes_reduce" "$WLAN_RX_BYTES"

WLAN_RX_PACKETS=`rgdb -i -g /runtime/stats/wireless/rx/packets_c`
rgdb -i -s "/runtime/stats/wireless/rx/packets_reduce" "$WLAN_RX_PACKETS"

WLAN_RX_DROP=`rgdb -i -g /runtime/stats/wireless/rx/drop_c`
rgdb -i -s "/runtime/stats/wireless/rx/drop_reduce" "$WLAN_RX_DROP"

WLAN_TX_BYTES=`rgdb -i -g /runtime/stats/wireless/tx/bytes_c`
rgdb -i -s "/runtime/stats/wireless/tx/bytes_reduce" "$WLAN_TX_BYTES"

WLAN_TX_PACKETS=`rgdb -i -g /runtime/stats/wireless/tx/packets_c`
rgdb -i -s "/runtime/stats/wireless/tx/packets_reduce" "$WLAN_TX_PACKETS"

WLAN_TX_DROP=`rgdb -i -g /runtime/stats/wireless/tx/drop_c`
rgdb -i -s "/runtime/stats/wireless/tx/drop_reduce" "$WLAN_TX_DROP"
