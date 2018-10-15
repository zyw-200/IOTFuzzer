<? /* Maker :sam_pan Date: 2010/3/22 03:35 */

include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";

function startcmd($cmd)	{fwrite(a,$_GLOBALS["START"], $cmd."\n");}
function stopcmd($cmd)	{fwrite(a,$_GLOBALS["STOP"], $cmd."\n");} 

fwrite(w,$_GLOBALS["START"], "");
fwrite(w,$_GLOBALS["STOP"], "");
/*setup netbios and llmnr*/
function netbios_setup($name)
{
	$infp = XNODE_getpathbytarget("", "inf", "uid", $name, 0);
	$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);
	
	if ($infp=="" || $stsp=="")
	{
		SHELL_info($_GLOBALS["START"], "infsvcs_setup: (".$name.") not exist.");
		SHELL_info($_GLOBALS["STOP"],  "infsvcs_setup: (".$name.") not exist.");
		return;
	}
	$addrtype = query($stsp."/inet/addrtype");
	$ipaddr = query($stsp."/inet/ipv4/ipaddr");
	$devnam = query($stsp."/devnam");
	$hostname = query("device/hostname");
	
	if ( $ipaddr == "" || $devnam == "")
	{
		return;	
	}
	if($addrtype=="ipv4" || $addrtype=="ipv6")
	{
		startcmd("netbios -i ".$devnam." -r ".$hostname." &\n");
		startcmd("llmnresp -i ".$devnam." -r ".$hostname." &\n");
		stopcmd("killall netbios");
		stopcmd("killall llmnresp");
	}
}

?>