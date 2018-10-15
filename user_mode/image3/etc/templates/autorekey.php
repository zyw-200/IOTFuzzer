#!/bin/sh
echo [$0] ... > /dev/console
<?
/* vi: set sw=4 ts=4: */
require("/etc/templates/troot.php");

$autorekey_smtpenable = query("/security/log/smtpInfo");
$autorekey_state=0;
$index=1;
if($band_type==0)
{
	$autorekey_not_first = query("/rumtime/wlan/inf:1/autorekey/first");
	$autorekey_time = query("/wlan/inf:1/autorekey/time");
	$autorekey_time = $autorekey_time*60-3;
	$autorekey_length = query("/wlan/inf:1/autorekey/length");
	$wlan_state = query("/wlan/inf:1/enable");
	$autorekey_mail = "/var/log/autorekey_mail_g";
	$auth_state=query("/wlan/inf:1/authentication");    
	if($auth_state==3 || $auth_state==5 || $auth_state==7)
	{
		if(query("/wlan/inf:1/autorekey/ssid/enable")==1)
		{
			$autorekey_state=1;
		}
	}

	for("/wlan/inf:1/multi/index")
	{
		$auth_multi_state=query("/wlan/inf:1/multi/index:".$index."/auth");
		if($auth_multi_state==3 || $auth_multi_state==5 || $auth_multi_state==7)
		{
			if(query("/wlan/inf:1/multi/index:".$index."/autorekey/enable")==1)
			{
				$autorekey_state=1;
			}	
		}
		$index++;
	}
}
else
{
	$autorekey_not_first = query("/rumtime/wlan/inf:2/autorekey/first");
	$autorekey_time = query("/wlan/inf:1/autorekey/time");
	$autorekey_time = $autorekey_time*60-3;
	$autorekey_length = query("/wlan/inf:2/autorekey/length");
	$wlan_state = query("/wlan/inf:2/enable");
	$autorekey_mail = "/var/log/autorekey_mail_a";
	$auth_state=query("/wlan/inf:2/authentication");    
	if($auth_state==3 || $auth_state==5 || $auth_state==7)
	{
		if(query("/wlan/inf:2/autorekey/ssid/enable")==1)
		{
			$autorekey_state=1;
		}
	}

	for("/wlan/inf:2/multi/index")
	{
		$auth_multi_state=query("/wlan/inf:2/multi/index:".$index."/auth");
		if($auth_multi_state==3 || $auth_multi_state==5 || $auth_multi_state==7)
		{
			if(query("/wlan/inf:2/multi/index:".$index."/autorekey/enable")==1)
			{
				$autorekey_state=1;
			}	
		}
		$index++;
	}
}

if($autorekey_smtpenable==1)
{
	$autorekey_smtpindex = query("/sys/log/smtpindex");
	$smtp_cmd = "email -V -f";
	$autorekey_node_test=1;
	$autorekey_fromemail = query("/sys/log/smtp/index:".$autorekey_smtpindex."/fromemail");
	if($autorekey_fromemail!="")
	{
		$smtp_cmd = $smtp_cmd." ".$autorekey_fromemail." -n";
	}
	$autorekey_username = query("/sys/log/smtp/index:".$autorekey_smtpindex."/username");
	if($autorekey_username!="")
	{
		$smtp_cmd = $smtp_cmd." ".$autorekey_username." -s \"Periodical change key\" -r";
	}
	$autorekey_mailserver = query("/sys/log/smtp/index:".$autorekey_smtpindex."/mailserver");
	if($autorekey_mailserver!="")
	{
		$smtp_cmd = $smtp_cmd." ".$autorekey_mailserver." -z \"".$autorekey_mail."\" -p";
	}
	$autorekey_mailport = query("/sys/log/smtp/index:".$autorekey_smtpindex."/mailport");
	if($autorekey_mailport!="")
	{
		$smtp_cmd = $smtp_cmd." ".$autorekey_mailport;
	}
	$autorekey_ssl = query("/sys/log/smtp/index:".$autorekey_smtpindex."/ssl");
	if($autorekey_ssl==1)
	{
		$smtp_cmd = $smtp_cmd." -tls";
	}
	$autorekey_smtpauthtype = query("/sys/log/smtp/index:".$autorekey_smtpindex."/authtype");
	if($autorekey_smtpauthtype==1)
	{
		$autorekey_pass1 = query("/sys/log/smtp/index:".$autorekey_smtpindex."/pass1");
		$smtp_cmd = $smtp_cmd." -m login -u ".$autorekey_username." -i ".$autorekey_pass1;
	}
	$autorekey_toemail = query("/sys/log/smtp/index:".$autorekey_smtpindex."/toemail");
	if($autorekey_toemail!="")
	{
		$smtp_cmd = $smtp_cmd." ".$autorekey_toemail;
	}
	if($autorekey_fromemail=="" || $autorekey_username=="" || $autorekey_mailserver=="" || $autorekey_mailport=="" || $autorekey_toemail=="")
	{
		$autorekey_node_test=0;
	}
}

if ($generate_start==1)
{
	if($wlan_state==1)
	{
		if ($autorekey_state!=0)
		{
			if($band_type==0)
			{
				$autorekey_not_first = query("/rumtime/wlan/inf:1/autorekey/first");
			}else
			{
				$autorekey_not_first = query("/rumtime/wlan/inf:2/autorekey/first");
			}
			if($autorekey_not_first==1)
			{
			
				echo "autorekeysleep ".$autorekey_time." \n";//-180
				if($band_type==0)
				{
					echo "autorekey 8 0\n";
				}else
				{	
					echo "autorekey 8 1\n";
				}
				echo "sleep 2\n";
				if($autorekey_node_test==1 && $autorekey_smtpenable==1)
				{
					echo $smtp_cmd." &\n";
				}
				echo "autorekeysleep 3\n";
			//	if($band_type==0)
			//	{
					echo "rgdb -i -s /rumtime/wlan/inf:1/autorekey/first 1\n";
			//	}else
			//	{
					echo "rgdb -i -s /rumtime/wlan/inf:2/autorekey/first 1\n";
			//	}
				echo "sh /etc/templates/wlan.sh restart 2> /dev/null";
			}
			else
			{
				if($band_type==0)
				{
					echo "autorekey 8 0\n";
				}else
				{
					echo "autorekey 8 1\n";
				}
				echo "sleep 2\n";
				if($autorekey_node_test==1 && $autorekey_smtpenable==1)
				{
					echo $smtp_cmd." &\n";
				}
				if($band_type==0)
				{
					echo "autorekeymatchtime 0\n";
				}else
				{
					echo "autorekeymatchtime 1\n";
				}
				echo "autorekeysleep ".$autorekey_time."\n";//rember -180
				if($band_type==0)
				{
					echo "autorekey 8 0\n";
				}else
				{
					echo "autorekey 8 1\n";
				}
				echo "sleep 2\n";
				if($autorekey_node_test==1 && $autorekey_smtpenable==1)
				{
					echo $smtp_cmd." &\n";
				}		
				echo "autorekeysleep 3\n";
			//	if($band_type==0)
			//	{
					echo "rgdb -i -s /rumtime/wlan/inf:1/autorekey/first 1\n";
			//	}else
			//	{
					echo "rgdb -i -s /rumtime/wlan/inf:2/autorekey/first 1\n";
			//	}
				echo "sh /etc/templates/wlan.sh restart 2> /dev/null";
			}
		}
		else
		{
			if($band_type==0)
			{
				echo "rgdb -i -s /rumtime/wlan/inf:1/autorekey/first 0\n";
			}else
			{
				echo "rgdb -i -s /rumtime/wlan/inf:2/autorekey/first 0\n";
			}
		}
	}
}
else
{
	echo "kill -9 `ps | grep autorekey | grep -v grep | cut -b 1-5`\n";
}
?>
