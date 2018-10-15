<? /* Maker :sam_pan Date: 2010/3/22 03:35 */

include "/htdocs/phplib/trace.php";
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";
	
function mk_neapscfg($name, $neaps_conf)
{	
	
	/* the interface status <runtime><inf> */
	$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", $name, 0);			
	$addrtype = query($stsp."/inet/addrtype");
	if ($addrtype == "ipv4")	$ipaddr = query($stsp."/inet/ipv4/ipaddr");
	else						$ipaddr = query($stsp."/inet/ppp4/local");		
	$mask = query($stsp."/inet/ipv4/mask");				
	$netmask = ipv4int2mask($mask);				
	$phyinf = query($stsp."/phyinf");	
	
	/* the interface status <runtime><phyinf> */	
	$rphyinf = XNODE_getpathbytarget("/runtime", "phyinf", "uid", $phyinf, 0);												
	$rmacaddr = query($rphyinf."/macaddr");
	$rname = query($rphyinf."/name");
	
	/* Get other info*/
	$vender = "D-Link";
	$model = query("/runtime/device/modelname");
	$description = $vender." ".$model." Wireless Broadband Router";	
	$version = "v0.5.0";
				
	fwrite("w", $neaps_conf, 			
		'ipaddr='.$ipaddr.								'\n'.
		'netmask='.$netmask.                            '\n'.
		'macaddr='.$rmacaddr.                           '\n'.
		'vender='.$vender.                              '\n'.
		'description='.$description.                    '\n'.
		'model='.$model.                                '\n'.
		'version='.$version.                            '\n'		
	);		
		
	return $rname;			
}						
?>
