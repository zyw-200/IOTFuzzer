<?
	/* common cmd */
	$IWPRIV="iwpriv ".$wlanif_g;
    $IWCONF="iwconfig ".$wlanif_g;

	anchor("/wlan/inf:1");
	$wmmenable  = query("wmm/enable");
    	$acktimeout = query("acktimeout_g");

        //mac clone
        $clonetype = query("/wlan/inf:1/macclone/type");
        if ($clonetype==0 || $clonetype==""){//clone disabled
                $wlanmac= query("/runtime/layout/wlanmac");
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

	echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi0/disable_ipv6\n";
	echo "ifconfig wifi0 hw ether ".$wlanmac."\n";
    	echo "wlanconfig ".$wlanif_g." create wlandev wifi0 wlanmode sta nosbeacon\n";
	echo "brctl apc br0 1"."\n";
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif_g."/disable_ipv6\n";

	echo "iwpriv wifi0 HALDbg 0x0\n";
	echo $IWPRIV." dbgLVL 0x100\n";
	echo $IWPRIV." autoassoc 1\n";
	echo "ifconfig ".$wlanif_g." txqueuelen 1000\n";
	echo "ifconfig wifi0 txqueuelen 1000\n";

	echo "ifconfig ".$wlanif_g." allmulti\n";
	if($acktimeout != "")	{ echo "iwpriv wifi0 acktimeout ".$acktimeout."\n"; }//default to 48
	else	{ echo "iwpriv wifi0 acktimeout 48\n"; }
	echo "iwpriv wifi0 ANIEna 1 \n";
	//echo "iwpriv ".$wlanif_g." shortgi 1 \n";

		echo $IWPRIV." mode 11NGHT40\n";
	
//	echo "iwpriv wifi0 ForBiasAuto 1\n";
	echo "iwpriv ".$wlanif_g." vap_doth 0\n";
	
	echo "iwpriv wifi0 AMPDU 1 \n";
	echo "iwpriv wifi0 AMPDUFrames 32 \n";
	echo "iwpriv wifi0 AMPDULim 50000 \n";
	
	echo "iwpriv wifi0 txchainmask 3\n";
	echo "iwpriv wifi0 rxchainmask 3\n";
	echo "iwpriv wifi0 noisespuropt 1\n";
	
	$auth_mode = query("/wlan/inf:1/authentication");
	echo "iwconfig ".$wlanif_g." essid \"".get("s","/wlan/inf:1/ssid")."\" mode managed\n";

	if ($wmmenable>0)       { echo $IWPRIV." wmm 1\n"; }
	else                    { echo $IWPRIV." wmm 0\n"; }

	if ($auth_mode > 1) {
	    require($template_root."/__auth_supplicant_g.php");
	}
	else    { require($template_root."/__auth_openshared_g.php"); }
/*	move to __wlan_device_up.php	
       	if ($clonetype != 0){
                $wlanmac= query("/runtime/layout/wlanmac");
                echo "ifconfig br0 hw ether ".$wlanmac." \n";
        }
*/
?>
