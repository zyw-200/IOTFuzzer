HTTP/1.1 200 OK
Content-Type:test/plain
Content-Disposition:attachment;filename="log.txt"

<?
echo "[System]\n";
echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
foreach("/runtime/log/sysact/entry")
{
        $time = get("TIME.ASCTIME", "/runtime/log/sysact/entry:".$InDeX."/time");
        $msg = get("x", "/runtime/log/sysact/entry:".$InDeX."/message");
	echo "[Time]".$time;
	echo "[Message:".$InDeX."]".$msg."\n";
	echo "--------------------------------------------------------------------------------------------\n";
}
echo "\n[Firewall & Security]\n";
echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
foreach("/runtime/log/attack/entry")
{
        $time = get("TIME.ASCTIME", "/runtime/log/attack/entry:".$InDeX."/time");
        $msg = get("x", "/runtime/log/attack/entry:".$InDeX."/message");
	echo "[Time]".$time;
        echo "[Message:".$InDeX."]".$msg."\n";
	echo "--------------------------------------------------------------------------------------------\n";
}
echo "\n[Router Status]\n";
echo "+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++\n";
foreach("/runtime/log/drop/entry")
{
        $time = get("TIME.ASCTIME", "/runtime/log/drop/entry:".$InDeX."/time");
        $msg = get("x", "/runtime/log/drop/entry:".$InDeX."/message");
	echo "[Time]".$time;
        echo "[Message:".$InDeX."]".$msg."\n";
	echo "--------------------------------------------------------------------------------------------\n";
}
?>
