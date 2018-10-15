<module>
	<service><?=$GETCFG_SVC?></service>
	<device>
		<account>
<?
$cnt = query("/device/account/count");
if ($cnt=="") $cnt=0;
echo "\t\t\t<seqno>".query("/device/account/seqno")."</seqno>\n";
echo "\t\t\t<max>".query("/device/account/max")."</max>\n";
echo "\t\t\t<count>".$cnt."</count>\n";
foreach("/device/account/entry")
{
	if ($InDeX > $cnt) break;
	if (query("password")=="") $pwd = ""; else $pwd = "==OoXxGgYy==";
	echo "\t\t\t<entry>\n";
	echo "\t\t\t\t<name>".		get("x","name").	"</name>\n";
	echo "\t\t\t\t<password>".	$pwd.				"</password>\n";
	echo "\t\t\t\t<group>".		get("x", "group").	"</group>\n";
	echo "\t\t\t\t<description>".get("x","description")."</description>\n";
	echo "\t\t\t</entry>\n";
}
?>		</account>
		<session>
<?
	echo dump(3, "/device/session");
?>		</session>
	</device>
</module>
