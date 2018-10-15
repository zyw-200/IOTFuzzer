<html>
<head>
<meta http-equiv="pragma" content="no-cache" /> 
<title></title>
</head>
<?
$wr_sta_mac = query("/runtime/webredirect/client_mac");
$wr_da_ip = query("/runtime/webredirect/daddr_ip_flag");
$wr_sta_mac_a = query("/runtime/webredirect/client_mac_a");
?>
<script>
function init()
{
	var wr_reload = "<?=$reload?>";
	if(wr_reload == "")
	{
		setTimeout("reload_page()",1000);
	}
	else
	{
		login();
	}
}
var url = window.location.href;
var url_split = url.split("/");
var url_cut = url_split[4];
function reload_page()
{
    if(url_cut == "")
    {
        self.location.href="session_login.php?reload=1";
    }
    else
    {
        self.location.href="/session_login.php?reload=1";
    }
}
/*
function reload_page()
{
	self.location.href="/session_login.php?reload=1";
}
*/
function login()
{
	var wr_sta_mac = "<?=$wr_sta_mac?>";
	var wr_da_ip = "<?=$wr_da_ip?>";	
	var wr_sta_mac_a = "<?=$wr_sta_mac_a?>";
	if("<?=$wr_sta_mac_a?>" == "")
	{
		if(wr_sta_mac !=1 && wr_da_ip != 0)
		{
			parent.location.href="web_redirect.php";
		}
		else
		{
			parent.location.href="login.php";
		}
	}
	else
	{
		if((wr_sta_mac !=1 && wr_da_ip != 0) || (wr_sta_mac_a !=1 && wr_da_ip != 0))
		{
			parent.location.href="web_redirect.php";
		}
		else
		{
			parent.location.href="login.php";
		}
	}
}
</script>
<body onload="init();">
	
</body>
</html>
