#!/bin/sh
##### TimeZone set up File ##########################################

if [ $# -ne 1 ]; then
	echo "Usage: Enter Time Zone Code [0-279]"
fi

case "$1" in
	0)
	echo "AFT-4:30" > /etc/TZ
	export "TZ=AFT-4:30"
	;;
	1)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	2)
	echo "CET-1" > /etc/TZ
	export "TZ=CET-1"
	;;
	3)
	echo "SST11" > /etc/TZ
	export "TZ=SST11"
	;;
	4)
	echo "CET-1CEST" > /etc/TZ
	export "TZ=CET-1CEST"
	;;
	5)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	6)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	7)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	8)
	echo "ART3ARST,M10.1.6/24:00:00,M3.3.6/24:00:00" > /etc/TZ
	export "TZ=ART3ARST,M10.1.6/24:00:00,M3.3.6/24:00:00"
	;;
	9)
	echo "AMT-4AMST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=AMT-4AMST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	10)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	11)
	echo "LHST-10:30LHST,M10.5.0/3:00:00,M3.5.0/3:00:00" > /etc/TZ
	export "TZ=LHST-10:30LHST,M10.5.0/3:00:00,M3.5.0/3:00:00"
	;;
	12)
	echo "EST-10EDT,M10.5.0/2:00:00,M3.5.0/3:00:00" > /etc/TZ
	export "TZ=EST-10EDT,M10.5.0/2:00:00,M3.5.0/3:00:00"
	;;
	13)
	echo "CST-9:30" > /etc/TZ
	export "TZ=CST-9:30"
	;;
	14)
	echo "EST-10" > /etc/TZ
	export "TZ=EST-10"
	;;
	15)
	echo "CST-9:30CDT,M10.5.0/2:00:00,M3.5.0/3:00:00" > /etc/TZ
	export "TZ=CST-9:30CDT,M10.5.0/2:00:00,M3.5.0/3:00:00"
	;;
	16)
	echo "EST-10EDT,M10.5.0/2:00:00,M3.5.0/3:00:00" > /etc/TZ
	export "TZ=EST-10EDT,M10.5.0/2:00:00,M3.5.0/3:00:00"
	;;
	17)
	echo "WST-8" > /etc/TZ
	export "TZ=WST-8"
	;;
	18)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	19)
	echo "AZT-4AZST,M3.5.0/5:00:00,M10.5.0/5:00:00" > /etc/TZ
	export "TZ=AZT-4AZST,M3.5.0/5:00:00,M10.5.0/5:00:00"
	;;
	20)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	21)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	22)
	echo "BDT-6" > /etc/TZ
	export "TZ=BDT-6"
	;;
	23)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	24)
	echo "EET-2EEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	25)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	26)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	27)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	28)
	echo "AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	29)
	echo "BTT-6" > /etc/TZ
	export "TZ=BTT-6"
	;;
	30)
	echo "BOT4" > /etc/TZ
	export "TZ=BOT4"
	;;
	31)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	32)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	33)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	34)
	echo "BRST3BRDT,M11.1.6/24:00:00,M2.3.6/24:00:00" > /etc/TZ
	export "TZ=BRST3BRDT,M11.1.6/24:00:00,M2.3.6/24:00:00"
	;;
	35)
	echo "FNT2" > /etc/TZ
	export "TZ=FNT2"
	;;
	36)
	echo "BRAST5BRADT" > /etc/TZ
	export "TZ=BRAST5BRADT"
	;;
	37)
	echo "BRWST4BRWDT,M11.1.6/24:00:00,M2.3.6/24:00:00" > /etc/TZ
	export "TZ=BRWST4BRWDT,M11.1.6/24:00:00,M2.3.6/24:00:00"
	;;
	38)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	39)
	echo "BNT-8" > /etc/TZ
	export "TZ=BNT-8"
	;;
	40)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	41)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	42)
	echo "MMT-6:30" > /etc/TZ
	export "TZ=MMT-6:30"
	;;
	43)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	44)
	echo "ICT-7" > /etc/TZ
	export "TZ=ICT-7"
	;;
	45)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	46)
	echo "AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	47)
	echo "CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	48)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	49)
	echo "MST7MDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=MST7MDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	50)
	echo "NST3:30NDT,M3.2.0/24:00:00,M11.1.0/24:00:00" > /etc/TZ
	export "TZ=NST3:30NDT,M3.2.0/24:00:00,M11.1.0/24:00:00"
	;;
	51)
	echo "PST8PDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=PST8PDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	52)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	53)
	echo "CVT1" > /etc/TZ
	export "TZ=CVT1"
	;;
	54)
	echo "EST5" > /etc/TZ
	export "TZ=EST5"
	;;
	55)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	56)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	57)
	echo "CLT4CLST,M10.5.6/24:00:00,M3.5.6/24:00:00" > /etc/TZ
	export "TZ=CLT4CLST,M10.5.6/24:00:00,M3.5.6/24:00:00"
	;;
	58)
	echo "EAST6EASST,M10.2.6/10:00:00,M3.5.6/10:00:00" > /etc/TZ
	export "TZ=EAST6EASST,M10.2.6/10:00:00,M3.5.6/10:00:00"
	;;
	59)
	echo "CST-8" > /etc/TZ
	export "TZ=CST-8"
	;;
	60)
	echo "CXT-14" > /etc/TZ
	export "TZ=CXT-14"
	;;
	61)
	echo "CCT-6:30" > /etc/TZ
	export "TZ=CCT-6:30"
	;;
	62)
	echo "COT5" > /etc/TZ
	export "TZ=COT5"
	;;
	63)
	echo "CAT-1" > /etc/TZ
	export "TZ=CAT-1"
	;;
	64)
	echo "CKT10" > /etc/TZ
	export "TZ=CKT10"
	;;
	65)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	66)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	67)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	68)
	echo "CST5CDT,M3.3.6/1:00:00,M10.5.0/1:00:00" > /etc/TZ
	export "TZ=CST5CDT,M3.3.6/1:00:00,M10.5.0/1:00:00"
	;;
	69)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	70)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	71)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	72)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	73)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	74)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	75)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	76)
	echo "ECT5" > /etc/TZ
	export "TZ=ECT5"
	;;
	77)
	echo "GALT6" > /etc/TZ
	export "TZ=GALT6"
	;;
	78)
	echo "EET-2EEST,M4.5.4/24:00:00,M8.5.4/24:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M4.5.4/24:00:00,M8.5.4/24:00:00"
	;;
	79)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	80)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	81)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	82)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	83)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	84)
	echo "WET0WEST,M3.5.0/2:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=WET0WEST,M3.5.0/2:00:00,M10.5.0/2:00:00"
	;;
	85)
	echo "FJT-12" > /etc/TZ
	export "TZ=FJT-12"
	;;
	86)
	echo "EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00"
	;;
	87)
	echo "CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00" > /etc/TZ
	export "TZ=CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00"
	;;
	88)
	echo "GFT3" > /etc/TZ
	export "TZ=GFT3"
	;;
	89)
	echo "GAMT9" > /etc/TZ
	export "TZ=GAMT9"
	;;
	90)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	91)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	92)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	93)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	94)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	95)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	96)
	echo "EET-2CEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2CEST,M3.5.0/3:00:00,M10.5.0/4:00:00"
	;;
	97)
	echo "EGT1EGST,M3.5.6/1:00:00,M10.5.6/1:00:00" > /etc/TZ
	export "TZ=EGT1EGST,M3.5.6/1:00:00,M10.5.6/1:00:00"
	;;
	98)
	echo "AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=AST4ADT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	99)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	100)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	101)
	echo "ChST-10" > /etc/TZ
	export "TZ=ChST-10"
	;;
	102)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	103)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	104)
	echo "GYT4" > /etc/TZ
	export "TZ=GYT4"
	;;
	105)
	echo "UTC5" > /etc/TZ
	export "TZ=UTC5"
	;;
	106)
	echo "HST10" > /etc/TZ
	export "TZ=HST10"
	;;
	107)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	108)
	echo "HKT-8" > /etc/TZ
	export "TZ=HKT-8"
	;;
	109)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	110)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	111)
	echo "IST-5:30" > /etc/TZ
	export "TZ=IST-5:30"
	;;
	112)
	echo "CIT-8" > /etc/TZ
	export "TZ=CIT-8"
	;;
	113)
	echo "EIT-9" > /etc/TZ
	export "TZ=EIT-9"
	;;
	114)
	echo "WIT-7" > /etc/TZ
	export "TZ=WIT-7"
	;;
	115)
	echo "IRST-3:30IRDT,M3.4.1/00:00:00,M9.4.3/00:00:00" > /etc/TZ
	export "TZ=IRST-3:30IRDT,M3.4.1/00:00:00,M9.4.3/00:00:00"
	;;
	116)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	117)
	echo "GMT0IST,M3.5.0/1:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=GMT0IST,M3.5.0/1:00:00,M10.5.0/2:00:00"
	;;
	118)
	echo "IST-2IDT,M3.5.5/2:00:00,M10.1.0/2:00:00" > /etc/TZ
	export "TZ=IST-2IDT,M3.5.5/2:00:00,M10.1.0/2:00:00"
	;;
	119)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	120)
	echo "EST5" > /etc/TZ
	export "TZ=EST5"
	;;
	121)
	echo "JST-9" > /etc/TZ
	export "TZ=JST-9"
	;;
	122)
	echo "HST10" > /etc/TZ
	export "TZ=HST10"
	;;
	123)
	echo "EET-2EEST,M3.5.4/1:00:00,M10.5.5/1:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.4/1:00:00,M10.5.5/1:00:00"
	;;
	124)
	echo "JFST4JFDT" > /etc/TZ
	export "TZ=JFST4JFDT"
	;;
	125)
	echo "ALMT-6" > /etc/TZ
	export "TZ=ALMT-6"
	;;
	126)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	127)
	echo "LINT-14" > /etc/TZ
	export "TZ=LINT-14"
	;;
	128)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	129)
	echo "KGT-6" > /etc/TZ
	export "TZ=KGT-6"
	;;
	130)
	echo "ICT-7" > /etc/TZ
	export "TZ=ICT-7"
	;;
	131)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	132)
	echo "EET-2EEST,M3.5.6/24:00:00,M10.5.6/24:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.6/24:00:00,M10.5.6/24:00:00"
	;;
	133)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	134)
	echo "SAST-2" > /etc/TZ
	export "TZ=SAST-2"
	;;
	135)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	136)
	echo "EET-2" > /etc/TZ
	export "TZ=EET-2"
	;;
	137)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	138)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	139)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	140)
	echo "CST-8" > /etc/TZ
	export "TZ=CST-8"
	;;
	141)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	142)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	143)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	144)
	echo "MYT-8" > /etc/TZ
	export "TZ=MYT-8"
	;;
	145)
	echo "MVT-5" > /etc/TZ
	export "TZ=MVT-5"
	;;
	146)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	147)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	148)
	echo "ChST-10" > /etc/TZ
	export "TZ=ChST-10"
	;;
	149)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	150)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	151)
	echo "MUT-4" > /etc/TZ
	export "TZ=MUT-4"
	;;
	152)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	153)
	echo "CST6CDT,M4.1.0/2:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=CST6CDT,M4.1.0/2:00:00,M10.5.0/2:00:00"
	;;
	154)
	echo "PST8PDT,M4.1.0/2:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=PST8PDT,M4.1.0/2:00:00,M10.5.0/2:00:00"
	;;
	155)
	echo "PST8PDT,M4.1.0/2:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=PST8PDT,M4.1.0/2:00:00,M10.5.0/2:00:00"
	;;
	156)
	echo "SST11" > /etc/TZ
	export "TZ=SST11"
	;;
	157)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	158)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	159)
	echo "ULAT-8ULAST,M3.5.0/2,M9.5.0/2" > /etc/TZ
	export "TZ=ULAT-8ULAST,M3.5.0/2,M9.5.0/2"
	;;
	160)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	161)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	162)
	echo "WT0WST,M5.5.6/24:00:00,M9.5.6/24:00:00" > /etc/TZ
	export "TZ=WT0WST,M5.5.6/24:00:00,M9.5.6/24:00:00"
	;;
	163)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	164)
	echo "WAT-1WAST,M9.1.0/2:00:00,M4.1.0/2:00:00" > /etc/TZ
	export "TZ=WAT-1WAST,M9.1.0/2:00:00,M4.1.0/2:00:00"
	;;
	165)
	echo "NRT-12" > /etc/TZ
	export "TZ=NRT-12"
	;;
	166)
	echo "NPT-5:45" > /etc/TZ
	export "TZ=NPT-5:45"
	;;
	167)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	168)
	echo "CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00" > /etc/TZ
	export "TZ=CET-1CEST-2,M3.5.0/02:00:00,M10.5.0/03:00:00"
	;;
	169)
	echo "NCT-11" > /etc/TZ
	export "TZ=NCT-11"
	;;
	170)
	echo "SBT-11" > /etc/TZ
	export "TZ=SBT-11"
	;;
	171)
	echo "NZST-12NZDT,M10.1.0/2:00:00,M3.3.0/3:00:00" > /etc/TZ
	export "TZ=NZST-12NZDT,M10.1.0/2:00:00,M3.3.0/3:00:00"
	;;
	172)
	echo "CHAST-12:45CHADT,M10.1.0/02:45:00,M3.3.0/03:45:00" > /etc/TZ
	export "TZ=CHAST-12:45CHADT,M10.1.0/02:45:00,M3.3.0/03:45:00"
	;;
	173)
	echo "CST6" > /etc/TZ
	export "TZ=CST6"
	;;
	174)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	175)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	176)
	echo "NUT11" > /etc/TZ
	export "TZ=NUT11"
	;;
	177)
	echo "NFT-11:30" > /etc/TZ
	export "TZ=NFT-11:30"
	;;
	178)
	echo "KST-9" > /etc/TZ
	export "TZ=KST-9"
	;;
	179)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	180)
	echo "GST-4" > /etc/TZ
	export "TZ=GST-4"
	;;
	181)
	echo "PKT-5PKST,M5.5.6/24:00:00,M8.5.0/24:00:00" > /etc/TZ
	export "TZ=PKT-5PKST,M5.5.6/24:00:00,M8.5.0/24:00:00"
	;;
	182)
	echo "PWT-9" > /etc/TZ
	export "TZ=PWT-9"
	;;
	183)
	echo "EST5" > /etc/TZ
	export "TZ=EST5"
	;;
	184)
	echo "PGT-10" > /etc/TZ
	export "TZ=PGT-10"
	;;
	185)
	echo "PYT4PYST,M10.3.6/24:00:00,M3.2.6/24:00:00" > /etc/TZ
	export "TZ=PYT4PYST,M10.3.6/24:00:00,M3.2.6/24:00:00"
	;;
	186)
	echo "PET5" > /etc/TZ
	export "TZ=PET5"
	;;
	187)
	echo "PHT-8" > /etc/TZ
	export "TZ=PHT-8"
	;;
	188)
	echo "PST8" > /etc/TZ
	export "TZ=PST8"
	;;
	189)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	190)
	echo "AZOT1AZOST,M3.5.6/1:00:00,M10.5.0/1:00:00" > /etc/TZ
	export "TZ=AZOT1AZOST,M3.5.6/1:00:00,M10.5.0/1:00:00"
	;;
	191)
	echo "WET0WEST,M3.5.0/1:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=WET0WEST,M3.5.0/1:00:00,M10.5.0/2:00:00"
	;;
	192)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	193)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	194)
	echo "RET-4" > /etc/TZ
	export "TZ=RET-4"
	;;
	195)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	196)
	echo "MSK-4MSD,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=MSK-4MSD,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	197)
	echo "EET-2EEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	198)
	echo "MAGT-11MAGST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=MAGT-11MAGST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	199)
	echo "PETT-12PETST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=PETT-12PETST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	200)
	echo "MSK-4MSD,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=MSK-4MSD,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	201)
	echo "SAMT-4SANST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=SAMT-4SANST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	202)
	echo "YEKT-5YEKST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=YEKT-5YEKST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	203)
	echo "NOVT-6NOVST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=NOVT-6NOVST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	204)
	echo "KRAT-7KRAST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=KRAT-7KRAST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	205)
	echo "IRKT-8IRKST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=IRKT-8IRKST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	206)
	echo "YAKT-10YAKST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "YAKT-10YAKST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	207)
	echo "VLAT-10VLAST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=VLAT-10VLAST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	208)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	209)
	echo "PMST3PMDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=PMST3PMDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	210)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	211)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	212)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	213)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	214)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	215)
	echo "SCT-4" > /etc/TZ
	export "TZ=SCT-4"
	;;
	216)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	217)
	echo "SGT-8" > /etc/TZ
	export "TZ=SGT-8"
	;;
	218)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	219)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	220)
	echo "SBT-11" > /etc/TZ
	export "TZ=SBT-11"
	;;
	221)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	222)
	echo "SAST-2" > /etc/TZ
	export "TZ=SAST-2"
	;;
	223)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	224)
	echo "KST-9" > /etc/TZ
	export "TZ=KST-9"
	;;
	225)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	226)
	echo "WET0WEST,M3.5.0/2:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=WET0WEST,M3.5.0/2:00:00,M10.5.0/2:00:00"
	;;
	227)
	echo "IST-5:30" > /etc/TZ
	export "TZ=IST-5:30"
	;;
	228)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	229)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	230)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	231)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	232)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	233)
	echo "SRT3" > /etc/TZ
	export "TZ=SRT3"
	;;
	234)
	echo "SAST-2" > /etc/TZ
	export "TZ=SAST-2"
	;;
	235)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	236)
	echo "CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/2:00:00,M10.5.0/3:00:00"
	;;
	237)
	echo "EET-2EEST,M4.1.4/24:00:00,M9.5.2/24:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M4.1.4/24:00:00,M9.5.2/24:00:00"
	;;
	238)
	echo "THAT10" > /etc/TZ
	export "TZ=THAT10"
	;;
	239)
	echo "CST-8" > /etc/TZ
	export "TZ=CST-8"
	;;
	240)
	echo "TJT-5" > /etc/TZ
	export "TZ=TJT-5"
	;;
	241)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	242)
	echo "ICT-7" > /etc/TZ
	export "TZ=ICT-7"
	;;
	243)
	echo "GMT0" > /etc/TZ
	export "TZ=GMT0"
	;;
	244)
	echo "TOT-13" > /etc/TZ
	export "TZ=TOT-13"
	;;
	245)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	246)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	247)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	248)
	echo "TMT-5" > /etc/TZ
	export "TZ=TMT-5"
	;;
	249)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	250)
	echo "TVT-12" > /etc/TZ
	export "TZ=TVT-12"
	;;
	251)
	echo "EAT-3" > /etc/TZ
	export "TZ=EAT-3"
	;;
	252)
	echo "EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/3:00:00,M10.5.0/4:00:00"
	;;
	253)
	echo "EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00" > /etc/TZ
	export "TZ=EET-2EEST,M3.5.0/4:00:00,M10.5.0/4:00:00"
	;;
	254)
	echo "GST-4" > /etc/TZ
	export "TZ=GST-4"
	;;
	255)
	echo "GMT0BST,M3.5.0/1:00:00,M10.5.0/2:00:00" > /etc/TZ
	export "TZ=GMT0BST,M3.5.0/1:00:00,M10.5.0/2:00:00"
	;;
	256)
	echo "UYT3UYST,M10.1.0/2:00:00,M3.2.0/2:00:00" > /etc/TZ
	export "TZ=UYT3UYST,M10.1.0/2:00:00,M3.2.0/2:00:00"
	;;
	257)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	258)
	echo "AKST9AKDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=AKST9AKDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	259)
	echo "HAST10HADT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=HAST10HADT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	260)
	echo "MST7" > /etc/TZ
	export "TZ=MST7"
	;;
	261)
	echo "CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=CST6CDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	262)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	263)
	echo "EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=EST5EDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	264)
	echo "MST7MDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=MST7MDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	265)
	echo "PST8PDT,M3.2.0/2:00:00,M11.1.0/2:00:00" > /etc/TZ
	export "TZ=PST8PDT,M3.2.0/2:00:00,M11.1.0/2:00:00"
	;;
	266)
	echo "UZT-5" > /etc/TZ
	export "TZ=UZT-5"
	;;
	267)
	echo "VUT-11" > /etc/TZ
	export "TZ=VUT-11"
	;;
	268)
	echo "CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00" > /etc/TZ
	export "TZ=CET-1CEST,M3.5.0/3:00:00,M10.5.0/3:00:00"
	;;
	269)
	echo "VET4" > /etc/TZ
	export "TZ=VET4"
	;;
	270)
	echo "ICT-7" > /etc/TZ
	export "TZ=ICT-7"
	;;
	271)
	echo "WAKT-12" > /etc/TZ
	export "TZ=WAKT-12"
	;;
	272)
	echo "WFT-12" > /etc/TZ
	export "TZ=WFT-12"
	;;
	273)
	echo "WST11" > /etc/TZ
	export "TZ=WST11"
	;;
	274)
	echo "AST4" > /etc/TZ
	export "TZ=AST4"
	;;
	275)
	echo "AST-3" > /etc/TZ
	export "TZ=AST-3"
	;;
	276)
	echo "WAT-2" > /etc/TZ
	export "TZ=WAT-2"
	;;
	277)
	echo "WAT-1" > /etc/TZ
	export "TZ=WAT-1"
	;;
	278)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
	279)
	echo "CAT-2" > /etc/TZ
	export "TZ=CAT-2"
	;;
esac
