<?
	$wlanif = "ath1";//query("/runtime/layout/wlanif");
        $IWCONF="iwconfig ".$wlanif;
        $IWPRIV="iwpriv ".$wlanif
        $wepmode        = query("/wlan/inf:1/wpa/wepmode");
        $cwmmode        = query("/wlan/inf:1/cwmmode");
        $wmmenable      = query("/wlan/inf:1/wmm/enable");
        $g_mode		= query("/wlan/inf:1/wlmode");
        $auth_mode      = query("/wlan/inf:1/authentication");
        $acktimeout   = query("/wlan/inf:1/acktimeout_g");
        $router_enable = query("/runtime/router/enable");
        $wlxmlpatch_pid = "/var/run/wlxmlpatch.pid";
        $ethlink        = query("/wlan/inf:1/ethlink");
        $wlanmac= query("/runtime/layout/wlanmac");

	// Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/wifi0/disable_ipv6;\n";
        
        echo "ifconfig wifi0 hw ether ".$wlanmac."\n";
	echo "wlanconfig ath0 create wlandev wifi0 wlanmode ap\n";
        echo "wlanconfig ath1 create wlandev wifi0 wlanmode sta nosbeacon 2>&1 > /dev/console\n";

        // Disable IPv6 on wireless interface
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/".$wlanif."/disable_ipv6;\n";
	echo "echo \"1\" > /proc/sys/net/ipv6/conf/ath0/disable_ipv6;\n";
        
	echo "iwpriv wifi0 acktimeout ".$acktimeout."\n";
        echo "iwpriv wifi0 HALDbg 0\n";
	echo "iwpriv ath0 dbgLVL 0x100\n";
        echo "iwpriv ath1 dbgLVL 0x100\n";
	echo "iwpriv ath1 autoassoc 1\n";
	echo "iwpriv ath1 roaming 0\n";
        echo "iwpriv wifi0 burst 1\n";
        echo "iwpriv wifi0 noisespuropt 1\n";
	echo "ifconfig ath0 txqueuelen 1000\n";
        echo "ifconfig ath1 txqueuelen 1000\n";
        echo "ifconfig wifi0 txqueuelen 1000\n";
	echo "ifconfig ath0 allmulti\n";
        echo "ifconfig ath1 allmulti\n";
	if ($cwmmode==1){
        	//echo "iwpriv ath0 cwmmode 1\n";
        	//echo "iwpriv ath1 cwmmode 1\n";
		echo "iwpriv ath0 mode 11NGHT40\n";
		echo "iwpriv ath1 mode 11NGHT40\n";
        }
        else{
        //    echo "iwpriv ath0 cwmmode 0\n";
          //  echo "iwpriv ath1 cwmmode 0\n";
	    if($g_mode==2) {//b,g mode
                echo "iwpriv ath0 mode 11G\n";
                echo "iwpriv ath1 mode 11G\n";
	    }
	    else {//bgn mixed or n only
                echo "iwpriv ath0 mode 11NGHT20\n";
                echo "iwpriv ath1 mode 11NGHT20\n";
	    }
        }
        echo "iwpriv wifi0 AMPDU 1\n";
        echo "iwpriv wifi0 AMPDUFrames 32\n";
        echo "iwpriv wifi0 AMPDULim 50000\n";
        echo "iwpriv wifi0 ANIEna 1\n";

        if ($wmmenable>0)       { echo "iwpriv ath1 wmm 1\n"; }
        else                    { echo "iwpriv ath1 wmm 0\n"; }
	echo "iwconfig ath0 essid \"".get("s","/wlan/inf:1/ssid")."\" mode master\n";
	echo "iwconfig ath1 essid \"".get("s","/wlan/inf:1/ssid")."\" mode managed\n";
        echo "iwpriv wifi0 txchainmask 3\n";
        echo "iwpriv wifi0 rxchainmask 3\n";
       if ($auth_mode>1){
              require("/etc/templates/__auth_supplicant_g.php");
              require("/etc/templates/__auth_hostapd_g.php");
              echo "iwpriv ".$wlanif." apband 0\n"; //show ap band in wireless driver
              echo "wlxmlpatch -L ath1 /runtime/stats/wlan/inf:1 RADIO_SSID1_ON RADIO_SSID1_BLINK MADWIFI > /dev/console &\n";
              echo "echo $! > ".$wlxmlpatch_pid."\n";
           //   echo "ifconfig ath1 up\n";
              echo "brctl apmode br0 2\n";
              echo "brctl addif br0 ath1\n";
//	      echo "ifconfig ath0 mode master\n"; 	//wiliam_201208, should remove, cause long wait.
            } else { 
             //   echo "ifconfig ath1 up\n";
		echo "brctl addif br0 ath1\n";
                require("/etc/templates/__auth_openshared_g.php"); 
                echo "iwpriv ".$wlanif." apband 0\n"; //show ap band in wireless driver
           //     echo "wlxmlpatch -L ath1 /runtime/stats/wlan/inf:1 RADIO_SSID1_ON RADIO_SSID1_BLINK MADWIFI > /dev/console &\n";
          //      echo "echo $! > ".$wlxmlpatch_pid."\n";
                echo "brctl apmode br0 2\n";
                $IWCONF="iwconfig ath0";
                $IWPRIV="iwpriv ath0";
                require("/etc/templates/__auth_openshared_g.php");                
        }

      	/*jacky port ethernet integration from dap2553 start 20100511*/
        if ($ethlink==1){
            echo "brctl ethlink br0 ".$ethlink."\n";
        }else{
            echo "brctl ethlink br0 0\n";
        }
        /*jacky port ethernet integration from dap2553 end 20100511*/
      	echo "rgdb -i -s /runtime/stats/wireless/led11g 1\n";

        exit;
?>
