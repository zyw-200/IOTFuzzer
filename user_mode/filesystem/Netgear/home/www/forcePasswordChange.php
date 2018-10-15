<?php 
if (empty($_REQUEST['action'])) {

    echo 'failed';

}else
{
if($_REQUEST['action']=='passwo'){	
session_start();
$passwd = explode(' ', conf_get('system:basicSettings:adminPasswd'));
		$passwd = conf_decrypt($passwd[1]);
		//system("/usr/local/bin/passwd_check admin password  >> /dev/null", $authCheck);
		$connectionStatus=explode(' ',exec("grep opswat.activation_state: /var/pal.cfg"));
		$configVersion=explode(' ',exec("grep opswat.config_version: /var/pal.cfg"));
		if($passwd == 'password' && $_SESSION['username']=='admin' && ($connectionStatus[1]!='activated' || $configVersion[1]<='0')){
		$pswd="password";
		echo $pswd;
		}
}
else if($_REQUEST['action']=='alertDefaultpwd'){
		session_start();
		$passwd = explode(' ', conf_get('system:basicSettings:adminPasswd'));
		$passwd = conf_decrypt($passwd[1]);
		$connectionStatus=explode(' ',exec("grep opswat.activation_state: /var/pal.cfg"));
		$configVersion=explode(' ',exec("grep opswat.config_version: /var/pal.cfg"));
		if($passwd == 'password' && $_SESSION['username']=='admin' && ($connectionStatus[1]=='activated' && $configVersion[1]>'0')){
		$pswd="password";
		echo $pswd;
		}
}
}
?>