#!/bin/sh
TZ="/tmp/tz"
<? /* vi: set sw=4 ts=4: */
set("/tmp/tz/zone:1/name", "dummy");
anchor("/tmp/tz");

$USA="M3.2.0/02:00:00,M11.1.0/02:00:00";
set("zone:1/name",	"(GMT-12:00) International Date Line West");
set("zone:1/gen",	"GMT+12:00");
set("zone:1/dst",	"");
set("zone:2/name",	"(GMT-11:00) Midway Island, Samoa");
set("zone:2/gen",	"GMT+11:00");
set("zone:2/dst",	"");
set("zone:3/name",	"(GMT-10:00) Hawaii");
set("zone:3/gen",	"GMT+10:00");
set("zone:3/dst",	"");
set("zone:4/name",	"(GMT-09:00) Alaska");
set("zone:4/gen",	"GMT+09:00");
set("zone:4/dst",	"GDT,".$USA);
set("zone:5/name",	"(GMT-08:00) Pacific Time (US & Canada); Tijuana");
set("zone:5/gen",	"PST+08:00");
set("zone:5/dst",	"PDT,".$USA);
set("zone:6/name",	"(GMT-07:00) Arizona");
set("zone:6/gen",	"GMT+07:00");
set("zone:6/dst",	"");
set("zone:7/name",	"(GMT-07:00) Chihuahua, La Paz, Mazatlan");
set("zone:7/gen",	"GMT+07:00");
set("zone:7/dst",	"GDT,".$USA);
set("zone:8/name",	"(GMT-07:00) Mountain Time (US & Canada)");
set("zone:8/gen",	"GMT+07:00");
set("zone:8/dst",	"GDT,".$USA);
set("zone:9/name",	"(GMT-06:00) Central America");
set("zone:9/gen",	"GMT+06:00");
set("zone:9/dst",	"");
set("zone:10/name",	"(GMT-06:00) Central Time (US & Canada)");
set("zone:10/gen",	"GMT+06:00");
set("zone:10/dst",	"GDT,".$USA);
set("zone:11/name",	"(GMT-06:00) Guadalajara, Mexico City, Monterrey");
set("zone:11/gen",	"GMT+06:00");
set("zone:11/dst",	"GDT,".$USA);
set("zone:12/name",	"(GMT-06:00) Saskatchewan");
set("zone:12/gen",	"GMT+06:00");
set("zone:12/dst",	"");
set("zone:13/name",	"(GMT-05:00) Bogota, Lima, Quito");
set("zone:13/gen",	"GMT+05:00");
set("zone:13/dst",	"");
set("zone:14/name",	"(GMT-05:00) Eastern Time (US & Canada)");
set("zone:14/gen",	"EST+05:00");
set("zone:14/dst",	"EDT,".$USA);
set("zone:15/name",	"(GMT-05:00) Indiana (East)");
set("zone:15/gen",	"EST+05:00");
set("zone:15/dst",	"");
set("zone:16/name",	"(GMT-04:00) Atlantic Time (Canada)");
set("zone:16/gen",	"GMT+04:00");
set("zone:16/dst",	"GDT,".$USA);
set("zone:17/name",	"(GMT-04:00) Caracas, La Paz");
set("zone:17/gen",	"GMT+04:00");
set("zone:17/dst",	"");
set("zone:18/name",	"(GMT-04:00) Santiago");
set("zone:18/gen",	"GMT+04:00");
set("zone:18/dst",	"GDT,M10.2.6/00:00:00,M3.2.6/00:00:00");
set("zone:19/name",	"(GMT-03:30) Newfoundland");
set("zone:19/gen",	"GMT+03:30");
set("zone:19/dst",	"GDT,M4.1.0/00:01:00,M10.5.0/00:01:00");
set("zone:20/name",	"(GMT-03:00) Brasilia");
set("zone:20/gen",	"GMT+03:00");
set("zone:20/dst",      "GDT,M10.3.0/00:00:00,M2.4.0/00:00:00");
set("zone:21/name",	"(GMT-03:00) Buenos Aires, Georgetown");
set("zone:21/gen",	"GMT+03:00");
set("zone:21/dst",	"");
set("zone:22/name",	"(GMT-03:00) Greenland");
set("zone:22/gen",	"GMT+03:00");
set("zone:22/dst",	"GDT,".$USA);
set("zone:23/name",	"(GMT-02:00) Mid-Atlantic");
set("zone:23/gen",	"GMT+02:00");
set("zone:23/dst",	"");
set("zone:24/name",	"(GMT-01:00) Azores");
set("zone:24/gen",	"GMT+01:00");
set("zone:24/dst",	"GDT,M3.5.0/00:00:00,M10.5.0/01:00:00");
set("zone:25/name",	"(GMT-01:00) Cape Verde Is.");
set("zone:25/gen",	"GMT+01:00");
set("zone:25/dst",	"");
set("zone:26/name",	"(GMT) Casablanca, Monrovia");
set("zone:26/gen",	"GMT");
set("zone:26/dst",	"");
set("zone:27/name",	"(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London");
set("zone:27/gen",	"GMT-00:00");
set("zone:27/dst",	"GDT,M3.5.0/01:00:00,M10.5.0/02:00:00");
set("zone:28/name",	"(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna");
set("zone:28/gen",	"GMT-01:00");
set("zone:28/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:29/name",	"(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague");
set("zone:29/gen",	"GMT-01:00");
set("zone:29/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:30/name",	"(GMT+01:00) Brussels, Copenhagen, Madrid, Paris");
set("zone:30/gen",	"GMT-01:00");
set("zone:30/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:31/name",	"(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb");
set("zone:31/gen",	"GMT-01:00");
set("zone:31/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:32/name",	"(GMT+01:00) West Central Africa");
set("zone:32/gen",	"GMT-01:00");
set("zone:32/dst",	"");
set("zone:33/name",	"(GMT+02:00) Athens, Istanbul, Minsk");
set("zone:33/gen",	"GMT-02:00");
set("zone:33/dst",	"GDT,M3.5.0/03:00:00,M10.5.0/04:00:00");
set("zone:34/name",	"(GMT+02:00) Bucharest");
set("zone:34/gen",	"GMT-02:00");
set("zone:34/dst",	"GDT,M3.5.0/03:00:00,M10.5.0/04:00:00");
set("zone:35/name",	"(GMT+02:00) Cairo");
set("zone:35/gen",	"GMT-02:00");
set("zone:35/dst",	"GDT,M4.5.5/00:00:00,M9.5.4/00:00:00");
set("zone:36/name",	"(GMT+02:00) Harare, Pretoria");
set("zone:36/gen",	"GMT-02:00");
set("zone:36/dst",	"");
set("zone:37/name",	"(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius");
set("zone:37/gen",	"GMT-02:00");
set("zone:37/dst",	"GDT,M3.5.0/03:00:00,M10.5.0/04:00:00");
set("zone:38/name",	"(GMT+02:00) Jerusalem");
set("zone:38/gen",	"GMT-02:00");
set("zone:38/dst",	"");
set("zone:39/name",	"(GMT+03:00) Baghdad");
set("zone:39/gen",	"GMT-03:00");
set("zone:39/dst",	"");
set("zone:40/name",	"(GMT+03:00) Kuwait, Riyadh");
set("zone:40/gen",	"GMT-03:00");
set("zone:40/dst",	"");
set("zone:41/name",	"(GMT+03:00) Nairobi");
set("zone:41/gen",	"GMT-03:00");
set("zone:41/dst",	"");
set("zone:42/name",	"(GMT+03:30) Tehran");
set("zone:42/gen",	"GMT-03:30");
set("zone:42/dst",	"");
set("zone:43/name",	"(GMT+04:00) Moscow, St. Petersburg, Volgograd");
set("zone:43/gen",	"GMT-04:00");
set("zone:43/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:44/name",	"(GMT+04:00) Abu Dhabi, Muscat");
set("zone:44/gen",	"GMT-04:00");
set("zone:44/dst",	"");
set("zone:45/name",	"(GMT+04:00) Baku, Tbilisi, Yerevan");
set("zone:45/gen",	"GMT-04:00");
set("zone:45/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:46/name",	"(GMT+04:30) Kabul");
set("zone:46/gen",	"GMT-04:30");
set("zone:46/dst",	"");
set("zone:47/name",	"(GMT+05:00) Islamabad, Karachi, Tashkent");
set("zone:47/gen",	"GMT-05:00");
set("zone:47/dst",	"");
set("zone:48/name",	"(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi");
set("zone:48/gen",	"GMT-05:30");
set("zone:48/dst",	"");
set("zone:49/name",	"(GMT+05:45) Kathmandu");
set("zone:49/gen",	"GMT-05:45");
set("zone:49/dst",	"");
set("zone:50/name",	"(GMT+06:00) Ekaterinburg");
set("zone:50/gen",	"GMT-06:00");
set("zone:50/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:51/name",	"(GMT+06:00) Almaty");
set("zone:51/gen",	"GMT-06:00");
set("zone:51/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:52/name",	"(GMT+06:00) Astana, Dhaka");
set("zone:52/gen",	"GMT-06:00");
set("zone:52/dst",	"");
set("zone:53/name",	"(GMT+06:00) Sri Jayawardenepura");
set("zone:53/gen",	"GMT-06:00");
set("zone:53/dst",	"");
set("zone:54/name",	"(GMT+06:30) Rangoon");
set("zone:54/gen",	"GMT-06:30");
set("zone:54/dst",	"");
set("zone:55/name",	"(GMT+07:00) Bangkok, Hanoi, Jakarta, Novosibirsk");
set("zone:55/gen",	"GMT-07:00");
set("zone:55/dst",	"");
set("zone:56/name",	"(GMT+08:00) Krasnoyarsk");
set("zone:56/gen",	"GMT-08:00");
set("zone:56/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:57/name",	"(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi");
set("zone:57/gen",	"CST-08:00");
set("zone:57/dst",	"");
set("zone:58/name",	"(GMT+08:00) Ulaan Bataar");
set("zone:58/gen",	"GMT-08:00");
set("zone:58/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:59/name",	"(GMT+08:00) Kuala Lumpur, Singapore");
set("zone:59/gen",	"GMT-08:00");
set("zone:59/dst",	"");
set("zone:60/name",	"(GMT+08:00) Perth");
set("zone:60/gen",	"GMT-08:00");
set("zone:60/dst",	"");
set("zone:61/name",	"(GMT+08:00) Taipei");
set("zone:61/gen",	"GMT-08:00");
set("zone:61/dst",	"");
set("zone:62/name",	"(GMT+09:00) Irkutsk, Osaka, Sapporo, Tokyo");
set("zone:62/gen",	"GMT-09:00");
set("zone:62/dst",	"");
set("zone:63/name",	"(GMT+09:00) Seoul");
set("zone:63/gen",	"GMT-09:00");
set("zone:63/dst",	"");
set("zone:64/name",	"(GMT+09:30) Adelaide");
set("zone:64/gen",	"GMT-09:30");
set("zone:64/dst",	"GDT,M10.5.0/02:00:00,M3.5.0/03:00:00");
set("zone:65/name",	"(GMT+09:30) Darwin");
set("zone:65/gen",	"GMT-09:30");
set("zone:65/dst",	"");
set("zone:66/name",	"(GMT+10:00) Yakutsk");
set("zone:66/gen",	"GMT-10:00");
set("zone:66/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:67/name",	"(GMT+10:00) Brisbane");
set("zone:67/gen",	"GMT-10:00");
set("zone:67/dst",	"");
set("zone:68/name",	"(GMT+10:00) Canberra, Melbourne, Sydney");
set("zone:68/gen",	"GMT-10:00");
set("zone:68/dst",	"GDT,M10.5.0/02:00:00,M3.5.0/03:00:00");
set("zone:69/name",	"(GMT+10:00) Guam, Port Moresby");
set("zone:69/gen",	"GMT-10:00");
set("zone:69/dst",	"");
set("zone:70/name",	"(GMT+10:00) Hobart");
set("zone:70/gen",	"GMT-10:00");
set("zone:70/dst",	"GDT,M10.1.0/02:00:00,M3.5.0/03:00:00");
set("zone:71/name",	"(GMT+11:00) Vladivostok");
set("zone:71/gen",	"GMT-11:00");
set("zone:71/dst",	"GDT,M3.5.0/02:00:00,M10.5.0/03:00:00");
set("zone:72/name",	"(GMT+11:00)  Solomon Is., New Caledonia");
set("zone:72/gen",	"GMT-11:00");
set("zone:72/dst",	"");
set("zone:73/name",	"(GMT+12:00) Magadan,Auckland, Wellington");
set("zone:73/gen",	"GMT-12:00");
set("zone:73/dst",	"GDT,M10.1.0/02:00:00,M3.5.0/03:00:00");
set("zone:74/name",	"(GMT+12:00) Fiji, Kamchatka, Marshall Is.");
set("zone:74/gen",	"GMT-12:00");
set("zone:74/dst",	"");
set("zone:75/name",	"(GMT+13:00) Nuku'alofa");
set("zone:75/gen",	"GMT-13:00");
set("zone:75/dst",	"");
?>
index=`rgdb -g /time/timezone`
tz=`rgdb -g $TZ/zone:$index/gen`
ds=`rgdb -g /time/daylightsaving`

ST_M=`rgdb -g /time/daylightsaving/startdate/month`
ST_W=`rgdb -g /time/daylightsaving/startdate/week`
ST_D=`rgdb -g /time/daylightsaving/startdate/dayofweek`
ST_H=`rgdb -g /time/daylightsaving/startdate/time`

END_M=`rgdb -g /time/daylightsaving/enddate/month`
END_W=`rgdb -g /time/daylightsaving/enddate/week`
END_D=`rgdb -g /time/daylightsaving/enddate/dayofweek`
END_H=`rgdb -g /time/daylightsaving/enddate/time`

if [ "$ST_H" = "0" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "0" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "1" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "1" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "2" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "2" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "3" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "3" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "4" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "4" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "5" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "5" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "6" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "6" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "7" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "7" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "8" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "8" ]; then
	END_H="0"$END_H
fi
if [ "$ST_H" = "9" ]; then
	ST_H="0"$ST_H
fi
if [ "$END_H" = "9" ]; then
	END_H="0"$END_H
fi

DSTS="GDT";
if [ "$index" = "5" ]; then
	DSTS="PDT";
fi
if [ "$index" = "14" ]; then
	DSTS="EDT";
fi
DSTF=$DSTS",M"$ST_M"."$ST_W"."$ST_D"/"$ST_H":00:00,M"$END_M"."$END_W"."$END_D"/"$END_H":00:00";

if [ "$ds" = "1" ]; then
	tz="$tz"$DSTF
fi
echo $tz > /etc/TZ
exit 0
