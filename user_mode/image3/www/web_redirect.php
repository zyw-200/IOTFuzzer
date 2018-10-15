<html>
<head>
<title></title>
</head>
<?
//$runtime_ipv6 = query("/runtime/web/display/ipv6");
$cfg_lan_type = query("/wan/rg/inf:1/mode");
if($cfg_lan_type == 1)
{
	anchor("/wan/rg/inf:1/static");
}
else
{
	anchor("/runtime/wan/inf:1");
}
$cfg_ipaddr = query("ip");
$cfg_url_enable = query("/wlan/inf:1/webredirect/url/enable");
$cfg_url_addr = query("/wlan/inf:1/webredirect/url/");
$cfg_auth_enable = query("/wlan/inf:1/webredirect/auth/enable");
/*if($cfg_auth_enable != 1)
{
	$mc_flag=query("/runtime/webredirect/mc_flag");
	if($mc_flag != 0)
	{
			for("/runtime/stats/wlan/inf:1/mssid:".$mc_flag."/client")
			{
				$client_mac  = query("/runtime/stats/wlan/inf:1/mssid:".$mc_flag."/client:".$@."/mac");
				$sta_mac  = query("/runtime/webredirect/client_mac");
				if($client_mac == $sta_mac)
				{
					set("/runtime/webredirect/inf:1/ms:".$mc_flag."/client:".$@."/wr_flag","1");
					set("/runtime/webredirect/client_mac","1");
					set("/runtime/webredirect/client_pass","1");
				}
			}
			for("/runtime/stats/wlan/inf:2/mssid:".$mc_flag."/client")
    	    {
	            $client_mac  = query("/runtime/stats/wlan/inf:2/mssid:".$mc_flag."/client:".$@."/mac");
            	$sta_mac  = query("/runtime/webredirect/client_mac_a");
        	    if($client_mac == $sta_mac)
    	        {
	                set("/runtime/webredirect/inf:2/ms:".$mc_flag."/client:".$@."/wr_flag","1");
                	set("/runtime/webredirect/client_mac_a","1");
            	    set("/runtime/webredirect/client_pass","1");
            	}
        	}
	}
	else
	{
			for("/runtime/stats/wlan/inf:1/client")
			{
				$client_mac  = query("/runtime/stats/wlan/inf:1/client:".$@."/mac");
				$sta_mac  = query("/runtime/webredirect/client_mac");
				if($client_mac == $sta_mac)
				{
					set("/runtime/webredirect/inf:1/client:".$@."/wr_flag","1");
					set("/runtime/webredirect/client_mac","1");
					set("/runtime/webredirect/client_pass","1");
				}
			}
			for("/runtime/stats/wlan/inf:2/client")
            {
                $client_mac  = query("/runtime/stats/wlan/inf:2/client:".$@."/mac");
                $sta_mac  = query("/runtime/webredirect/client_mac_a");
                if($client_mac == $sta_mac)
                {
                    set("/runtime/webredirect/inf:2/client:".$@."/wr_flag","1");
                    set("/runtime/webredirect/client_mac_a","1");
                    set("/runtime/webredirect/client_pass","1");
                }
            }
	}
}*/
?>
<script>
function init()
{
	//alert("You are the first time to connect outside URL, \n and therefore need to transfer to web redirect authentication page.");
	if("<?=$cfg_url_enable?>" == "")
	{
		setTimeout("wr()",1000);
	}
	else
	{
		setTimeout("wr()",200);
	}
}
function wr()
{
	if("<?=$cfg_url_enable?>" == "")
	{
		parent.location.href = "//<?=$cfg_ipaddr?>/wr_login.php";
	}
	else
	{
		if("<?=$cfg_auth_enable?>" == 1)
		{
			parent.location.href = "//<?=$cfg_ipaddr?>/wr_login.php";
		}
		else
		{
			setTimeout("wr_add()",200);
		}
	}
}

function wr_add()
{
	parent.location.href = "//<?=$cfg_ipaddr?>/web_redirect_out.php";
}
	
</script>
<body onload="init();">
	
</body>
</html>
