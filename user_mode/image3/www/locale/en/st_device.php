<?
$ssid_flag = query("/runtime/web/display/mssid_index4");
$m_context_title = "Device Information";
$m_ethernet = "Ethernet";
$m_wireless = "Wireless";
$m_2.4g = "(2.4GHz)";
$m_5g = "(5GHz)";
$m_status = "Device Status";
$m_fw_version = "Firmware Version";
$m_eth_mac = "Ethernet MAC Address";
$m_wlan_mac = "Wireless MAC Address";
$m_ap_array = "AP Array";
$m_role = "Role";
$m_master = "Master";
$m_backup_master = "Backup Master";
$m_slave = "Slave";
$m_location = "Location";
$m_pri = "Primary";
if($ssid_flag == "1")
{
	$m_ms	="SSID 1~3";
}
else
{
$m_ms = "SSID 1~7";
}
$m_ip = "IP Address";
$m_ipv6_ip = "IPv6 IP Address";
$m_ipv6_prefix = "IPv6 Prefix";
$m_ipv6_dns = "IPV6 DNS";
$m_ipv6_gateway = "IPv6 Gateway";
$m_link_ip = "Link-Local IP Address";
$m_link_prefix = "Link-Local Prefix";
$m_mask = "Subnet Mask";
$m_gate = "Gateway";
$m_dns = "DNS";
$m_na = "N/A";
$m_ssid = "Network Name (SSID)";
$m_channel = "Channel";
$m_rate = "Data Rate";
$m_sec = "Security";
$m_bits		= "bits";
$m_tkip		= "TKIP";
$m_aes		= "AES";
$m_cipher_auto	= "Auto";
$m_wpa		= "WPA-";
$m_wpa2		= "WPA2-";
$m_wpa_auto		= "WPA2-Auto-";
$m_eap		= "Enterprise/";
$m_psk		= "Personal /";
$m_open		="Open /";
$m_shared	="Shared Key /";
$m_disabled		="Disable";
$m_cpu = "CPU Utilization";
$m_memory = "Memory Utilization";
$m_none = "None";
$m_auto  ="Auto";
$m_54	= "54";
$m_48	= "48";
$m_36	= "36";
$m_24	= "24";
$m_18	= "18";
$m_12	= "12";
$m_9	= "9";
$m_6	= "6";
$m_11	= "11";
$m_5	= "5.5";
$m_2	= "2";
$m_1	= "1";
$m_swctrl = "Central WiFiManager";
$m_connect_status = "Connection Status";
$m_connect = "Connect";
$m_disconnect = "Disconnect";
$m_server_ip = "Server IP";
$m_service_port = "Service Port";
$m_live_port = "Live Port";
$m_group_id = "Group ID";
$m_bswctrl = "Backup Central WiFiManager";
?>
