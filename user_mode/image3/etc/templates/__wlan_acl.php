#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
$band = query("/wlan/ch_mode");
$need_kick = 1;
if ($band == 0 || $band == "")
{
	anchor("/wlan/inf:1");
	/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
	$ifnum = 0;
	$ifnum_end = 8;
	$band_index = 1;
	$primary = 0;
	$multi_index = 1;
}
else
{
	anchor("/wlan/inf:2");
	/* aclmode 0:disable, 1:allow all of the list, 2:deny all of the list */
	$ifnum = 16;	
	$ifnum_end = 24;
	$band_index = 2;
	$primary = 16;
	$multi_index =1;
}
while($ifnum < $ifnum_end)
{
	if($ifnum==$primary)
	{
		//primary ssid acl
        	echo "iwpriv ath".$ifnum." maccmd 3\n";   // flush the ACL database.
        	$aclmode=query("acl/mode");
        	if      ($aclmode == 1)     { echo "iwpriv ath".$ifnum." maccmd 1\n"; }
        	else if ($aclmode == 2)     { echo "iwpriv ath".$ifnum." maccmd 2\n"; }
        	else                        { echo "iwpriv ath".$ifnum." maccmd 0\n"; }
        	if ($aclmode == 1 || $aclmode == 2)
        	{
        	
        	
        	
        	        for("/runtime/stats/wlan/inf:".$band_index."/client")
                        {
                        	$mac_client=query("mac");
                        	
                        	for("/wlan/inf:".$band_index."/acl/mac")
                        	{
                        		$mac=query("/wlan/inf:".$band_index."/acl/mac:".$@);
                        	
                        	
                        		if ($mac_client == $mac)
                        		{
                        			$need_kick = 0;
                        		}
                        	}
                        	
                        	if ($need_kick ==1)
                        	{
                        		echo "iwpriv ath".$ifnum." kickmac ".$mac_client."\n";
                        	}
                        	$need_kick =1;                        	
                        }        	
        	        	
       	
                	for("/wlan/inf:".$band_index."/acl/mac")
                	{
                        	$mac=query("/wlan/inf:".$band_index."/acl/mac:".$@);
                        	echo "iwpriv ath".$ifnum." addmac ".$mac."\n"; 
				if ($aclmode == 2)     {echo "iwpriv ath".$ifnum." kickmac ".$mac."\n"; } 
               		 }
        	}
	}else
	{
		//multi ssid acl
		echo "iwpriv ath".$ifnum." maccmd 3\n";   // flush the ACL database.
                $aclmode=query("/wlan/inf:".$band_index."/multi/index:".$multi_index."/acl/mode");
		if($aclmode=="")
		{
        		$aclmode=query("acl/mode");
                	if      ($aclmode == 1)     { echo "iwpriv ath".$ifnum." maccmd 1\n"; }
               		else if ($aclmode == 2)     { echo "iwpriv ath".$ifnum." maccmd 2\n"; }
                	else                        { echo "iwpriv ath".$ifnum." maccmd 0\n"; }
                	if ($aclmode == 1 || $aclmode == 2)
                	{
                	
        	        	for("/runtime/stats/wlan/inf:".$band_index."/client")
                        	{
                        		$mac_client=query("mac");
                        	
                        		for("/wlan/inf:".$band_index."/acl/mac")
                        		{
                        			$mac=query("/wlan/inf:".$band_index."/acl/mac:".$@);
                        	
                        	
                        			if ($mac_client == $mac)
                        			{
                        				$need_kick = 0;
                        			}
                        		}
                        	
                        		if ($need_kick ==1)
                        		{
                        		echo "iwpriv ath".$ifnum." kickmac ".$mac_client."\n";
                        		}
                        		
                        		$need_kick =1;                        		
                        	}                	
        	
                	
            
                        	for("/wlan/inf:".$band_index."/acl/mac")
                        	{
                                	$mac=query("/wlan/inf:".$band_index."/acl/mac:".$@);
                                	echo "iwpriv ath".$ifnum." addmac ".$mac."\n";
                                	if ($aclmode == 2)     {echo "iwpriv ath".$ifnum." kickmac ".$mac."\n"; }
                         	}
                	}
		}else
		{
			if      ($aclmode == 1)     { echo "iwpriv ath".$ifnum." maccmd 1\n"; }
                        else if ($aclmode == 2)     { echo "iwpriv ath".$ifnum." maccmd 2\n"; }
                        else                        { echo "iwpriv ath".$ifnum." maccmd 0\n"; }
                        if ($aclmode == 1 || $aclmode == 2)
                        {
                        
                        
                        for("/wlan/inf:".$band_index."/multi/index")
                        {
                        	$multissid_index = $@;
         	        	for("/runtime/stats/wlan/inf:".$band_index."/mssid:".$multissid_index."/client")
                        	{
                        		$mac_client=query("mac");
                        	
                        		for("/wlan/inf:".$band_index."/multi/index:".$multi_index."/acl/mac")
                        		{
                        			$mac=query("/wlan/inf:".$band_index."/multi/index:".$multi_index."/acl/mac:".$@);
                    	
                        			if ($mac_client == $mac)
                        			{
                        				$need_kick = 0;
                        			}
                        		}
                        	
                        		if ($need_kick ==1)
                        		{
                        			echo "iwpriv ath".$ifnum." kickmac ".$mac_client."\n";
                        		}
                         		$need_kick =1;                       		
                        	}
                        }	                       
      
                                for("/wlan/inf:".$band_index."/multi/index:".$multi_index."/acl/mac")
                                {
                                        $mac=query("/wlan/inf:".$band_index."/multi/index:".$multi_index."/acl/mac:".$@);
                                        echo "iwpriv ath".$ifnum." addmac ".$mac."\n";
                                        if ($aclmode == 2)     {echo "iwpriv ath".$ifnum." kickmac ".$mac."\n"; }
                                }
                        }
		}
		$multi_index++;
	}
        $ifnum++;
}
?>
