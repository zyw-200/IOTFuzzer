HTTP/1.1 200 OK

<?
$TEMP_MYNAME    = "web_filter_info";
$TEMP_MYGROUP   = "";
$TEMP_STYLE		= "simple";
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="shortcut icon" href="/favicon.ico" >
	<link rel="stylesheet" href="/css/general.css" type="text/css">
	<meta http-equiv="Content-Type" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>D-LINK SYSTEMS, INC. | WIRELESS ROUTER | HOME</title>		

	<script type="text/javascript" charset="utf-8" src="./js/comm.js"></script>
	<script type="text/javascript" charset="utf-8" src="./js/libajax.js"></script>
	<script type="text/javascript" charset="utf-8" src="./js/postxml.js"></script>
<?
	if (isfile("/htdocs/webinc/js/".$TEMP_MYNAME.".php")==1)
		dophp("load", "/htdocs/webinc/js/".$TEMP_MYNAME.".php");
?>
	<script type="text/javascript">
	var OBJ	= COMM_GetObj;
	var PAGE = <? if (isfile("/htdocs/webinc/js/".$TEMP_MYNAME.".php")==1) echo "new Page();"; else echo "null;"; ?>
</script>
</head>

<body class="mainbg" >
<div class="maincontainer">
	<div class="headercontainer">
		<span class="product"><?echo i18n("Product Page");?> : <a href="http://support.dlink.com" target="_blank"><?echo query("/runtime/device/modelname");?></a></span>
		<span class="version"><?echo i18n("Firmware Version");?> : <?echo query("/runtime/device/firmwareversion");?></span>
	</div>
	<div class="bannercontainer">
		<span class="bannerhead"><a href="<?echo query("/runtime/device/producturl");?>"><img src="/pic/head_01.gif" width="162" height="92"></a></span>
		<span class="bannertail"><img src="/pic/head_03.gif"></span>
	</div>

	<div id="content" class="simplecontainer">
	<div class="simplebody">

	<!-- Start of Page Depedent Part. -->
	<?
		if (isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php")==1)
		dophp("load", "/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php");
	?>
	<!-- End of Page Dependent Part. -->

	</div>
	</div>
	<div class="footercontainer">
		<span class="footermark"><img src="/pic/tail.gif"></span>
	</div>
</div>
<div class="copyright">Copyright &copy; 2008-2010 D-Link Systems, Inc.</div>
</body>
</html>

