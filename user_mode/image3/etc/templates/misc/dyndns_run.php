<? if ($rg_script!=1) { echo "#!/bin/sh\necho [$0] ... > /dev/console\n"; } ?># dyndns_run.php >>>
<?
/* vi: set sw=4 ts=4: */
$progfile="/var/run/dyndns.name";

echo "if [ -f ".$progfile." ]; then\n";
echo "	prog=`cat ".$progfile."`\n";
echo "	if [ \"$prog\" != \"\" ]; then\n";
echo "		killall $prog\n";
echo "	fi\n";
echo "	rm -f ".$progfile."\n";
echo "fi\n";

if ($generate_start==1 && query("/ddns/enable")==1)
{
	$provider=query("/ddns/provider");
	$HOST=query("/ddns/hostname");
	$USER=query("/ddns/user");
	$PASS=query("/ddns/password");
	$IPADDR=query("/runtime/wan/inf:1/ip");
	$TIME=query("/ddns/ipchecktime");
	if ($TIME=="") { $TIME="21600"; }	/* set default to 15 day */

	$vendor=query("/sys/vendor");
	$model=query("/sys/modelname");

	if ($provider!=1 && $provider!=2 && $provider!=3 &&
		$provider!=4 && $provider!=6 && $provider!=7)
	{
		echo "echo \"Unsupported dyndns provider #".$provider."\" > /dev/console\n";
		echo "# dyndns_run.php <<<\n";
		exit;
	}

	if ($USER!="" && $PASS!="")
	{
		echo "echo Start dyndns #".$provider."... > /dev/console\n";
		echo "dyndns -S".$provider;
		echo " -u ".$USER." -p ".$PASS." -i ".$IPADDR." -t ".$TIME;
		echo " -o /var/run/dyndns.html -d \"".$vendor." ".$model."\"";
		if ($HOST!="") { echo " -n ".$HOST; }
		echo " > /dev/console &\n";
		echo "echo dyndns > ".$progfile."\n";
	}
	else
	{
		echo "echo \"No user/password for dyndns ...\" > /dev/console\n";
	}
}
?>
# dyndns_run.php <<<
