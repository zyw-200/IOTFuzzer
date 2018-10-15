<? /* Maker :sam_pan Date: 2010/3/22 03:35 */

include "/etc/services/NEAP/neapscfg.php";

function neapsetup($name)
{
			
	$neaps_conf = "/var/run/neaps.conf";	
	$neaps_inf = mk_neapscfg($name, $neaps_conf);	
	
	fwrite("w", $_GLOBALS["START"],"#!/bin/sh\n");
	fwrite("w", $_GLOBALS["STOP"],"#!/bin/sh\n");	
	
	/* start script */				
	fwrite("a",$_GLOBALS["START"], 
		'neaps -i '.$neaps_inf.' -c '.$neaps_conf.' &\n'.
		'exit 0\n'
	);	
	
	/* stop script */		
	fwrite("a",$_GLOBALS["STOP"], 
		'killall neaps\n'.
		'rm -f '.$neaps_conf.'\n'.
		'exit 0\n'
	);
}	


?>