<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";
include "/htdocs/phplib/xnode.php";


fwrite("w",$START, "#!/bin/sh\n");
fwrite("w",$STOP,  "#!/bin/sh\n");

$layout = query("/runtime/device/layout");
if ($layout!="router"){ exit; }

/* detect interface */
$i = 1;
while ($i<4)
{
	$WANSTR = "WAN-".$i;
	$WANDEV = PHYINF_getruntimeifname($WANSTR);
	///echo "loop".$i.$waninf."=".$WANDEV."\n";
	if ( $WANDEV!="" )break;
	$i++;
}

$LANSTR = "LAN-1";
$LANDEV = PHYINF_getruntimeifname($LANSTR);

$s1 = "echo Interface is wanif=".$WANDEV." lanif=".$LANDEV." ";
if ( $WANDEV=="" || $LANDEV=="" ){ $s2 = " ... Error!!! \n"; }else{ $s2 = " ... OK \n"; }
fwrite("a",$START, $s1.$s2);
fwrite("a",$STOP , $s1.$s2);
if ( $WANDEV=="" || $LANDEV=="" )exit;

/* tc debug */
$TC="echo tc";
$TC="tc";
$K=kbps;
$K=kbit;

/* main-stop */
fwrite("a",$STOP, "echo Stop QOS system ... \n");

/*main-start*/
fwrite("a",$START, "echo Start QOS system ... \n");

/* process node */
$WANPTR= XNODE_getpathbytarget("", "inf", "uid", $WANSTR, 0);
$QOS_ENABLE  = query("/device/qos/enable");
$UPSTREAM    = query($WANPTR."/bandwidth/upstream");
$DOWNSTREAM  = query($WANPTR."/bandwidth/downstream");

if ( $DOWNSTREAM==0 || $DOWNSTREAM=="" ){ $DOWNSTREAM=102400; }
if ( $UPSTREAM==0   || $UPSTREAM==""   ){ $UPSTREAM=102400; }
fwrite("a",$START, "echo QOS=".$QOS_ENABLE." UPSTREAM=".$UPSTREAM." DOWNSTREAM=".$DOWNSTREAM." \n");
if ( $QOS_ENABLE!=1 && $QOS_ENABLE!=2 ){ echo "echo QOS is disabled. \n";exit; }

fwrite("a",$STOP, $TC." qdisc del dev ".$WANDEV." root \n");
fwrite("a",$STOP, $TC." qdisc del dev ".$LANDEV." root \n");
fwrite("a",$STOP, "echo 0 > /proc/fastnat/qos \n");

/*--------------------------------------------------------*/
	$UBICOM = query("/device/qos/autobandwidth");
	$BWUP   = query("/runtime/device/qos/bwup");
	$BWDOWN = query("/runtime/device/qos/bwdown");
	
	if( $UBICOM == 1 )
	{ 
		if( $BWUP == 0 || $BWUP == ""  )
		{
		$UPSTREAM = 102400;
		fwrite("a",$START, "echo qos system first detection use ".$UPSTREAM.".\n");
		}
		else
		{
		$UPSTREAM = $BWUP;
		fwrite("a",$START, "echo qos system detect upstream bandwidth ".$UPSTREAM.".\n");
		}
	}
	else
	{
		set("/runtime/device/qos/bwup","0");
		set("/runtime/device/qos/bwdown","");
	}
/*--------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
$PRIO_ALL=$UPSTREAM ;
$PRIO0_MAX=$PRIO_ALL * 90 / 100 ;
$PRIO1_MAX=$PRIO_ALL * 80 / 100 ;
$PRIO2_MAX=$PRIO_ALL * 75 / 100 ;
$PRIO3_MAX=$PRIO_ALL * 70 / 100 ;
$PRIO0_MIN=$PRIO_ALL * 70 / 100 ;
$PRIO1_MIN=$PRIO_ALL * 50 / 100 ;
$PRIO2_MIN=$PRIO_ALL * 40 / 100 ;
$PRIO3_MIN=$PRIO_ALL * 30 / 100 ;

fwrite("a",$START, $TC." qdisc add dev ".$WANDEV." handle 1: root htb default 3 \n");
fwrite("a",$START, $TC." class add dev ".$WANDEV." parent 1:0 classid 1:1 htb rate ".$PRIO_ALL.$K." burst 100k cburst 100k \n");
fwrite("a",$START, $TC." class add dev ".$WANDEV." parent 1:1 classid 1:40 htb prio 0  rate ".$PRIO0_MIN.$K." ceil ".$PRIO0_MAX.$K." burst 50k cburst 50k \n");
fwrite("a",$START, $TC." class add dev ".$WANDEV." parent 1:1 classid 1:41 htb prio 3  rate ".$PRIO1_MIN.$K." ceil ".$PRIO1_MAX.$K." burst 50k cburst 50k \n");
fwrite("a",$START, $TC." class add dev ".$WANDEV." parent 1:1 classid 1:42 htb prio 5  rate ".$PRIO2_MIN.$K." ceil ".$PRIO2_MAX.$K." \n");
fwrite("a",$START, $TC." class add dev ".$WANDEV." parent 1:1 classid 1:43 htb prio 7  rate ".$PRIO3_MIN.$K." ceil ".$PRIO3_MAX.$K." \n");
fwrite("a",$START, $TC." qdisc add dev ".$WANDEV." parent 1:40 handle 400: pfifo limit 50 \n");
fwrite("a",$START, $TC." qdisc add dev ".$WANDEV." parent 1:41 handle 410: pfifo limit 40 \n");
fwrite("a",$START, $TC." qdisc add dev ".$WANDEV." parent 1:42 handle 420: pfifo limit 30 \n");
fwrite("a",$START, $TC." qdisc add dev ".$WANDEV." parent 1:43 handle 430: pfifo limit 10 \n");

$PRIO_ALL=$DOWNSTREAM ;
$PRIO0_MAX=$PRIO_ALL * 90 / 100 ;
$PRIO1_MAX=$PRIO_ALL * 80 / 100 ;
$PRIO2_MAX=$PRIO_ALL * 75 / 100 ;
$PRIO3_MAX=$PRIO_ALL * 70 / 100 ;
$PRIO0_MIN=$PRIO_ALL * 70 / 100 ;
$PRIO1_MIN=$PRIO_ALL * 50 / 100 ;
$PRIO2_MIN=$PRIO_ALL * 40 / 100 ;
$PRIO3_MIN=$PRIO_ALL * 30 / 100 ;

fwrite("a",$START, $TC." qdisc add dev ".$LANDEV." handle 2: root htb default 3 \n");
fwrite("a",$START, $TC." class add dev ".$LANDEV." parent 2:0 classid 2:1 htb rate ".$PRIO_ALL.$K." burst 100k cburst 100k \n");
fwrite("a",$START, $TC." class add dev ".$LANDEV." parent 2:1 classid 2:40 htb prio 0  rate ".$PRIO0_MIN.$K." ceil ".$PRIO0_MAX.$K." burst 50k cburst 50k \n");
fwrite("a",$START, $TC." class add dev ".$LANDEV." parent 2:1 classid 2:41 htb prio 3  rate ".$PRIO1_MIN.$K." ceil ".$PRIO1_MAX.$K." burst 50k cburst 50k \n");
fwrite("a",$START, $TC." class add dev ".$LANDEV." parent 2:1 classid 2:42 htb prio 5  rate ".$PRIO2_MIN.$K." ceil ".$PRIO2_MAX.$K." \n");
fwrite("a",$START, $TC." class add dev ".$LANDEV." parent 2:1 classid 2:43 htb prio 7  rate ".$PRIO3_MIN.$K." ceil ".$PRIO3_MAX.$K." \n");
fwrite("a",$START, $TC." qdisc add dev ".$LANDEV." parent 2:40 handle 400: pfifo limit 50 \n");
fwrite("a",$START, $TC." qdisc add dev ".$LANDEV." parent 2:41 handle 410: pfifo limit 40 \n");
fwrite("a",$START, $TC." qdisc add dev ".$LANDEV." parent 2:42 handle 420: pfifo limit 30 \n");
fwrite("a",$START, $TC." qdisc add dev ".$LANDEV." parent 2:43 handle 430: pfifo limit 10 \n");
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
fwrite("a",$START, $TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x00 0xE0 flowid 1:40 \n");
fwrite("a",$START, $TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x00 0xE0 flowid 2:40 \n");
fwrite("a",$START, $TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x80 0xE0 flowid 1:41 \n");
fwrite("a",$START, $TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x80 0xE0 flowid 2:41 \n");
fwrite("a",$START, $TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x40 0xE0 flowid 1:42 \n");
fwrite("a",$START, $TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x40 0xE0 flowid 2:42 \n");
fwrite("a",$START, $TC." filter add dev ".$WANDEV." parent 1: protocol all prio 1 u32 match ip tos 0x20 0xE0 flowid 1:43 \n");
fwrite("a",$START, $TC." filter add dev ".$LANDEV." parent 2: protocol all prio 1 u32 match ip tos 0x20 0xE0 flowid 2:43 \n");
/*----------------------------------------------------------------------------*/

/*----------------------------------------------------------------------------*/
fwrite("a",$START, $TC." filter add dev ".$WANDEV." parent 1: protocol ip prio 100 u32  match ip src 0.0.0.0/0 flowid 1:43 \n");
fwrite("a",$START, $TC." filter add dev ".$LANDEV." parent 2: protocol ip prio 100 u32  match ip dst 0.0.0.0/0 flowid 2:43 \n");
/*----------------------------------------------------------------------------*/

/*-----------------------------------------------------*/
fwrite("a",$START, "echo ".$QOS_ENABLE." ".$UPSTREAM." ".$DOWNSTREAM." > /proc/fastnat/qos \n");
fwrite("a",$START, "echo -1 > /proc/fastnat/formqossupport \n");
/*-----------------------------------------------------*/

/*-----------------------------------------------------*/
$i = 0;
$cnt = query($WANPTR."/qos/mqos/count");
if ($cnt=="") $cnt = 0;
while ($i < $cnt)
{
    $i++;
	$SSS=$WANPTR."/qos/mqos/entry:".$i;

    $ENABLE     = query($SSS."enable");
    $STARTIP    = query($SSS."startip");
    $ENDIP      = query($SSS."endip");
    $STARTPORT  = query($SSS."startport");
    $ENDPORT    = query($SSS."endport");
    $PRIORITY   = query($SSS."priority");

	if ( $ENABLE == 1 )
	{
	if( $STARTIP == "*" ){ $STARTIP="0.0.0.0"; }
	if( $STARTPORT == "*"  ||  $ENDPORT == "*" ){ $STARTPORT=0;$ENDPORT=0; }

	fwrite("a",$START, "echo ".$STARTIP.":".$STARTPORT."-".$ENDPORT.":".$PRIORITY." > /proc/fastnat/formqossupport \n");
	}
}
/*-----------------------------------------------------*/


/*--------------------------------------------------------*/
	if( $UBICOM == "1" )
	{ 
		//sam_pan add 
		$MONITOR   = query("/runtime/device/qos/monitor");
	
		if( $BWUP == 0 || $BWUP == ""  )
		{
			if ( $MONITOR != "1")
			{
				fwrite("a",$START, "/etc/scripts/ubcom-run.sh restart\n");
			}
		}	
	}

	fwrite("a",$STOP, "/etc/scripts/ubcom-run.sh stop\n");
/*--------------------------------------------------------*/

fwrite("a",$START, "exit 0\n");
fwrite("a",$STOP,  "exit 0\n");
?>
