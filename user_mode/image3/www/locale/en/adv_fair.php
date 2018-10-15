<?
/* ---------------------------------------------------------------------- */
//$TITLE=$m_pre_title."WIRELESS";
/* ---------------------------------------------------------------------- */
$m_context_title		="Bandwidth Optimization";
$m_enable_fair		="Enable Bandwidth Optimization";
$m_Trafficmanage_type		="Traffic Control Type";

$m_disable    = "Disable";
$m_enable    = "Enable";

$m_average="Rule Type       ";
$m_averagetype_s="Allocate average BW for each station";
$m_averagetype_fssid="Allocate specific BW for SSID";
if(query("/wlan/inf:2/ap_mode") != "")
{
	$m_averagetype_fw="Allocate different BW for 11a/b/g/n stations";
}
else
{
	$m_averagetype_fw="Allocate different BW for 11b/g/n stations";
}
$m_averagetype_fstation="Allocate maximum BW for each station";
$m_pri_ssid = "Primary SSID";
$m_ms_ssid1 = "SSID 1";
$m_ms_ssid2 = "SSID 2";
$m_ms_ssid3 = "SSID 3";
$m_ms_ssid4 = "SSID 4";
$m_ms_ssid5 = "SSID 5";
$m_ms_ssid6 = "SSID 6";
$m_ms_ssid7 = "SSID 7";
$m_ms_ssid8 = "SSID 8";
$m_ms_ssid9 = "SSID 9";
$m_ms_ssid10 = "SSID 10";
$m_ms_ssid11 = "SSID 11";
$m_ms_ssid12 = "SSID 12";
$m_ms_ssid13 = "SSID 13";
$m_ms_ssid14 = "SSID 14";
$m_ms_ssid15 = "SSID 15";
$m_ssid = "SSID";
$m_ssidindex = "SSID Index";
$m_band = "Band";
$m_band_2_4G = "2.4 GHz";
$m_band_5G = "5 GHz";
$m_n="80211n";
$m_b="80211b";
$m_g="80211g";
$m_a="80211a";
$m_100="100%";
$m_50="50%";
$m_30="30%";
$m_10="10%";
$m_0="0%";

$m_speed_m="Mbits/sec";
$m_speed_k="Kbits/sec";
$m_Comment ="Comment";

$m_context_add_title		="Add Bandwidth Optimization Rule";
$m_context_qos_title		="Bandwidth Optimization Rules";
$m_type ="Type"; 
$m_b_add		="Add";
$m_b_cancel		="Clear";
$a_disable_trafficctrl = "Traffic Manager and QoS will be disabled if enabling Bandwidth Optimization!";
$a_empty_value_for_bandwidth = "Please input Downlink Bandwidth and Uplink Bandwidth!";
$a_empty_value_for_two_speed = "Please input downlink speed and uplink speed!";
$a_rule_del_confirm	="Are you sure that you want to delete this rule?";
$a_invalid_value_for_bandwidth  ="Invalid value of Bandwidth!";
$a_invalid_range_for_bandwidth_st = "Bandwidth range should be 0~";
$a_invalid_range_for_bandwidth_end = " Mbits/sec.";
$a_primaryless_value_for_bandwidth ="Bandwidth should not less than maximal speed for the Bandwidth Optimization rules! ";
$a_GreaterThanPrimary_value_for_speed ="The speed for the Bandwidth Optimization rules should not less than 1 Kbits/sec,or greater than bandwidth!";
$a_invalid_value_for_speed  ="Invalid value for the speed!";
$a_Rule_maxnum = "Rule number you added should not be more than 64!";
$a_Dynamic_flow_max ="The Dynamic flow is max sumflow!";

$m_DownlinkInterface = "Downlink Bandwidth";
$m_UplinkInterface = "Uplink Bandwidth";
$m_edit = "Edit";
$m_del = "Del";
$m_Downlink_Speed		="Downlink Speed";
$m_Uplink_Speed		="Uplink Speed";
?>
