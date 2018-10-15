<?php
@include('sessionCheck.inc');
session_start();
$passwd = explode(' ', conf_get('system:basicSettings:adminPasswd'));
		$passwd = conf_decrypt($passwd[1]);
//system("/usr/local/bin/passwd_check admin password  >> /dev/null", $authCheck);

$connectionStatus=explode(' ',exec("grep opswat.activation_state: /var/pal.cfg"));
$configVersion=explode(' ',exec("grep opswat.config_version: /var/pal.cfg"));	
if($passwd=='password' && $_SESSION['username']=='admin'&& ($connectionStatus[1]!='activated' || $configVersion[1]<='0')){
$x="password";
}
function getProductID() {
	$confdEnable = true;
	if ($confdEnable) {
		$productIdArr = explode(' ', conf_get('system:monitor:productId'));
		$productId = $productIdArr[1];
	}
	else {
		$productId = "WG103";
	}
	return $productId;
}
//BEGIN Change from Moulidaren: Function to obtain the status of the Cloud. This will return either 1/0 depending on the status.
//cloud enabled=1 ; cloud disabled=0.
function getCloudStatus() {
	$localCloudStatusArr = explode(' ', conf_get('system:basicSettings:cloudStatus'));
	$localCloudStatus = $localCloudStatusArr[1];
	return $localCloudStatus;
}
function getCloudConnectivityUI() {
	$localCloudStatusArr = explode(' ', conf_get('system:basicSettings:cloudConnectivityUI'));
	$localCloudStatus = $localCloudStatusArr[1];
	return $localCloudStatus;
}
function getCloudConnectivityStatus() {
	$localCloudStatusArr = explode(' ', conf_get('system:basicSettings:cloudConnectivityStatus'));
	$localCloudStatus = $localCloudStatusArr[1];
	return $localCloudStatus;
}
//END of change from Moulidaren.
$APMode = conf_get("system:wlanSettings:wlanSettingTable:wlan0:apMode");
$mode_arr = explode(' ',$APMode);
$mode = $mode_arr[1];
echo <<<EOL
<?xml version="1.0"?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
EOL;
if ($mode == '5') { 
echo<<<EOL
<script>
var _apmode = false;
</script>
EOL;
} else {
echo<<<EOL
<script>
var _apmode = true;
</script>
EOL;
}
echo<<<EOL
<script type="text/javascript" src="include/scripts/prototype.js?code=6153137"></script>
<script type="text/javascript" src="include/scripts/prototype-ext.js?code=6153137"></script>
<script type="text/javascript" src="include/scripts/browser-ext.js?code=6153137"></script>
<script type="text/javascript" src="config.php?json=true&code=6153137"></script>
<script type="text/javascript" src="include/scripts/common.js?code=6153137"></script>
<script type="text/javascript" src="include/scripts/menu.js?code=6153137"></script>
<link rel="stylesheet" href="include/css/default.css?code=6153137" type="text/css">
<link rel="stylesheet" href="include/css/style.css?code=6153137" type="text/css">
<link rel="stylesheet" href="include/css/fonts.css?code=6153137" type="text/css">
<link rel="stylesheet" href="include/css/layout.css?code=6153137" type="text/css">
<script>
var _initiateMenu = true;
</script>
</head>
<body style="background: #FFFFFF; margin: 0px;">
<form name="navForm" action="header.php" method="post">
	<input type="hidden" name="menu1" id="menu1" value="Configuration">
	<input type="hidden" name="menu2" id="menu2" value="System">
	<input type="hidden" name="menu3" id="menu3" value="Basic">
	<input type="hidden" name="menu4" id="menu4" value="General">
	<input type="hidden" name="mode7" id="mode7" value="">
	<input type="hidden" name="mode8" id="mode8" value="">
	<input type="hidden" name="mode9" id="mode9" value="">
	<input type="hidden" id="logout">
		<!-- start main Frame -->
<table class="tableStyle" style="margin: 0px;">
	<tr class="topAlign">
					<td class="leftEdge"></td>
		<td valign="top">
			<table class="tableStyle">
				<tr>
					<td style="width: 8px;"><img src="images/clear.gif" width="8"/></td>
					<td>
						<table class="tableStyle" style="margin-top: 8px;">
							<tr>
								<td class="logoNetGear space50Percent topAlign"><img src="images/clear.gif" width="150" height="50"/></td>
EOL;
								if (getProductID() == 'WG102') {
									echo "<td class=\"vertionImage2 spacer50Percent topAlign rightHAlign\"><img src=\"images/clear.gif\" width=\"205\" height=\"50\"/></td>";
								}
								else {
									echo "<td class=\"vertionImage spacer50Percent topAlign rightHAlign\"><img src=\"images/clear.gif\" width=\"205\" height=\"50\"/></td>";
								}
echo <<<EOL
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/clear.gif" width="8"/></td>
					<td>
						<table class="tableStyle">
							<tr>
								<td class="bottomAlign">
									<table class="tableStyle">
										<tr>
											<td class="spacer80Percent bottomAlign" id="tabs">
												<ul class="tabs" id="primaryNav">
EOL;
/*if($x=="password"){
													echo"<li style=\"background: url(../../images/tab_r_A.gif) no-repeat right top;\"><a href=\"javascript:prepareURL('','','','Password','');\" target=\"master\"  style=\"background: url(../../images/tab_l_A.gif) no-repeat left top; color: #FFFFFF;\">Password</a></li>";
EOL;
}else{*/

													echo"<li style=\"background: url(../../images/tab_r_A.gif) no-repeat right top;\"><a href=\"javascript:prepareURL('','','','Configuration','');\" target=\"master\"  style=\"background: url(../../images/tab_l_A.gif) no-repeat left top; color: #FFFFFF;\">Configuration</a></li>
													<li ><a href=\"javascript:prepareURL('','','','Monitoring','');\" target=\"master\" >Monitoring</a></li>"; 
EOL;
//BEGIN Change from Moulidaren: Adding a condition to make the other tabs available only when the cloud is disabled. 
if (getCloudConnectivityUI()) {
													echo "<li ><a href=\"javascript:prepareURL('','','','Maintenance','');\" target=\"master\" >Maintenance</a></li>
													<li ><a href=\"javascript:prepareURL('','','','Support','');\" target=\"master\" >Support</a></li>
													" ;
}
//}
//END of change from Moulidaren.
echo <<<EOF
												</ul>
											</td>
											<td class="loginActionCell spacer20Percent rightHAlign padding5LeftRight"><a href="javascript:void(0);"><img src="images/logout_button.gif" style="border:0px; margin-right: 7px; margin-bottom: 4px; float:right;" onclick="processLogout();"></a></td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="background-blue">
					<td><img src="images/clear.gif" width="8"/></td>
					<td class="padding7Top spacer100Percent">
						<div class="submenu" submenu="yes">
							<ul id="secondaryNav">
EOF;
/*if($x=="password"){
echo "<li><a href=\"javascript:prepareURL('','','Password','Password','');\" target=\"master\"  style=\"color: #ffa400;\">Password</a></li>";
}else{*/
echo <<<EOF
								<li><a href="javascript:prepareURL('','','System','Configuration','');" target="master"  style="color: #ffa400;">System</a>
EOF;
								if (getCloudConnectivityUI()) {
								echo "&nbsp;<img src=\"images/tab_separator.gif\" class=\"separatorImage\">&nbsp;</li><li><a href=\"javascript:prepareURL('','','IP','Configuration','');\" target=\"master\" >IP</a>&nbsp;<img src=\"images/tab_separator.gif\" class=\"separatorImage\">&nbsp;</li>
								<li><a href=\"javascript:prepareURL('','','Wireless','Configuration','');\" target=\"master\" >Wireless</a>&nbsp;<img src=\"images/tab_separator.gif\" class=\"separatorImage\">&nbsp;</li>
								<li><a href=\"javascript:prepareURL('','','Security','Configuration','');\" target=\"master\" >Security</a>&nbsp;<img src=\"images/tab_separator.gif\" class=\"separatorImage\">&nbsp;</li>
								<li><a href=\"javascript:prepareURL('','','Wireless Bridge','Configuration','');\" target=\"master\" >Wireless Bridge</a></li>
								<li><a href=\"javascript:prepareURL('','','IPS','Configuration','');\" target=\"master\" >IPS</a></li>			
								<li>&nbsp;</li>";
								}
//}
echo <<<EOF
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td class="rightEdge"></td>
	</tr>
	<tr class="topAlign">
					<td valign="top" class="leftBodyNotch topAlign"></td>
					<td>
						<table class="tableStyle">
				<tr class="topAlign">
					<td class="leftNextBodyNotch"><img src="images/clear.gif" width="11" height="16"/></td>
					<td class="middleBodyNotch spacer100Percent"></td>
											<td class="rightNextBodyNotch"><img src="images/clear.gif" width="11"/></td>
				</tr>
						</table>
					</td>
					<td class="rightBodyNotch"></td>
			</tr>
</table>
</form>
</body>
</html>
EOF;
?>
