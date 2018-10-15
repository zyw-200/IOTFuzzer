<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

function pppoptions($inf, $ifname, $profile, $default, $mode)
{
	anchor($profile);

	$optfile	= "/etc/ppp/options.".$inf;
	$mtu		= query("mtu");
	$mru		= query("mru");
	if ($mtu=="") $mtu = 1492;
	$automode	= get("s","tty/auto_config/mode");
	if($automode == "1")
	{
		$user	= query("/runtime/auto_config/username");
		$pass	= query("/runtime/auto_config/password");	
	}
	else
	{	
		$user	= get("s","username");
		$pass	= get("s","password");
	}
	$idle		= query("dialup/idletimeout");
	if ($mode=="") $mode = query("dialup/mode");
	$mppe		= query("mppe/enable");
	$static		= query("static");
	if ($static==1)	$ipaddr = query("ipaddr");
	else			$ipaddr = "";
	$over		= query("over");
	$auth_proto	= query("authproto");
	if($over=="tty")
	{
		$infp   = XNODE_getpathbytarget("", "inf", "uid", "LAN-1", 0);
		if (query($infp."/active")==1 && query($infp."/disable")!=1)
		{
			$inet   = query($infp."/inet");
			$inetp  = XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
			$lan1ip = query($inetp."/ipv4/ipaddr");
		}
		$infp   = XNODE_getpathbytarget("", "inf", "uid", "LAN-2", 0);
		if (query($infp."/active")==1 && query($infp."/disable")!=1)
		{
			$inet   = query($infp."/inet");
			$inetp  = XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
			$lan2ip = query($inetp."/ipv4/ipaddr");
		}

	}
	else if ($over=="eth")
	{
		/* Starspeed */
		if (query("pppoe/starspeed/enable") == 1)
		{
			$user = get("s", "/runtime/starspeed/username");
			$pass = get("s", "/runtime/starspeed/password");
			if (query("/runtime/starspeed/userformat") == "hex")
				$user_hex = 1;
		}
	}
	fwrite("w",$optfile, "noauth nodeflate nobsdcomp nodetach");
	if ($mppe==1)	fwrite("a",$optfile, "\n");
	else			fwrite("a",$optfile, " noccp\n");

	/* for debug !!! */
	//fwrite("a",$optfile, "debug dump logfd 1\n");

	/* convert mtu to number. */
	$mtu = $mtu + 1 - 1;

	/* static options */
	fwrite("a",$optfile, "lcp-echo-failure 3\n");
	fwrite("a",$optfile, "lcp-echo-interval 30\n");
	fwrite("a",$optfile, "lcp-echo-failure-2 14\n");
	fwrite("a",$optfile, "lcp-echo-interval-2 6\n");
	fwrite("a",$optfile, "lcp-timeout-1 10\n");
	fwrite("a",$optfile, "lcp-timeout-2 10\n");
	fwrite("a",$optfile, "ipcp-accept-remote ipcp-accept-local\n");
	fwrite("a",$optfile, "mtu ".$mtu."\n");
	if ($mru!="") fwrite("a",$optfile, "mru ".$mru."\n");
	fwrite("a",$optfile, "linkname ".$inf."\n");
	fwrite("a",$optfile, "ipparam ".$inf."\n");
	fwrite("a",$optfile, "usepeerdns\n");	/* always use peer's dns. */
	if ($default=="1") fwrite("a",$optfile, "defaultroute\n");

	/* set idle timeout */
	if ($mode == "ondemand" && $idle != "" && $idle > 0)
	{
		$idlesec = $idle * 60;
		fwrite("a",$optfile, "idle ".$idlesec."\n");
	}
	if ($user_hex == 1)				fwrite("a",$optfile, "user-hex\n");
	if ($user!="")					fwrite("a",$optfile, 'user "'.$user.'"\n');
	else
	{
		if ($over=="tty")			fwrite("a",$optfile, 'user guest\n');
	}
	if ($pass!="")					fwrite("a",$optfile, 'password "'.$pass.'"\n');
	else
	{
		if ($over=="tty")			fwrite("a",$optfile, 'password guest\n');
	}

	/* dialup mode */
	if ($mode=="ondemand")
	{
		fwrite("a",$optfile, "demand\nconnect true\nktune\n");
		/* pick a fake IP for WAN port. */
		if($over!="tty")
		{
			if ($ipaddr=="") $ipaddr = "10.112.112.".cut($inf, 1, "-");
		}
		else
			fwrite("a",$optfile, "defaultroute\n");
	}
	if ($mode!="manual")
	{
		fwrite("a",$optfile, "persist\nmaxfail 1\n");
	}

	/* Set local and remote IP */
	if ($ipaddr == "")
	{
		fwrite("a",$optfile, "noipdefault\n");
	}
	else
	{
		fwrite("a",$optfile, $ipaddr.":10.112.113.".cut($inf, 1, "-")."\n");
		if ($static==1) fwrite("a",$optfile, "ipcp-ignore-local\n");
	}

	if ($over=="eth")
	{
		$acname	= get(s, "pppoe/acname");
		$service= get(s, "pppoe/servicename");
		if ($mppe == 1)
			fwrite("a",$optfile, "refuse-eap\nrefuse-chap\nrefuse-mschap\nrequire-mppe\n");
		fwrite("a",$optfile, "kpppoe pppoe_device ".$ifname."\n");
		fwrite("a",$optfile, "pppoe_hostuniq\n");
		if ($acname!="")  fwrite("a",$optfile, "pppoe_ac_name \"".$acname."\"\n");
		if ($service!="") fwrite("a",$optfile, "pppoe_srv_name \"".$service."\"\n");
	}
	else if	($over=="pptp")
	{
		fwrite("a",$optfile, "pty_pptp pptp_server_ip ".query("pptp/server")."\n");
		fwrite("a",$optfile, "name \"".$user."\"\n");
		fwrite("a",$optfile, "refuse-eap\n");
		if ($mppe == 1) fwrite("a",$optfile, "refuse-chap\nrefuse-mschap\nrequire-mppe\n");
		fwrite("a",$optfile, "sync pptp_sync\n");
	}
	else if	($over=="l2tp")
	{
		fwrite("a",$optfile, "pty_l2tp l2tp_peer ".query("l2tp/server")."\n");
		fwrite("a",$optfile, "sync l2tp_sync\n");
	}
	else if($over=="tty")
	{
		if ($auth_proto != "")
		{
			/* Authentication protocol PAP only */
			if( $auth_proto == "PAP" )
			{
				fwrite("a", $optfile, "refuse-eap\n");
				fwrite("a", $optfile, "refuse-chap\n");
				fwrite("a", $optfile, "refuse-mschap\n");
				fwrite("a", $optfile, "refuse-mschap-v2\n");
			}
			/* Authentication protocol CHAP only */
			if( $auth_proto == "CHAP" )
			{
				fwrite("a", $optfile, "refuse-eap\n");
				fwrite("a", $optfile, "refuse-pap\n");
				fwrite("a", $optfile, "refuse-mschap\n");
				fwrite("a", $optfile, "refuse-mschap-v2\n");
			}
		}
		fwrite("a",$optfile, "tty_ppp3g ppp3g_chat /etc/ppp/chat.".$inf."\n");
		fwrite("a",$optfile, "modem\n");
		fwrite("a",$optfile, "crtscts\n");
		fwrite("a",$optfile, $ifname."\n");
		fwrite("a",$optfile, "115200\n");
		fwrite("a",$optfile, "novj\n");
		if ($lan1ip != "" || $lan2ip != "")
		{
			if($lan1ip != "") $lanip = $lan1ip;
			if($lan2ip != "") $lanip = $lanip.",".$lan2ip;
			fwrite("a",$optfile, "excluded_peer_ip ".$lanip."\n");
		}

	}
	return $optfile;
}

/* PPP IPv4 *****************************************************/
fwrite("a",$START, "# INFNAME = [".$_GLOBALS["INET_INFNAME"]."]\n");
fwrite("a",$STOP,  "# INFNAME = [".$_GLOBALS["INET_INFNAME"]."]\n");

/* These parameter should be valid. */
$inf	= $_GLOBALS["INET_INFNAME"];
$infp	= XNODE_getpathbytarget("", "inf", "uid", $inf, 0);
$phyinf	= query($infp."/phyinf");

/*+++, Get devname/devnum/vid/pid if usb 3G is found.*/
if (substr($phyinf, 0, 3)==TTY)
{
	$phyinfp= XNODE_getpathbytarget("", "phyinf", "uid", $phyinf, 0);
	$slot	= query($phyinfp."/slot");
	$ttyp	= XNODE_getpathbytarget("/runtime/tty", "entry", "slot", $slot, 0);
	if ($ttyp!="")
	{
		$devname = query($ttyp."/devname");
		$devnum  = query($ttyp."/devnum");
		$vid     = query($ttyp."/vid");
		$pid     = query($ttyp."/pid");
	}
	else
	{
		/* We should never reach here !!!! */
		fwrite("a",$_GLOBALS["START"], "exit 9\n");
		fwrite("a",$_GLOBALS["STOP"],  "exit 9\n");
	}
}
/*+++*/

$default= query($infp."/defaultroute");
$flets	= query($infp."/flets");
$inet	= query($infp."/inet");
$lower	= query($infp."/lowerlayer");
$inetp	= XNODE_getpathbytarget("/inet", "entry", "uid", $inet, 0);
$ifname	= PHYINF_getifname($phyinf);
$over   = query($inetp."/ppp4/over");

$dial = XNODE_get_var($inf.".DIALUP");
if ($dial=="") $dial = query($inetp."/ppp4/dialup/mode");

/* Add event to handle flets account. */
if ($flets!="1")
{
	/* When combo and AuthFail, ip-down won't executed
	   thus no one stops INET.$lower. It causes some
	   problem with "service WAN stop".                Builder 2010/01/29 */
	if ($lower!="")
	{
		fwrite(a,$START, 'event '.$inf.'.PPP.AUTHFAILED add "service INET.'.$lower.' stop; service WAN stop"\n');
	}
	else
	{
		fwrite(a,$START, 'event '.$inf.'.PPP.AUTHFAILED add true\n');
	}
}
else
{
	/* If the flets flags is set, we will try the 2 account.
	 * At PPP.AUTHFAILED, change the account to another and try again. */
	fwrite(a,$START, 'event '.$inf.'.PPP.AUTHFAILED add "service INET.'.$inf.' restart"\n');

	$user = query($inetp."/ppp4/username");
	if ($user == "guest@flets")
	{	/* Try West NTT (flets@flets/flets) */
		$user = "flets@flets";
		$pass = "flets";
	}
	else
	{	/* Try East NTT (guest@flest/guest) */
		$user = "guest@flets";
		$pass = "guest";
	}
	set($inetp."/ppp4/username", $user);
	set($inetp."/ppp4/password", $pass);
}

/* generate ppp3g chat file */
if ($over=="tty")
{
	/*To get tty entry with slot of USB-1 under runtime.*/
	if ($ttyp!="")
	{
		$autocf	= query($inetp."/ppp4/tty/auto_config/mode");
		if($autocf == "1")
		{
            $dialno = query("/runtime/auto_config/dialno");
            $apn    = query("/runtime/auto_config/apn");			
		}
		else
		{
			$dialno	= query($inetp."/ppp4/tty/dialno");
			$apn	= query($inetp."/ppp4/tty/apn");
		}
        /* If there is no SIM card exist, no need to try for dialing up. Leon Yen. */
        $simstatus = query("/runtime/auto_config/SIM/PIN1status");
        if ($simstatus!="" && $simstatus!="READY")
        {
            fwrite(a, $START,
            'echo [$0]: SIM is not ready!!\n'.
            'exit 1\n'
            );
        }
        /* Some 3G adapter has ability to fix connection type(auto/2G/3G) and it needs to
           finish before start to dial up, so a script is used here to block follwoing actions
           until the setting connect type process is completed. Leon Yen.
         */
        fwrite(a, $START,
            'if [ -f /etc/scripts/mod3gsettype.sh ]; then\n'.
            '   sh /etc/scripts/mod3gsettype.sh\n'.
            'fi\n'
            );
		fwrite(a, $START,
			'xmldbc -s '.$ttyp.'/apn "'.$apn.'"\n'.
			'xmldbc -s '.$ttyp.'/dialno "'.$dialno.'"\n'.
			'usb3gkit -o /etc/ppp/chat.'.$inf.' -v 0x'.$vid.' -p 0x'.$pid.' -d '.$devnum.'\n'.
			'# chatfile=[/etc/ppp/char'.$inf.']\n'
			);
		$ifname = $devname;
	}
}

/* NetSniper */
if ($over=="eth")
{
	/* Get NAT */
	$nat = query($infp."/nat");
	$natp = XNODE_getpathbytarget("/nat", "entry", "uid", $nat, 0);
	
	/* enable netsniper, must remove sw_tcpip module. Or else web access from lan will fail! */
	if (query($natp."/netsniper/enable") == 1)
	{
		fwrite("a",$START,"rmmod sw_tcpip\n");
		fwrite("a",$STOP,"insmod /lib/modules/sw_tcpip.ko\n");
	}
}

/* Adjust the dialup mode. If the dialup mode is on demand, PPPD must handle this.
 * If not, the INET.COMBO.XXX will handle the dialup mode. So better be manual mode here. */
if ($lower != "" && $dial != "ondemand") $dial = "manual";

/* generate option file */
$optfile = pppoptions($inf, $ifname, $inetp."/ppp4", $default, $dial);
fwrite("a",$START, "# optfile = [".$optfile."]\n");

/* Files */
$sfile		= "/var/run/ppp-".$inf.".status";
$pppd_pid	= "/var/run/ppp-".$inf.".pid";
$dialuppid	= "/var/run/ppp-".$inf."-dialup.pid";
$dialupsh	= "/var/run/ppp-".$inf."-dialup.sh";
$hangupsh	= "/var/run/ppp-".$inf."-hangup.sh";

/* Dialup/Hangup script ****************************/
fwrite(w, $dialupsh, "#!/bin/sh\n");
fwrite(w, $hangupsh, "#!/bin/sh\n");

fwrite(a, $hangupsh, '/etc/scripts/killpid.sh '.$pppd_pid.'\n');

fwrite(a, $dialupsh,
	'if [ -f '.$dialuppid.' ]; then\n'.
	'	echo [$0]: already dialing ...\n'.
	'	exit 0\n'.
	'fi\n'.
	'xmldbc -k redial.'.$inf.'\n'.
    'event DIALINIT \n'.
	'echo $$ > '.$dialuppid.'\n'.
	'pppd file '.$optfile.' > /dev/console\n'
	);
if ($dial!="manual") fwrite(a, $dialupsh, 'xmldbc -t redial.'.$inf.':5:'.$dialupsh.'\n');
fwrite(a, $dialupsh, 'rm -f '.$dialuppid.'\nexit 0\n');

/* Start script ************************************/
/* Prepare ip-up ip-down & events */
if ($dial=="ondemand")	$dialcmd = 'ping 10.112.113.'.cut($inf, 1, '-');
else					$dialcmd = $dialupsh.' &';

/* Wait for the previous running PPPD to stop. */
fwrite(a, $START,
	'while [ -f '.$sfile.' ]; do\n'.
	'	echo "[$0]: '.$sfile.' exist !!" > /dev/console\n'.
	'	sleep 1\n'.
	'done\n'
	);

fwrite(a, $START,
	'cp /etc/scripts/ip-up /etc/ppp/.\n'.
	'cp /etc/scripts/ip-down /etc/ppp/.\n'.
	'cp /etc/scripts/ppp-status /etc/ppp/.\n'.
	'chmod +x '.$dialupsh.' '.$hangupsh.'\n'.
	'event '.$inf.'.PPP.DIALUP add "'.$dialcmd.'"\n'.
	'event '.$inf.'.PPP.HANGUP add "'.$hangupsh.'"\n'
	);


/* If the dial mode is manual, DO NOT run the loop script. */
if ($lower!="" || $dial!="manual") fwrite(a, $START, $dialupsh.' &\n');

/* Stop script *************************************/
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
	'	echo "[$0]: '.$sfile.' still exist, kill again and wait 2 second ..." > /dev/console\n'.
	'	/etc/scripts/killpid.sh '.$dialuppid.'\n'.
	'	/etc/scripts/killpid.sh '.$pppd_pid.'\n'.
	'	sleep 2\n'.
	'done\n'
	);

/* If PPP is in 'on demand' mode and the interface is never 'UP',
 * ip_down will never been executed. This should have no problem in
 * the normal condition, but in COMBO mode, the lowerlayer will never
 * been trigger to stop. */
if ($lower!="")
{
	fwrite(a, $STOP,
		'if [ "$STATUS" = "on demand" -o "$STATUS" = "connecting" ]; then\n'.
		'	service INET.'.$lower.' stop\n'.
		'fi\n'
		);
}
?>
