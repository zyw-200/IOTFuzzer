<?
// category
$m_menu_top_bsc		="Setup";
$m_menu_top_adv		="Advanced";
$m_menu_top_tools	="MAINTENANCE";
$m_menu_top_st		="Status";
$m_menu_top_spt		="Support";

// basic
$m_menu_bsc_wizard      ="WIZARD";
$m_menu_bsc_internet	="INTERNET";
$m_menu_bsc_wlan	="WIRELESS SETUP";
$m_menu_bsc_lan		="LAN SETUP";

// advanced
$m_menu_adv_vrtsrv	="VIRTUAL SERVER";
$m_menu_adv_port	="PORT FORWARDING";
$m_menu_adv_app		="APPLICATION RULES";
$m_menu_adv_mac_filter	="NETWORK FILTER";
$m_menu_adv_acl		="FILTER";
$m_menu_adv_url_filter	="WEBSITE FILTER";
$m_menu_adv_dmz		="FIREWALL SETTINGS";
$m_menu_adv_wlan	="PERFORMANCE";
$m_menu_adv_network	="ADVANCED NETWORK";
$m_menu_adv_dhcp	="DHCP SERVER";
$m_menu_adv_mssid	="MULTI-SSID";
$m_menu_adv_group	="User Limit";
$m_menu_adv_wtp		="WLAN Switch";
$m_menu_adv_wlan_partition	="WLAN Partition";

// tools
$m_menu_tools_admin	="Device Administration";
$m_menu_tools_time	="TIME";
$m_menu_tools_system	="SYSTEM";
$m_menu_tools_firmware	="FIRMWARE";
$m_menu_tools_misc	="MISC";
$m_menu_tools_ddns	="DDNS";
$m_menu_tools_vct	="SYSTEM CHECK";
$m_menu_tools_sch	="SCHEDULES";
$m_menu_tools_log_setting	="LOG SETTINGS";

// status
$m_menu_st_device	="DEVICE INFO";
$m_menu_st_log		="LOG";
$m_menu_st_stats	="Statistics";
$m_menu_st_wlan		="CLIENT INFO";

// support
$m_menu_spt_menu	="MENU";

$m_logout	="Logout";

$m_menu_home	="Home";
$m_menu_tool	="Maintenance";
$m_menu_config	="Configuration";
$m_menu_sys	="System";
$m_menu_logout	="Logout";
$m_menu_help	="Help";

$cfg_ipv6 = query("/inet/entry:1/ipv6/valid");
$m_menu_tool_admin	="Administrator Settings";
if($cfg_ipv6==1)
{
	$m_menu_tool_fw	="Firmware Upload";
}
else
{
$m_menu_tool_fw	="Firmware and SSL Certification Upload";
}
$m_menu_tool_config	="Configuration File";
$m_menu_tool_sntp	="SNTP";

$m_menu_config_save	="Save and Activate";
$m_menu_config_discard	="Discard Changes";

$a_config_discard ="All of your changes will be discarded!Continue?";

?>
