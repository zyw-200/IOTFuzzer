<?php
@include('sessionCheck.inc');
if (empty($_REQUEST['action'])) {
	echo 'failed';
}
else
{
	if($_REQUEST['action']=='clientInfo')
		{
			$clientName;
			$wpsClientName = explode(' ',conf_get("system:monitor:wpsClientName"));								//This will work on Board...
			for($x=1;$x<sizeof($wpsClientName);$x++)
			$clientName=$clientName." ".$wpsClientName[$x];
	
			echo $clientName;
			$wpsClientMacAddress = explode(' ',conf_get("system:monitor:wpsClientMacAddress"));				//This will work on Board...
			$wpsClientMacAddress[1] = str_replace('\\:',':',$wpsClientMacAddress[1]);
			echo ",".$wpsClientMacAddress[1];
		}
	else if($_REQUEST['action']=='associationState')
		{	
			$wpsStatus = explode(' ',conf_get("system:monitor:wpsAssociationStatus"));				//This will work on Board...
		    echo $wpsStatus[1];
			$wpsClientPin = explode(' ',conf_get("system:monitor:wpsClientPin"));								//This will work on Board...
			echo " ".$wpsClientPin[1];
		}
		
		if($_REQUEST['action']=='getWPSSession')
		{
				$wpsSessionStatus = explode(' ',conf_get("system:monitor:wpsSessionStatus"));					//This will work on Board...
				if($wpsSessionStatus[1]== 0)
					echo "success";
				else
					echo 'failed'	;
		}
		if($_REQUEST['action']=='pushBtnUpdate')
		{
				$wpsSessionStatus = explode(' ',conf_get("system:monitor:wpsSoftPushButton"));					//This will work on Board...
				if($wpsSessionStatus[1]== 1)
					echo "success";
				else
					echo 'failed'	;
		}
		if($_REQUEST['action']=='pinUpdate')
		{
				$wpsSessionStatus = explode(' ',conf_get("system:monitor:wpsPinMethod:" . $_REQUEST['pinValue']));					//This will work on Board...
				if($wpsSessionStatus[1]== 1)
					echo "success";
				else
					echo "failure";
		}

		if($_REQUEST['action']=='routerPin')
		{
				$wpsSessionStatus = explode(' ',conf_set("system:wpsSettings:wpsAPSetupLocked" , $_REQUEST['routerPinValue']));					//This will work on Board...
				$wpsSessionStatus = explode(' ',conf_save());					//This will work on Board...
				//if($wpsSessionStatus[1]== "10" || $wpsSessionStatus[1]=="01")
					echo "success";
				//else
					//echo "failure";
		}
		if($_REQUEST['action']=='wpsCancelByUser')
		{
				$wpsCancelStatus = explode(' ',conf_get("system:monitor:wpsCancel"));				//This will work on Board...
				if($wpsCancelStatus[1]== 1)
					echo "success";
				else
					echo "failure";
		}

}
?>
