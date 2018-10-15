<?
/* vi: set sw=4 ts=4: */
//echo "Username[".$LOGIN_USER."], Password[".$LOGIN_PASSWD."]\n";
$AUTH_RESULT="401";

$authnum=0;
$max_authnum=query("/proc/web/authnum");
$max_session=query("/proc/web/sessionum");
$cfg_radiusclient_enable=query("/wlan/inf:1/radiusclient_enable");
$index=1;
while($index<=$max_session)
{
	if(fread("/var/proc/web/session:".$index."/user/ac_auth")=="1"){$authnum++;}
	$index++;
}
$ac_auth=fread("/var/proc/web/session:".$sid."/user/ac_auth");
if($authnum>=$max_authnum && $ac_auth!="1"){$full="1";}

if($full=="1" || $sid=="-1")
{
	$AUTH_RESULT="full";
}
else
{
	$match="";
	if($LOGIN_USER!="")// && $password!="")
	{
		// check the user name and password.
		if($cfg_radiusclient_enable!=1)
		{
			for("/sys/user")
			{
				if($match=="")
				{
					$user_d=query("name");
	
					if($LOGIN_USER == $user_d)
					{
						$prefix="/var/proc/web/session:".$sid."/user";
						$password_d=query("password");
						if($LOGIN_PASSWD == $password_d)
						{
							$match="1";
							$group=query("group");
							fwrite($prefix."/name",     $LOGIN_USER);
							fwrite($prefix."/group",    $group);
							fwrite($prefix."/ac_auth",  "1");
						}
						else
						{
							$match="-1";
							unlink($prefix."/ac_auth");
						}
					}
				}
			}
		}
		else
		{
			set("/wlan/inf:1/radiusclient_username",$LOGIN_USER);
			set("/wlan/inf:1/radiusclient_password",$LOGIN_PASSWD);
			set("/runtime/web/sub_str","RADIUSCLIENT");
			$user_d=query("/sys/user:1/name");
			$password_d=query("/sys/user:1/password");
			$group=query("/sys/user:1/group");
			$prefix="/var/proc/web/session:".$sid."/user";
			fwrite($prefix."/name",     $user_d);
			fwrite($prefix."/group",    $group);
			fwrite($prefix."/ac_auth",  "1");
			$match="-1";
			$AUTH_RESULT="radiusclient";
		}
	}
	if($match=="1")	{$AUTH_RESULT="";}
}
?>
