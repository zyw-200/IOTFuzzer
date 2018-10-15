<?php
    function checkSessionLink()
	{
		if(file_exists('/tmp/sessionid')) {
			return true;
		}
		else {
			return false;
		}
	}

	function checkSessionExpired()
	{
		if (checkSessionLink()) {
			if ((time()-@filemtime('/tmp/sessionid'))>300) 
				return true;
			else 
				return false;
		}
		else
			return true;
	}
	
	$productIdArr = explode(' ', conf_get('system:monitor:productId'));
	$productId = $productIdArr[1];
	$passStr = conf_get("system:basicSettings:adminPasswd");
    	$str = explode(' ',$passStr);
	$str[1] = str_replace('\:',':',$str[1]);
	//$str[1] = str_replace('\\ ',' ',$str[1]);
	//$str[1] = str_replace('\\\\','\\',$str[1]);
        //$str[1] = 'password';
	//system("/usr/local/bin/passwd_check \"".$_REQUEST['username']."\" \"".$_REQUEST['password']."\" >> /dev/null", $authCheck);
	if($productId == 'WNAP320' || $productId == 'WNDAP350' || $productId == 'WNDAP360' || $productId == 'WNAP210' || strtoupper ($productId) == 'WNAP210V2' || $productId == 'WN604'){
    if ($_REQUEST['username'] == 'admin' && htmlentities($_REQUEST['password']) == htmlentities(conf_decrypt($str[1]))) {
    //if($authCheck=='0'){
                                     
        if (checkSessionExpired()===false) {
    		echo 'sessionexists';
        }
		else {
			session_start();
            $_SESSION['username']=$_REQUEST['username'];
			$fp = fopen('/tmp/sessionid', 'w');
			//fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR']);
			fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR'].','.$_SESSION['username']);
			fclose($fp);
			echo 'loginok';
		}
	}
	else {
		header('location:index.php');
	}
}
	else{
	function getLoginUser()
	{
		$file = '/tmp/sessionid';
		if (!file_exists($file)) return '';
		$fp = fopen($file, "rb");
		if (!$fp) return '';
		$str = file_get_contents($file);
		fclose($fp);
		$strArr = explode(",",$str);
		return $strArr[2];
	}
	function getPrevilige($val)
	{
		if($val==1)
		return "rw";
		else if($val==2)
		return "ro";
		else
		return "0";
	}
	
	$confdEnable = true;
	if ($confdEnable) {
	//user1
		$user1 		= 	conf_get("system:userSettings:user1");
		$user1		=	explode(' ',$user1);
		$user1pwd 	= 	conf_get("system:userSettings:user1pword");
		$user1pwd	=	explode(' ',$user1pwd);
		$user1status= 	conf_get("system:userSettings:user1status");
		$user1status=	explode(' ',$user1status);	
		$user1status=	getPrevilige($user1status[1]);
		
		//user2
		$user2 		= 	conf_get("system:userSettings:user2");
		$user2		=	explode(' ',$user2);
		$user2pwd 	=	conf_get("system:userSettings:user2pword");
		$user2pwd	=	explode(' ',$user2pwd);
		$user2status= 	conf_get("system:userSettings:user2status");
		$user2status=	explode(' ',$user2status);	
		$user2status=	getPrevilige($user2status[1]);
		
		//user3
		$user3 		= 	conf_get("system:userSettings:user3");
		$user3		=	explode(' ',$user3);
		$user3pwd 	= 	conf_get("system:userSettings:user3pword");
		$user3pwd	=	explode(' ',$user3pwd);
		$user3status= 	conf_get("system:userSettings:user3status");		
		$user3status=	explode(' ',$user3status);	
		$user3status=	getPrevilige($user3status[1]);
	
		//user4
		$user4 		= 	conf_get("system:userSettings:user4");
		$user4		=	explode(' ',$user4);
		$user4pwd 	=	conf_get("system:userSettings:user4pword");
		$user4pwd	=	explode(' ',$user4pwd);
		$user4status= 	conf_get("system:userSettings:user4status");		
		$user4status=	explode(' ',$user4status);	
		$user4status=	getPrevilige($user4status[1]);
			
		$usernames	=	array($user1[1],$user2[1],$user3[1],$user4[1]);
		$passwords	=	array($user1pwd[1],$user2pwd[1],$user3pwd[1],$user4pwd[1]);
		$previlige	=	array($user1status,$user2status,$user3status,$user4status);		
		
		//Admin
		$passStr = conf_get("system:basicSettings:adminPasswd");
	    $str = explode(' ',$passStr);
	}	
	//getting logged user
	$tmpusername=getLoginUser();
	$GLOBALS['loggedUserName']=$tmpusername;
	$tmpusername=getLoginUser();
	$_SESSION['username']   = $_REQUEST['username'];
	//system("/usr/local/bin/passwd_check \"".$_REQUEST['username']."\" \"".$_REQUEST['password']."\" >> /dev/null", $authCheck);
	//if($authCheck=='0'){
	
    if ($_REQUEST['username'] == 'admin' && $_REQUEST['password'] == conf_decrypt($str[1])) {
    	if (checkSessionExpired()===false) {
    		echo 'sessionexists';
    	}
		else {
			session_start();
			$_SESSION['username']   = $_REQUEST['username'];
			$fp = fopen('/tmp/sessionid', 'w');
			//fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR'].','.$_REQUEST['username']);
			fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR'].','.$_SESSION['username']);
			fclose($fp);
			echo 'loginok';
		}
	}
	else if ($_REQUEST['username'] != "admin") {
		if($tmpusername == 'admin')
			{
				echo "restricted";
			}
		else
		{
			$userpword = $_REQUEST['password'];
			for($i=0;$i<count($usernames);$i++)
			{
				if ($_REQUEST['username']== $usernames[$i] && $userpword == conf_decrypt($passwords[$i]))
				{
			    	if (checkSessionExpired()===false) {
			    		echo 'sessionexists';
			    	}
					else {
						session_start();
						$_SESSION['username']   = $_REQUEST['username'];
						$_SESSION['previlige']	= $previlige[$i];
						$_SESSION['user_logged']="user".$i;						
						$fp = fopen('/tmp/sessionid', 'w');
						//fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR'].','.$_REQUEST['username']);
						fwrite($fp, session_id().','.$_SERVER['REMOTE_ADDR'].','.$_SESSION['username'].','.$_SESSION['previlige'].','.$_SESSION['user_logged']);
						fclose($fp);
						echo 'loginok';
						break;
					}
				}
			}
		}
	}
	else {
		echo 'failed';
	}
}
?>
