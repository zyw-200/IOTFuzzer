<?
include "/htdocs/phplib/xnode.php";
function get_runtime_eth_path($uid)
{
	$p = XNODE_getpathbytarget("", "inf", "uid", $uid, 0);
	if($p == "") return $p;

	return XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($p."/phyinf"));
}
function get_runtime_wifi_path($uid)
{
	$p = XNODE_getpathbytarget("", "phyinf", "wifi", $uid);
	if($p == "") return $p;

	return XNODE_getpathbytarget("/runtime", "phyinf", "uid", query($p."/uid"));
}

/* we reset the counters and get them immediately here, it may has the synchronization issue. */
/* Do RESET count */
if ($_POST["act"]!="")
{
	$p = get_runtime_eth_path("WAN-1");		if ($p != "") set($p."/stats/reset", "dummy");
	$p = get_runtime_eth_path("LAN-1");		if ($p != "") set($p."/stats/reset", "dummy");
	$p = get_runtime_wifi_path("WIFI-1");	if ($p != "") set($p."/stats/reset", "dummy");
	$p = get_runtime_wifi_path("WIFI-2");	if ($p != "") set($p."/stats/reset", "dummy");
}

$tx = "/stats/tx/packets";
$rx = "/stats/rx/packets";
$p = get_runtime_eth_path("WAN-1");
if ($p == ""){$wan1_tx = i18n("N/A");						$wan1_rx = i18n("N/A");}
else		 {$wan1_tx = query($p.$tx)." ".i18n("Packets");	$wan1_rx = query($p.$rx)." ".i18n("Packets");}

$p = get_runtime_eth_path("LAN-1");
if ($p == ""){$lan1_tx = i18n("N/A");						$lan1_rx = i18n("N/A");}
else		 {$lan1_tx = query($p.$tx)." ".i18n("Packets");	$lan1_rx = query($p.$rx)." ".i18n("Packets");}

$p = get_runtime_wifi_path("WIFI-1");
if ($p == ""){$wifi_tx = i18n("N/A");						$wifi_rx = i18n("N/A");}
else		 {$tx_cnt = query($p.$tx);						if ($tx_cnt == "")	$tx_cnt = "0";
			  $rx_cnt = query($p.$rx);						if ($rx_cnt == "")	$rx_cnt = "0";
			  $wifi_tx = $tx_cnt." ".i18n("Packets");		$wifi_rx = $rx_cnt." ".i18n("Packets");}

$p = get_runtime_wifi_path("WIFI-2");
if ($p == ""){$wifi_tx_aband = i18n("N/A");					$wifi_rx_aband = i18n("N/A");}
else		 {$tx_cnt = query($p.$tx);						if ($tx_cnt == "")	$tx_cnt = "0";
			  $rx_cnt = query($p.$rx);						if ($rx_cnt == "")	$rx_cnt = "0";
			  $wifi_tx_aband = $tx_cnt." ".i18n("Packets");	$wifi_rx_aband = $rx_cnt." ".i18n("Packets");}

?>
<div class="orangebox">
	<h1><?echo i18n("Traffic Statistics");?></h1>
	<p><?
		echo i18n("Traffic Statistics displays Receive and Transmit packets passing through the device.");
	?></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Traffic Statistics");?></h2>
	<div class="centerline" align="center">
		<form id="mainform" name="resetcount" action="<?=$TEMP_MYNAME?>.php" method="POST">
			<input type="button" value="<?echo i18n("Refresh");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();">&nbsp;
			<input type="submit" name="act" value="<?echo i18n("Reset");?>">
		</form>
		<table id="client_list" class="general">
		<tr>
			<th width="150">&nbsp;</th>
			<th><?echo i18n("Receive");?></th>
			<th><?echo i18n("Transmit");?></th>
		</tr>
		<tr>
			<th><?echo i18n("Internet");?></th>
			<td><?=$wan1_rx?></td>
			<td><?=$wan1_tx?></td>
		</tr>
		<tr>
			<th><?echo i18n("LAN");?></th>
			<td><?=$lan1_rx?></td>
			<td><?=$lan1_tx?></td>
		</tr>
		<tr>
			<th><?echo i18n("WIRELESS 2.4G");?></th>
			<td><?=$wifi_rx?></td>
			<td><?=$wifi_tx?></td>
		</tr>
		<tr>
			<th><?echo i18n("WIRELESS 5G");?></th>
			<td><?=$wifi_rx_aband?></td>
			<td><?=$wifi_tx_aband?></td>
		</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
