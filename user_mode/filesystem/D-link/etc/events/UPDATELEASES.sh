#!/bin/sh
xmldbc -P /etc/events/UPDATELEASES.php -V INF=$1 -V FILE=$2  > /var/run/UPDATELEASES.$1.sh
sh /var/run/UPDATELEASES.$1.sh
exit 0
