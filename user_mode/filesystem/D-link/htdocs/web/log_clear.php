<?
if($_POST["act"]  == "clear")
{
	$count = query("/runtime/log/".$_POST["logtype"]."/entry#");
	while($count >0)
	{
		del("/runtime/log/".$_POST["logtype"]."/entry");
		$count--;
	}
	$time = query("/runtime/device/uptime");
	set("/runtime/log/sysact/entry:1/time", $time);
	set("/runtime/log/sysact/entry:1/message", "Log cleared by user");
}
include "/htdocs/web/getcfg.php";
?>
