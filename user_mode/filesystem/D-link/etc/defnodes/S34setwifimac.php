<? 
include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/mac.php";

//set wlanmac and wlanmac2 to db
$wanmac = query("/runtime/devdata/wanmac");
$lanmac = query("/runtime/devdata/lanmac");



del("/runtime/devdata/wlanmac");
del("/runtime/devdata/wlanmac2");

set("/runtime/devdata/wlanmac", $lanmac);	//5 Ghz
set("/runtime/devdata/wlanmac2", $wanmac);	//2.4 Ghz

//TRACE_error("$wlanmac=".query("/runtime/devdata/wlanmac"));
//TRACE_error("$wlanmac2=".query("/runtime/devdata/wlanmac2"));

?>
