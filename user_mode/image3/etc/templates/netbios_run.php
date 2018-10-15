#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");
$netbios_pid = "/var/run/netbios.pid";
$netbios_state = query("/netbios/state");
if ($generate_start==1)
{
		if ($netbios_state!=0)  /* Jack add 26/12/07 */
		{
		   echo "echo \"Enable NetBIOS!\" > /dev/console\n";
	     echo "netbios & > /dev/console\n";   
	     echo "echo $! > ".$netbios_pid."\n";
	  }
}
else
{
	 echo "if [ -f ".$netbios_pid." ]; then\n";/* Jack add 26/12/07 */
	 echo "kill \`cat ".$netbios_pid."\` > /dev/null 2>&1\n";
	 echo "rm -f ".$netbios_pid."\n";
	 echo "echo \"Disable NetBIOS!\" > /dev/console\n";
	 echo "fi\n\n";
}
?>
