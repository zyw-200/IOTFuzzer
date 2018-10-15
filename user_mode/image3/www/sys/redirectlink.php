<html>
<head>
<title></title>
</head>
<script>
function init()
{
<?
$page=query("/runtime/web/redirect_next_page");
if($page == "")
{
	echo "parent.location.href=\"sys_fw_valid.php\";";
}
else
{
	echo "parent.location.href=\"".$page."\";";
}
?>
}
</script>
<body onload="init();">

</body>
</html>
