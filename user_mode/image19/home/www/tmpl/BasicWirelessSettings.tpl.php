<?php /* Smarty version 2.6.18, created on 2010-06-09 10:14:04
         compiled from BasicWirelessSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
      require_once('modesList.php'); 
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'BasicWirelessSettings.tpl', 61, false),array('function', 'ip_field', 'BasicWirelessSettings.tpl', 63, false),array('modifier', 'regex_replace', 'BasicWirelessSettings.tpl', 167, false),array('modifier', 'default', 'BasicWirelessSettings.tpl', 300, false),)), $this); ?>
<?php 
	function special($str){
			include('specialChars.php');
			return $str;
	}

?>
<tr>
<td>
<table class="tableStyle">
<tr>
	<td colspan="3"><script>tbhdr('Wireless Settings','radioSettings')</script></td>
</tr>
<tr>
<td class="subSectionBodyDot">&nbsp;</td>
<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
<table class="tableStyle">
<tr>
<td>
<div  id="WirelessBlock">
<table class="inlineBlockContent" style="margin-top: 10px; width: 100%;">
<tr>
<td>
<ul class="inlineTabs">
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
	<li id="inlineTab1" <?php if ( $this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode0'] == '0' || $this->_tpl_vars['data']['activeMode0'] == '1' || $this->_tpl_vars['data']['activeMode0'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '3' || $this->_tpl_vars['data']['activeMode0'] == '4'): ?>class="Active" activeRadio="true"<?php else: ?> activeRadio="false"<?php endif; ?> currentId="1"><a id="inlineTabLink1" href="javascript:void(0)">802.11<?php if ($this->_tpl_vars['config']['B0']['status']): ?><span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode0'] == '0'): ?>Active<?php endif; ?>">b<?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode0'] == '0'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['BG0']['status']): ?><?php if ($this->_tpl_vars['config']['MODE11G']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode0'] == '1'): ?>Active<?php endif; ?>">bg<?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode0'] == '1'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php endif; ?><?php if ($this->_tpl_vars['config']['NG0']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '2'): ?>Active<?php endif; ?>">ng<?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '2'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['A0']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode0'] == '3'): ?>Active<?php endif; ?>">a<?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode0'] == '3'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['NA0']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode0'] == '4'): ?>Active<?php endif; ?>">na<?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode0'] == '4'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?></a></li>
<?php endif; ?>

<!--@@@FIVEGHZSTART@@@-->
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
<li id="inlineTab2" <?php if ( $this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '4'): ?>class="Active" activeRadio="true"<?php else: ?> activeRadio="false"<?php endif; ?> currentId="2"><a id="inlineTabLink2" href="javascript:void(0)">802.11<?php if ($this->_tpl_vars['config']['B1']['status']): ?><span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0'): ?>Active<?php endif; ?>">b<?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['BG1']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1'): ?>Active<?php endif; ?>">bg<?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['NG1']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2'): ?>Active<?php endif; ?>">ng<?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['A1']['status']): ?><?php if ($this->_tpl_vars['config']['NG1']['status']): ?>/<?php endif; ?><span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3'): ?>Active<?php endif; ?>">a<?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?><?php if ($this->_tpl_vars['config']['NA1']['status']): ?>/<span class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'><b class="RadioText<?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4'): ?>Active<?php endif; ?>">na<?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4'): ?><img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php endif; ?></b></span><?php endif; ?></a></li>
<?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
	</ul>
</td>
</tr>
</table>
</div>
<div id="IncludeTabBlock">
 
	<input type="hidden" name="activeMode" id="activeMode" value="<?php echo $this->_tpl_vars['data']['activeMode']; ?>">
	<input type="hidden" name="currentMode" id="currentMode" value="<?php echo $this->_tpl_vars['data']['activeMode']; ?>">
 	
	<input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum">
	<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
	<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
		<?php $this->assign('apMode', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']); ?>
	<?php endif; ?>
	<div  class="BlockContent" id="wlan1">
	<table class="BlockContent Trans" id="table_wlan1">
	<tr class="Gray">
	<td class="DatablockLabel" style="width: 1%;">Wireless Mode</td>
	<td class="DatablockContent" style="width: 100%;">
	<span class="legendActive">2.4GHz Band</span>
	<?php if ($this->_tpl_vars['config']['B0']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode0'] == '0' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode0'] == '0' )): ?>checked="checked"<?php endif; ?> value="0"><span id="mode_b" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode0'] == '0'): ?>class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11b<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['BG0']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode0'] == '1' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode0'] == '1' )): ?>checked="checked"<?php endif; ?> value="1"><span id="mode_bg" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode0'] == '1'): ?>class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11bg<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['NG0']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '2' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode0'] == '2' )): ?>checked="checked"<?php endif; ?> value="2"><span id="mode_ng" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '2'): ?>class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11ng<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['A0']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode0'] == '3' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode0'] == '3' )): ?>checked="checked"<?php endif; ?> value="3"><span id="mode_a" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode0'] == '3'): ?>class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11a<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11a<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['NA0']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode1" id="WirelessMode1" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode0'] == '4' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode0'] == '4' )): ?>checked="checked"<?php endif; ?> value="4"><span id="mode_na" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode0'] == '4'): ?>class="Active" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11na<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11na<?php endif; ?></span><?php endif; ?>

	<input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['operateMode']; ?>" id="activeMode0" value="<?php echo $this->_tpl_vars['data']['activeMode0']; ?>">
<input type="hidden" name="radioStatus0" id="radioStatus0" value="<?php echo $this->_tpl_vars['data']['radioStatus0']; ?>">
<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
<input type="hidden" name="apMode0" id="apMode0" value="<?php echo $this->_tpl_vars['apMode']; ?>">
<?php endif; ?>
	<input type="hidden" name="modeWlan0" id="modeWlan0" value="<?php if ($this->_tpl_vars['data']['activeMode'] > 2): ?>2<?php else: ?><?php echo $this->_tpl_vars['data']['activeMode']; ?><?php endif; ?>">
	</td>
	</tr>
<?php $this->assign('radioStatus', $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus']);  ?>
<?php $this->assign('operateMode', $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['operateMode']); ?>
<?php if ($this->_tpl_vars['config']['SCH_WIRELESS_ON_OFF']['status']): ?>
	<input type="hidden" name="sch_Stat" id="sch_Stat" value="<?php echo $this->_tpl_vars['data']['basicSettings']['scheduledWirelessStatus']; ?>">
<?php endif; ?>
<?php echo smarty_function_input_row(array('row_id' => 'radioRow1','label' => 'Turn Radio On','id' => 'chkRadio0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus']), $this);?>

<?php if ($this->_tpl_vars['config']['SCH_WIRELESS_ON_OFF']['status']): ?>
	<?php echo smarty_function_ip_field(array('id' => 'radioBkup0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['scheduledRadioBackup'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['scheduledRadioBackup']), $this);?>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['CLIENT']['status'] && $this->_tpl_vars['apMode'] == 5): ?>
<tr>
	<td class="DatablockLabel">Wireless Network Name (SSID)</td>
	<td class="DatablockContent">
	<?php echo smarty_function_ip_field(array('label' => "Wireless Network Name (SSID)",'id' => 'wirelessSSID0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['ssid'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['ssid'],'maxlength' => '32','validate' => "Presence, ((allowQuotes: true,allowSpace: true,allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"), $this);?>
	<input name="clientMode_button" style="text-align: center;" value="Site Survey" onclick="showSurveyPopupWindow(); return false;" type="button">
	</td>
</tr>
<?php else: ?>
        <?php echo smarty_function_input_row(array('label' => "Wireless Network Name (SSID)",'id' => 'wirelessSSID0','name' => $this->_tpl_vars['parentStr']['vapSettings']['vapSettingTable']['wlan0']['vap0']['ssid'],'type' => 'text','value' => $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap0']['ssid'],'maxlength' => '32','validate' => "Presence, ((allowQuotes: true,allowSpace: true,allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"), $this);?>
<?php if ($this->_tpl_vars['config']['WN604']['status']): ?>
<tr>
	<td class="DatablockLabel">RF Switch Status</td>
        <input type="hidden" id="rfSwitchStatus0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rfSwitchStatus']; ?>">
        <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rfSwitchStatus'] == '1'): ?>
	        <td class="DatablockLabel" id="rfSwitchStatusLabel0"><font color="green">ON</font></td>
        <?php else: ?>
        <td class="DatablockLabel" id="rfSwitchStatusLabel0"><font color="red">OFF</font></td>
<?php endif; ?>
</tr>
<?php endif; ?>
<?php endif; ?>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['SCH_WIRELESS_ON_OFF']['status']): ?>
<tr>
        <td class="DatablockLabel">Wireless On-Off Status</td>
	        <input type="hidden" id="schedular0" value="<?php echo $this->_tpl_vars['data']['basicSettings']['scheduledWirelessStatus']; ?>">
                <?php if ($this->_tpl_vars['data']['basicSettings']['scheduledWirelessStatus'] == '1'): ?>
	                <td class="DatablockLabel" id="schedStatusLabel0"><font color="green">ON</font></td>
                <?php else: ?>
					<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>				
        			<td class="DatablockLabel" id="schedStatusLabel0"><font color="black">OFF</font></td>				
					<?php else: ?>
        			<td class="DatablockLabel" id="schedStatusLabel0"><font color="red">OFF</font></td>
					<?php endif; ?>
<?php endif; ?>
</tr>
	<?php $this->assign('broadcastSSID1', $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap0']['hideNetworkName']); ?>
	<?php echo smarty_function_input_row(array('label' => "Broadcast Wireless Network Name (SSID)",'id' => 'idbroadcastSSID1','name' => $this->_tpl_vars['parentStr']['vapSettings']['vapSettingTable']['wlan0']['vap0']['hideNetworkName'],'type' => 'radio','options' => "0-Yes,1-No",'value' => $this->_tpl_vars['broadcastSSID1'],'selectCondition' => "==".($this->_tpl_vars['broadcastSSID1']),'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php echo smarty_function_input_row(array('label' => "Channel / Frequency",'id' => 'ChannelList1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['channel'],'type' => 'select','options' => $this->_tpl_vars['ChannelList0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channel'],'onchange' => "checkWDSStatus(this,'0');",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php echo smarty_function_input_row(array('label' => 'Data Rate','row_id' => 'datarate11n1','id' => 'DatarateList1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['dataRate'],'type' => 'select','options' => $this->_tpl_vars['DataRateList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['dataRate'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
		<?php echo smarty_function_input_row(array('label' => "MCS Index / Data Rate",'row_id' => 'mcsrate11n1','label_id' => 'mcsrateLabel','id' => 'MCSrateList1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['mcsRate'],'type' => 'select','options' => $this->_tpl_vars['MCSRateList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['mcsRate'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
		<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
                	<?php $this->assign('activeModeString', 'activeMode0'); ?>
		<?php else: ?>
                	<?php $this->assign('activeModeString', 'activeMode'); ?>
		<?php endif; ?>
<?php if ($this->_tpl_vars['config']['EXT_CHAN']['status']): ?>
	<?php echo smarty_function_input_row(array('label' => 'Channel Width','row_id' => 'bandwidth11n1','id' => 'Bandwidth1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['channelWidth'],'type' => 'select','options' => $this->_tpl_vars['channelWidthList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channelWidth'],'onchange' => "DispChannelList(1,$('".($this->_tpl_vars['activeModeString'])."').value),DisplayExtControls(this.value);",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
<?php else: ?>
	<?php echo smarty_function_input_row(array('label' => 'Channel Width','row_id' => 'bandwidth11n1','id' => 'Bandwidth1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['channelWidth'],'type' => 'select','options' => $this->_tpl_vars['channelWidthList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channelWidth'],'onchange' => "DispChannelList(1,$('".($this->_tpl_vars['activeModeString'])."').value);",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['EXT_CHAN']['status']): ?>
	<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channelWidth'] == 0 )): ?>
        	<?php $this->assign('hideExtRow', "style=\"display: none;\" disabled='true'"); ?>
	<?php else: ?>
        <?php $this->assign('hideExtRow', "style=\"display: none;\" disabled='false'"); ?>
<?php endif; ?>
<?php if (( $this->_tpl_vars['apMode'] == 5 )): ?>
	<?php $this->assign('disableContnt', "style=\"display: '';\" disabled='true'"); ?>
<?php endif; ?>
<tr mode="extCtrl" <?php echo $this->_tpl_vars['hideExtRow']; ?> id="extProtSpacRow_1">
	<td class="DatablockLabel">Ext Protection Spacing</td>
        <td class="DatablockContent" <?php echo $this->_tpl_vars['disableContnt']; ?>>
	        <?php echo smarty_function_ip_field(array('id' => 'extProtSpec_0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['extProtSpec'],'type' => 'select','options' => $this->_tpl_vars['ext_protect_spacingList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['extProtSpec'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	</td>
</td>
</tr>
<tr mode="extCtrl" <?php echo $this->_tpl_vars['hideExtRow']; ?> id="extChanOffsetRow_1">
	<td class="DatablockLabel">Ext Channel Offset</td>
	<td class="DatablockContent" <?php echo $this->_tpl_vars['disableContnt']; ?>>
	        <?php echo smarty_function_ip_field(array('id' => 'extChanOffset_0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['extChanOffset'],'type' => 'select','options' => $this->_tpl_vars['ext_chan_offsetList_0'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['extChanOffset'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	</td>
</td>
</tr>
<?php endif; ?>
	<?php echo smarty_function_input_row(array('label' => 'Guard Interval','row_id' => 'gi11n1','id' => 'GI1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['guardInterval'],'type' => 'select','options' => $this->_tpl_vars['guardIntervalList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['guardInterval'],'onchange' => "DispChannelList(1,$('".($this->_tpl_vars['activeModeString'])."').value);",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
<?php endif; ?>
	<?php echo smarty_function_input_row(array('label' => 'Output Power','id' => 'PowerList1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['txPower'],'type' => 'select','options' => $this->_tpl_vars['outputPowerList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['txPower'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php if ($this->_tpl_vars['config']['CLIENT']['status'] && $this->_tpl_vars['apMode'] == 5): ?>
	<?php echo smarty_function_input_row(array('label' => 'Network Authentication','id' => 'authenticationType','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'],'type' => 'select','options' => $this->_tpl_vars['clientAuthenticationTypeList'],'selected' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'],'onchange' => "DisplayClientSettings(this.value);"), $this);?>
	<?php $this->_tpl_vars['encTypeList'] = $this->_tpl_vars['clientEncryptionTypeList'][$this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType']]; ?>
	<?php echo smarty_function_input_row(array('label' => 'Data Encryption','id' => 'key_size_11g','name' => 'encryptionType','type' => 'select','options' => $this->_tpl_vars['encTypeList'],'selected' => $this->_tpl_vars['sta0encryptionSel'],'onchange' => "if ($('authenticationType').value=='0') DisplayClientSettings($('authenticationType').value); setEncryption(this.value,$('authenticationType').value);"), $this);?>
	<?php if (! ( $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 1 || ( $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 0 && $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['encryption'] != 0 ) )): ?>
		<?php $this->assign('hideWepRow', "style=\"display: none;\" disabled='true'"); ?>
	<?php endif; ?>
	<?php if (! ( $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 16 || $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 32 || $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 48 )): ?>
		<?php $this->assign('hideWPARow', "style=\"display: none;\" disabled='true'"); ?>
	<?php endif; ?>
	<?php echo smarty_function_ip_field(array('id' => 'encryption','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['encryption'],'type' => 'hidden','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['encryption']), $this);?>
	<?php if (! ( $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 1 || ( $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['authenticationType'] == 0 && $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['encryption'] != 0 ) )): ?>
		<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyType'],'disabled' => 'true'), $this);?>
	<?php else: ?>
		<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyType']), $this);?>
	<?php endif; ?>
<tr id="wep_row" <?php echo $this->_tpl_vars['hideWepRow']; ?>>
	<td class="DatablockLabel">Passphrase</td>
	<td class="DatablockContent">
		<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepPassPhrase']; ?>" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepPassPhrase'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="Passphrase"  onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();" validate="Presence, <?php echo '{ isMasked: \'wepPassPhrase\', allowQuotes: true, allowSpace: true, allowTrimmed:false }'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true); $('wepPassPhrase_hidden').value='';">&nbsp;
		<input name="szPassphrase_button" style="text-align: center;" value="Generate Keys" onclick="gen_11g_keys()" type="button">
             <input type="hidden" id="wepPassPhrase_hidden" value="<?php echo special(conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepPassPhrase'])); ?>">
	</td>
</tr>
<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>>
	<td class="DatablockLabel">Key 1&nbsp;<input id="keyno_11g1" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo']; ?>
" value="1" <?php if ($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo'] == '1'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>></td>
	<td class="DatablockContent">
		<input class="input" size="20" id="wepKey1" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey1']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey1']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="WEP Key 1" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g1\' }^HexaDecimal,{ isMasked: \'wepKey1\' }^Length,{ isWep: true }'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();">
	</td>
</tr>
<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>>
	<td class="DatablockLabel">Key 2&nbsp;<input id="keyno_11g2" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo']; ?>" value="2" <?php if ($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo'] == '2'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>></td>
	<td class="DatablockContent">
		<input class="input" size="20" id="wepKey2" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey2']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey2']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="WEP Key 2" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g2\' }^HexaDecimal,{ isMasked: \'wepKey2\' }^Length,{ isWep: true }'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();">
	</td>
</tr>
<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>>
	<td class="DatablockLabel">Key 3&nbsp;<input id="keyno_11g3" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo']; ?>" value="3" <?php if ($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo'] == '3'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>></td>
	<td class="DatablockContent">
		<input class="input" size="20" id="wepKey3" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey3']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey3']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="WEP Key 3" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g3\' }^HexaDecimal,{ isMasked: \'wepKey3\' }^Length,{ isWep: true }'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();">
	</td>
</tr>
<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>>
	<td class="DatablockLabel">Key 4&nbsp;<input id="keyno_11g4" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo']; ?>" value="4" <?php if ($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKeyNo'] == '4'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>></td>
	<td class="DatablockContent">
		<input class="input" size="20" id="wepKey4" name="system['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey4']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['wepKey4']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="WEP Key 4" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g4\' }^HexaDecimal,{ isMasked: \'wepKey4\' }^Length,{ isWep: true }'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();">
	</td>
</tr>
<tr id="wpa_row" <?php echo $this->_tpl_vars['hideWPARow']; ?>>
	<td class="DatablockLabel">WPA Passphrase (Network Key)</td>
	<td class="DatablockContent">
		<input id="wpa_psk" class="input" size="28" maxlength="63" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['presharedKey']; ?>" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['presharedKey'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>" type="text" label="WPA Passphrase (Network Key)" validate="Presence,<?php echo ' { allowQuotes: true, allowSpace: true, allowTrimmed: false }'; ?>^Length,<?php echo '{minimum: 8}'; ?>" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);" onfocus="if(/^\*<?php echo '{1,}$'; ?>/.test(this.value)) this.value='';setActiveContent();">
	</td>
</tr>
<?php endif; ?>
</table>
</div>
<?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
	<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
		<?php $this->assign('apMode', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']); ?>
	<?php endif; ?>
<div  class="BlockContent" id="wlan2">
	<table class="BlockContent Trans" id="table_wlan2">
	<tr class="Gray">
	<td class="DatablockLabel" style="width: 1%;">Wireless Mode</td>
	<td class="DatablockContent" style="width: 100%;">
	<span class="legendActive">5GHz Band</span>
	<!-- @@@620 Single Radio with 2 and 5Ghz modes as DUAL_CONCURRENT false@@@ -->	
	<?php if ($this->_tpl_vars['config']['WNDAP620']['status']): ?>
		<?php if ($this->_tpl_vars['config']['B1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '0' )): ?>checked="checked"<?php endif; ?> value="0"><span id="mode_b" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11b<?php endif; ?></span><?php endif; ?>
	    <?php if ($this->_tpl_vars['config']['BG1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '1' )): ?>checked="checked"<?php endif; ?> value="1"><span id="mode_bg" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11bg<?php endif; ?></span><?php endif; ?>
		<?php if ($this->_tpl_vars['config']['NG1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '2' )): ?>checked="checked"<?php endif; ?> value="2"><span id="mode_ng" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11ng<?php endif; ?></span><?php endif; ?>
		<?php if ($this->_tpl_vars['config']['A1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '3' )): ?>checked="checked"<?php endif; ?> value="3"><span id="mode_a" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11a<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11a<?php endif; ?></span><?php endif; ?>
	    <?php if ($this->_tpl_vars['config']['NA1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '4' )): ?>checked="checked"<?php endif; ?> value="4"><span id="mode_na" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11na<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11na<?php endif; ?></span><?php endif; ?>
	<?php else: ?>
	<?php if ($this->_tpl_vars['config']['B1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '0' )): ?>checked="checked"<?php endif; ?> value="0"><span id="mode_b" <?php if ($this->_tpl_vars['data']['activeMode'] == '0' || $this->_tpl_vars['data']['activeMode1'] == '0'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11b<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11b<?php endif; ?></span><?php endif; ?>
        <?php if ($this->_tpl_vars['config']['BG1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '1' )): ?>checked="checked"<?php endif; ?> value="1"><span id="mode_bg" <?php if ($this->_tpl_vars['data']['activeMode'] == '1' || $this->_tpl_vars['data']['activeMode1'] == '1'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11bg<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11bg<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['NG1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '2' )): ?>checked="checked"<?php endif; ?> value="2"><span id="mode_ng" <?php if ($this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode1'] == '2'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11ng<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11ng<?php endif; ?></span><?php endif; ?>
	<?php if ($this->_tpl_vars['config']['A1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '3' )): ?>checked="checked"<?php endif; ?> value="3"><span id="mode_a" <?php if ($this->_tpl_vars['data']['activeMode'] == '3' || $this->_tpl_vars['data']['activeMode1'] == '3'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11a<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11a<?php endif; ?></span><?php endif; ?>
         <?php if ($this->_tpl_vars['config']['NA1']['status']): ?><input type="radio" style="padding: 2px;" name="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" id="WirelessMode<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>2<?php else: ?>1<?php endif; ?>" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4' || ( $this->_tpl_vars['data']['activeMode'] == '' && $this->_tpl_vars['defaultMode1'] == '4' )): ?>checked="checked"<?php endif; ?> value="4"><span id="mode_na" <?php if ($this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4'): ?>class="Active" id="radioAct" onmouseover='showLayer(this);' onmouseout='hideLayer(this);'>11na<img src="../images/activeRadio.gif"><span>Radio is set to 'ON'</span><?php else: ?>>11na<?php endif; ?></span><?php endif; ?>
	<?php endif; ?>
	
	<input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['operateMode']; ?>" id="activeMode1" value="<?php echo $this->_tpl_vars['data']['activeMode1']; ?>">
	<input type="hidden" name="radioStatus1" id="radioStatus1" value="<?php echo $this->_tpl_vars['data']['radioStatus1']; ?>">
	<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
		<input type="hidden" name="apMode1" id="apMode1" value="<?php echo $this->_tpl_vars['apMode']; ?>">
	<?php endif; ?>
	<input type="hidden" name="modeWlan1" id="modeWlan1" value="<?php if ($this->_tpl_vars['data']['activeMode'] < 3): ?>4<?php else: ?><?php echo $this->_tpl_vars['data']['activeMode']; ?><?php endif; ?>">
	</td>
	</tr>
	<?php if ($this->_tpl_vars['config']['SCH_WIRELESS_ON_OFF']['status']): ?>
		<?php echo smarty_function_ip_field(array('id' => 'radioBkup1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['scheduledRadioBackup'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['scheduledRadioBackup']), $this);?>
	<?php endif; ?>
	<?php $this->assign('radioStatus', $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus']); ?>
	<?php $this->assign('operateMode', $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['operateMode']); ?>
		
	<?php if ($this->_tpl_vars['config']['WNDAP620']['status']): ?>	
		<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>
			<?php $this->assign('modeString', 'WirelessMode2'); ?>
		<?php else: ?>
		<?php $this->assign('modeString', 'WirelessMode1'); ?>
		<?php endif; ?>
	<?php else: ?>
		<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>
			<?php $this->assign('modeString', 'WirelessMode2'); ?>
		<?php else: ?>
		<?php $this->assign('modeString', 'WirelessMode1'); ?>
		<?php endif; ?>	
	<?php endif; ?>
	
	<?php echo smarty_function_input_row(array('row_id' => 'radioRow2','label' => 'Turn Radio On','id' => 'chkRadio1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus']), $this);?>
	<?php echo smarty_function_input_row(array('label' => "Wireless Network Name (SSID)",'id' => 'wirelessSSID1','name' => $this->_tpl_vars['parentStr']['vapSettings']['vapSettingTable']['wlan1']['vap0']['ssid'],'type' => 'text','value' => $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan1']['vap0']['ssid'],'maxlength' => '32','validate' => "Presence, ((allowQuotes: true,allowSpace: true,  allowTrimmed: false))^Length, (( minimum: 2, maximum: 32 ))"), $this);?>
	<?php if ($this->_tpl_vars['config']['SCH_WIRELESS_ON_OFF']['status']): ?>
	<tr>
		<td class="DatablockLabel">Wireless On-Off Status</td>
		<input type="hidden" id="schedular1" value="<?php echo $this->_tpl_vars['data']['basicSettings']['scheduledWirelessStatus']; ?>">
	        <?php if ($this->_tpl_vars['data']['basicSettings']['scheduledWirelessStatus'] == '1'): ?>
        		<td class="DatablockLabel" id="schedStatusLabel1"><font color="green">ON</font></td>
	        <?php else: ?>
					<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>				
        			<td class="DatablockLabel" id="schedStatusLabel0"><font color="black">OFF</font></td>				
					<?php else: ?>
        			<td class="DatablockLabel" id="schedStatusLabel0"><font color="red">OFF</font></td>
					<?php endif; ?>
	        <?php endif; ?>
	 </tr>
	 <?php endif; ?>

	<?php $this->assign('broadcastSSID2', $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan1']['vap0']['hideNetworkName']); ?>
	<?php echo smarty_function_input_row(array('label' => "Broadcast Wireless Network Name (SSID)",'id' => 'idbroadcastSSID1','name' => $this->_tpl_vars['parentStr']['vapSettings']['vapSettingTable']['wlan1']['vap0']['hideNetworkName'],'type' => 'radio','options' => "0-Yes,1-No",'value' => $this->_tpl_vars['broadcastSSID2'],'selectCondition' => "==".($this->_tpl_vars['broadcastSSID2']),'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php echo smarty_function_input_row(array('label' => "Channel / Frequency",'id' => 'ChannelList2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['channel'],'type' => 'select','options' => $this->_tpl_vars['ChannelList1'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['channel'],'onchange' => "checkWDSStatus(this,'1');",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php echo smarty_function_input_row(array('label' => 'Data Rate','row_id' => 'datarate11n2','id' => 'DatarateList2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['dataRate'],'type' => 'select','options' => $this->_tpl_vars['DataRateList_1'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['dataRate'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

	<?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
	<?php echo smarty_function_input_row(array('label' => "MCS Index / Data Rate",'row_id' => 'mcsrate11n2','label_id' => 'mcsrateLabel','id' => 'MCSrateList2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['mcsRate'],'type' => 'select','options' => $this->_tpl_vars['MCSRateList_1'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['mcsRate'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
	       	<?php $this->assign('activeModeString', 'activeMode1'); ?>
	<?php else: ?>
        	<?php $this->assign('activeModeString', 'activeMode'); ?>
	<?php endif; ?>
	<?php echo smarty_function_input_row(array('label' => 'Channel Width','row_id' => 'bandwidth11n2','id' => 'Bandwidth2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['channelWidth'],'type' => 'select','options' => $this->_tpl_vars['channelWidthList_1'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['channelWidth'],'onchange' => "DispChannelList(2,$('".($this->_tpl_vars['activeModeString'])."').value);",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php echo smarty_function_input_row(array('label' => 'Guard Interval','row_id' => 'gi11n2','id' => 'GI2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['guardInterval'],'type' => 'select','options' => $this->_tpl_vars['guardIntervalList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['guardInterval'],'onchange' => "DispChannelList(2,$('".($this->_tpl_vars['activeModeString'])."').value);",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
	<?php endif; ?>

	<?php echo smarty_function_input_row(array('label' => 'Output Power','id' => 'PowerList2','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['txPower'],'type' => 'select','options' => $this->_tpl_vars['outputPowerList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['txPower'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>
</table>
</div>
<?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
</div>
</td>
</tr>
</table>
</td>
<td class="subSectionBodyDotRight">&nbsp;</td>
</tr>
<tr>
<td colspan="3" class="subSectionBottom">&nbsp;</td>
</tr>
</table>
</td>
</tr>
</table>
<?php if ($this->_tpl_vars['support5GHz'] == true): ?>
	<input id="supports5GHz" value='true' type="hidden">
<?php else: ?>
	<input id="supports5GHz" value='false' type="hidden">
<?php endif; ?>

<script language="javascript">
<!--
	var prevInterface = <?php echo ((is_array($_tmp=@$_POST['previousInterfaceNum'])) ? $this->_run_mod_handler('default', true, $_tmp, "''") : smarty_modifier_default($_tmp, "''")); ?>;
	<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
		var ChannelList_0 = <?php echo $this->_tpl_vars['ChannelList_0']; ?>; //11b
		<?php if ($this->_tpl_vars['config']['MODE11G']['status']): ?>
			var ChannelList_1 = <?php echo $this->_tpl_vars['ChannelList_1']; ?>; //11bg
			<?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
				var ChannelList_0_20 = <?php echo $this->_tpl_vars['ChannelList_0_20']; ?>;
				var wlan0_40MHzSupport = <?php echo $this->_tpl_vars['wlan0_40MHzSupport']; ?>;
				<?php if ($this->_tpl_vars['wlan0_40MHzSupport'] == true && $this->_tpl_vars['ChannelList_0_40'] != ''): ?>
					var ChannelList_0_40 = <?php echo $this->_tpl_vars['ChannelList_0_40']; ?>;
				<?php endif; ?>
			<?php endif; ?>
			<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] != 0 && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] != 5): ?>
				var disableChannelonWDS0 = true;
			<?php endif; ?>
			<?php if ($this->_tpl_vars['interface'] == 'wlan1'): ?>
				<?php echo 'window.onload=function changeHelp() {$(\'helpURL\').value=$(\'helpURL\').value+\'_g\';};'; ?>
			<?php endif; ?>
		<?php endif; ?>
//<!-- Starting Generic code  for 2.4 GHZ -->
		var ChannelList_3 = <?php echo $this->_tpl_vars['ChannelList_3']; ?>; //11a
                <?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
                        var ChannelList_1_20 = <?php echo $this->_tpl_vars['ChannelList_1_20']; ?>;
                        var wlan1_40MHzSupport = <?php echo $this->_tpl_vars['wlan1_40MHzSupport']; ?>;
                        <?php if ($this->_tpl_vars['wlan1_40MHzSupport'] == true && $this->_tpl_vars['ChannelList_1_40'] != ''): ?>
                                var ChannelList_1_40 = <?php echo $this->_tpl_vars['ChannelList_1_40']; ?>;
                        <?php endif; ?> 
                <?php endif; ?>
                <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] != 0 && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] != 5): ?>
                        var disableChannelonWDS1 = true;
                <?php endif; ?>
                <?php if ($this->_tpl_vars['interface'] == 'wlan2'): ?>
                        <?php echo 'window.onload=function changeHelp() {$(\'helpURL\').value=$(\'helpURL\').value+\'_a\';};'; ?>
                <?php endif; ?>
//<!-- Ending Generic code  for 2.4 GHZ -->
	<?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
	<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
//<!-- Starting Generic code  for 5 GHZ -->
		var ChannelList_0 = <?php echo $this->_tpl_vars['ChannelList_0']; ?>; //11b
                <?php if ($this->_tpl_vars['config']['MODE11G']['status']): ?>
                        var ChannelList_1 = <?php echo $this->_tpl_vars['ChannelList_1']; ?>; //11bg
                        <?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
                                var ChannelList_0_20 = <?php echo $this->_tpl_vars['ChannelList_0_20']; ?>;
                                var wlan0_40MHzSupport = <?php echo $this->_tpl_vars['wlan0_40MHzSupport']; ?>;
                                <?php if ($this->_tpl_vars['wlan0_40MHzSupport'] == true && $this->_tpl_vars['ChannelList_0_40'] != ''): ?>
                                        var ChannelList_0_40 = <?php echo $this->_tpl_vars['ChannelList_0_40']; ?>;
                                <?php endif; ?>
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] != 0 && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] != 5): ?>
                                var disableChannelonWDS0 = true;
                        <?php endif; ?>
                        <?php if ($this->_tpl_vars['interface'] == 'wlan1'): ?>
                                <?php echo 'window.onload=function changeHelp() {$(\'helpURL\').value=$(\'helpURL\').value+\'_g\';};'; ?>
                        <?php endif; ?>
                <?php endif; ?>

//<!-- Ending Generic code  for 5 GHZ -->
		var ChannelList_3 = <?php echo $this->_tpl_vars['ChannelList_3']; ?>; //11a
		<?php if ($this->_tpl_vars['config']['MODE11N']['status']): ?>
			var ChannelList_1_20 = <?php echo $this->_tpl_vars['ChannelList_1_20']; ?>;
			var wlan1_40MHzSupport = <?php echo $this->_tpl_vars['wlan1_40MHzSupport']; ?>;
			<?php if ($this->_tpl_vars['wlan1_40MHzSupport'] == true && $this->_tpl_vars['ChannelList_1_40'] != ''): ?>
				var ChannelList_1_40 = <?php echo $this->_tpl_vars['ChannelList_1_40']; ?>;
			<?php endif; ?>
		<?php endif; ?>
		<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] != 0 && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] != 5): ?>
			var disableChannelonWDS1 = true;
		<?php endif; ?>
		<?php if ($this->_tpl_vars['interface'] == 'wlan2'): ?>
			<?php echo 'window.onload=function changeHelp() {$(\'helpURL\').value=$(\'helpURL\').value+\'_a\';};'; ?>
		<?php endif; ?>
	<?php endif; ?>
//<!--@@@FIVEGHZEND@@@-->
	var form = new formObject();
	<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
	        $('cb_chkRadio0').observe('click',form.tab1.setActiveMode.bindAsEventListener(form.tab1,true));
        	var i = 0;
		$RD('WirelessMode1').each( function(radio) <?php echo ' {
                if (parseInt(radio.value) < numModes0) {
			$(radio).observe(\'click\',form.tab1.enableMode.bindAsEventListener(form.tab1, i++));
                }
		});
	       '; ?>
	<?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
	<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
        	$('cb_chkRadio1').observe('click',form.tab2.setActiveMode.bindAsEventListener(form.tab2,true));
		if (config.B1.status){
                        var i = 0; 
                } else if (!config.B1.status && config.BG1.status){
                        var i = 1;
                } else if (!config.B1.status && !config.BG1.status && config.NG1.status){
                        var i = 2;
                } else if (!config.B1.status && !config.BG1.status && !config.NG1.status && config.A1.status){
                        var i = 3;
                } else if (!config.B1.status && !config.BG1.status && !config.NG1.status && !config.A1.status && config.NA1.status){
                        var i = 4;
                }
		<?php if ($this->_tpl_vars['config']['WNDAP620']['status']): ?>		
			$RD(<?php if (!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>'WirelessMode2'<?php else: ?>'WirelessMode1'<?php endif; ?>).each( function(radio) <?php echo '{
	                if (parseInt(radio.value) >= i){
				$(radio).observe(\'click\',form.tab2.enableMode.bindAsEventListener(form.tab2, i++)); }
			});
		        '; ?>
		<?php else: ?>
		$RD(<?php if ($this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>'WirelessMode2'<?php else: ?>'WirelessMode1'<?php endif; ?>).each( function(radio) <?php echo '{
	                if (parseInt(radio.value) >= i){
				$(radio).observe(\'click\',form.tab2.enableMode.bindAsEventListener(form.tab2, i++)); }
			});
		        '; ?>		
		<?php endif; ?>	
	<?php endif; ?>
//<!--@@@FIVEGHZEND@@@-->

<?php echo '
	if (prevInterface != \'\') {
        	if(prevInterface == \'1\'){
                	form.tab1.activate();
                }
                else if(prevInterface == \'2\'){
	                form.tab2.activate();
                }
        }
        else {
'; ?>


<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
	form.tab1.activate();
<?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
	<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
        	<?php if (!$this->_tpl_vars['config']['TWOGHZ']['status'] ): ?>
                    form.tab2.activate();
                <?php endif; ?>
		<?php if ($this->_tpl_vars['data']['radioStatus1'] == '1' && $this->_tpl_vars['data']['radioStatus0'] != '1'): ?>
                	form.tab2.activate();
                <?php endif; ?>
        <?php endif; ?>
//<!--@@@FIVEGHZEND@@@-->
<?php echo '
           }
'; ?>

<?php if ($this->_tpl_vars['config']['WNDAP620']['status'] && $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 ): ?>
$('inlineTab2').observe('click', disableForm);
<?php echo '
	function disableForm()
	{
	Form.disable(document.dataForm);
	}
'; ?>

<?php endif; ?>


-->
</script>
