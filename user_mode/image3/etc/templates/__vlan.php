#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$WLAN_g = "/wlan/inf:1";  // b, g, n band
$WLAN_a = "/wlan/inf:2";  // a, n band 

$multi_ssid_path_g = $WLAN_g."/multi";
$multi_ssid_path_a = $WLAN_a."/multi";
$wds_path_g = $WLAN_g."/wds";
$wds_path_a = $WLAN_a."/wds";

$sys = "/sys";

$multi_total_state_g = query($multi_ssid_path_g."/state");
$multi_total_state_a = query($multi_ssid_path_a."/state");
$ap_operate_mode_g = query($WLAN_g."/ap_mode");
$ap_operate_mode_a = query($WLAN_a."/ap_mode");

if ($generate_start==1)
{    
	// for dual band AP, only when both multi-ssid enable, VLAN can be activated, Joe, 2009-06-03
	// if ($multi_total_state_g == 1 && $multi_total_state_a == 1)

	// for dual band AP, if either one band(2.4G/5G) set as APC, disable VLAN, Joe, 2009-10-13
	if ($ap_operate_mode_g != 1 && $ap_operate_mode_a != 1)
	{       	    
	    // MULTI_VLAN +++
	    $vlan_state = query($sys."/vlan_state"); 
	    $vlan_mode = query($sys."/vlan_mode"); 
	    $auth_mode_g = query($WLAN_g."/authentication");	
	    $auth_mode_a = query($WLAN_a."/authentication");	
	    
	    // check vlan status
	    if ($vlan_state == 1 && $vlan_mode==1)  // dynamic vlan mode
	    {    
	        if ($auth_mode_g==1 || // shared key 
				$auth_mode_a==1 ||
                $auth_mode_g==0 || // open
                $auth_mode_a==0 || // open
                $auth_mode_g==3 || //wpa-psk 
                $auth_mode_a==3 || //wpa-psk 
                $auth_mode_g==5 || //wpa2-psk  
                $auth_mode_a==5 || //wpa2-psk  
                $auth_mode_g==7 || //wpa-auto-psk     
                $auth_mode_a==7 || //wpa-auto-psk     
                $auth_mode_g==9 || //802.1x
                $auth_mode_a==9    //802.1x
                )
            {   // if not wpa-eap -> disable vlan.
                //echo "\necho set($sys./vlan_state, 0  ...\n > /dev/console\n";  
                set($sys."/vlan_state", 0);  // 
                set($sys."/vlan_mode", 0);  // 
            } 
        } 
	    $vlan_state = query($sys."/vlan_state"); 
	    // end of // check vlan status
	    
	    $vlan_id_sys = query($sys."/vlan_id"); 
	    $vlan_id_g = query($WLAN_g."/vlan_id"); 	    
	    $vlan_id_a = query($WLAN_a."/vlan_id"); 	    
	    if($vlan_state == 1 && $vlan_mode==0) // static vlan mode
	    {	        
            echo "\necho start vlan setting...\n > /dev/console\n"; 
            
	        echo "brctl setvlanstate br0 1\n";
	        $group_vid_path = "/sys/group_vlan";
	        //   set vlan mode
	        if ($vlan_id_g!="")			
	        { 
	            echo "brctl setpvidif br0 ath0 ".$vlan_id_g."\n";
	        } 	    
	        if ($vlan_id_a!="")			
	        { 
	            echo "brctl setpvidif br0 ath16 ".$vlan_id_a."\n";
	        } 	    
	        // set multi-ssid pvid
		if ($multi_total_state_g == 1)
		{
	        	$index=0;
	        	for ($multi_ssid_path_g."/index")
	        	{      
	        	    	$index++;	            
	            		$multi_ind_state_g = query($multi_ssid_path_g."/index:".$index."/state");  
	            		if ($multi_ind_state_g==1)  
	            		{ 
	                
	                		$ath_pvid = query($multi_ssid_path_g."/index:".$index."/vlan_id");  
	                		if ($ath_pvid!="")
	                    		{echo "brctl setpvidif br0 ath".$index." ".$ath_pvid."\n";
                    			// add vap->pvid a default value... jack
	                    		}
	            		}
	        	}
		}
		if ($multi_total_state_a == 1)
		{
	        	$index=0;
	        	for ($multi_ssid_path_a."/index")
	        	{      
	            		$index++;
	            		$multi_ind_state_a = query($multi_ssid_path_a."/index:".$index."/state");  
	            		if ($multi_ind_state_a==1)  
	            		{ 
	                
	                		$ath_pvid = query($multi_ssid_path_a."/index:".$index."/vlan_id");  
					$vapindex = $index + 16;
	                		if ($ath_pvid!="")
	                    		{echo "brctl setpvidif br0 ath".$vapindex." ".$ath_pvid."\n";
                    			// add vap->pvid a default value... jack
	                    		}
	            		}
	        	}
		}
	        // jack, check papa, sandy, to not delete WDS's mac rgdb entry.......
	        // set WDS pvid
		if ($ap_operate_mode_g == 3 || $ap_operate_mode_g == 4)
		{
	        	$index=0;
	        	for ($wds_path_g."/index")
	        	{      
	            		$index++;	            
	            		$wds_mac_g = query($wds_path_g."/list/index:".$index."/mac");  
	            		if ($wds_mac_g!="")  
	            		{ 
	                		$ath_pvid = query($wds_path_g."/index:".$index."/vlan_id");
	                		$wds_index_g = $index + 7;  
	                		if ($ath_pvid!="")
	                    		{echo "brctl setpvidif br0 ath".$wds_index_g." ".$ath_pvid."\n";
	                    
	                    		}
	            		}
	        	}
		}
		if ($ap_operate_mode_a == 3 || $ap_operate_mode_a == 4)
		{
	        	$index=0;
	        	for ($wds_path_a."/index")
	        	{      
	            		$index++;
	            		$wds_mac_a = query($wds_path_a."/list/index:".$index."/mac");  
	            		if ($wds_mac_a!="")  
	            		{ 
	                		$ath_pvid = query($wds_path_a."/index:".$index."/vlan_id");
					$wds_index_a = $index + 23;
	                		if ($ath_pvid!="")
	                    		{echo "brctl setpvidif br0 ath".$wds_index_a." ".$ath_pvid."\n";
	                    
	                    		}
	            		}
	        	}
		}
               
		$ind=0;
	        for ($group_vid_path."/index") 
            {
    	        $ind++;
    	        echo "echo ind: ".$ind."  \n"; 
    	        $group_vid = query($group_vid_path."/index:".$ind."/group_vid");
    	        if ($group_vid!="")
    	        {
    	            //$egress = query($group_vid_path."/index:".$ind."/egress");    	        
    	            //$tag = query($group_vid_path."/index:".$ind."/tag");
    	            // 0: none, 1: tag port, 2: un-tag port
    	            $ath0_egress= query($group_vid_path."/index:".$ind."/ath:1/egress"); 
    	            $ath1_egress= query($group_vid_path."/index:".$ind."/ath:2/egress"); 
                    $ath2_egress= query($group_vid_path."/index:".$ind."/ath:3/egress"); 
                    $ath3_egress= query($group_vid_path."/index:".$ind."/ath:4/egress"); 
                    $ath4_egress= query($group_vid_path."/index:".$ind."/ath:5/egress"); 
                    $ath5_egress= query($group_vid_path."/index:".$ind."/ath:6/egress"); 
                    $ath6_egress= query($group_vid_path."/index:".$ind."/ath:7/egress"); 
                    $ath7_egress= query($group_vid_path."/index:".$ind."/ath:8/egress"); 
                    $ath8_egress= query($group_vid_path."/index:".$ind."/ath:9/egress"); 
                    $ath9_egress= query($group_vid_path."/index:".$ind."/ath:10/egress"); 
                    $ath10_egress=query($group_vid_path."/index:".$ind."/ath:11/egress"); 
                    $ath11_egress=query($group_vid_path."/index:".$ind."/ath:12/egress"); 
                    $ath12_egress=query($group_vid_path."/index:".$ind."/ath:13/egress"); 
                    $ath13_egress=query($group_vid_path."/index:".$ind."/ath:14/egress"); 
                    $ath14_egress=query($group_vid_path."/index:".$ind."/ath:15/egress"); 
                    $ath15_egress=query($group_vid_path."/index:".$ind."/ath:16/egress"); 
    	            $ath16_egress=query($group_vid_path."/index:".$ind."/ath:17/egress"); 
    	            $ath17_egress=query($group_vid_path."/index:".$ind."/ath:18/egress"); 
                    $ath18_egress=query($group_vid_path."/index:".$ind."/ath:19/egress"); 
                    $ath19_egress=query($group_vid_path."/index:".$ind."/ath:20/egress"); 
                    $ath20_egress=query($group_vid_path."/index:".$ind."/ath:21/egress"); 
                    $ath21_egress=query($group_vid_path."/index:".$ind."/ath:22/egress"); 
                    $ath22_egress=query($group_vid_path."/index:".$ind."/ath:23/egress"); 
                    $ath23_egress=query($group_vid_path."/index:".$ind."/ath:24/egress"); 
                    $ath24_egress=query($group_vid_path."/index:".$ind."/ath:25/egress"); 
                    $ath25_egress=query($group_vid_path."/index:".$ind."/ath:26/egress"); 
                    $ath26_egress=query($group_vid_path."/index:".$ind."/ath:27/egress"); 
                    $ath27_egress=query($group_vid_path."/index:".$ind."/ath:28/egress"); 
                    $ath28_egress=query($group_vid_path."/index:".$ind."/ath:29/egress"); 
                    $ath29_egress=query($group_vid_path."/index:".$ind."/ath:30/egress"); 
                    $ath30_egress=query($group_vid_path."/index:".$ind."/ath:31/egress"); 
                    $ath31_egress=query($group_vid_path."/index:".$ind."/ath:32/egress"); 
    	            //  jack test debug +++
    	            /*
    	            echo "echo ath0_egress: ".$ath0_egress." \n";
    	            echo "echo ath1_egress: ".$ath1_egress." \n";
    	            echo "echo ath2_egress: ".$ath2_egress." \n";
    	            echo "echo ath3_egress: ".$ath3_egress." \n";
    	            echo "echo ath4_egress: ".$ath4_egress." \n";
    	            echo "echo ath5_egress: ".$ath5_egress." \n";
    	            echo "echo ath6_egress: ".$ath6_egress." \n";
    	            echo "echo ath7_egress: ".$ath7_egress." \n";
    	            echo "echo ath8_egress: ".$ath8_egress." \n";
    	            echo "echo ath9_egress: ".$ath9_egress." \n";
    	            echo "echo ath10_egress: ".$ath10_egress." \n";
    	            echo "echo ath11_egress: ".$ath11_egress." \n";
    	            echo "echo ath12_egress: ".$ath12_egress." \n";
    	            echo "echo ath13_egress: ".$ath13_egress." \n";
    	            echo "echo ath14_egress: ".$ath14_egress." \n";
    	            echo "echo ath15_egress: ".$ath15_egress." \n";
    	            */
			//  jack test debug ---            
		            if ($ath0_egress==1)  {echo "brctl setgroupvidif br0 ath0 ".$group_vid." 1\n"; }    else if ($ath0_egress==2)  {echo "brctl setgroupvidif br0 ath0 ".$group_vid." 0\n"; }        
                    if ($ath1_egress==1)  {echo "brctl setgroupvidif br0 ath1 ".$group_vid." 1\n"; }    else if ($ath1_egress==2)  {echo "brctl setgroupvidif br0 ath1 ".$group_vid." 0\n"; }        
                    if ($ath2_egress==1)  {echo "brctl setgroupvidif br0 ath2 ".$group_vid." 1\n"; }    else if ($ath2_egress==2)  {echo "brctl setgroupvidif br0 ath2 ".$group_vid." 0\n"; }        
                    if ($ath3_egress==1)  {echo "brctl setgroupvidif br0 ath3 ".$group_vid." 1\n"; }    else if ($ath3_egress==2)  {echo "brctl setgroupvidif br0 ath3 ".$group_vid." 0\n"; }        
                    if ($ath4_egress==1)  {echo "brctl setgroupvidif br0 ath4 ".$group_vid." 1\n"; }    else if ($ath4_egress==2)  {echo "brctl setgroupvidif br0 ath4 ".$group_vid." 0\n"; }        
                    if ($ath5_egress==1)  {echo "brctl setgroupvidif br0 ath5 ".$group_vid." 1\n"; }    else if ($ath5_egress==2)  {echo "brctl setgroupvidif br0 ath5 ".$group_vid." 0\n"; }        
                    if ($ath6_egress==1)  {echo "brctl setgroupvidif br0 ath6 ".$group_vid." 1\n"; }    else if ($ath6_egress==2)  {echo "brctl setgroupvidif br0 ath6 ".$group_vid." 0\n"; }        
                    if ($ath7_egress==1)  {echo "brctl setgroupvidif br0 ath7 ".$group_vid." 1\n"; }    else if ($ath7_egress==2)  {echo "brctl setgroupvidif br0 ath7 ".$group_vid." 0\n"; }        
                    if ($ath8_egress==1)  {echo "brctl setgroupvidif br0 ath8 ".$group_vid." 1\n"; }    else if ($ath8_egress==2)  {echo "brctl setgroupvidif br0 ath8 ".$group_vid." 0\n"; }        
                    if ($ath9_egress==1)  {echo "brctl setgroupvidif br0 ath9 ".$group_vid." 1\n"; }    else if ($ath9_egress==2)  {echo "brctl setgroupvidif br0 ath9 ".$group_vid." 0\n"; }                         
		            if ($ath10_egress==1) {echo "brctl setgroupvidif br0 ath10 ".$group_vid." 1\n"; }   else if ($ath10_egress==2) {echo "brctl setgroupvidif br0 ath10 ".$group_vid." 0\n"; }       
                    if ($ath11_egress==1) {echo "brctl setgroupvidif br0 ath11 ".$group_vid." 1\n"; }   else if ($ath11_egress==2) {echo "brctl setgroupvidif br0 ath11 ".$group_vid." 0\n"; }       
                    if ($ath12_egress==1) {echo "brctl setgroupvidif br0 ath12 ".$group_vid." 1\n"; }   else if ($ath12_egress==2) {echo "brctl setgroupvidif br0 ath12 ".$group_vid." 0\n"; }       
                    if ($ath13_egress==1) {echo "brctl setgroupvidif br0 ath13 ".$group_vid." 1\n"; }   else if ($ath13_egress==2) {echo "brctl setgroupvidif br0 ath13 ".$group_vid." 0\n"; }       
                    if ($ath14_egress==1) {echo "brctl setgroupvidif br0 ath14 ".$group_vid." 1\n"; }   else if ($ath14_egress==2) {echo "brctl setgroupvidif br0 ath14 ".$group_vid." 0\n"; }       
                    if ($ath15_egress==1) {echo "brctl setgroupvidif br0 ath15 ".$group_vid." 1\n"; }   else if ($ath15_egress==2) {echo "brctl setgroupvidif br0 ath15 ".$group_vid." 0\n"; }                         
		            if ($ath16_egress==1) {echo "brctl setgroupvidif br0 ath16 ".$group_vid." 1\n"; }   else if ($ath16_egress==2) {echo "brctl setgroupvidif br0 ath16 ".$group_vid." 0\n"; }        
                    if ($ath17_egress==1) {echo "brctl setgroupvidif br0 ath17 ".$group_vid." 1\n"; }   else if ($ath17_egress==2) {echo "brctl setgroupvidif br0 ath17 ".$group_vid." 0\n"; }        
                    if ($ath18_egress==1) {echo "brctl setgroupvidif br0 ath18 ".$group_vid." 1\n"; }   else if ($ath18_egress==2) {echo "brctl setgroupvidif br0 ath18 ".$group_vid." 0\n"; }        
                    if ($ath19_egress==1) {echo "brctl setgroupvidif br0 ath19 ".$group_vid." 1\n"; }   else if ($ath19_egress==2) {echo "brctl setgroupvidif br0 ath19 ".$group_vid." 0\n"; }        
                    if ($ath20_egress==1) {echo "brctl setgroupvidif br0 ath20 ".$group_vid." 1\n"; }   else if ($ath20_egress==2) {echo "brctl setgroupvidif br0 ath20 ".$group_vid." 0\n"; }        
                    if ($ath21_egress==1) {echo "brctl setgroupvidif br0 ath21 ".$group_vid." 1\n"; }   else if ($ath21_egress==2) {echo "brctl setgroupvidif br0 ath21 ".$group_vid." 0\n"; }        
                    if ($ath22_egress==1) {echo "brctl setgroupvidif br0 ath22 ".$group_vid." 1\n"; }   else if ($ath22_egress==2) {echo "brctl setgroupvidif br0 ath22 ".$group_vid." 0\n"; }        
                    if ($ath23_egress==1) {echo "brctl setgroupvidif br0 ath23 ".$group_vid." 1\n"; }   else if ($ath23_egress==2) {echo "brctl setgroupvidif br0 ath23 ".$group_vid." 0\n"; }        
                    if ($ath24_egress==1) {echo "brctl setgroupvidif br0 ath24 ".$group_vid." 1\n"; }   else if ($ath24_egress==2) {echo "brctl setgroupvidif br0 ath24 ".$group_vid." 0\n"; }        
                    if ($ath25_egress==1) {echo "brctl setgroupvidif br0 ath25 ".$group_vid." 1\n"; }   else if ($ath25_egress==2) {echo "brctl setgroupvidif br0 ath25 ".$group_vid." 0\n"; }                         
		            if ($ath26_egress==1) {echo "brctl setgroupvidif br0 ath26 ".$group_vid." 1\n"; }   else if ($ath26_egress==2) {echo "brctl setgroupvidif br0 ath26 ".$group_vid." 0\n"; }       
                    if ($ath27_egress==1) {echo "brctl setgroupvidif br0 ath27 ".$group_vid." 1\n"; }   else if ($ath27_egress==2) {echo "brctl setgroupvidif br0 ath27 ".$group_vid." 0\n"; }       
                    if ($ath28_egress==1) {echo "brctl setgroupvidif br0 ath28 ".$group_vid." 1\n"; }   else if ($ath28_egress==2) {echo "brctl setgroupvidif br0 ath28 ".$group_vid." 0\n"; }       
                    if ($ath29_egress==1) {echo "brctl setgroupvidif br0 ath29 ".$group_vid." 1\n"; }   else if ($ath29_egress==2) {echo "brctl setgroupvidif br0 ath29 ".$group_vid." 0\n"; }       
                    if ($ath30_egress==1) {echo "brctl setgroupvidif br0 ath30 ".$group_vid." 1\n"; }   else if ($ath30_egress==2) {echo "brctl setgroupvidif br0 ath30 ".$group_vid." 0\n"; }       
                    if ($ath31_egress==1) {echo "brctl setgroupvidif br0 ath31 ".$group_vid." 1\n"; }   else if ($ath31_egress==2) {echo "brctl setgroupvidif br0 ath31 ".$group_vid." 0\n"; }                         
    	        }   // end of if ($group_vid!="")
    	    } // end of for loop
	    }  // end of if($vlan_state == 1)
	    else if($vlan_state == 1 && $vlan_mode==1) // NAP vlan mode
	    {	        
	        echo "iwpriv ath0 napstate 1\n";		              
	        echo "iwpriv ath16 napstate 1\n";		              
	    }
	    // MULTI_VLAN ---         
	} // end of ($ap_operate_mode_g != 1 && $ap_operate_mode_a != 1)

} // end of ($generate_start==1)
else
{
    
	//if ($multi_total_state_g == 1 && $multi_total_state_a == 1)
	if ($ap_operate_mode_g != 1 && $ap_operate_mode_a != 1)
	{       
	    // MULTI_VLAN +++
	    $vlan_state = query($sys."/vlan_state");  
	    $vlan_mode = query($sys."/vlan_mode");   
	    $auth_mode_g = query($WLAN_g."/authentication");	
	    $auth_mode_a = query($WLAN_a."/authentication");	
	    	    
	    $vlan_state = query($sys."/vlan_state"); 
	     
	    if($vlan_state == 1 && $vlan_mode==0)  	// static vlan mode                                            
	    {	        
            echo "\necho kill vlan  ...\n > /dev/console\n"; 
            // disable 
              
            // set default value
	        echo "brctl setpvidif br0 ath0   1 \n";
	        echo "brctl setpvidif br0 ath1   1 \n";
	        echo "brctl setpvidif br0 ath2   1 \n";
	        echo "brctl setpvidif br0 ath3   1 \n";
	        echo "brctl setpvidif br0 ath4   1 \n";
	        echo "brctl setpvidif br0 ath5   1 \n";
	        echo "brctl setpvidif br0 ath6   1 \n";
	        echo "brctl setpvidif br0 ath7   1 \n";
	        echo "brctl setpvidif br0 ath8   1 \n";
	        echo "brctl setpvidif br0 ath9   1 \n";
	        echo "brctl setpvidif br0 ath10  1 \n";
	        echo "brctl setpvidif br0 ath11  1 \n";
	        echo "brctl setpvidif br0 ath12  1 \n";
	        echo "brctl setpvidif br0 ath13  1 \n";
	        echo "brctl setpvidif br0 ath14  1 \n";
	        echo "brctl setpvidif br0 ath15  1 \n";
	        echo "brctl setpvidif br0 ath16  1 \n";
	        echo "brctl setpvidif br0 ath17  1 \n";
	        echo "brctl setpvidif br0 ath18  1 \n";
	        echo "brctl setpvidif br0 ath19  1 \n";
	        echo "brctl setpvidif br0 ath20  1 \n";
	        echo "brctl setpvidif br0 ath21  1 \n";
	        echo "brctl setpvidif br0 ath22  1 \n";
	        echo "brctl setpvidif br0 ath23  1 \n";
	        echo "brctl setpvidif br0 ath24  1 \n";
	        echo "brctl setpvidif br0 ath25  1 \n";
	        echo "brctl setpvidif br0 ath26  1 \n";
	        echo "brctl setpvidif br0 ath27  1 \n";
	        echo "brctl setpvidif br0 ath28  1 \n";
	        echo "brctl setpvidif br0 ath29  1 \n";
	        echo "brctl setpvidif br0 ath30  1 \n";
	        echo "brctl setpvidif br0 ath31  1 \n";
            
            //    del all group vid
            echo "brctl delallgroupvidif br0 ath0  \n";  
            echo "brctl delallgroupvidif br0 ath1  \n";  
            echo "brctl delallgroupvidif br0 ath2  \n";  
            echo "brctl delallgroupvidif br0 ath3  \n";  
            echo "brctl delallgroupvidif br0 ath4  \n";  
            echo "brctl delallgroupvidif br0 ath5  \n";  
            echo "brctl delallgroupvidif br0 ath6  \n";  
            echo "brctl delallgroupvidif br0 ath7  \n";  
            echo "brctl delallgroupvidif br0 ath8  \n";  
            echo "brctl delallgroupvidif br0 ath9  \n";  
            echo "brctl delallgroupvidif br0 ath10 \n";  
            echo "brctl delallgroupvidif br0 ath11 \n";  
            echo "brctl delallgroupvidif br0 ath12 \n";  
            echo "brctl delallgroupvidif br0 ath13 \n";  
            echo "brctl delallgroupvidif br0 ath14 \n";  
            echo "brctl delallgroupvidif br0 ath15 \n";  
            echo "brctl delallgroupvidif br0 ath16 \n";  
            echo "brctl delallgroupvidif br0 ath17 \n";  
            echo "brctl delallgroupvidif br0 ath18 \n";  
            echo "brctl delallgroupvidif br0 ath19 \n";  
            echo "brctl delallgroupvidif br0 ath20 \n";  
            echo "brctl delallgroupvidif br0 ath21 \n";  
            echo "brctl delallgroupvidif br0 ath22 \n";  
            echo "brctl delallgroupvidif br0 ath23 \n";  
            echo "brctl delallgroupvidif br0 ath24 \n";  
            echo "brctl delallgroupvidif br0 ath25 \n";  
            echo "brctl delallgroupvidif br0 ath26 \n";  
            echo "brctl delallgroupvidif br0 ath27 \n";  
            echo "brctl delallgroupvidif br0 ath28 \n";  
            echo "brctl delallgroupvidif br0 ath29 \n";  
            echo "brctl delallgroupvidif br0 ath30 \n";  
            echo "brctl delallgroupvidif br0 ath31 \n";  
            
	        echo "sleep 3\n";
	    }  // end of if($vlan_state == 1)
	    else if($vlan_state == 1 && $vlan_mode==1) // NAP vlan mode
	    {	        
	        echo "iwpriv ath0 napstate 0\n";	  
	        echo "iwpriv ath16 napstate 0\n";	  
	    } 
	    
	}  // end of if ($ap_operate_mode_g != 1 && $ $ap_operate_mode_a != 1)
	
}  // end of else ($generate_start!=1)
?>
