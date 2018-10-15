<?
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
include "/htdocs/phplib/trace.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"] = $result;
	$_GLOBALS["FATLADY_node"]   = $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

set_result("FAILED","","");
$rlt = "0";

$cnt = query($FATLADY_prefix."/device/account/count");
TRACE_debug("FATLADY: DEVICE.ACCOUNT got ".$cnt." accounts");
$i = 0;
while ($i < $cnt)
{
	$i++;
	$name	= query($FATLADY_prefix."/device/account/entry:".$i."/name");
	$passwd	= query($FATLADY_prefix."/device/account/entry:".$i."/password");
	TRACE_debug("FATLADY: account[".$i."]: name=".$name.",passwd=".$passwd);
	if ($name == "")
	{
		set_result("FAILED", $FATLADY_prefix."/device/account/entry:".$i."/password", i18n("Login Name cannot be empty."));
		$rlt = "-1";
		break;
	}
	$p = "/device/account/entry:".$i;
	if (query($FATLADY_prefix.$p."/confirmcurrentpassword/enable")==1)
	{
		if (query($FATLADY_prefix.$p."/confirmcurrentpassword/password")!=query($p."/password"))
		{
			set_result("FAILED", $FATLADY_prefix.$p."/confirmcurrentpassword/password", i18n("The current password doesn't match."));
			$rlt = "-1";
		}
		break;
	}
}

if ($rlt=="0")
{
	set($FATLADY_prefix."/valid", "1");
	set_result("OK", "", "");
}
?>
