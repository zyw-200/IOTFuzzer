<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

fwrite("w", $START, "#!/bin/sh\n");
$conf = "/var/servd/DNS.conf";

$infdncmd = "";
/* Get host domain name */
$enhdn = query("/device/hostdomainname/enable");
$hdn = query("/device/hostdomainname/name");
if ($enhdn != "" && $enhdn != "0" && $hdn != "")
{
	/* There is an issue about this.
	   If we name the same name on several interfaces,
	   the dnsmasq will return the first match interface but not the specific interface (input interface).
	   For this, we should seperate  different interfaces to use individual dnsmasq daemon.
	   By Enos. 2010/07/19  */
	$i = 1;
	while ($i > 0)
	{
		/* get LAN path */
		$lan = "LAN-".$i;
		$linfp = XNODE_getpathbytarget("", "inf", "uid", $lan, 0);
		$lstsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $lan, 0);
		if ($lstsp=="" || $linfp=="") { $i=0; break; }
		/* Get phyinf */
		$laninf = PHYINF_getruntimeifname($lan);
		$infdncmd = $infdncmd." --interface-name=".$hdn.",".$laninf;
		$i++;
	}
}

fwrite("a", $START,
	'killall dnsmasq\n'.
	'xmldbc -P /etc/services/DNS/dnscfg.php -V CONF='.$conf.'\n'.
	'if [ -f '.$conf.' ]; then\n'.
	'   dnsmasq -C '.$conf.' '.$infdncmd.'\n'.
	'else\n'.
	'   echo "[$0]: no config file!"\n'.
	'   exit 9\n'.
	'fi\n'.
	'exit 0\n'
	);

/* stop the dnsmaq */
fwrite("w", $STOP,
	'#!/bin/sh\n'.
	'killall dnsmasq\n'.
	'/etc/scripts/dns-helper.sh flush\n'.
	'exit 0\n'
	);
?>
