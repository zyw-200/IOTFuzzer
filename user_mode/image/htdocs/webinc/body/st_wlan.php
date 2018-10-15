<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Connected Wireless Client List");?></h1>
	<p><?
		echo i18n("View the wireless clients that are connected to the router. (A client might linger in the list for a few minutes after an unexpected disconnect.)");
	?></p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Number Of Wireless Clients - 2.4GHz Band ");?>: <span id="client_cnt"></span></h2>
	<div class="centerline">
		<table id="client_list" class="general" width="535px">
		<tr>
			<th><?echo i18n("SSID");?></th>
			<th width="120px"><?echo i18n("MAC Address");?></th>
			<th width="100px"><?echo i18n("IP Address");?></th>
			<th width="50px"><?echo i18n("Mode");?></th>
			<th width="80px"><?echo i18n("Rate");?> (Mbps)</th>
<!--			<th><?echo i18n("Signal");?> (%)</th>-->
		</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>

<div class="blackbox">
	<h2><?echo i18n("Number Of Wireless Clients - 5GHz Band ");?>: <span id="client_cnt_Aband"></span></h2>
	<div class="centerline">
		<table id="client_list_Aband" class="general" width="535px">
		<tr>
			<th><?echo i18n("SSID");?></th>
			<th width="120px"><?echo i18n("MAC Address");?></th>
			<th width="100px"><?echo i18n("IP Address");?></th>
			<th width="50px"><?echo i18n("Mode");?></th>
			<th width="80px"><?echo i18n("Rate");?> (Mbps)</th>
<!--			<th><?echo i18n("Signal");?> (%)</th>-->
		</tr>
		</table>
	</div>
	<div class="gap"></div>
</div>
</form>
