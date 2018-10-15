#!/bin/sh
echo [$0] ... > /dev/console

# NVRAM, rgcfg.
xmldbc -x /runtime/nvram/flashspeed				"get:devdata get -e flashspeed"
xmldbc -x /runtime/nvram/pin					"get:devdata get -e pin"
xmldbc -x /runtime/nvram/wanmac					"get:devdata get -e wanmac"
xmldbc -x /runtime/nvram/lanmac					"get:devdata get -e lanmac"
xmldbc -x /runtime/nvram/wlanmac				"get:devdata get -e wlanmac"
xmldbc -x /runtime/nvram/wlanmac_a				"get:devdata get -e wlanmac_a"
xmldbc -x /runtime/nvram/hwrev					"get:devdata get -e hwrev"
xmldbc -x /runtime/nvram/countrycode				"get:devdata get -e countrycode"
xmldbc -x /runtime/nvram/flash					"get:devdata get -e flash"
xmldbc -x /runtime/nvram/mfc_phase                             "get:devdata get -e mfc_phase"
# time
xmldbc -x /runtime/sys/uptime					"get:uptime seconly"
xmldbc -x /runtime/time/date					"get:date +%m/%d/%Y"
xmldbc -x /runtime/time/time					"get:date +%T"
xmldbc -x /runtime/time/rfc1123					"get:date +'%a, %d %b %Y %X %Z'"
xmldbc -x /runtime/time/dateddyymm  				"get:date +%d,%b,%Y"
xmldbc -x /runtime/time/week					"get:date +'%a'"
# statistics
xmldbc -x /runtime/stats/ethernet/rx/bytes_c			"get:scut -p eth0: -f 1 /proc/net/dev"
xmldbc -x /runtime/stats/ethernet/rx/packets_c			"get:scut -p eth0: -f 2 /proc/net/dev"
xmldbc -x /runtime/stats/ethernet/rx/drop_c          		"get:scut -p eth0: -f 4 /proc/net/dev"
xmldbc -x /runtime/stats/ethernet/tx/bytes_c			"get:scut -p eth0: -f 9 /proc/net/dev"
xmldbc -x /runtime/stats/ethernet/tx/packets_c			"get:scut -p eth0: -f 10 /proc/net/dev"
xmldbc -x /runtime/stats/ethernet/tx/drop_c                     "get:scut -p eth0: -f 12 /proc/net/dev"

#Free Memory
xmldbc -x /runtime/stats/memfree "get:scut -p MemFree: -f 1 /proc/meminfo"

rgdb -i -s "/runtime/stats/ethernet/rx/bytes_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/rx/bytes"	"0"
rgdb -i -s "/runtime/stats/ethernet/rx/packets_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/rx/packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/rx/drop_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/rx/drop"	"0"

rgdb -i -s "/runtime/stats/ethernet/tx/bytes_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/tx/bytes"	"0"
rgdb -i -s "/runtime/stats/ethernet/tx/packets_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/tx/packets"	"0"
rgdb -i -s "/runtime/stats/ethernet/tx/drop_reduce"	"0"
rgdb -i -s "/runtime/stats/ethernet/tx/drop"	"0"

rgdb -i -s "/runtime/stats/wireless/led11a" "0"
rgdb -i -s "/runtime/stats/wireless/led11g" "0"

rgdb -i -s "/runtime/sys/info/deviceversion" ""

#ethernet new statistic by traveller
j=0
while [ $j -lt 2 ] 
do
xmldbc -x   /runtime/stats/eth:$j/running  				"get:ifconfig eth"$j" | scut -p BROADCAST"
j=`expr $j + 1`
done 

# wireless statistic 
j=0
while [ $j -lt 32 ] 
do
xmldbc -x /runtime/stats/wireless/ath:$j/rx/bytes			"get:scut -p ath"$j": -f 1 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/rx/packets			"get:scut -p ath"$j": -f 2 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/tx/bytes			"get:scut -p ath"$j": -f 9 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/tx/packets			"get:scut -p ath"$j": -f 10 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/rx/drop       		"get:scut -p ath"$j": -f 4 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/tx/drop       		"get:scut -p ath"$j": -f 12 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/rx/error       	"get:scut -p ath"$j": -f 3 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/tx/error       	"get:scut -p ath"$j": -f 11 /proc/net/dev"
xmldbc -x /runtime/stats/wireless/ath:$j/running  			"get:ifconfig ath"$j" | scut -p BROADCAST" 
j=`expr $j + 1`
done 

j=0
while [ $j -lt 32 ] 
do
xmldbc -x /runtime/stats/wireless/ath:$j/mac  	"get:ifconfig ath"$j" | scut -p HWaddr " 
j=`expr $j + 1`
done 
# cable status
xmldbc -x /runtime/switch/port:1/linktype		"get:psts -i 3"
xmldbc -x /runtime/switch/port:2/linktype		"get:psts -i 2"
xmldbc -x /runtime/switch/port:3/linktype		"get:psts -i 1"
xmldbc -x /runtime/switch/port:4/linktype		"get:psts -i 0"
xmldbc -x /runtime/switch/wan_port				"get:psts -i 4"

#warm start status by traveller
#xmldbc -x /runtime/warm_start 					"get:cat /proc/rebootm"

#ip which get from device by traveller 
xmldbc -x /runtime/ip_inuse			  			"get:ifconfig br0 | scut -p addr:"	

#txpower dbm
xmldbc -x /runtime/inf:1/txpower                "get:iwconfig ath0 | scut -p Tx-Power:"
xmldbc -x /runtime/inf:2/txpower                "get:iwconfig ath16 | scut -p Tx-Power:"

#u-boot version
xmldbc -x /runtime/bootver/version                            "get:getbootver -s 'bootloader' 'ALPHA U-boot ' '\n'"
xmldbc -x /runtime/bootver/buildtime                          "get:getbootver -s 'bootloader' 'U-Boot 1.1.4 (' ')'"

