HTTP/1.1 200 OK
Content-Type: text/xml

<?
echo '<?xml version="1.0"?>\n';
echo '<ddns4>\n';

include "/htdocs/phplib/xnode.php";
if (query("/runtime/device/router/mode")=="3G")	$inf = "WAN-3";
else						$inf = "WAN-1";

if ($_POST["act"] == "update")
{
	event("DDNS4.".$inf.".UPDATE");
	echo "OK";
}
else if ($_POST["act"] == "getreport")
{
	$p = XNODE_getpathbytarget("/runtime", "inf", "uid", $inf, 0);
	if ($p != "")
	{
		echo dump(2, $p."/ddns4");
	}
	else
	{
		echo "<valid>0</valid>\n";
	}
}

echo '</ddns4>\n';
?>
