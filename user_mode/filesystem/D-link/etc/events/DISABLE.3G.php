<?
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";

$infp = XNODE_getpathbytarget("", "inf", "uid", "WAN-3", 0);
if ($infp =="") exit;
$inet = query($infp."/inet");
if ($inet == "") exit;
/*modify mode*/
$stsp = XNODE_getpathbytarget("", "inet/entry", "uid", $inet, 0);
if($stsp == "")	exit; 
set($stsp."/ppp4/dialup/mode",	"manual");

?>
