<?
/* We use VID 2 for WAN port, VID 1 for LAN ports.
 * by David Hsieh <david_hsieh@alphanetworks.com> */
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/phyinf.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");}
function error($errno)	{startcmd("exit ".$errno); stopcmd("exit ".$errno);}

function layout_bridge()
{
	SHELL_info($START, "LAYOUT: Start bridge layout ...");

	/* Start .......................................................................... */
	/* Config RTL8367 as bridge mode layout. */
	//startcmd("rtlioc bridgemode");

	/* Using WAN MAC address during bridge mode. */
	$mac = PHYINF_getmacsetting("BRIDGE-1");
	//startcmd("vconfig add eth2 1; ip link set eth2.1 addr ".$mac."; ip link set eth2.1 up");
	startcmd("ip link set eth3 addr ".$mac."; ip link set eth3 up");

	/* Create bridge interface. */
	startcmd("brctl addbr br0; brctl stp br0 off; brctl setfd br0 0");
	startcmd("brctl addif br0 eth2");
	startcmd("brctl addif br0 eth3");
	startcmd("brctl addif br0 ra0");
	startcmd("brctl addif br0 rai0");
	startcmd("ip link set br0 up");

	/* Setup the runtime nodes. */
	PHYINF_setup("ETH-1", "eth", "br0");

	/* Done */
	startcmd("xmldbc -s /runtime/device/layout bridge");
	startcmd("usockc /var/gpio_ctrl BRIDGE");
	startcmd("service ENLAN start");
	startcmd("service PHYINF.ETH-1 alias PHYINF.BRIDGE-1");
	startcmd("service PHYINF.ETH-1 start");

	/* Stop ........................................................................... */
	SHELL_info($STOP, "LAYOUT: Stop bridge layout ...");
	stopcmd("service PHYINF.ETH-1 stop");
	stopcmd("service PHYINF.BRIDGE-1 delete");
	stopcmd('xmldbc -s /runtime/device/layout ""');
	stopcmd("/etc/scripts/delpathbytarget.sh /runtime phyinf uid ETH-1");
	stopcmd("brctl delif br0 rai0");
	stopcmd("brctl delif br0 ra0");
	stopcmd("brctl delif br0 eth3");
	stopcmd("brctl delif br0 eth2");
	stopcmd("ip link set eth3 down");
	stopcmd("brctl delbr br0");
	//stopcmd("rtlioc initvlan");
	return 0;
}

function layout_router($mode)
{
	SHELL_info($START, "LAYOUT: Start router layout ...");

	/* Start .......................................................................... */
	/* Config RTL8367 as router mode layout. (1 WAN + 4 LAN) */
	//startcmd("rtlioc routermode");

	/* Setup MAC address */
	$wanmac = PHYINF_getmacsetting("WAN-1");
	$lanmac = PHYINF_getmacsetting("LAN-1");
	/*we have low memory ,so we queue less packet*/
	startcmd("ip link set eth2 addr ".$lanmac."; ip link set eth2 txqueuelen 200;ip link set eth2 up");
	startcmd("ip link set eth3 addr ".$wanmac."; ip link set eth3 txqueuelen 200;ip link set eth3 up");

	/* Create bridge interface. */
	startcmd("brctl addbr br0; brctl stp br0 off; brctl setfd br0 0");
	startcmd("brctl addif br0 eth2");
	//startcmd("brctl addif br0 ra0");
	//startcmd("brctl addif br0 ra1");
	startcmd("ip link set br0 up");
	if ($mode=="1W2L")
	{
		startcmd("brctl addbr br1; brctl stp br1 off; brctl setfd br1 0");
		startcmd("ip link set br1 up");
	}

	/* Setup the runtime nodes. */
	if ($mode=="1W1L")
	{
		PHYINF_setup("ETH-1", "eth", "br0");
		PHYINF_setup("ETH-2", "eth", "eth3");
		/* set Service Alias */
		startcmd('service PHYINF.ETH-1 alias PHYINF.LAN-1');
		startcmd('service PHYINF.ETH-2 alias PHYINF.WAN-1');
		/* WAN: set extension nodes for linkstatus */
		$path = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "ETH-2", 0);
		startcmd('xmldbc -x '.$path.'/linkstatus "get:psts -i 4"');
	}
	else if ($mode=="1W2L")
	{
		PHYINF_setup("ETH-1", "eth", "br0");
		PHYINF_setup("ETH-2", "eth", "br1");
		PHYINF_setup("ETH-3", "eth", "eth3");
		/* set Service Alias */
		startcmd('service PHYINF.ETH-1 alias PHYINF.LAN-1');
		startcmd('service PHYINF.ETH-2 alias PHYINF.LAN-2');
		startcmd('service PHYINF.ETH-3 alias PHYINF.WAN-1');
		/* WAN: set extension nodes for linkstatus */
		$path = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "ETH-3", 0);
		startcmd('xmldbc -x '.$path.'/linkstatus "get:psts -i 4"');
	}

	/* LAN: set extension nodes for linkstatus */
	$path = XNODE_getpathbytarget("/runtime", "phyinf", "uid", "ETH-1", 0);
	startcmd('xmldbc -x '.$path.'/linkstatus:1 "get:psts -i 3"');
	startcmd('xmldbc -x '.$path.'/linkstatus:2 "get:psts -i 2"');
	startcmd('xmldbc -x '.$path.'/linkstatus:3 "get:psts -i 1"');
	startcmd('xmldbc -x '.$path.'/linkstatus:4 "get:psts -i 0"');

	/* Done */
	startcmd("xmldbc -s /runtime/device/layout router");
	startcmd("xmldbc -s /runtime/device/router/mode ".$mode);
	startcmd("usockc /var/gpio_ctrl ROUTER");
	startcmd("service PHYINF.ETH-1 start");
	startcmd("service PHYINF.ETH-2 start");
	if ($mode=="1W2L") startcmd("service PHYINF.ETH-3 start");

	/* Stop ........................................................................... */
	SHELL_info($STOP, "LAYOUT: Stop router layout ...");
	if ($mode=="1W2L")
	{
		stopcmd("service PHYINF.ETH-3 stop");
		stopcmd('service PHYINF.LAN-2 delete');
	}
	stopcmd("service PHYINF.ETH-2 stop");
	stopcmd("service PHYINF.ETH-1 stop");
	stopcmd('service PHYINF.WAN-1 delete');
	stopcmd('service PHYINF.LAN-1 delete');
	stopcmd('xmldbc -s /runtime/device/layout ""');
	stopcmd('/etc/scripts/delpathbytarget.sh /runtime phyinf uid ETH-1');
	stopcmd('/etc/scripts/delpathbytarget.sh /runtime phyinf uid ETH-2');
	stopcmd('/etc/scripts/delpathbytarget.sh /runtime phyinf uid ETH-3');
	//stopcmd('brctl delif br0 ra0');
	stopcmd('brctl delif br0 eth2');
	//stopcmd('brctl delif br1 ra1');
	stopcmd('ip link set eth2 down');
	stopcmd('ip link set eth3 down');
	stopcmd('brctl delbr br0; brctl delbr br1');
	//stopcmd('vconfig rem eth2.1; vconfig rem eth2.2');
	//stopcmd('rtlioc initvlan');
	return 0;
}

/* everything starts from here !! */
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");

$ret = 9;
$layout	= query("/device/layout");

startcmd("ifconfig lo up");
stopcmd("ifconfig lo down");

if ($layout=="router")
{
	/* only 1W1L & 1W2L supported for router mode. */
	$mode = query("/device/router/mode"); if ($mode!="1W1L") $mode = "1W2L";
	$ret = layout_router($mode);
}
else if ($layout=="bridge")
{
	$ret = layout_bridge();
}
//start lan up
startcmd("sphypower -p lan -d up");
startcmd("sphypower -p wan -d up");
error($ret);

?>
