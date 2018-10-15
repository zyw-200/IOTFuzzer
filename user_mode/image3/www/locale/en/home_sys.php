<?
$ssid_flag = query("/runtime/web/display/mssid_index4");
$m_context_title = "System Information";
$m_model_name	= "Model Name";
$m_sys_time	="System Time";
$m_up_time	="Up Time";
$m_firm_version	="Firmware Version";
$m_ip	="IP Address";
$m_mac	="MAC Address";
$m_ipv6_ip = "IPv6 IP Address";
$m_link_ip = "Link-Local IP Address";
if($ssid_flag == "1")
{
	$m_mssid	="SSID 1~3";
}
else
{
$m_mssid	="SSID 1~7";
}
$m_ap_mode ="Operation Mode";
$m_days = "Days";
$m_sysname = "System Name";
$m_location = "Location";
$m_ap = "Access Point";
$m_wireless_client = "Wireless client";
$m_wds_ap = "WDS with AP";
$m_wds = "WDS";
$m_ap_repeater = "AP Repeater";
?>
