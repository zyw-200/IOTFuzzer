HTTP/1.1 200 OK
Content-Type: text/xml

<?
	include "/htdocs/phplib/trace.php";
	
	$result = "OK";
	$code   = "";
	$message= "";
	TRACE_debug("CHECK_NODE=================".$_POST["CHECK_NODE"]);
	// get value.
	if($_POST["CHECK_NODE"] != "")
	{
		$code = query($_POST["CHECK_NODE"]);
		TRACE_debug("code=============".$code);
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
		echo "<status>\n";
		echo "\t<result>".$result."</result>\n";
		echo "\t<code>".$code."</code>\n";
		echo "\t<message></message>\n";
		echo "</status>\n";
	}
?>
