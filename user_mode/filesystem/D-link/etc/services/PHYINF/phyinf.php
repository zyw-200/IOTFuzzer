<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function startcmd($cmd)			{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)			{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");}
function pifsetup_error($errno)	{startcmd("exit ".$errno); stopcmd( "exit ".$errno);}

function phyinf_setmedia($layout, $ifname, $media)
{
	/* Only support for WAN port now. */
	if		($layout=="1W1L" && $ifname=="ETH-2") $port = 4;
	else if	($layout=="1W2L" && $ifname=="ETH-3") $port = 4;
	else return;

	if ($media=="") $media="AUTO";
	startcmd("slinktype -i ".$port." -d ".$media);
	stopcmd( "slinktype -i ".$port." -d AUTO");

	startcmd("sleep 1");
}

function phyinf_setipv6($layout, $ifname)
{
	if ($layout=="1W2L")
	{
		if		($ifname=="ETH-1") $phy="br0";
		else if ($ifname=="ETH-2") $phy="br1";
		else if ($ifname=="ETH-3") $phy="eth3";
	}
	else
	{
		if		($ifname=="ETH-1") $phy="br0";
		else if	($ifname=="ETH-2") $phy="eth3";
	}

	if ($phy!="")
	{
		startcmd("echo 0 > /proc/sys/net/ipv6/conf/".$phy."/disable_ipv6");
		stopcmd( "echo 1 > /proc/sys/net/ipv6/conf/".$phy."/disable_ipv6");
	}
}

function phyinf_setup($ifname)
{
	$phyinf	= XNODE_getpathbytarget("", "phyinf", "uid", $ifname, 0);
	if ($phyinf=="") { pifsetup_error("9"); return; }
	if (query($phyinf."/active")!="1") { pifsetup_error("8"); return; }

	/* Get layout mode */
	$layout = query("/runtime/device/layout");
	if		($layout=="bridge") $mode = "1BRIDGE";
	else if	($layout=="router") $mode = query("/runtime/device/router/mode");
	else { pifsetup_error("9"); return; }
	if ($mode=="") $mode = "1W2L";

	/* Set media */
	$media = query($phyinf."/media/linktype");
	phyinf_setmedia($mode, $ifname, $media);

	/* Set IPv6 */
	if (isfile("/proc/net/if_inet6")==1)
	{
		/**********************************************************************************
		 * only enable ipv6 function at br0(LAN) and eth3(WAN), other disable by default
		 *********************************************************************************/
		phyinf_setipv6($mode, $ifname);
	}

	/* Set the MAC address */
	$stsp = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $ifname, 0);
	if ($stsp=="")
	{
		/* The LAYOUT service should be start before PHYINF.XXX.
		 * We should never reach here !! */
		fwrite("w", "/dev/console", "PHYINF: The LAYOUT service should be start before PHYINF !!!\n");
	}
	else
	{
		$mac = PHYINF_gettargetmacaddr($mode, $ifname);
		$curr= tolower(query($stsp."/macaddr"));
		if(PHYINF_validmacaddr($mac)!=1 || PHYINF_validmacaddr($curr)!=1)
		{
			fwrite("w", "/dev/console", "===========================MFC MAC ERROR==================================\n");
			fwrite("w", "/dev/console", "PHYINF.".$ifname.": mac[".$mac."] is error,please check mfc init!!!\n");
			fwrite("w", "/dev/console", "====================================================================\n");
			return;
		}
		if ($mac != $curr)
		{
			fwrite("w", "/dev/console", "PHYINF.".$ifname.": cfg[".$mac."] curr[".$curr."], restart the device !!!\n");
			//startcmd('xmldbc -t "restart:3:/etc/init0.d/rcS"');
			startcmd('event REBOOT');
			/*
			$if_name = query($stsp."/name");
			startcmd('ifconfig '.$if_name.' down');
			startcmd('ifconfig '.$if_name.' hw ether '.$mac);
			startcmd('ifconfig '.$if_name.' up');
			*/
		}
	}
}
?>
