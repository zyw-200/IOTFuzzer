<?

$WR_AUTH="401";

for("/wlan/inf:1/webredirect/index")
{
	$wr_match="";	
	//$wr_match_idx="";
	
	if($WR_LOGIN_USER!="")
	{
		if($wr_match=="")
		{
			$wr_enable = query("enable");
			if($wr_enable == "1")
			{
				$wr_user_d=query("name");
				if($WR_LOGIN_USER == $wr_user_d)
				{
					//$wr_match_idx = $@;
					$wr_password_d=query("passwd");
					if($WR_LOGIN_PASSWD == $wr_password_d)
					{
						$wr_match="1";
						if(query("/wlan/inf:2/ap_mode") != "")
						{
							$WR_AUTH="";
						}
					}
				}
			}
		}
	}
	
if(query("/wlan/inf:2/ap_mode") == "")
{
	if($wr_match=="1")	
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
					set("/runtime/webredirect/ms:".$mc_flag."/client:".$@."/wr_flag","1");
					//set("/runtime/stats/wlan/inf:1/mssid:".$mc_flag."/client:".$@."/wr_flag","1");
					set("/runtime/webredirect/client_mac","1");
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
					set("/runtime/webredirect/client:".$@."/wr_flag","1");
					
					//set("/runtime/stats/wlan/inf:1/client:".$@."/wr_flag","1");
					set("/runtime/webredirect/client_mac","1");
					set("/runtime/webredirect/client_pass","1");
				}
			}						
		}
		$WR_AUTH="";
	}
}
}
?>
