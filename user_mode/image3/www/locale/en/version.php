<?
/*$version_no=query("/runtime/sys/info/firmwareverreve");
$build_no=query("/runtime/sys/info/firmwarebuildno");*/
$check_band = query("/wlan/inf:2/ap_mode");
$m_context_title	="Version";

$m_context_title_mandatory="Mandatory";
$m_context_title_optional="Optional";
$m_firmware_external_version = "Firmware External Version: ";
$m_firmware_internal_version = "Firmware Internal Version: ";
$m_firmware_reversion = "Firmware Revision: ";
$m_date = "Date:";
$m_checksum = "CheckSum:";//jana modified
$m_wlan_domain = "WLAN Domain:";
$m_firmare_query = "Firmware Notify:";//jana modified
$m_kernel = "Kernel:";//jana added
$m_system_uptime = "System Uptime:";
$m_wan_mac = "WAN MAC: ";
$m_lan_mac = "LAN MAC: ";
$m_wlan_mac = "WLAN MAC: ";

$m_kernel = "Kernel: ";
$m_ssid = "SSID: ";
$m_default_setting = "Default Setting: ";
$m_ssid = "SSID: ";
$m_svn = "SVN: ";
$m_debug_mode = "Debug Mode: ";
$m_apps = "Apps: ";
$m_wlan_driver = "WLAN Driver: ";
$m_restore_default = "Restore Default:";//jana added

$m_c_mandatory = "<table width='80%'>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb width=200>".$m_firmware_external_version."</td><td>"."V".$version_no."</td></tr>";//jana added
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_firmware_internal_version."</td><td>".$build_no."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_firmware_reversion."</td><td>".$fw_reversion."</td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_date."</td><td>".$system_date."</td></tr>";
//$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_system_uptime."</td><td><script>document.write(shortTime());</script></td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_checksum."</td><td>"."<script>document.write(EncodeHex());</script></td></tr>";
$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_wlan_domain."</td><td>".$domain_region."</td></tr>";
//$m_c_mandatory = $m_c_mandatory."<tr><td>".$m_firmare_query."</td><td>http:\/\/".$fwinfo_srv.$fwinfo_path."?model=".$model_name."</td></tr>";
//$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_kernel."</td><td>".$kernel."</td></tr>";//jana added
//$m_c_mandatory2 = "<tr><td class=l_tb>".$m_firmare_query."</td><td><script>document.write(getQueryUrl());</script></td></tr>";

//$m_c_optional = "<tr><td class=l_tb>".$m_wan_mac."</td><td>".$wan_mac."</td></tr>";//jana removed

$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_lan_mac."</td><td>".$lan_mac."</td></tr>";
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wan_mac."</td><td>".$wan_mac."</td></tr>";//jana added
if($check_band != "")
{
	$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_wlan_mac."(2.4GHz):</td><td>".$wlan_mac."</td></tr>";
	$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_wlan_mac."(5GHz):</td><td>".$wlan_mac_5g."</td></tr>";
}
else
{
	$m_c_mandatory = $m_c_mandatory."<tr><td class=l_tb>".$m_wlan_mac."</td><td>".$wlan_mac."</td></tr>";
}
$m_c_optional = "<tr><td class=l_tb>".$m_apps."</td><td>".$apps_date."</td></tr>";//jana added
$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wlan_driver."</td><td>".$wlan_driver."</td></tr>";//jana added
//$m_c_optional = $m_c_optional."<tr><td >".$m_kernel."</td><td>".$."</td></tr>";
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_apps."</td><td>".$build_date."</td></tr>";//jana removed
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_wlan_driver."</td><td>".$wlan_driver."</td></tr>";//jana removed
if($check_band != "")
{
	$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_ssid."(2.4GHz):</td><td>".$ssid."</td></tr>";
	$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_ssid."(5GHz):</td><td>".$ssid_5g."</td></tr>";
}
else
{
	$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_ssid."</td><td>".$ssid."</td></tr>";
}
//$m_c_optional = $m_c_optional."<tr><td class=l_tb>".$m_restore_default."</td><td>".$restore_default."</td></tr>";//jana added

$m_c_optional = $m_c_optional."</table>";

$m_context = $m_c_mandatory;
$m_context2 = $m_c_mandatory2;//jana added
//length limit of $m_context, so add $m_context_next.
$m_context_next = $m_c_optional;

//$m_context = "Version : ".$version_no."<br><br>Build Number : ".$build_no."<br><br>";
//$m_context = $m_context."System Uptime : <script>document.write(shortTime());</script>";

$m_days		= "Days";
$m_button_dsc	=$m_continue;
?>
