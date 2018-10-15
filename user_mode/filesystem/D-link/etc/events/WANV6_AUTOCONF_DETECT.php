<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

echo '#!/bin/sh\n';

if($ACT=="START") 
{
	echo 'echo AUTOCONF detect ... > /dev/console\n';
	//Try IO.GUEST and register next parse.
	/*
	echo 'cp /etc/scripts/ip-up /etc/ppp/.\n';
	echo 'cp /etc/scripts/ip-down /etc/ppp/.\n';
	echo 'cp /etc/scripts/ppp-status /etc/ppp/.\n';
	echo 'xmldbc -P /etc/events/option.discover.php -V INF='.$INF.' > /var/run/option.discover\n';
	echo 'pppd file /var/run/option.discover &\n';
	echo 'xmldbc -t ppp.dis.guest:10:"sh /etc/events/WAN_ppp_dis.sh WAN-1 DISCOVER"\n';
	echo 'event INFSVCS.'.$INF.'.UP add true\n';
	*/
	$infp = XNODE_getpathbytarget("", "inf", "uid", $INF, 0);
	$phyinf = query($infp."/phyinf");
	$devnam = PHYINF_getifname($phyinf);
	/* generate callback script */
	$hlp = "/var/servd/".$INF."-test-rdisc6.sh";
	fwrite(w, $hlp,
		"#!/bin/sh\n".
		"echo [$0]: [$IFNAME] [$MFLAG] [$OFLAG] > /dev/console\n".
		"phpsh /etc/services/INET/inet6_rdisc6_helper.php".
			' "IFNAME=$IFNAME"'.
			' "MFLAG=$MFLAG"'.
			' "OFLAG=$OFLAG"'.
			' "PREFIX=$PREFIX"'.
			' "PFXLEN=$PFXLEN"'.
			' "LLADDR=$LLADDR"'.
			' "RDNSS=$RDNSS"'.
			"\n");

	echo 'chmod +x '.$hlp.'\n';
	echo 'rdisc6 -c '.$hlp.' -q '.$devnam.' &\n';	
	echo 'xmldbc -t autoconf.dis.guest:10:"sh /etc/events/WANV6_AUTOCONF_DETECT.sh '.$INF.' CHECK"\n';
}
else if($ACT=="CHECK")
{
	$mode = query("/runtime/services/wandetect/autoconf/".$ACT);
	//$connected = query("/runtime/services/wandetect/ppp/".$ACT."/connected");
	//$authFail = query("/runtime/services/wandetect/ppp/".$ACT."/authFail");
	if($mode!="")
	{
		/*
		echo 'if [ -f /var/run/ppp-'.$ACT.'.pid ]; then\n';
		echo '	pid=`pfile -f /var/run/ppp-'.$ACT.'.pid`\n';
		echo '	[ "$pid" != "0" ] && kill $pid > /dev/console 2>&1\n';
		echo '	rm -rf /var/run/ppp-'.$ACT.'.pid\n';
		echo 'fi\n';
		*/
		echo 'killall rdisc6\n';
		set("/runtime/services/wandetect/wantype", $mode);
		set("/runtime/services/wandetect/desc", "Normal");
	}
	else
	{
		set("/runtime/services/wandetect/wantype", "unknown");
		set("/runtime/services/wandetect/desc", "No Response");
	}
}

echo 'exit 0\n';
?>
