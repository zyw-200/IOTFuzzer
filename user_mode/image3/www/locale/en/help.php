<?
$check_band=query("/wlan/inf:2/ap_mode");
$switch = query("/runtime/web/display/switchable");
$runtime_ipv6=query("/runtime/web/display/ipv6");    
$cfg_ipv6=query("/inet/entry:1/ipv6/valid");
$head_bsc = "Basic Settings";

$title_bsc_cloud = "Cloud Manager";
$bsc_cloud_msg = "Enable this option allows this AP controlled by internet cloud management server. To configure and view your cloud APs, please log in at http://dlink.cloudcommand.com.";

$title_bsc_wlan ="Wireless Settings";
$bsc_wlan_msg ="Change the wireless settings on the device for an existing network or create a new network.";
$bsc_wireless_band ="Wireless Band";
if($check_band == "" && $switch != 1)
{
	$bsc_wireless_band_msg ="This is the operating frequency band. This Access Point (AP), operates 2.4GHz. 2.4GHz works best with legacy devices and suitable for longer ranges.";
}
else
{
	$bsc_wireless_band_msg ="This is the operating frequency band. This Access Point (AP), operates within 2 bands, 2.4GHz and 5GHz. "."2.4GHz works best with legacy devices and suitable for longer ranges. Select 5GHz for least interference and better performance.";
}
$bsc_application="Application";
$bsc_application_msg="This option allows the user to choose for indoor or outdoor mode at the 5G Band.";
$bsc_mode = "Mode";
if($cfg_ipv6=="1")
{
	$bsc_mode_msg = "Select between Access Point, Wireless Distribution System (WDS) with AP, WDS and Wireless Client mode (only support IPv4).";
}
else
{
$bsc_mode_msg = "Select between Access Point, Wireless Distribution System (WDS) with AP, WDS and Wireless Client mode.";
}
if($TITLE=="DAP-2310")
{
    $bsc_mode_msg = "Select between Access Point, Wireless Distribution System (WDS) with AP, WDS, Wireless Client (only support IPv4) and AP Repeater mode.";
}
$bsc_network_name = "Network Name/Service Set Identifier (SSID)";
$bsc_network_name_msg = "The SSID factory default is \"dlink\". Change the SSID to connect to existing wireless networks or establish a new wireless network.";
$bsc_ssid_visibility = "SSID Visibility";
$bsc_ssid_visibility_msg = "The SSID Visibility signal is enabled by default. Select Disable to make the Access Point invisible to all client devices.";
$bsc_auto_channel="Auto Channel Selection";
$bsc_auto_channel_msg="Enabled by default, when the device boots up, to automatically search for the best available channel.";
$bsc_channel="Channel";
$bsc_channel_msg ="Auto Channel Selection is set as default. Settings for the channel can be configured to work with existing wireless networks or customized a new wireless network.";
$bsc_channel_width="Channel Width";
if($check_band == "" && $switch != 1)
{
	$bsc_channel_width_msg="Setup the Channel bandwidths. Use 20MHz and Auto 20/40MHz for 802.11n and non-802.11n wireless devices. Connect Mixed 802.11b/g/n for 2.4GHz. "."When using Auto 20/40 MHz channel settings data can be transmitted using 40MHz.";
}
else
{
	$bsc_channel_width_msg="Setup the Channel bandwidths. Use 20MHz and Auto 20/40MHz for 802.11n and non-802.11n wireless devices. Connect Mixed 802.11b/g/n for 2.4GHz and Mixed 802.11a/n for 5GHz. "."Configure Auto 20/40/80 MHz for 802.11ac and non 802.11ac wireless devices, and Mixed 802.11ac for 5GHz. "."When using Auto 20/40 MHz channel settings data can be transmitted using 40MHz and when using Auto 20/40/80MHz data can be transmitted using 80MHz.";
}
$bsc_captive_profile="Captive Profile";
$bsc_captive_profile_msg="This is the front-end authentication method for the Primary SSID. Bind a captive portal policy profile to the Primary SSID after being defined in the Captive Portal Settings page.";
$bsc_authentication="Authentication";
$bsc_authentication_msg="Open System is the default authentication mode. Choose Data Encryption Mode to enable encryption.";
$bsc_open_sys="Open System";
$bsc_open_sys_msg="All devices are allowed to access the Access Point.";
$bsc_shared_key="Shared Key";
$bsc_shared_key_msg="Users must use the same WEP Share Key to access the Access Point on this network.";
$bsc_personal_type="WPA-Personal/WPA2-Personal/WPA-Auto-Personal";
$bsc_personal_type_msg="Wi-Fi Protected Access (WPA) uses AES/TKIP encryption to protect the network. WPA and WPA2 Personal uses different algorithms. WPA Auto-Personal uses both WPA and WPA2 authentication.";
$bsc_periodrical_change_key="Periodical Key Change";
$bsc_periodrical_change_key_msg="Periodical Key Change generates a random WPA key from the time the device is activated. An email is sent bearing the current key and Periodical Key Change information to the administrator."; 
$bsc_enterprise_type="WPA-Enterprise/ WPA2-Enterprise/ WPA-Auto-Enterprise";
$bsc_enterprise_type_msg="Wi-Fi Protected Access authorizes and authenticates users onto the wireless network. ".
"WPA uses stronger security than WEP and is based on a key that changes automatically at regular intervals.".
" Encryption relies on a RADIUS server for authentication but doesn't require an Accounting, Backup, or Backup Accounting server.";
$bsc_8021x_type="802.1x";
$bsc_8021x_type_msg="802.1x is an access control system used on Ethernet and wireless networks. A key is automatically generated from a server or switch. "."In order to use 801.1x, implement PAE and restart the Access Point. The AP then authenticates either to a RADIUS server, local server or switch. "."Select one of the options from the encryption menu to create an authentication sequence and key generation.";
$bsc_network_access="Network Access Protection";
$bsc_network_access_msg="Network Access Protection (NAP) is a feature of Windows Server 2008. NAP ".
"controls access to network resources based on a client computer's identity and compliance with corporate governance policy.".
" NAP allows network administrators to define granular levels of network access based on the client, the groups to which ".
"the client belongs, and the degree to which that client is compliant with corporate governance policy. If a client is not".
" compliant, NAP provides a mechanism to automatically bring the client back into compliance and then dynamically increase its level of network access.";
$bsc_mac_clone = "MAC Clone";
$bsc_mac_clone_msg = "Assign a mac address to the AP which is set to APC mode, for the communication with another AP as a network card. You can entry any address or choose an address in the scan list if select \"manually\". \"Auto\" means to assign the first mac address in that AP detected.";

$title_bsc_lan = "LAN Settings";
$title_bsc_lan_msg = "The default IP address is 192.168.0.50 and the subnet mask is 255.255.255.0. Alternatively use the given parameters provided to configure the LAN settings.";
$bsc_get_ip_from = "Get IP From";
$bsc_get_ip_from_msg = "Static IP is default. Set the IP address manually. Enable Dynamic IP (DHCP) for the host to automatically assign IP addresses.";
$bsc_ip_address = "IP Address";
$bsc_ip_address_msg = "The default IP address is 192.168.0.50. Configure the wireless clients accessing the AP to be within the same IP address and subnet mask range. The IP address range can be from 1-254.";
$bsc_submask = "Subnet Mask";
$bsc_submask_msg = "Subnet mask determines what subnet an IP address belongs to. The default subnet is 255.255.255.0.";
$bsc_gateway = "Default Gateway";
$bsc_gateway_msg = "The Default Gateway is the external IP address networks use. This is either provided by your ISP or network administrator.";
$bsc_dns = "DNS";
$bsc_dns_msg = "Domain Name System turns domain names, like dlink.com into an IP address that computers use to identify each other on the network.";
$title_bsc_ipv6 = "IPv6 LAN Settings";
$title_bsc_ipv6_msg = "IPv6 is the upgrade to IPv4. It specifies the formats of packets and the addressing scheme across multiple networks.";
$bsc_get_ip_from_msg_ipv6 = "IPv6 default setting is Auto. Select Static to manually configure IP addresses.";
$bsc_ip_address_msg_ipv6 = "Configure the IPv6 address. It is aparted to eight segments by \":\". Each segment has four characters: 0~9 or A~F.";
$bsc_prefix = "Prefix";
$bsc_prefix_msg = "The Prefix is between 0-128 and determines what subnet an IP address belongs to.";
$bsc_dns_ipv6 = "DNS";
$bsc_dns_ipv6_msg = "DNS used to parse the URL to IP.";
$bsc_gateway_ipv6_msg = "The Default Gateway is the external IP address networks use. This is either provided by your ISP or network administrator.";


$head_adv ="Advanced Settings";

$title_adv_per = "Performance";
$title_adv_per_msg = "Customize the Network Radio settings by tuning the Radio Parameters. Used by advanced users who are familiar with 802.11 wireless networks and radio settings.";
$adv_wireless = "Wireless";
$adv_wireless_msg ="Enable or disable wireless.";
$adv_wireless_mode ="Wireless Mode";
if($check_band == "" && $switch != 1)
{
	$adv_wireless_mode_msg ="Select the wireless mode. The ".query("/sys/hostname")." is backward compatible when enabling legacy wireless modes (802.11g/b). 802.11n wireless performance degradation is expected.";
}
else
{
	$adv_wireless_mode_msg ="Select the wireless mode. The ".query("/sys/hostname")." is backward compatible when enabling legacy wireless modes (802.11a/g/b). 802.11n wireless performance degradation is expected.";
}

$adv_date_rate="Data Rate";
$adv_date_rate_msg="Displays the base transfer rate on the Wireless Local Area Network. The AP adjusts the transfer rate depending on the connected devices performance. Interference from obstacles will impair performance.";
$adv_beacon = "Beacon Interval (40-500)";
$adv_beacon_msg = "Beacons are packets sent by an access point to synchronize a wireless network. Setting a higher beacon".
" interval can help to save the power of a wireless client while setting a lower one can help a wireless client connect ".
"to an access point faster. A 100 millisecond setting is recommended for most users.";
$adv_dtim="DTIM Interval (1-15)";
$adv_dtim_msg="The default DTIM interval value is 1. DTIM Interval specifies the number of AP beacons between each Delivery Traffic Indication Message (DTIM). "."It informs associated stations of the next window for listening to broadcast and multicast messages. You can specify a DTIM value range from 1 to 15."." The AP will send the next DTIM with the specified DTIM value to stations if there is any buffered broadcast or multicast message. Stations hear the beacons and get ready to receive the broadcast or multicast messages.";
$adv_transmit_power="Transmit Power";
$adv_transmit_power_msg="Determines the wireless transmission power level. Adjusting the power level eliminates overlapping of wireless area coverage between two access points. "."A 100% is default, 50%(-3dB), 25%(-6dB), and 12.5% (-9dB) is also available. For example, select 50% to cover half the wireless range.";
$adv_wmm="WMM (Wi-Fi Multimedia)";
$adv_wmm_msg="The Wi-Fi Multimedia feature improves the user experience for audio, video and voice applications over a ".
"Wi-Fi network. WMM is based on a subset of the IEEE 802.11e WLAN QoS standard. Enabling this feature improves a user".
" experience for audio and video applications over Wi-Fi.";
$adv_ack_timeout="Ack TimeOut";
if(query("/runtime/web/display/ack_timeout_range")=="0")
{
	if($check_band == "" && $switch != 1)
	{
		$adv_ack_timeout_msg="Specify an Ack Timeout value based on a range from 48-200 for 2.4GHz. This optimizes throughput over long disctance links. Ack Timeout is measured in microseconds(&micro;s). 48 &micro;s for 2.4GHz.";
	}
	else
	{
		$adv_ack_timeout_msg="Specify an Ack Timeout value based on a range from 48-200 for 2.4GHz and 25-200 for 5GHz. This optimizes throughput over long disctance links. Ack Timeout is measured in microseconds(&micro;s). 48 &micro;s for 2.4GHz and 25 &micro;s for 5GHz.";
	}
	
}
else
{
	if($check_band == "" && $switch != 1)
	{
		$adv_ack_timeout_msg="Specify an Ack Timeout value based on a range from 64-200 for 2.4GHz. This optimizes throughput over long disctance links. Ack Timeout is measured in microseconds(&micro;s). 64 &micro;s for 2.4GHz.";
	}
	else
	{
		$adv_ack_timeout_msg="Specify an Ack Timeout value based on a range from 64-200 for 2.4GHz and 50-200 for 5GHz. This optimizes throughput over long disctance links. Ack Timeout is measured in microseconds(&micro;s). 64 &micro;s for 2.4GHz and 50 &micro;s for 5GHz.";
	}

}

$adv_short_gi="Short GI";
$adv_short_gi_msg="A short GI of 400ns intervals increases throughput.";
$adv_igmp="IGMP Snooping";
$adv_igmp_msg="Internet Group Management Protocol (IGMP) snooping allows the AP to recognize IGMP queries and reports sent between routers and an ".
"IGMP host (wireless STA). When IGMP snooping is enabled, the AP will forward multicast packets to IGMP host based on IGMP messages passing through the AP.";
if($check_band == "" && $switch != 1)
{
	$adv_igmp_msg2="If IGMP snooping is disabled, user can manually select the multicast rate from drop-down manual. When IGMP snooping is enabled, multicast rate will disable and operate in suggested rate(11Mbps) and cannot be selected maually.";
}
else
{
	$adv_igmp_msg2="If IGMP snooping is disabled, user can manually select the multicast rate from drop-down manual. When IGMP snooping is enabled, multicast rate will disable and operate in suggested rate(11Mbps for 2.4GHz and 6Mbps for 5GHz) and cannot be selected maually.";
}
$adv_link_integrality="Link Integrity";
$adv_link_integrality_msg="Link Integrity, when enabled, terminates all wireless clients from the AP should the Ethernet connection between the LAN and AP disconnect.";
$adv_connection_limit="Connection Limit";
if(query("/runtime/web/display/utilization") !="0")
{
	$utilization_string="or the network utilization of this AP exceeds the percentage that you specify,";
}
else
{
	$utilization_string=" ";
}
$adv_connection_limit_msg="Connection Limit restricts a client to a specific AP.";
$adv_user_limit ="User Limit (0-64)";
$adv_user_limit_msg ="Set the limit for user access to each Access Point. 20 is recommended.";
$adv_11n_preferred = "11n Preferred";
$adv_11n_preferred_msg = "11n Preferred gives preference to 802.11n users and denies non-802.11n clients access to the AP when the maximum limit is reached.";
$adv_network_utilization="Network Utilization";
$adv_network_utilization_msg="This option sets the maximum network utilization value used by this access point. The access point will not allow new wireless "."clients to connect to it when the network utilization value exceeds the value specified. By default, this value is 100%.";
$adv_mcast_rate="Multicast rate";
if($check_band == "" && $switch != 1)
{
	$adv_mcast_rate_msg="Multicast rate adjusts multicast packet data rates. Multicast rate supports AP mode, Multi-SSID, and WDS with AP mode for 2.4GHz. When multicast rate is disabled, device will operate in suggested rate(11Mbps) and cannot be selected manually.";
}
else
{
	$adv_mcast_rate_msg="Multicast rate adjusts multicast packet data rates. Multicast rate supports AP mode, Multi-SSID, and WDS with AP mode for 2.4GHz and 5GHz. When multicast rate is disabled, device will operate in suggested rate(11Mbps for 2.4GHz and 6Mbps for 5GHz) and cannot be selected manually.";
}
$adv_mcast_control = "Multicast Bandwidth Control";
$adv_mcast_control_msg = "Multicast Bandwidth Control monitors multicast/broadcast packet flow from the Ethernet interface to the ".query("/sys/hostname").". It reduces load hence protecting the AP from unknown attacks. When enabled, multicast packets drop if they exceed the maximum multicast bandwidth.";
$adv_bandwidth_rate = "Maximum Multicast Bandwidth";
$adv_bandwidth_rate_msg = "Set the multicast packets maximum bandwidth pass through rate from the Ethernet interface to the Access Point.";
$adv_coexistence="HT20/40 Coexistence";
$adv_coexistence_msg="Enabled by default, HT20/40 coexistence monitors a channel's strength. Should it drop to 20MHz from its auto 20/40MHz baseline, adjustments are made back to auto. When Disabled this doesn't apply.";
$adv_m2u="Transfer DHCP Offer to Unicast";
$adv_m2u_msg="Suggest enable this function if stations number is larger than 30. Select Static IP if enable this function because Stations wouldn't get IP address through AP Client Mode.";

$title_adv_resource = "Wireless Resource Control";
$title_adv_resource_msg = "Wireless Resource Control is for advanced users familiar with 802.11 systems.";
$adv_5g_preferred = "Band Steering";
$adv_5g_preferred_msg = "Band steering allows stations to upscale their connection to the stronger 5G signal if the 2.4G signal isn't strong enough.";
$adv_5g_preferred_age = "Band Steering Age";
$adv_5g_preferred_age_msg = "Set the cycle in seconds.";
$adv_5g_preferred_diff = "Band Steering Difference";
$adv_5g_preferred_diff_msg = "The Band Steering Difference Value is equal to the amount of the 5GHz wireless clients connected, minus the amount of the 2.4GHz client connections. If there are more 5GHz clients trying to access the network, their connections are transferred to 2.4GHz.";
$adv_5g_preferred_ref = "Band Steering Refuse Number";
$adv_5g_preferred_ref_msg = "This option specifies the maximum number of times a wireless client will be rejected by this access point when attempting to connect to the 5GHz band.";
$adv_5g_preferred_rssi = "Band Steering RSSI";
$adv_5g_preferred_rssi_msg = "Specify the Band Steering RSSI percentage threshold limit. When a 2.4GHz connection is lower than the specified percentage limit the connection is bumped up to 5GHz.";
$adv_aging_out = "Aging Out";
$adv_aging_out_msg = "Aging Out limits clients with poor RSSI or Data Rates.";
$adv_rssi_thr = "RSSI Threshold";
$adv_rssi_thr_msg = "Specify the RSSI threshold.";
$adv_data_rate_thr = "Data Rate Threshold";
$adv_data_rate_thr_msg = "Specify the Data Rate threshold.";
$adv_acl_rssi = "ACL RSSI";
$adv_acl_rssi_msg = "ACL RSSI blocks requests from clients whose RSSI are below the threshold.";
$adv_acl_rssi_thr = "ACL RSSI Threshold";
$adv_acl_rssi_thr_msg = "Specify the ACL RSSI threshold.";

$title_adv_mssid="Multi-SSID";
/*$title_adv_mssid_msg="Multiple SSIDs are only supported in AP mode. One primary SSID and at most seven guest SSIDs ".
"can be configured to allow virtual segregation stations which share the same channel.";*/
if(query("/runtime/web/display/mssid_index4")==1)
{
$title_adv_mssid_msg="Multi-SSID limits one primary SSID and 3 guest SSID's on the same channel.";
}
else
{
$title_adv_mssid_msg="Multi-SSID limits one primary SSID and 7 guest SSID's on the same channel.";
}
$adv_mssid_msg1="Enable VLAN State for the ".query("/sys/hostname")." to communicate with VLAN devices.";
$adv_mssid_msg6="0-7 SSID with 0 being the lowest priority and 7 the highest.";
//$adv_mssid_msg2="When the Primary SSID is set to Open System without encryption, the Guest SSIDs can only be set to no encryption, WEP, WPA-Personal or WPA2-Personal.";
//$adv_mssid_msg3="When the Primary SSID's security is set to Open or Shared System WEP key, the Guest SSIDs can be set to use no encryption, use three other WEP keys, WPA-Personal, or WPA2-Personal.";
//$adv_mssid_msg4="When the Primary SSID's security is set to WPA-Personal, WPA2-Personal, or WPA-Auto-Personal, slot 2 and slot 3 are used. The Guest SSIDs can be set to use no encryption, WEP, or WPA-Personal.";
//$adv_mssid_msg5="When the Primary SSID's security is set to WPA-Enterprise, WPA2-Enterprise, or WPA-Auto-Enterprise, the Guest SSIDs can be set to use any security.";

$title_adv_vlan="VLAN";
$title_adv_vlan_msg="The ".query("/sys/hostname")." supports VLANs. VLANs can be created with a Name and VID. Mgmt (TCP stack), LAN, Primary / Multiple SSID and WDS Connection can be assigned to".
" VLAN as they are physical ports. Any packet that enters the ".query("/sys/hostname")." without a VLAN tag will have a VLAN tag inserted with a PVID.";

$title_adv_intrusion="Wireless Intrusion Protection";
$title_adv_intrusion_msg="Setup wireless intrusion protection.";

$title_adv_scheduling="Schedule";
$title_adv_scheduling_msg="Setup the ".query("/sys/hostname")."'s scheduling daily or weekly.";

$title_adv_qos="QoS";
$title_adv_qos_msg="QoS stands for Quality of Service for Wireless Intelligent Stream Handling, a technology developed to enhance the experience of using a wireless network by".
" prioritizing the traffic of different applications.";
$adv_enable_qoS="Enable QoS";
$adv_enable_qoS_msg="Enable this option if you want to allow QoS to prioritize your traffic.";
$adv_enable_qoS_msg1="Priority Classifiers.";
$adv_http="HTTP";
$adv_http_msg="Allows the access point to recognize HTTP transfers for many common audio and video streams and prioritize them above other traffic. Such".
" streams are frequently used by digital media players.";
$adv_automatic="Automatic";
$adv_automatic_msg="When enabled, this option causes the access point to automatically attempt to prioritize traffic streams that".
" it doesn't otherwise recognize, based on the behavior that the streams exhibit. This acts to de-prioritize streams that exhibit ".
"bulk transfer characteristics, such as file transfers, while leaving interactive traffic, such as gaming or VoIP, running at a normal priority.";
$adv_qos_rule="Qos Rules";
$adv_qos_rule_msg="A QoS Rule identifies a specific message flow and assigns a priority to that flow. For most applications, the priority classifiers ".
"ensure the right priorities and specific QoS Rules are not required.";
$adv_qos_rule_msg1="QoS supports overlaps between rules. If more than one rule matches a specific message flow, the rule with the highest priority will be used.";
$adv_name="Name";
$adv_name_msg="Create a name for the rule that is meaningful to you.";
$adv_priority="Priority";
$adv_priority_msg="The priority of the message flow is entered here. Four priorities are defined:";
$adv_priority_msg1="* BK: Background (least urgent).";
$adv_priority_msg2="* BE: Best Effort.";
$adv_priority_msg3="* VI: Video.";
$adv_priority_msg4="* VO: Voice (most urgent).";
$adv_protocol="Protocol";
$adv_protocol_msg="The protocol used by the messages.";
$adv_host_1_ip="Host 1 IP Range";
$adv_host_1_ip_msg="The rule applies to a flow of messages for which one computer's IP address ".
"falls within the range set here.";
$adv_host_1_port="Host 1 Port Range";
$adv_host_1_port_msg="The rule applies to a flow of messages for which host 1's port number is within the range set here.";
$adv_host_2_ip="Host 2 IP Range";
$adv_host_2_ip_msg="The rule applies to a flow of messages for which the other computer's IP address falls within the range set here.";   
$adv_host_2_port="Host 2 Port Range";
$adv_host_2_port_msg="The rule applies to a flow of messages for which host 2's port number is within the range set here.";
$title_adv_ap_array="AP Array";
$title_adv_ap_array_msg="An AP array is a set of Access Points connected to form a single-grouped network.";
$adv_enable_array="Enable Array"; 
$adv_enable_array_msg="Click the checkbox to enable AP Array. There are 3 modes: Master, Backup Master, and Slave. The Master AP controls all AP's associated to it. "."The Backup Master AP is what the name implies, backup to the Master AP. The Slave AP syncs with the Master AP and gets all its instructions from the Master AP.";
$adv_ap_array_name="AP Array Name";
$adv_ap_array_name_msg="Enter a username for the AP Array.";
$adv_ap_array_pwd="AP Array Password";
$adv_ap_array_pwd_msg="Enter a password for the AP Array.";
$adv_scan_ap_array_list="Scan AP Array List";
$adv_scan_ap_array_list_msg="Click this button to initiate a scan of all the available APs currently on the network.";
$adv_ap_array_list="AP Array List";
$adv_ap_array_list_msg="The AP Array List displays the array status.";
$adv_current_array_members="Current Array";
$adv_current_array_members_msg="The table displays a list of all AP's within the array.";
$adv_syn_parameters="Synchronized Parameters";
$adv_syn_parameters_msg="Select parameters associated to the AP Array. Click Clear All to clear all synchronized parameters.";

$title_adv_url="Web Redirection";
$title_adv_url_msg="";
$adv_enable_web = "Enable Web Redirection";
$adv_enable_web_msg = "Click to enable Web Redirection.";
$adv_web_site = "Web Site";
$adv_web_site_msg = "Enter a domain name or IP address.";
$adv_enable_auth = "Enable Web Authentication";
$adv_enable_auth_msg = "Click to enable Web Authentication.";
$adv_enable_url="Enable Web Redirection";
$adv_enable_url_msg="This check box allows the user to enable the Web Redirection function.";
$adv_url_username="User Name";
$adv_url_username_msg="Enter a username to authenticate Web Redirection.";
$adv_url_password="Password";
$adv_url_password_msg="Enter a password to authenticate Web Redirection.";
$adv_url_status="Status";
$adv_url_status_msg="Enable or Disable Web Redirection.";
$adv_url_account_list="Web Redirection Account List";
$adv_url_account_list_msg="Enable Web Redirection, enter a Username and Password under Add Web Redirection, and click Save. The new entry appears in the Web Redirection list. "
."Click the username to edit the account, and use the Enable/Disable radio button to edit Web Redirection. Click the icon to delete the current Web Redirection account.";

$title_adv_int_radius_server="Internal RADIUS Server";
$title_adv_int_radius_server_msg="The ".query("/sys/hostname")." has a built-in RADIUS server.";
$adv_user_name="User Name";
$adv_user_name_msg="Enter a user name to authenticate user access to the internal RADIUS server.";
$adv_pwd="Password"; 
$adv_pwd_msg="Enter a password to authenticate user access to the internal RADIUS server.";
$adv_status="Status";
$adv_status_msg="Use the drop-down menu to toggle between enabling and disabling the internal RADIUS server.";
$adv_radius_account_list="RADIUS Account List";
$adv_radius_account_list_msg="Enable RADIUS server, enter a Username and Password to add a RADIUS account. Click Save. The new entry appears in the RADIUS list. "
."Click the username to edit the account, and use the Enable/Disable radio button to edit the RADIUS account. Click the icon to delete the current RADIUS account.";

$title_adv_arp_spoofing="ARP Spoofing Prevention";
$title_adv_arp_spoofing_msg="ARP Spoofing Prevention deters ARP spoofing attacks  by adding IP/MAC address mapping.";
$adv_arp_spoofing="ARP Spoofing Prevention";
$adv_arp_spoofing_msg="Click the checkbox to enable ARP Spoofing Prevention.";
$adv_gateway_ip="Gateway IP Address";
$adv_gateway_ip_msg="Enter a Gateway IP address.";
$adv_gateway_mac="Gateway MAC Address";
$adv_gateway_mac_msg="Enter a Gateway MAC address.";

$title_adv_fair_air_time = "Bandwidth Optimization";
$title_adv_fair_air_time_msg = "The Bandwidth Optimization feature allows users to manage the total uplink and downlink bandwidth of the Access Point. It also allows users to balance the bandwidth on each SSID with different allocation methods.";
$adv_downlink_and_uplink_bandwidth = "Downlink and Uplink Bandwidth";
$adv_downlink_and_uplink_bandwidth_msg = "The Uplink consists of ETH and WDS interfaces. The Downlink consists of SSIDs. The bandwidth settings affect the AP's throughput.";
$adv_add_fair_air_time_rule = "Add Bandwidth Optimization Rule";
$adv_add_fair_air_time_rule_msg = "SSIDs have different rules. Add a rule for each SSID.";
$adv_rule_type = "Rule Type";
$adv_rule_type_msg1 = "Rule 1 -> Allocate the average uplink and downlink bandwidth speeds of each client.";
$adv_rule_type_msg2 = "Rule 2 -> Allocate the maximum uplink and downlink bandwidth speeds of each client.";
$adv_rule_type_msg3 = "Rule 3 -> Allocate the uplink and downlink bandwidths for different wireless bands (802.11a/b/g/n).";
$adv_rule_type_msg4 = "Rule 4 -> Allocate bandwidths for specific SSID clients. Each device competes for bandwidth.";
$adv_fair_air_time_band = "BAND";
if($check_band == "" && $switch != 1)
{
	$adv_fair_air_time_band_msg = "Support 2.4GHz Band.";
}
else
{
	$adv_fair_air_time_band_msg = "Select between 2.4GHz or 5GHz.";
}
$adv_fair_air_time_ssid = "SSID";
$adv_fair_air_time_ssid_msg = "Select an SSID for each band.";
$adv_downlink_speed = "Downlink Speed";
$adv_downlink_speed_msg = "Rules 1, 3, and 4 monitors the downlink speed of the selected SSIDs. Rule 2 monitors the downlink speed of each client.";
$adv_uplink_speed = "Uplink Speed";
$adv_uplink_speed_msg = "Rules 1, 3, and 4 monitors the uplink speed of the selected SSIDs. Rule 2 monitors the uplink speed of each station.";


$head_ap_array = "AP Array";
$head_ap_array_msg = "An AP array is a set of access points, connected to form a single-grouped network.";
$title_array_scan = "AP Array Scan";
$adv_array_enable = "Enable Array";
$adv_array_enable_msg = "Select the checkbox to enable the AP array. There are 3 modes: Master, Backup Master, and Slave. The Master AP controls all APs associated to it. The Backup Master AP is the backup to the Master AP. The Slave AP synchronizes with the Master AP and gets all its instructions from the Master AP.";
$adv_array_name = "AP Array Name";
$adv_array_name_msg = "Enter a username for the AP array.";
$adv_array_password = "AP Array Password";
$adv_array_password_msg = "Enter a password for the AP array.";
$adv_array_scan = "Scan AP Array List";
$adv_array_scan_msg = "Click this button to initiate a scan of all the available APs currently on the network.";
$adv_array_list = "AP Array List";
$adv_array_list_msg = "The AP array list displays the array status.";
$adv_array_current = "Current Array";
$adv_array_current_msg = "The table displays a list of all APs within the array.";

$title_array_config = "AP Array Configurations";
$adv_enbale_config = "Enable AP Array Configurations";
$adv_enbale_config_msg = "Enable this function to select parameters associated to the AP array.";
$adv_array_clear = "Clear All";
$adv_array_clear_msg = "Click <strong>Clear All</strong> to clear all synchronized parameters.";

$title_auto_rf = "Auto-RF";
$title_auto_rf_msg = "The Auto-RF function can optimize the AP's RF transmit power and channel for each AP in the AP array group.";
$adv_enable_autorf = "Enable Auto-RF";
$adv_enable_autorf_msg = "Enable or disable the Auto-RF function. If no AP array was configured, this option will take no effect.";
$adv_init_autorf = "Initiate Auto-RF";
$adv_init_autorf_msg = "When an AP array group is already running, this button will allow all APs in the group to automatically scan for channels used by other APs and then to initiaite the RF parameter in each AP.";
$adv_auto_init = "Auto-Initiate";
$adv_auto_init_msg = "This function will automatically optimize the RF in the period of time specified. The data link will be interrupted for a short time in the auto channel scan period.";
$adv_auto_period = "Auto-Initiate Period";
$adv_auto_period_msg = "A period of time allowed for auto-initialte.";
$adv_auto_rssi = "RSSI Threshold";
$adv_auto_rssi_msg = "When RSSI between AP is bigger than RSSI Threshold, the Auto-RF option will decrease the transmit RF power of AP or trigger auto channel selection.";
$adv_report_freq = "RF Report Frequency";
$adv_report_freq_msg = "Each slave AP report its environment to master AP at this frequency.";
$adv_auto_miss = "Miss Threshold";
$adv_auto_miss_msg = "If a slave AP misses to report its environment within this threshold time, it will be regarded as a miss.";

$title_balance = "Load Balance";
$title_balance_msg = "Dynamically adjusting the number of the stations connected to the device.";
$adv_balance_enable = "Enable Load Balance";
$adv_balance_enable_msg = "Enable or disable the load balance function.";
$adv_balance_thre = "Active Threshold";
$adv_balance_thre_msg = "If the number of clients that are connected to one device in the AP array group is bigger than the acitve threshold value, the load balance function will be initiated immediately.";

$head_captive = "Captive Portal";
$head_captive_msg = "Captive Portal is a built-in web authentication server. When a station connects to an AP, the web brower will be redirected to a web authentication page.";
$title_authentication = "Authentication Settings";
$adv_encryption_type = "Authentication Type";
$adv_encryption_type_msg = "This is the backend authentication method. The front-end authentication method is web.";
$adv_captive_add = "Save";
$adv_captive_add_msg = "Add the selected authentication type to the profile list.";
$adv_web_redirect = "WEB Redirection";
$adv_web_redirect_msg = "This function will redirect the URL to the specified web site. Click the option to enable this function.";
$adv_url_path = "URL Path";
$adv_url_path_msg = "Enter a IP address or a domain name.";
$adv_ticket = "Passcode";
$adv_ticket_msg = "A Time effectiveness passcode.";
$adv_quantity = "Passcode Quantity";
$adv_quantity_msg = "The number of passcode that will be added.";
$adv_duration = "Duration";
$adv_duration_msg = "The duration time of the passcode.";
$adv_last_active_day = "Last Active Day";
$adv_last_active_day_msg = "The deadline of the passcode.";
$adv_ticket_user_limit = "User Limit";
$adv_ticket_user_limit_msg = "How many users can use the connection at the same time.";
$adv_local = "Username/Password";
$adv_local_msg = "The username and password for local authentication.";
$adv_restricted_subnets = "Restricted Subnets";
$adv_restricted_subnets_msg = "The guest user cannot access the subnets listed below.";
$adv_local_username = "Username";
$adv_local_username_msg = "The username for the local rule.";
$adv_local_password = "Password";
$adv_local_password_msg = "The password for the local rule.";
$adv_group = "Group";
$adv_group_msg = "This can be a manager or a guest. Guests have limited access.";
$adv_remote_radius = "Remote RADIUS";
$adv_remote_radius_msg = "RADIUS authentication to the RADIUS server.";
$adv_type = "Remote RADIUS type";
$adv_type_msg = "SPAP set by default. Other types may be used in the next version.";
$adv_radius_server = "RADIUS Server";
$adv_radius_server_msg = "RADIUS server's IP address or domain name.";
$adv_radius_port = "RADIUS Port";
$adv_radius_port_msg = "RADIUS server's port number.";
$adv_radius_secret = "RADIUS Secret";
$adv_radius_secrett_msg = "RADIUS server's shared secret.";
$adv_accouting = "Accounting Mode";
$adv_accouting_msg = "Disable or enable accounting.";
$adv_accouting_server = "Accounting Server";
$adv_accouting_server_msg = "Accounting server's IP address or domain name.";
$adv_accouting_port = "Accounting Port";
$adv_accouting_port_msg = "Accounting server's port number.";
$adv_accouting_secret = "Accounting Secret";
$adv_accouting_secret_msg = "Accounting server's shared secret.";
$adv_ldap = "LDAP";
$adv_ldap_msg = "Use the LDAP server, like Windows Active Directory or Open LDAP.";
$adv_ldap_server = "Server";
$adv_ldap_server_msg = "LDAP server's IP address or domain name.";
$adv_ldap_port = "Port";
$adv_ldap_port_msg = "LDAP server's port number.";
$adv_ldap_mode = "Authenticate Mode";
$adv_ldap_mode_msg = "Simple or SSL/TLS for transport.";
$adv_ldap_username = "Username";
$adv_ldap_username_msg = "Administrator's name of the LDAP server.";
$adv_ldap_password = "Password";
$adv_ldap_password_msg = "Administrator's password of the LDAP server.";
$adv_basedn = "Base DN";
$adv_basedn_msg = "Administrator's domain name of the LDAP server.";
$adv_account = "Account Attribute";
$adv_account_msg = "The LDAP attribute will search string for client.";
$adv_identity = "Identity";
$adv_identity_msg = "The full patch of the administrator.";
$adv_auto_copy = "Auto Copy";
$adv_auto_copy_msg = "The generic full path of the web page that uses the username and base DN.";
$adv_pop3 = "POP3";
$adv_pop3_msg = "Authentication configured to use POP3 server.";
$adv_pop3_server = "Server";
$adv_pop3_server_msg = "POP3 server's IP address or domain name.";
$adv_pop3_port = "Port";
$adv_pop3_port_msg = "POP3 server's port number.";
$adv_connection_type = "Connection Type";
$adv_connection_type_msg = "None or SSL/TLS for transport.";

$title_login_upload = "Login Page Upload";
$title_login_upload_msg = "Customized login page can be uploaded.";

$title_web_redirect = "WEB Redirect";
$title_web_redirect_msg = "This function will redirect the URL to the specified web site. Click the option to enable this function.";
$adv_web_site = "Web Site";
$adv_web_site_msg = "Enter a IP address or a domain name.";

$title_ipfilter = "IP Filter";
$title_ipfilter_msg = "Users cannot access the IP address listed below.";
$ipfilter_ip_address = "IP Address";
$ipfilter_ip_address_msg = "Enter a IP address.";
$ipfilter_ip_mask = "Subnet Mask";
$ipfilter_ip_mask_msg = "Enter a Subnet Mask.";
$ipfilter_upload_download="IP Filter File Upload and Download";
$ipfilter_upload_download_msg="Update the IP address list by browsing to the location where the saved file resides. Click Open, select the file and click Upload. Alternatively, "."click Download IP Filter File to download the file to the local hard drive. 64 are the maximum IP address entries possible for each ssid.";

$title_macbypass = "MAC Bypass";
$title_macbypass_msg = "The MAC Address in the list can turn to internet without authentication of Captive Portal.";
$macbypass_mac = "MAC Address";
$macbypass_mac_msg = "Enter a MAC address.";
$macbypass_upload_download="MAC Bypass File Upload and Download";
$macbypass_upload_download_msg="Update the MAC list by browsing to the location where the saved file resides. Click Open, select the file and click Upload. Alternatively, "."click Download MAC File to download the file to the local hard drive. 64 are the maximum MAC entries possible for each ssid.";

$head_dhcp="DHCP Server";
$head_dhcp_msg="Dynamic Host Control Protocol (DHCP) server assigns IP addresses to connected DHCP clients. DHCP Server is disabled by default on the ".query("/sys/hostname").".";

$title_dhcp_dynamic_pool="Dynamic Pool Settings";
$title_dhcp_dynamic_pool_msg="The DHCP Dynamic Pool is a range of IP addresses used to assign IP's to DHCP clients with leased time control.";
$dhcp_server_control="DHCP Server Control";
$dhcp_server_control_msg="The default setting for DHCP Server is disable.";
$dhcp_ip_assigned="IP Assigned From";
$dhcp_ip_assigned_msg="Specify the start of the IP address pool.";
$dhcp_range_of_pool="The Range of Pool (1-254)";
$dhcp_range_of_pool_msg="Specify the IP address range.";
$dhcp_submask="Subnet Mask";
$dhcp_submask_msg="Specify the Subnet Mask for the IP Assigned From field.";
$dhcp_gateway="Gateway";
$dhcp_gateway_msg="Specify the wireless network Gateway address.";
$dhcp_wins="WINS";
$dhcp_wins_msg="Specify the wireless network WINS server.";
$dhcp_dns="DNS";
$dhcp_dns_msg="Specify the wireless network DNS server.";
$dhcp_domain="Domain Name";
$dhcp_domain_msg="Specify the wireless network Domain Name.";
$dhcp_lease_time="Lease Time";
$dhcp_lease_time_msg="Define a lease time for each IP address.";

$title_dhcp_static_pool="Static Pool Settings";
$title_dhcp_static_pool_msg="Static Pool Settings assigns IP addresses to connected clients. Clients receive IP addresses without time constraints.";
$host_name="Host Name";
$host_name_msg="Create a name for the rule that is meaningful to you.";
$dhcp_assigned_ip="Assigned IP";
$dhcp_assigned_ip_msg="Type the IP address for a specific client's MAC address in the Assigned MAC Address field.";
$dhcp_assigned_mac="Assigned MAC Address";
$dhcp_assigned_mac_msg="Type the MAC address of the client.";
$dhcp_submask_static_msg="Type the Subnet Mask in the IP Assigned From field.";
$dhcp_gateway_static_msg="Specify the wireless network's Gateway.";
$dhcp_wins_static_msg="Specify the wireless network's WINS server address.";
$dhcp_dns_static_msg="Specify the wireless network's DNS server.";
$dhcp_domain_static_msg="Specify the wireless network's Domain Name.";

$title_dhcp_current_ip="Current IP Mapping";
$title_dhcp_current_ip_msg="The Current IP Mapping consists of Dynamic and Static DHCP IP addresses, MAC addresses and their lease times.";


$head_filters="Filters";
$head_filters_msg="Filters configures two sections, MAC Address Filtering and Wireless LAN Partitions. "."Accept or block client devices based on their MAC addresses. Wireless LAN Partitions, accept or block client devices to wired or wireless networks.";

$title_filters_wireless_access="Wireless MAC ACL";
$filters_wireless_band="Wireless Band";
if($check_band == "" && $switch != 1)
{
	$filters_wireless_band_msg="The ".query("/sys/hostname")." operates well with legacy devices in environments with least interference in 2.4GHz.";
}
else
{
	$filters_wireless_band_msg="The ".query("/sys/hostname")." operates in two frequency bands. 2.4GHz operates well with legacy devices and in long distances while 5GHz operates well in environments with least interference.";
}
$filters_acl_list="Access Control List";
$filters_acl_list_msg= "Can be configured to deny or only allow wireless stations association by filtering MAC addresses. By selecting \"Accept\"".
",can only be associated with MAC addresses listed in the Authorization table. By selecting \"Reject\",will only disassociate with MAC addresses listed.";
$filters_acl_mac = "MAC Address";
$filters_acl_mac_msg = "Enter a MAC address.";
$filters_acl_client_information="Current Client Information";
$filters_acl_client_information_msg="Displays the associated clients SSID, MAC, band, authentication method and
signal strength for the ".query("/sys/hostname")." network.";

$filters_acl_upload_download="Wireless MAC ACL File Upload and Download";
if(query("/runtime/web/display/acl_perssid") == 1)
{
	$filters_acl_upload_download_msg="Update the MAC ACL list by browsing to the location where the saved file resides. "
."Click Open, select the file and click Upload. Alternatively, click Download ACL File to download the file to the local hard drive. "
."Configure the ACL file policy by selecting OFF/ALLOW/DENY. 64 are the maximum ACL entries possible.";
}
else
{
	$filters_acl_upload_download_msg="Update the MAC ACL list by browsing to the location where the saved file resides. "
."Click Open, select the file and click Upload. Alternatively, click Download ACL File to download the file to the local hard drive. "
."Configure the ACL file policy by selecting OFF/ALLOW/DENY. 256 are the maximum ACL entries possible.";
}

$title_filters_wlan_partition="WLAN Partition";
$filters_internal_station="Internal Station Connection";
$filters_internal_station2_msg="The Internal Station Connection has three modes:";
$filters_internal_station2_msg1="* Enable: Select this option to allow communication between wireless clients connected to the same SSID and between wireless clients connected to different SSIDs configured on this access point.";
$filters_internal_station2_msg2="* Disable: Select this option to disallow communication between wireless clients connected to the same SSID, but to allow communication between wireless clients connected to different SSIDs configured on this access point.";
$filters_internal_station2_msg3="* Guest: Select this option to disallow communication between wireless clients connected to the same SSID and between wireless clients connected to different SSIDs configured on the access point.";
$filters_internal_station_msg="The Internal Station Connection has three modes:<\br>".
"Enable: Select this option to allow communication between wireless clients connected to the same SSID and between wireless clients connected to different SSIDs configured on this access point.<\br>".
"Disable: Select this option to disallow communication between wireless clients connected to the same SSID, but to allow communication between wireless clients connected to different SSIDs configured on this access point.<\br>".
"Guest: Select this option to disallow communication between wireless clients connected to the same SSID and between wireless clients connected to different SSIDs configured on the access point.";
$filters_eth_to_wlan="Ethernet to WLAN Access";
$filters_eth_to_wlan_msg="Enabled by default, wireless devices can sync with the Ethernet port of the Access Point. Disabled, multicast and broadcast packets are dropped but DHCP clients are able to communicate with the Access Point. The default value is \"Enable\", "."which allows data flow from the Ethernet to wireless stations connected to the AP. By disabling this function, all multicast and broadcast packets from the Ethernet to associated wireless devices are blocked, except DHCP's.";

$head_traffic_control="Traffic Control";
$head_traffic_control_msg="Manage Traffic Manager Rules, Uplink/Downlink settings, and Quality of Service (QoS) settings.";

$title_traffic_updownlink_st="Uplink/Downlink Setting";
$title_traffic_updownlink_st_msg="Customize the Downlink/Uplink interfaces as well as bandwidth rates in Mbits per second.";

$title_traffic_qos="QoS";
$title_traffic_qos_msg="Quality of Service (QoS) Intelligent Stream Handling prioritizes traffic to different applications. ".query("/sys/hostname")." supports four Priority Levels.";

$adv_priority="Priority";
$adv_priority2_msg="Message Flow Priority Level:";
$adv_priority2_msg1="* Highest Priority (most urgent).";
$adv_priority2_msg2="* Second Priority.";
$adv_priority2_msg3="* Third Priority.";
$adv_priority2_msg4="* Low Priority (least urgent).";
$traffic_qos_enable="Enable Qos";
$traffic_qos_enable_msg="Click the checkbox to enable QoS. Use the drop-down menu to select one of the priority levels. Click Save.";
$traffic_qos_down_banwidth="Downlink Bandwidth";
$traffic_qos_down_banwidth_msg="Measured in Mbits per second.";
$traffic_qos_up_banwidth="Uplink Bandwidth";
$traffic_qos_up_banwidth_msg="Measured in Mbits per second.";

$title_traffic_manager="Traffic Manager";
$title_traffic_manager_msg="Create Traffic Management Rules based on Listed Client Traffic and/or Downlink/Uplink speeds.";

$traffic_traffic_manager="Traffic Manager";
$traffic_traffic_manager_msg="Enable Traffic Manager.";
$traffic_unlisted_clients_traffic="Unlisted Clients Traffic";
$traffic_unlisted_clients_traffic_msg="Select Deny or Forward.";
$traffic_down_bandwidth="Downlink Bandwidth";
$traffic_down_bandwidth_msg="Measured in Mbits per second.";
$traffic_up_bandwidth="Uplink Bandwidth";
$traffic_up_bandwidth_msg="Measured in Mbits per second.";
$traffic_name="Name";
$traffic_name_msg="Type in a name for the new Traffic Manager Rule.";
$traffic_client_ip="Client IP (optional)";
$traffic_client_ip_msg="Enter a Client IP address (optional).";
$traffic_client_mac="Client MAC (optional)";
$traffic_client_mac_msg="Enter a Client MAC address (optional).";
$traffic_down_speed="Downlink Speed";
$traffic_down_speed_msg="Enter the Downlink speed (Mbits/sec).";
$traffic_up_speed="Uplink Speed";
$traffic_up_speed_msg="Enter the Uplink speed (Mbits/sec).";
$traffic_list="Traffic Manager Rules";
$traffic_list_msg="This table displays the current Traffic manager rules for the following parameters: Name, Client IP, Client MAC,"." Downlink Speed, Uplink Speed, Edit and Del.";

$head_st="Status Settings";

$title_st_dev_info="Device Information";
if(query("/runtime/web/display/status/cpu")!="1")
{
$title_st_dev_info_msg="Device Information displays Firmware Version, Ethernet and Wireless Parameters and Memory Utilization.";
}
else
{
$title_st_dev_info_msg="Device Information displays Firmware Version, Ethernet and Wireless Parameters, CPU and Memory Utilization.";
}

$title_st_cli_info="Client Information";
$title_st_cli_info_msg="Client Information displays Client SSID's. MAC addresses, Frequency Bands, Signal Strength, and Power-Saving mode.";

$title_st_wds_info="WDS Information";
$title_st_wds_info_msg="Wireless Distribution System (WDS) displays the WDS name, MAC address, Authentication Method, Signal Strength, and Connection Status.";

$title_chan_analyze="Channel Analyze";
$title_chan_analyze_msg="Channel Analyzer checks for current channel availability.";

$head_statistics="Statisics";

$title_st_ethernet="Ethernet Traffic Statistics";
$title_st_ethernet_msg="Displays wired interface network traffic information.";

$title_st_wlan="WLAN Traffic Statistics";
$title_st_wlan_msg="Displays Throughput, Transmitted Frame, Received Frame, and WEP Frame Error AP information.";


$head_log="Log";
$head_log_msg="Displays Log Event records from a remote system log server.";

$title_log_view="View Log";
$title_log_view_msg="The following log information is displayed: Cold Start AP, Upgrading Firmware, Client Associate/Disassociate AP, and Web Login. View up to 500 logs.";

$title_log_set="Log Settings";
$title_log_set_msg="Enter the Log server IP address and Enter the EU directive Syslog Server IP address or Domain Name. Click the checkboxes to monitor these parameters: System Activity, Wireless Activity, and Notices.";
$title_email="Email Notification";
$title_email_msg="Email Notification supports Simple Mail Transfer Protocol (SMTP) for Log Scheduling and Periodic Key change. Gmail works at SMTP port 25 and/or 587.";
$title_email_schedule="Email Log Schedule";
$title_email_schedule_msg="Set the Email Log Schedule.";


$head_mt="Maintenance";

$title_mt_admin="Administrator Settings";
$mt_limit_admin_vid="Limit Administrator VID";
$mt_limit_admin_vid_msg="Limit Administrator privileges by tagging the VLAN packets to the VID.";
$mt_limit_admin_ip="Limit Administrator IP Range";
$mt_limit_admin_ip_msg="Enter a list of IP addresses which restricts administrator privileges at login.";
$title_mt_sysname="System Name Settings";
$mt_sysname="System Name";
$mt_sysname_msg="Enter a name for the ".query("/sys/hostname").".";
$mt_location="Location";
$mt_location_msg="Enter a physical location for the ".query("/sys/hostname").".";
$title_mt_login="Login Settings";
$mt_username="Login Name";
$mt_username_msg="The default Login Name is admin. The default Password is blank. However, the Login Name is customizable.";
$mt_oldpass="Old Password";
$mt_oldpass_msg="Enter the old password.";
$mt_newpass="New Password";
$mt_newpass_msg="Enter a new case-sensitive password between 0-64 characters.";
$mt_conpass="Confirm Password";
$mt_conpass_msg="Confirm the new Password.";
$mt_applypass="Apply New Password";
$mt_applypass_msg="Apply the new Password.";
$title_mt_console="Console Settings";
$mt_status="Status";
$mt_status_msg="Enable or disable console.";
$mt_console_protocol="Console Protocol";
$mt_console_protocol_msg="Select Telnet or SSH.";
$mt_timeout="Timeout";
$mt_timeout_msg="Select a Timeout period.";
$title_mt_snmp="SNMP Settings";
$mt_st_snmp="Status";
$mt_st_snmp_msg="Select to Enable/Disable Simple Network Management Protocol (SNMP).";
$mt_public_comm="Public Community String";
$mt_public_comm_msg="Enter the SNMP Public Community String.";
$mt_private_comm="Private Community String";
$mt_private_comm_msg="Enter the SNMP Private Community String.";
$mt_trap="Trap Status";
$mt_trap_msg="Enable/Disable Trap messages.";
$mt_trap_serv="Trap Server IP";
$mt_trap_serv_msg="Enter the IP address of the Trap Server.";
$title_mt_pingctrl="Ping Control Setting";
$mt_ping_st="Status";
$mt_ping_st_msg="Enabled by default, the Status Ping Control Settings works by sending Internet Control Message Protocol (ICMP) packets to the "
."target host and listening for ICMP echo message replies. No messages are sent when this setting is disabled.";
$title_mt_wifimanager="Central WiFiManager";
$title_mt_wifimanager_msg="The Central WiFiManager is a tool access points connected to the network to form a single grouped network that have a great deal of devices. It cooperates with the Central WiFiManager tool.";
$mt_enbale_wifimanager="Enable Central WiFiManager";
$mt_enbale_wifimanager_msg="Click this option to enable the Central WiFiManager.";
$mt_enbale_backup_wifimanager="Enable Backup Central WiFiManager";
$mt_enbale_backup_wifimanager_msg="Click this option to enable the backup Central WiFiManager.";

$title_mt_fw="Firmware and SSL";
$title_mt_fw_msg="Firmware and SSL Certification Upload";
$title_mt_fw_msg1="Upload the latest Firmware and SSL Certificates.";
$mt_upload_fw="Upload Firmware from Local Hard Drive";
$mt_upload_fw_msg="View the current firmware version number above the file location field. Download the latest firmware, "
."click Browse to locate the new firmware, click Open and then OK to update to the latest firmware. Do not turn the power off.";
$mt_upload_ssl="Upload SSL Certification from Local Hard Drive";
$mt_upload_language = "Language Pack Upgrade";
$mt_upload_language_msg = "Browse to the saved language pack file directory, click Open then Upload to complete the upgrade process.";
$mt_upload_ssl_msg="After you have downloaded a SSL certification to your local drive, click \"Browse\". Select the certification and click ".
"\"Open\" and \"Upload\" to complete the upgrade.";

$_title_mt_config="Configuration File";
$mt_config="Configuration File Upload and Download";
$mt_config_msg="Upload and Download the Access Point's configuration file.";
$mt_upload_config="Upload Configuration File";
$mt_upload_config_msg="Browse to the saved configuration file directory, click Open, then Upload the file to update the configuration.";
$mt_download_config="Download Configuration File";
$mt_download_config_msg="Click Download to save the current configuration file to the hard drive. "
."Please remember the administrator password when resetting the ".query("/sys/hostname").". Otherwise it will be lost even if you have saved the configuration file.";
$mt_upload_cwm="Upload CWM File";
$mt_upload_cwm_msg="Browse to the saved cwm file directory, click Open, then Upload the file to update the cwm.";

$title_mt_time="Time and Date";
$title_mt_time_msg="Select the Time zone. Enter the NTP server IP. Choose Enable/Disable for Daylight Saving Time.";
$auto_time = "Automatic Time Configuration";
$auto_time_msg = "Enable NTP get the date and time from a NTP server.";
$date_and_time_manu = "Set the Date and Time Manually";
$date_and_time_manu_msg = "You can set the date and time manually or copy your computer's time to AP.";
$head_config ="Configuration";
$config_save_activate="Save and Activate";
$config_save_activate_msg="Click \"Save and Activate\" to save all settings and re-activate the system.";
$config_discard_change="Discard change";
$config_discard_change_msg="Click \"Discard change\" to discard the settings.";

$head_sys = "System";
$title_sys = "System Settings";
$sys_apply_restart = "Restart the Device";
$sys_apply_restart_msg = "To apply and restart the ".query("/sys/hostname").", click Restart.";
$sys_apply_restore = "Restore to Factory Default Settings.";
$sys_apply_restore_msg = "To reset the ".query("/sys/hostname")." to factory default, click Restore.";
$sys_clear_language = "Clear Language pack";
$sys_clear_language_msg = "Click on clear to reset language to default settings.";


?>
