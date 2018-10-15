<?
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
include "/htdocs/phplib/inet.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"]  = $result;
	$_GLOBALS["FATLADY_node"]    = $node;
	$_GLOBALS["FATLADY_message"] = $message;
}

function check_remote($entry)
{
	$port = query($entry."/inf/web");
	if ($port != "")
	{
		if (isdigit($port)!="1")
		{
			set_result("FAILED", $entry."/inf/web", i18n("Invalid port number"));
			return 0;
		}
		if ($port<1 || $port>65535)
		{
			set_result("FAILED", $entry."/inf/web", i18n("Invalid port range"));
			return 0;
		}
	}

	$host = query($entry."/inf/weballow/hostv4ip");
	if ($host != "")
	{
		if (INET_validv4addr($host)!="1")
		{
			set_result("FAILED", $entry."/inf/weballow/hostv4ip", i18n("Invalid host IP address"));
			return 0;
		}
	}

	set_result("OK", "", "");
	return 1;
}

if (check_remote($FATLADY_prefix)=="1") set($FATLADY_prefix."/valid", "1");
?>
