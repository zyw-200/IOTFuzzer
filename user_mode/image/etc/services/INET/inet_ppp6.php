<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function pppoptions($inf, $ifname, $profile, $default, $mode , $addrtype)
{
	anchor($profile);

	$optfile     = "/etc/ppp/options.".$inf;
	$mtu         = query("mtu");
	$mru         = query("mru");
	if($mtu=="") $mtu = 1492;
	$user        = get("s", "username");
	$pass        = get("s", "password");
	$idle        = query("dialup/idletimeout");
	if($mode=="")  $mode = query("dialup/mode");
	$static      = query("static");
	//if($static == "1") $ipaddr = query("ipaddr");
	//else                $ipaddr = "";
	$ipaddr = "";
    
	fwrite("w",$optfile, "noauth nodeflate nobsdcomp nodetach");
	fwrite("a",$optfile, " noccp\n");
    
	/*for debug !!! */
	/*fwrite("a",$optfile, "debug dump logfd 1\n");*/

	/* convert mtu to number */
	$mtu = $mtu + 1 -1;

	/* static options */
	fwrite("a",$optfile, "lcp-echo-failure 3\n");
	fwrite("a",$optfile, "lcp-echo-interval 30\n");
	fwrite("a",$optfile, "lcp-echo-failure-2 14\n");
	fwrite("a",$optfile, "lcp-echo-interval-2 6\n");
	fwrite("a",$optfile, "lcp-timeout-1 10\n");
	fwrite("a",$optfile, "lcp-timeout-2 10\n");
	if($addrtype == "ppp10")
	{
   		fwrite("a",$optfile, "ipcp-accept-remote ipcp-accept-local +ipv6 ipv6cp-accept-local\n"); 
	}
	else
	{
    		fwrite("a",$optfile, "-ip +ipv6 ipv6cp-accept-local\n");
	}
	//fwrite("a",$optfile, "+ipv6 ipv6cp-accept-local ipv6cp-use-ipaddr\n");
	fwrite("a",$optfile, "mtu ".$mtu."\n");
	if($mru!="")   fwrite("a",$optfile, "mru ".$mru."\n");
	fwrite("a",$optfile, "linkname ".$inf."\n");
	fwrite("a",$optfile, "ipparam ".$inf."\n");
    
	fwrite("a",$optfile, "usepeerdns\n");  /* always use peer's dns. */
	if($default=="1")    fwrite("a",$optfile, "defaultroute\n");     

	/* set idle timeout */
	if($mode == "ondemand" && $idle != "" && $idle > 0)
	{
		$idlesec = $idle * 60;
		fwrite("a",$optfile, "idle ".$idlesec."\n");
	}
    
	if($user!="")       fwrite("a",$optfile, 'user "'.$user.'"\n');
	if($pass!="")       fwrite("a",$optfile, 'password "'.$pass.'"\n');
    
	/* dialup mode */
	if($mode=="ondemand")
	{
		fwrite("a",$optfile, "demand\nconnect true\nktune\n");
		/* pick a fake IP for WAN port. */
		if($ipaddr=="") $ipaddr = "10.112.112.".cut($inf, 1, "-");
	}
    
	/* set local and remote IP */
	if($ipaddr == "")
	{
		fwrite("a",$optfile, "noipdefault\n");
	}
	else
	{
		fwrite("a",$optfile, $ipaddr.":10.112.113.".cut($inf, 1, "-")."\n");
		if($static=="1") fwrite("a",$optfile, "ipcp-ignore-local\n");
	}
   
	$acname  = get(s, "pppoe/acname");
	$service = get(s, "pppoe/servicename");
	fwrite("a",$optfile, "kpppoe pppoe_device ".$ifname."\n");
	fwrite("a",$optfile, "pppoe_hostuniq\n");
	if($acname!="")   fwrite("a",$optfile, "pppoe_ac_name \"".$acname."\"\n");
	if($service!="")  fwrite("a",$optfile, "pppoe_srv_name \"".$service."\"\n");

	return $optfile;
        
}

/* PPP IPv6 *****************************************************/
fwrite("a",$START, "# INFNAME = [".$_GLOBALS["INET_INFNAME"]."]\n");
fwrite("a",$STOP,  "# INFNAME = [".$_GLOBALS["INET_INFNAME"]."]\n");

/* These parameter should be valid. */
$inf	= $INET_INFNAME;
$infp	= XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
$phyinf	= query($infp."/phyinf");
$inet	= query($infp."/inet");
$inetp	= XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$ifname	= PHYINF_getifname($phyinf);
$default= query($infp."/defaultroute");
$child  = query($infp."/child");
$addrtype = query($inetp."/addrtype");
$dial   = XNODE_get_var($inf.".DIALUP");
if ($dial=="") $dial = query($inetp."/ppp6/dialup/mode"); 

fwrite("a",$START, 'event '.$inf.'.PPP.AUTHFAILED add true\n');

/* generate option file */
$optfile = pppoptions($inf, $ifname, $inetp."/ppp6", $default, $dial, $addrtype);
fwrite("a",$START, "# optfile = [".$optfile."]\n");


/* Files */
$sfile      = "/var/run/ppp-".$inf.".status";
$pppd_pid   = "/var/run/ppp-".$inf.".pid";
$dialuppid  = "/var/run/ppp-".$inf."-dialup.pid";
$dialupsh   = "/var/run/ppp-".$inf."-dialup.sh";
$hangupsh   = "/var/run/ppp-".$inf."-hangup.sh";

/* Dialup/Hangup script ******************************/
fwrite(w, $dialupsh, "#!/bin/sh\n");
fwrite(w, $hangupsh, "#!/bin/sh\n");

fwrite(a, $hangupsh, '/etc/scripts/killpid.sh '.$pppd_pid.'\n');

fwrite(a, $dialupsh,
       'if [ -f '.$dialuppid.' ]; then\n'.
       '    echo [$0]: already dialing ...\n'.
       '    exit 0\n'.
       'fi\n'.
       'xmldbc -k redial.'.$inf.'\n'.
       'event DIALINIT \n'.
       'echo $$ > '.$dialuppid.'\n'.
       'pppd file '.$optfile.' > /dev/console\n'
       );

if($dial!="manual")     fwrite(a, $dialupsh, 'xmldbc -t redial.'.$inf.':5:'.$dialupsh.'\n');
fwrite(a, $dialupsh, 'rm -f '.$dialuppid.'\nexit 0\n');

/* Start script ***************************************/
/* Prepare ip-up ip-down & events */
if($ondemand == "1")  $dialcmd = 'ping 10.112.113.'.cut($inf, 1, '-');
else                  $dialcmd = $dialupsh.' &';

/* Wait for the previous running PPPD to stop */
fwrite(a, $START,
      'while [ -f '.$sfile.' ]; do\n'.
      '   echo "[$0]: '.$sfile.' exist !!" > /dev/console\n'.
      '   sleep 1\n'.
      'done\n'
      );

fwrite(a, $START,
      'cp /etc/scripts/ip-up /etc/ppp/.\n'.
      'cp /etc/scripts/ip-down /etc/ppp/.\n'.
      'cp /etc/scripts/ppp-status /etc/ppp/.\n'.
      'cp /etc/scripts/ipv6-up /etc/ppp/.\n'.
      'cp /etc/scripts/ipv6-down /etc/ppp/.\n'.
      'chmod +x '.$dialupsh.' '.$hangupsh.'\n'.
      'event '.$inf.'.PPP.DIALUP add "'.$dialcmd.'"\n'.
      'event '.$inf.'.PPP.HANGUP add "'.$hangupsh.'"\n'
      );

/* If the dial mode is manual, DO NOT run the loop script. */
if($dial != "manual") fwrite(a, $START, $dialupsh.' &\n');

/* Stop script ****************************************/
fwrite(a, $STOP,
      'xmldbc -k redial.'.$inf.'\n'.
      'event '.$inf.'.PPP.DIALUP add true\n'.
      'event '.$inf.'.PPP.HANGUP add true\n'.
      'rm -f '.$dialupsh.' '.$hangupsh.'\n'.
      'STATUS=`cat '.$sfile.'`\n'.
      '/etc/scripts/killpid.sh '.$dialuppid.'\n'.
      '/etc/scripts/killpid.sh '.$pppd_pid.'\n'.
      'sleep 2\n'.
      'while [ -f '.$sfile.' ]; do\n'.
      '   echo "[$0]: '.$sfile.' still exist, kill again and wait 2 second ..." > /dev/console\n'.
      '   /etc/scripts/killpid.sh '.$dialuppid.'\n'.
      '   /etc/scripts/killpid.sh '.$pppd_pid.'\n'.
      '   sleep 2\n'.
      'done\n'
      );
?>
