HTTP/1.1 200 OK

<?
$SMS_MAX_COUNT = query("runtime/callmgr/voice_service/mobile/sms/sm#");
if ($SMS_MAX_COUNT == "") $SMS_MAX_COUNT = 0;

$TEMP_MYNAME    = "bsc_sms_inbox";
$TEMP_MYGROUP   = "basic";
$TEMP_STYLE     = "complex";
include "/htdocs/webinc/templates.php";
?>
