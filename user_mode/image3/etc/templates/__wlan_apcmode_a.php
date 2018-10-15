<?
	/* common cmd */
	$IWPRIV="iwpriv ".$wlanif_a;

	anchor("/wlan/inf:2");
	$wmmenable  = query("wmm/enable");
    	$acktimeout = query("acktimeout_a");

        //mac clone
        $clonetype = query("/wlan/inf:1/macclone/type");
        if ($clonetype==0 || $clonetype==""){//clone disabled
                $wlanmac= query("/runtime/layout/wlanmac_a");
                echo "brctl clonetype br0 0\n";
        }else if ($clonetype==1){//auto clone
                $wlanmac= query("/runtime/macclone/addr");
                echo "brctl setcloneaddr br0 ".$wlanmac."\n";
                echo "brctl clonetype br0 1\n";
        }else if ($clonetype==2){//manual clone
                $wlanmac= query("/wlan/inf:1/macclone/addr");
                echo "brctl setcloneaddr br0 ".$wlanmac."\n";
                echo "brctl clonetype br0 2\n";
        }

	echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi1/disable_ipv6\n";
	echo "ifconfig wifi1 hw ether ".$wlanmac."\n";
    	echo "wlanconfig ".$wlanif_a." create wlandev wifi1 wlanmode sta nosbeacon\n";
	echo "brctl apc_a br0 1"."\n";
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif_a."/disable_ipv6\n";
//       echo $IWPRIV." dbgLVL 0x100\n";
        echo $IWPRIV." autoassoc 1\n";

	if($acktimeout != "")	{ echo $IWPRIV." acktimeout ".$acktimeout."\n"; }//default to 25
	else	{ echo $IWPRIV." acktimeout 25\n"; }
	echo "ifconfig ".$wlanif_a." txqueuelen 1000\n";
	echo "ifconfig wifi1 txqueuelen 1000\n";
	echo "ifconfig ".$wlanif_a." allmulti\n";
	echo "iwpriv wifi1 enable_ol_stats 1\n";
	
		echo $IWPRIV." mode 11ACVHT80\n";
	
	echo "iwpriv wifi1 txchainmask 3\n";
	echo "iwpriv wifi1 rxchainmask 3\n";

	

	$auth_mode = query("/wlan/inf:2/authentication");
	echo "iwconfig ".$wlanif_a." essid \"".get("s","/wlan/inf:2/ssid")."\" mode managed\n";
		
	if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
	else                    { echo $IWPRIV." wmm 0\n"; }

	if ($auth_mode > 1) {
    	require($template_root."/__auth_supplicant_a.php");
	}
	else    { require($template_root."/__auth_openshared_a.php"); }
/*move to __wlan_device_up.php
        if ($clonetype != 0){
                $wlanmac= query("/runtime/layout/wlanmac");
                echo "ifconfig br0 hw ether ".$wlanmac." \n";
        }
*/
?>
