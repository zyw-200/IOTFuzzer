<?
$MAX_RULES=query("/lan/dhcp/server/pool:1/staticdhcp/max_client");
if ($MAX_RULES==""){$MAX_RULES=25;}	
$m_context_title = "Static Pool Settings";
$m_srv_enable = "Function Enable/Disable";
$m_disable = "Disable";
$m_enable = "Enable";
$m_dhcp_srv = "DHCP Server Control";
$m_dhcp_pool = "Static Pool Setting";
$m_computer_name = "Host Name";
$m_ipaddr = "Assigned IP";
$m_macaddr = "Assigned MAC Address";
$m_ipmask = "Subnet Mask";
$m_gateway = "Gateway";
$m_wins = "WINS";
$m_dns = "DNS";
$m_m_domain_name = "Domain Name";
$m_on = "ON";
$m_off = "OFF";
$m_status_enable = "Status";
$m_mac = "MAC Address";
$m_ip = "IP Address";
$m_state = "State";
$m_edit = "Edit";
$m_del = "Delete";

$a_invalid_host		= "Invalid Host Name !";
$a_invalid_ip		= "Invalid IP address !";
$a_invalid_mac		= "Invalid MAC Address !";
$a_max_mac_num = "Maximum number of Static DHCP List is ".$MAX_RULES."!";
$a_same_ip = "There is an existent entry with the same IP Address.\\n Please change the IP Address.";
$a_same_mac = "There is an existent entry with the same MAC Address.\\n Please change the MAC Address.";
$a_invalid_netmask	= "Invalid Subnet Mask !";
$a_invalid_gateway	="Invalid Gateway !";
$a_invalid_wins	= "Invalid WINS !";
$a_invalid_dns	="Invalid DNS !";
$a_invalid_domain_name	= "Invalid domain name !";
$a_invalid_lease_time	= "Invalid DHCP Lease Time !";
$a_entry_del_confirm = "Are you sure that you want to delete this entry?";
?>
