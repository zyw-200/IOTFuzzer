#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

/*$snmp_1_local_file = "/var/snmp.conf";*/
/*$snmp_1_conf = "/etc/snmp_1.conf";*/
$snmpd_var = "/var/snmpd.conf";
/*$snmp_local_file = "/var/net-snmp/snmpd.conf";*/
/*$snmpd_conf  = "/etc/snmpd.conf";*/
$snmpd_var_netsnmp = "/var/net-snmp/snmpd.conf";

$comm           = "":
$authtcl        = "";
$privtcl        = "";/*Jordan_2006_12_13*/
$rwcomm       	= query("/sys/snmpd/rwcomm");/*snmp_2007_03_26*/
$rocomm       	= query("/sys/snmpd/rocomm");/*snmp_2007_03_26*/
$cmtype         = "";
$cmviewname     = "";
$viewname       = "";
$rview          = "";
$wview          = "";
$nview          = "";
$mapgroup       = "";
$groupname      = "";
$username       = "";
$snmpipv6				= query("/sys/snmpv6");


if ($generate_start==1)
{
    fwrite ($snmpd_var, "#######     SNMPv3 Configuration     #########\n");
    fwrite2($snmpd_var, "engineIDType 3\n");
    fwrite2($snmpd_var, "engineIDNic ixp0\n");
    fwrite($snmpd_var, "#######  Traditional Access Control  #########\n");
    fwrite2($snmpd_var, "rwcommunity ".$rwcomm." default"." .1"."\n");
    fwrite2($snmpd_var, "rocommunity ".$rocomm." default"." .1"."\n");
    fwrite2($snmpd_var, "rwcommunity6 ".$rwcomm." default"." .1"."\n");
    fwrite2($snmpd_var, "rocommunity6 ".$rocomm." default"." .1"."\n");
    /*Sandy_2008_4_16 start*/ 
    fwrite2($snmpd_var, "trapcommunity ".$rocomm." default"." .1"."\n");

    $trapstatus = query("/sys/snmpd/status");
    if($trapstatus=="1")
    {
        $i=1;
        while (query("/sys/snmpd/hostip:".$i)!="")
        {
            fwrite2($snmpd_var,"trapsink ".query("/sys/snmpd/hostip:".$i.)." ".$rocomm."\n");
            fwrite2($snmpd_var,"trap2sink ".query("/sys/snmpd/hostip:".$i.)." ".$rocomm."\n");
            $i++;
        }
    }
    
    /*Sandy_2008_4_16 end*/
    /*This will be a problem because of only R/W comm*/
    for("/sys/snmpd/community")
    {
        $cmtype = query("/sys/snmpd/cmtype:".$@.);
        $cmviewname = query("/sys/snmpd/cmviewname:".$@.);
        $comm = query("/sys/snmpd/community:".$@.);
        
        for("/sys/snmpd/viewname")
        {
            $viewname = query("/sys/snmpd/viewname:".$@.);
            if($viewname==$cmviewname){
            	  if($snmpipv6=="1"){
                		if($cmtype=="1"){
                    		fwrite2($snmpd_var, "rocommunity6"." ".$comm." "."default"." ".".".query("/sys/snmpd/viewoid:".$@.)."\n");
                		}else if($cmtype=="2"){
                    		fwrite2($snmpd_var, "rwcommunity6"." ".$comm." "."default"." ".".".query("/sys/snmpd/viewoid:".$@.)."\n");
                		}
            		}else{
            				if($cmtype=="1"){
                    		fwrite2($snmpd_var, "rocommunity"." ".$comm." "."default"." ".".".query("/sys/snmpd/viewoid:".$@.)."\n");
                		}else if($cmtype=="2"){
                    		fwrite2($snmpd_var, "rwcommunity"." ".$comm." "."default"." ".".".query("/sys/snmpd/viewoid:".$@.)."\n");
                		}
            		}
            }
        }
    }
    fwrite2($snmpd_var, "#######      VACM Configuration      #########\n");

	//if(query("/sys/snmpd/v3status")=="1")  //for CLI can`t set SNMPv3 success bug,by Ken 20150529
	//{
		for("/sys/snmpd/secuname")
		{
			$username=query("/sys/snmpd/secuname:".$@.);
			$privtcl=query("/sys/snmpd/privprotocol:".$@.);/*Jordan_2006_12_13*/
			$authtcl=query("/sys/snmpd/authprotocol:".$@.);
			$mapgroup=query("/sys/snmpd/mapgroup:".$@.);
			for("/sys/snmpd/groupname"){
				$groupname=query("/sys/snmpd/groupname:".$@.);
				if($mapgroup==$groupname){
					$rview=query("/sys/snmpd/readview:".$@.);
					$wview=query("/sys/snmpd/writeview:".$@.);
					$nview=query("/sys/snmpd/notifyview:".$@.);
					if($wview==""){
						fwrite2($snmpd_var, "com2sec"." ".$username." "."default"." "."public\n");
					}else{
						fwrite2($snmpd_var, "com2sec"." ".$username." "."default"." "."private\n");
					}
				}
			}
			/*Jordan_2006_12_13*/
			if ($authtcl=="none"){
				fwrite2($snmpd_var_netsnmp, "createUser ".query("/sys/snmpd/secuname:".$@.)."\n");
			}else{
				if ($privtcl=="none"){
					fwrite2($snmpd_var_netsnmp, "createUser ".query("/sys/snmpd/secuname:".$@.)." ".query("/sys/snmpd/authprotocol:".$@.)." ".query("/sys/snmpd/authkey:".$@.)." \n");
				}else{
					fwrite2($snmpd_var_netsnmp, "createUser ".query("/sys/snmpd/secuname:".$@.)." ".query("/sys/snmpd/authprotocol:".$@.)." ".query("/sys/snmpd/authkey:".$@.)." ".$privtcl." ".query("/sys/snmpd/privkey:".$@.)." \n");
				}
			}
		}
		
		for("/sys/snmpd/secuname")
		{
			fwrite2($snmpd_var, "group"." ".query("/sys/snmpd/mapgroup:".$@.)." "."usm"." ".query("/sys/snmpd/secuname:".$@.)."\n");
		}

		for("/sys/snmpd/viewname")
		{
			fwrite2($snmpd_var, "view"." ".query("/sys/snmpd/viewname:".$@.)." ".query("/sys/snmpd/viewtype:".$@.)." ".".".query("/sys/snmpd/oid:".$@.)." "."80\n");
		}

		for("/sys/snmpd/groupname")
		{
			fwrite2($snmpd_var, "access"." ".query("/sys/snmpd/groupname:".$@.)." "."\"\""." "."any"." ".query("/sys/snmpd/seculevel:".$@.)." "."exact"." ".query("/sys/snmpd/readview:".$@.)." ".query("/sys/snmpd/writeview:".$@.)." ".query("/sys/snmpd/notifyview:".$@.)."\n");
		}
	//}
	
	echo "echo Start SNMP daemon ... > /dev/console\n";
    /*echo "cp ".$snmpd_var_netsnmp." /var/net-snmp/snmpd_bak.conf  > /dev/console\n";*/
    if($snmpipv6=="1"){
     	echo "snmpd -c /var/snmpd.conf -f -Le udp:161,udp6:161 &  > /dev/console\n";
    }else{ 	
    	echo "snmpd -c /var/snmpd.conf -f -Le &  > /dev/console\n";
	}
}
else
{
    echo "echo Stop SNMP daemon ... > /dev/console\n";
    echo "killall snmpd\n";
}

?>
# neaps_run.php <<<

