#!/bin/sh
#echo [$0] ... > /dev/console

LAN_RX_BYTES=`rgdb -i -g /runtime/stats/ethernet/rx/bytes_c`
LAN_RX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/ethernet/rx/bytes_reduce`


LAN_RX_PACKETS=`rgdb -i -g /runtime/stats/ethernet/rx/packets_c`
LAN_RX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/ethernet/rx/packets_reduce`


LAN_RX_DROP=`rgdb -i -g /runtime/stats/ethernet/rx/drop_c`
LAN_RX_DROP_REDUCE=`rgdb -i -g /runtime/stats/ethernet/rx/drop_reduce`


LAN_TX_BYTES=`rgdb -i -g /runtime/stats/ethernet/tx/bytes_c`
LAN_TX_BYTES_REDUCE=`rgdb -i -g /runtime/stats/ethernet/tx/bytes_reduce`


LAN_TX_PACKETS=`rgdb -i -g /runtime/stats/ethernet/tx/packets_c`
LAN_TX_PACKETS_REDUCE=`rgdb -i -g /runtime/stats/ethernet/tx/packets_reduce`


LAN_TX_DROP=`rgdb -i -g /runtime/stats/ethernet/tx/drop_c`
LAN_TX_DROP_REDUCE=`rgdb -i -g /runtime/stats/ethernet/tx/drop_reduce`


LAN_TX_LAN_64=`rgdb -i -g /runtime/stats/ethernet/count/len_64_packets_c`
LAN_TX_LAN_64_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_64_packets_reduce`


LAN_TX_LAN_65_127=`rgdb -i -g /runtime/stats/ethernet/count/len_65_127_packets_c`
LAN_TX_LAN_65_127_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_65_127_packets_reduce`

LAN_TX_LAN_128_255=`rgdb -i -g /runtime/stats/ethernet/count/len_128_255_packets_c`
LAN_TX_LAN_128_255_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_128_255_packets_reduce`

LAN_TX_LAN_256_511=`rgdb -i -g /runtime/stats/ethernet/count/len_256_511_packets_c`
LAN_TX_LAN_256_511_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_256_511_packets_reduce`

LAN_TX_LAN_512_1023=`rgdb -i -g /runtime/stats/ethernet/count/len_512_1023_packets_c`
LAN_TX_LAN_512_1023_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_512_1023_packets_reduce`

LAN_TX_LAN_1024_1518=`rgdb -i -g /runtime/stats/ethernet/count/len_1024_1518_packets_c`
LAN_TX_LAN_1024_1518_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_1024_1518_packets_reduce`

LAN_TX_LAN_1519_MAX=`rgdb -i -g /runtime/stats/ethernet/count/len_1519_max_packets_c`
LAN_TX_LAN_1519_MAX_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/len_1519_max_packets_reduce`

LAN_TX_LAN_MULTICAST=`rgdb -i -g /runtime/stats/ethernet/count/multicast_c`
LAN_TX_LAN_MULTICAST_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/multicast_reduce`

LAN_TX_LAN_BROADCAST=`rgdb -i -g /runtime/stats/ethernet/count/broadcast_packets_c`
LAN_TX_LAN_BROADCAST_REDUCE=`rgdb -i -g /runtime/stats/ethernet/count/broadcast_packets_reduce`


if [ $LAN_RX_BYTES -ge $LAN_RX_BYTES_REDUCE ]; then
       LAN_RX_BYTES_SHOW=`expr $LAN_RX_BYTES - $LAN_RX_BYTES_REDUCE`
else 
       LAN_RX_BYTES_SHOW=$LAN_RX_BYTES 
       rgdb -i -s "/runtime/stats/ethernet/rx/bytes_reduce" "0"
fi

rgdb -i -s "/runtime/stats/ethernet/rx/bytes" "$LAN_RX_BYTES_SHOW"


if [ $LAN_RX_PACKETS -ge $LAN_RX_PACKETS_REDUCE ]; then 
LAN_RX_PACKETS_SHOW=`expr $LAN_RX_PACKETS - $LAN_RX_PACKETS_REDUCE`
else 
        LAN_RX_PACKETS_SHOW=$LAN_RX_PACKETS 
        rgdb -i -s "/runtime/stats/ethernet/rx/packets_reduce" "0"
        
fi
rgdb -i -s "/runtime/stats/ethernet/rx/packets" "$LAN_RX_PACKETS_SHOW"


if [ $LAN_RX_DROP -ge $LAN_RX_DROP_REDUCE ]; then 
LAN_RX_DROP_SHOW=`expr $LAN_RX_DROP - $LAN_RX_DROP_REDUCE`
else 
        LAN_RX_DROP_SHOW=$LAN_RX_DROP 
        rgdb -i -s "/runtime/stats/ethernet/rx/drop_reduce" "0"
fi
rgdb -i -s "/runtime/stats/ethernet/rx/drop" "$LAN_RX_DROP_SHOW"


if [ $LAN_TX_BYTES -ge $LAN_TX_BYTES_REDUCE ]; then
LAN_TX_BYTES_SHOW=`expr $LAN_TX_BYTES - $LAN_TX_BYTES_REDUCE`
else 
       LAN_TX_BYTES_SHOW=$LAN_TX_BYTES
       rgdb -i -s "/runtime/stats/ethernet/tx/bytes_reduce" "0"
fi
rgdb -i -s "/runtime/stats/ethernet/tx/bytes" "$LAN_TX_BYTES_SHOW"


if [ $LAN_TX_PACKETS -ge $LAN_TX_PACKETS_REDUCE ]; then
LAN_TX_PACKETS_SHOW=`expr $LAN_TX_PACKETS - $LAN_TX_PACKETS_REDUCE`
else 
       LAN_TX_PACKETS_SHOW=$LAN_TX_PACKETS 
       rgdb -i -s "/runtime/stats/ethernet/tx/packets_reduce" "0"
fi
rgdb -i -s "/runtime/stats/ethernet/tx/packets" "$LAN_TX_PACKETS_SHOW"



if [ $LAN_TX_DROP -ge $LAN_TX_DROP_REDUCE ]; then
LAN_TX_DROP_SHOW=`expr $LAN_TX_DROP - $LAN_TX_DROP_REDUCE`
else 
       LAN_TX_DROP_SHOW=$LAN_TX_DROP
       rgdb -i -s "/runtime/stats/ethernet/tx/drop_reduce" "0"
fi
rgdb -i -s "/runtime/stats/ethernet/tx/drop" "$LAN_TX_DROP_SHOW"


if [ $LAN_TX_LAN_64 -ge $LAN_TX_LAN_64_REDUCE ]; then
LAN_TX_LAN_64_SHOW=`expr $LAN_TX_LAN_64 - $LAN_TX_LAN_64_REDUCE`
else 
        LAN_TX_LAN_64_SHOW=$LAN_TX_LAN_64 
        rgdb -i -s "/runtime/stats/ethernet/count/len_64_packets_reduce"	"0"
fi 
rgdb -i -s "/runtime/stats/ethernet/count/len_64_packets" "$LAN_TX_LAN_64_SHOW"

if [ $LAN_TX_LAN_65_127 -ge $LAN_TX_LAN_65_127_REDUCE ]; then
LAN_TX_LAN_65_127_SHOW=`expr $LAN_TX_LAN_65_127 - $LAN_TX_LAN_65_127_REDUCE`
else 
        LAN_TX_LAN_65_127_SHOW=$LAN_TX_LAN_65_127
        rgdb -i -s "/runtime/stats/ethernet/count/len_65_127_packets_reduce"	"0" 
fi 
rgdb -i -s "/runtime/stats/ethernet/count/len_65_127_packets" "$LAN_TX_LAN_65_127_SHOW"


if [ $LAN_TX_LAN_128_255 -ge $LAN_TX_LAN_128_255_REDUCE ]; then
LAN_TX_LAN_128_255_SHOW=`expr $LAN_TX_LAN_128_255 - $LAN_TX_LAN_128_255_REDUCE`
else 
        LAN_TX_LAN_128_255_SHOW=$LAN_TX_LAN_128_255
        rgdb -i -s "/runtime/stats/ethernet/count/len_128_255_packets_reduce"	"0" 
fi 
rgdb -i -s "/runtime/stats/ethernet/count/len_128_255_packets" "$LAN_TX_LAN_128_255_SHOW"


if [ $LAN_TX_LAN_256_511 -ge $LAN_TX_LAN_256_511_REDUCE ]; then
LAN_TX_LAN_256_511_SHOW=`expr $LAN_TX_LAN_256_511 - $LAN_TX_LAN_256_511_REDUCE`
else 
        LAN_TX_LAN_256_511_SHOW=$LAN_TX_LAN_256_511
        rgdb -i -s "/runtime/stats/ethernet/count/len_256_511_packets_reduce"	"0"
fi 
rgdb -i -s "/runtime/stats/ethernet/count/len_256_511_packets" "$LAN_TX_LAN_256_511_SHOW"


if [ $LAN_TX_LAN_512_1023 -ge $LAN_TX_LAN_512_1023_REDUCE ]; then
LAN_TX_LAN_512_1023_SHOW=`expr $LAN_TX_LAN_512_1023 - $LAN_TX_LAN_512_1023_REDUCE`
else 
        LAN_TX_LAN_512_1023_SHOW=$LAN_TX_LAN_512_1023
        rgdb -i -s "/runtime/stats/ethernet/count/len_512_1023_packets_reduce"	"0"
fi 
rgdb -i -s "/runtime/stats/ethernet/count/len_512_1023_packets" "$LAN_TX_LAN_512_1023_SHOW"

if [ $LAN_TX_LAN_1024_1518 -ge $LAN_TX_LAN_1024_1518_REDUCE ]; then
LAN_TX_LAN_1024_1518_SHOW=`expr $LAN_TX_LAN_1024_1518 - $LAN_TX_LAN_1024_1518_REDUCE`
else 
        LAN_TX_LAN_1024_1518_SHOW=$LAN_TX_LAN_1024_1518 
        rgdb -i -s "/runtime/stats/ethernet/count/len_1024_1518_packets_reduce"	"0"
fi
rgdb -i -s "/runtime/stats/ethernet/count/len_1024_1518_packets" "$LAN_TX_LAN_1024_1518_SHOW"


if [ $LAN_TX_LAN_1519_MAX -ge $LAN_TX_LAN_1519_MAX_REDUCE ]; then
LAN_TX_LAN_1519_MAX_SHOW=`expr $LAN_TX_LAN_1519_MAX - $LAN_TX_LAN_1519_MAX_REDUCE`
else 
        LAN_TX_LAN_1519_MAX_SHOW=$LAN_TX_LAN_1519_MAX
       rgdb -i -s "/runtime/stats/ethernet/count/len_1519_max_packets_reduce"	"0"
fi
rgdb -i -s "/runtime/stats/ethernet/count/len_1519_max_packets" "$LAN_TX_LAN_1519_MAX_SHOW"



if [ $LAN_TX_LAN_MULTICAST -ge $LAN_TX_LAN_MULTICAST_REDUCE ]; then
LAN_TX_LAN_MULTICAST_SHOW=`expr $LAN_TX_LAN_MULTICAST - $LAN_TX_LAN_MULTICAST_REDUCE`
else 
        LAN_TX_LAN_MULTICAST_SHOW=$LAN_TX_LAN_MULTICAST
        rgdb -i -s "/runtime/stats/ethernet/count/multicast_reduce"	"0"
fi
rgdb -i -s "/runtime/stats/ethernet/count/multicast" "$LAN_TX_LAN_MULTICAST_SHOW"

if [ $LAN_TX_LAN_BROADCAST -ge $LAN_TX_LAN_BROADCAST_REDUCE ]; then
LAN_TX_LAN_BROADCAST_SHOW=`expr $LAN_TX_LAN_BROADCAST - $LAN_TX_LAN_BROADCAST_REDUCE`
else 
       LAN_TX_LAN_BROADCAST_SHOW=$LAN_TX_LAN_BROADCAST
       rgdb -i -s "/runtime/stats/ethernet/count/broadcast_packets_reduce"	"0"
fi
rgdb -i -s "/runtime/stats/ethernet/count/broadcast_packets" "$LAN_TX_LAN_BROADCAST_SHOW"
