#!/bin/sh
echo [$0] ... > /dev/console
# lldpd_run.php >>>
<?

if ($generate_start==1 && query("/sys/lldp/enable")==1)
{
	echo "echo Start LLDP Server ... > /dev/console\n";
	echo "lldpd &\n";
}
else
{
	echo "echo Stop LLDP Server ... > /dev/console\n";
	echo "killall lldpd> /dev/console\n";
}
?>
# lldpd_run.php <<<
