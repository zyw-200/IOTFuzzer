#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$sys = "/sys";
$eth = "/lan/ethernet";
$lan2 = "/lan/ethernet:2";

if ($generate_start==1)
{    
	    
	    // MULTI_VLAN +++
	    $vlan_state = query($sys."/vlan_state"); 
	    $vlan_mode = query($sys."/vlan_mode"); 
	    $auth_mode = query($WLAN."/authentication");	
	    
	    // check vlan status
	    if ($vlan_state == 1 && $vlan_mode==1)  // dynamic vlan mode
	    {    
	        if ($auth_mode==1 || // shared key 
                $auth_mode==0 || // open
                $auth_mode==3 || //wpa-psk 
                $auth_mode==5 || //wpa2-psk  
                $auth_mode==7    //wpa-auto-psk     
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
	    $vlan_id_eth = query($eth."/vlan_id"); 
	    $vlan_id_lan2 = query($lan2."/vlan_id");
	    $vlan_id = query($WLAN."/vlan_id"); 
	    if($vlan_state == 1 && $vlan_mode==0) // static vlan mode
	    {	        
            echo "\necho start eth vlan setting...\n > /dev/console\n"; 
            
	        echo "brctl setvlanstate br0 1\n";
	        $group_vid_path = "/sys/group_vlan";
	        //   set vlan mode
	        if ($vlan_mode==0)
	            {echo "brctl setvlanmode br0 0\n";}
	        else
	            {echo "brctl setvlanmode br0 1\n";}	            
	            
            //	 set pvid
	        if ($vlan_id_eth!="")			
	        { 
	            echo "brctl setpvidif br0 eth0 ".$vlan_id_eth."\n";
	        }
 
	        if ($vlan_id_sys!="")			
	        { 
	            echo "brctl setsysvid br0 ".$vlan_id_sys."\n";
	        }      
	        $index=0;
               
		$ind=0;
	        for ($group_vid_path."/index") 
            {
    	        $ind++;
    	        echo "echo ind: ".$ind."  \n"; 
    	        $group_vid = query($group_vid_path."/index:".$ind."/group_vid");
    	        if ($group_vid!="")
    	        {
                    $eth0_egress= query($group_vid_path."/index:".$ind."/eth:1/egress");
		    $sys_egress=  query($group_vid_path."/index:".$ind."/sys:1/egress"); 
                    
		    if ($eth0_egress==1) 
			 {echo "brctl setgroupvidif br0 eth0 ".$group_vid." 1\n"; }   
                    else if ($eth0_egress==2)  {echo "brctl setgroupvidif br0 eth0 ".$group_vid." 0\n"; }

                    if ($sys_egress==1) 
			 {echo "brctl setgroupvidif br0 sys ".$group_vid." 1\n"; }    
		    else if ($sys_egress==2)   {echo "brctl setgroupvidif br0 sys ".$group_vid." 0\n"; }     
    	        }   // end of if ($group_vid!="")
    	    } // end of for loop
	    }  // end of if($vlan_state == 1)
	    else if($vlan_state == 1 && $vlan_mode==1) // NAP vlan mode
	    {	        
	        echo "brctl setvlanstate br0 1\n";
	            echo "brctl setvlanmode br0 1\n";	  
	    }
	    else  // if($vlan_state != 1) 
	        { 
	        echo "brctl setvlanstate br0 0 \n";  
	        }   

} 
else
{
	    // MULTI_VLAN +++
	    $vlan_state = query($sys."/vlan_state");  
	    $vlan_mode = query($sys."/vlan_mode");   
	    $auth_mode = query($WLAN."/authentication");	
	    	    
	    // check vlan status
	    if ($vlan_state == 1 && $vlan_mode==1)  // dynamic vlan mode
	    {    
	        if ($auth_mode==1 || // shared key 
                $auth_mode==0 || // open
                $auth_mode==3 || //wpa-psk 
                $auth_mode==5 || //wpa2-psk  
                $auth_mode==7    //wpa-auto-psk     
                )
            {   // if not wpa-eap -> disable vlan.
                //echo "\necho set($sys./vlan_state, 0  ...\n > /dev/console\n";  
                set($sys."/vlan_state", 0);  // 
                set($sys."/vlan_mode", 0);  // 
            } 
        } 
	    $vlan_state = query($sys."/vlan_state"); 
	    // end of // check vlan status	    
	     
	    if($vlan_state == 1 && $vlan_mode==0)  	// static vlan mode                                            
	    {	        
            echo "\necho kill vlan  ...\n > /dev/console\n"; 
            // disable 
            echo "brctl setvlanstate br0 0 \n";  
              
            // set default value
	        echo "brctl setpvidif br0 eth0   1 \n";
	        echo "brctl setsysvid br0 1\n";
            
            //    del all group vid
            echo "brctl delallgroupvidif br0 eth0  \n";
            echo "brctl delallgroupvidif br0 sys   \n";     
            
	        echo "sleep 3\n";
	    }  // end of if($vlan_state == 1)
	    else if($vlan_state == 1 && $vlan_mode==1) // NAP vlan mode
	    {	        
	        echo "brctl setvlanstate br0 0\n";	  
	    } 
	    
	
}  // end of else ($generate_start!=1)
?>
