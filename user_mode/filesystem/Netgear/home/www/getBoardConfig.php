<?php
	@include('sessionCheck.inc');
	require_once('config.php');
	//XML output of an existing MySql database
	header("Content-type: text/xml");
	echo '<?xml version="1.0"?>';
?>
<system>
	<result>
		<paramName>Region</paramName>
		<paramValue><?php $regInfo = explode(' ',conf_get("system:monitor:region")); echo ($regInfo[1] == "NA")?"North America (NA)":($regInfo[1] == "JP"?"Japan (JP)":"Worldwide (WW)") ?></paramValue>
	</result>
	<result>
		<paramName>MAC Address</paramName>
		<paramValue><?php $macAddress = explode(' ',conf_get("system:monitor:macAddress")); echo $macAddress[1]; ?></paramValue>
	</result>
	<result>
		<paramName>Firmware Version</paramName>
		<paramValue><?php $sysVersion = explode(' ',conf_get("system:monitor:sysVersion")); echo $sysVersion[1]; ?></paramValue>
	</result>
	<result>
		<paramName>Product ID</paramName>
		<paramValue><?php $sysVersion = explode(' ',conf_get("system:monitor:productId")); echo $sysVersion[1]; ?></paramValue>
	</result>
	<result>
                <paramName>Hardware Version</paramName>
                <paramValue><?php $sysHWVersion = explode(' ',conf_get("system:monitor:sysHardWareVersion")); echo str_replace('\:',':',$sysHWVersion[1]); ?></paramValue>
        </result>
        <result>
                <paramName>Serial Number</paramName>
                <paramValue><?php $sysSerno = explode(' ',conf_get("system:monitor:sysSerialNumber")); echo $sysSerno[1]; ?></paramValue>
        </result>
        <result>
                <paramName>WPS PIN</paramName>
                <paramValue><?php $wpsPin = explode(' ',conf_get("system:monitor:wpsPin")); echo $wpsPin[1]; ?></paramValue>
        </result>

<?php if ($config["TWOGHZ"]["status"]) { ?>
	<result>
		<paramName>2.4GHz Channel</paramName>
		<paramValue><?php
							$Channel = explode(' ',conf_get("system:wlanSettings:wlanSettingTable:wlan0:channel"));
							$autoChannel = explode(' ',conf_get("system:monitor:currentChannel:wlan0"));
							echo ($Channel[1] == "0")?'Auto ('.$autoChannel[1].')':$Channel[1];
						?></paramValue>
	</result>
	<result>
		<paramName>2.4GHz SSID</paramName>
		<paramValue><?php
                            $ssid = explode(' ',conf_get("system:vapSettings:vapSettingTable:wlan0:vap0:ssid"));
                            $ssidStr = str_replace('&nbsp;',' ',$ssid[1]);
                            $ssidStr = str_replace('&amp;','&',$ssidStr);
                            echo $ssidStr;
                    ?></paramValue>
	</result>
		<result>
			<paramName>WPA PASSPHRASE</paramName>
			<paramValue>
				<?php
                    $ssid = explode(' ',conf_get("system:vapSettings:vapSettingTable:wlan0:vap0:presharedKey"));
                    $ssidStr = str_replace('&nbsp;',' ',$ssid[1]);
                    $ssidStr = str_replace('&amp;','&',$ssidStr);
                    echo $ssidStr;
	             ?>
			</paramValue>
		</result>
<?php } ?>
<?php if ($config["FIVEGHZ"]["status"]) { ?>
	<result>
		<paramName>5GHz Channel</paramName>
		<paramValue><?php
							$Channel = explode(' ',conf_get("system:wlanSettings:wlanSettingTable:wlan1:channel"));
							$autoChannel = explode(' ',conf_get("system:monitor:currentChannel:wlan1"));
							echo ($Channel[1] == "0")?'Auto ('.$autoChannel[1].')':$Channel[1];
						?></paramValue>
	</result>
	<result>
		<paramName>5GHz SSID</paramName>
		<paramValue><?php
                            $ssid = explode(' ',conf_get("system:vapSettings:vapSettingTable:wlan1:vap0:ssid"));
                            $ssidStr = str_replace('&nbsp;',' ',$ssid[1]);
                            $ssidStr = str_replace('&amp;','&',$ssidStr);
                            echo $ssidStr;
                    ?></paramValue>
	</result>
		<result>
			<paramName>WPA PASSPHRASE</paramName>
			<paramValue>
				<?php
                    $ssid = explode(' ',conf_get("system:vapSettings:vapSettingTable:wlan1:vap0:presharedKey"));
                    $ssidStr = str_replace('&nbsp;',' ',$ssid[1]);
                    $ssidStr = str_replace('&amp;','&',$ssidStr);
                    echo $ssidStr;
	             ?>
			</paramValue>
		</result>

<?php } ?>
</system>
