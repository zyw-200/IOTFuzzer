HTTP/1.1 200 OK
Content-Type: text/xml

<?
if ($_POST["act"] == "ping")
{
	set("/runtime/diagnostic/ping", $_POST["dst"]);
	$result = "OK";
}
else if ($_POST["act"] == "pingreport")
{
	$result = get("x", "/runtime/diagnostic/ping");
}
echo '<?xml version="1.0"?>\n';
?><diagnostic>
	<report><?=$result?></report>
</diagnostic>
