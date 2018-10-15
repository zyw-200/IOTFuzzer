<?php
@include('sessionCheck.inc');
if (empty($_REQUEST['action'])) {
	echo 'failed';
}else
{
	if($_REQUEST['action']=='cloudStatus'){	
		$activstate1 = explode(' ',exec("grep  system:basicSettings:cloudConnectivityUI /var/config"));
		 echo $activstate1[1];
	}
	if($_REQUEST['action']=='activationState'){	
		$activstate = explode(' ',exec("grep opswat.activation_state: /var/pal.cfg"));
		 echo $activstate[1];
	}
	else if($_REQUEST['action']=='connectionState'){	
		$connstate = explode(' ',exec("grep opswat.connection_state: /var/pal.cfg"));
		echo $connstate[1];
	}
	else if($_REQUEST['action']=='stanaloneUIState'){
		$standalonestate = explode(' ',exec("grep system:basicSettings:cloudConnectivityUI /var/config"));
		echo $standalonestate[1];
	}
}