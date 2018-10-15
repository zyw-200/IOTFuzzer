<?=$G_TAG_SCRIPT_START?>
function do_timeout()
{
	// logout or something else to do.
	parent.location.href="/logout.php";
}
setTimeout("do_timeout()",<?map("/sys/sessiontimeout","","300");?>*1000);
<?=$G_TAG_SCRIPT_END?>
