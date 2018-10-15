#!/bin/sh
#echo [$0] ... > /dev/console

rgdb -i -s "/runtime/stats/ethernet/rx/bytes" "0"
rgdb -i -s "/runtime/stats/ethernet/rx/packets" "0"
rgdb -i -s "/runtime/stats/ethernet/rx/drop" "0"
rgdb -i -s "/runtime/stats/ethernet/tx/bytes" "0"
rgdb -i -s "/runtime/stats/ethernet/tx/packets" "0"
rgdb -i -s "/runtime/stats/ethernet/tx/drop" "0"
rgdb -i -s "/runtime/stats/ethernet/count/len_64_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_65_127_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_128_255_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_256_511_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_512_1023_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_1024_1518_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/len_1519_max_packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/multicast"	"0"
rgdb -i -s "/runtime/stats/ethernet/count/broadcast_packets"	"0"

LAN_RX_BYTES=`rgdb -i -g /runtime/stats/ethernet/rx/bytes_c`
rgdb -i -s "/runtime/stats/ethernet/rx/bytes_reduce" "$LAN_RX_BYTES"

LAN_RX_PACKETS=`rgdb -i -g /runtime/stats/ethernet/rx/packets_c`
rgdb -i -s "/runtime/stats/ethernet/rx/packets_reduce" "$LAN_RX_PACKETS"

LAN_RX_DROP=`rgdb -i -g /runtime/stats/ethernet/rx/drop_c`
rgdb -i -s "/runtime/stats/ethernet/rx/drop_reduce" "$LAN_RX_DROP"

LAN_TX_BYTES=`rgdb -i -g /runtime/stats/ethernet/tx/bytes_c`
rgdb -i -s "/runtime/stats/ethernet/tx/bytes_reduce" "$LAN_TX_BYTES"

LAN_TX_PACKETS=`rgdb -i -g /runtime/stats/ethernet/tx/packets_c`
rgdb -i -s "/runtime/stats/ethernet/tx/packets_reduce" "$LAN_TX_PACKETS"

LAN_TX_DROP=`rgdb -i -g /runtime/stats/ethernet/tx/drop_c`
rgdb -i -s "/runtime/stats/lan/ethernet/drop_reduce" "$LAN_TX_DROP"

LAN_TX_LAN_64=`rgdb -i -g /runtime/stats/ethernet/count/len_64_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_64_packets_reduce" "$LAN_TX_LAN_64"

LAN_TX_LAN_65_127=`rgdb -i -g /runtime/stats/ethernet/count/len_65_127_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_65_127_packets_reduce" "$LAN_TX_LAN_65_127"

LAN_TX_LAN_128_255=`rgdb -i -g /runtime/stats/ethernet/count/len_128_255_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_128_255_packets_reduce" "$LAN_TX_LAN_128_255"

LAN_TX_LAN_256_511=`rgdb -i -g /runtime/stats/ethernet/count/len_256_511_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_256_511_packets_reduce" "$LAN_TX_LAN_256_511"

LAN_TX_LAN_512_1023=`rgdb -i -g /runtime/stats/ethernet/count/len_512_1023_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_512_1023_packets_reduce" "$LAN_TX_LAN_512_1023"

LAN_TX_LAN_1024_1518=`rgdb -i -g /runtime/stats/ethernet/count/len_1024_1518_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_1024_1518_packets_reduce" "$LAN_TX_LAN_1024_1518"

LAN_TX_LAN_1519_max=`rgdb -i -g /runtime/stats/ethernet/count/len_1519_max_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/len_1519_max_packets_reduce" "$LAN_TX_LAN_1519_max"

LAN_TX_LAN_multicast=`rgdb -i -g /runtime/stats/ethernet/count/multicast_c`
rgdb -i -s "/runtime/stats/ethernet/count/multicast_reduce" "$LAN_TX_LAN_multicast"

LAN_TX_LAN_broadcast_packets=`rgdb -i -g /runtime/stats/ethernet/count/broadcast_packets_c`
rgdb -i -s "/runtime/stats/ethernet/count/broadcast_packets_reduce" "$LAN_TX_LAN_broadcast_packets"



