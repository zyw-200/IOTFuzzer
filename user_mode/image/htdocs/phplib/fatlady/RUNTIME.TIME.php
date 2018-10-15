<?
/* fatlady is used to validate the configuration for the specific service.
 * FATLADY_prefix was defined to the path of Session Data.
 * 3 variables should be returned for the result:
 * FATLADY_result, FATLADY_node & FATLADY_message. */
include "/htdocs/phplib/trace.php";

function set_result($result, $node, $message)
{
	$_GLOBALS["FATLADY_result"]	= $result;
	$_GLOBALS["FATLADY_node"]	= $node;
	$_GLOBALS["FATLADY_message"]= $message;
}

function check_datetime($prefix)
{
	$date = query($prefix."/date");
	$time = query($prefix."/time");

	$month = cut($date, 0, "/");
	$day   = cut($date, 1, "/");
	$year  = cut($date, 2, "/");

	$hour = cut($time, 0, ":");
	$min  = cut($time, 1, ":");
	$sec  = cut($time, 2, ":");

	TRACE_debug("FATLADY: RUNTIME.TIME: ".$year."/".$month."/".$day);
	TRACE_debug("FATLADY: RUNTIME.TIME: ".$hour.":".$min.":".$sec);

	/* The latest time linux can support is: Tue Jan 19 11:14:07 CST 2038. */
	if (isdigit($year)==0 || $year < 2000 || $year > 2037)
	{
		set_result("FAILED", $prefix."/date", i18n("Invalid year")." - ".$year);
		return;
	}
	if (isdigit($month)==0 || $month <= 0 || $month > 12)
	{
		set_result("FAILED", $prefix."/date", i18n("Invalid month"));
		return;
	}
	if (isdigit($day)==0 || $day <= 0 || $day > 31)
	{
		set_result("FAILED", $prefix."/date", i18n("Invalid day"));
		return;
	}
	if (isdigit($hour)==0 || $hour < 0 || $hour > 23)
	{
		set_result("FAILED", $prefix."/time", i18n("Invalid hour"));
		return;
	}
	if (isdigit($min)==0 || $min < 0 || $min > 59)
	{
		set_result("FAILED", $prefix."/time", i18n("Invalid minute"));
		return;
	}
	if (isdigit($sec)==0 || $sec < 0 || $sec > 59)
	{
		set_result("FAILED", $prefix."/time", i18n("Invalid second"));
		return;
	}
	set_result("OK", "", "");
}

check_datetime($FATLADY_prefix."/runtime/device");
if ($_GLOBALS["FATLADY_result"]=="OK") set($FATLADY_prefix."/valid", 1);
?>
