<?php /* Smarty version 2.6.18, created on 2012-02-28 12:06:43
         compiled from Bridging.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'Bridging.tpl', 40, false),array('modifier', 'replace', 'Bridging.tpl', 41, false),array('modifier', 'default', 'Bridging.tpl', 270, false),)), $this); ?>
<?php
$confdEnable = true;
        if ($confdEnable) {
                $productIdArr = explode(' ', conf_get('system:monitor:productId'));
                $productId = $productIdArr[1];
        }
?>

	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Bridging','wirelessBridgeSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
							<tr>
								<td style="width: 100%;">
									<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bandStrip.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
										<div id="IncludeTabBlock">
<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
	<?php $this->assign('widthString', "width: 606px;"); ?>
				<?php 
								$this->_tpl_vars['widthArrMoz'] = Array('1'   =>  '35.3', '2' =>  '44.3', '3' =>  '13.6');
								$this->_tpl_vars['widthArrIE'] = Array('1'   =>  '40.3', '2' =>  '45.3', '3' =>  '14.3');
				 ?>
<?php else: ?>
	<?php $this->assign('widthString', "width: 536px; _width: 555px;"); ?>
				<?php 
								$this->_tpl_vars['widthArrMoz'] = Array('0'   =>  '48.7', '1' =>  '49');
								$this->_tpl_vars['widthArrIE'] = Array('0'   =>  '49.9', '1' =>  '50');
				 ?>
<?php endif; ?>
<?php if (( ( ! ( $this->_tpl_vars['config']['CLIENT']['status'] ) ) && ( ! ( $this->_tpl_vars['config']['P2P']['status'] ) ) && ( ( $this->_tpl_vars['config']['P2MP']['status'] ) ) )): ?>
	<?php $this->assign('widthString', "width: 536px; _width: 455px;"); ?>

				<?php 
								$this->_tpl_vars['widthArrMoz'] = Array('1'   =>  '48.7', '2' =>  '100');
								$this->_tpl_vars['widthArrIE'] = Array('1'   =>  '49.9', '2' =>  '100');
				 ?>

<?php endif; ?>
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
											<div  class="BlockContentTable" id="wlan1">
												<table style="<?php echo $this->_tpl_vars['widthString']; ?>
" id="table_wlan1">
													<?php echo smarty_function_input_row(array('label' => 'Enable Wireless Bridging','id' => 'wdsEnabled0','name' => 'wdsOnEnabled0','type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'],'selectCondition' => "!=0"), $this);?>

													<?php echo smarty_function_input_row(array('label' => 'Local MAC Address','type' => 'value','value' => ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['wdsMacAddress']['wlan0'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':'))), $this);?>

<input type="hidden" id="clientStatus" value="<?php echo $this->_tpl_vars['data']['basicSettings']['dhcpClientStatus'];?>">
<input type="hidden" id="apModeTab" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'];?>">

													

<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">
													<input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
" id="apMode0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">
                                                    <input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['operateMode']; ?>
" id="operateMode0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['operateMode']; ?>
">
													<input type="hidden" name="dummy" id="currentApMode0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">
													<tr>
														<td colspan="2" style="margin: 0px; padding: 0px; width: 100%; white-space: nowrap;">
															<ul class="inlineSubTabs">
                                                                <?php $_from = $this->_tpl_vars['wdsData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['wdsDataLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['wdsDataLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['wdsDataItem']):
        $this->_foreach['wdsDataLoop']['iteration']++;
?>
                                                                    <?php $this->assign('cnt', $this->_foreach['wdsDataLoop']['iteration']); ?>
                                                                    <?php $this->assign('tabLabel', $this->_tpl_vars['wdsDataItem']['tabLabel']); ?>
                                                                    <?php $this->assign('tabVal0', $this->_tpl_vars['wdsDataItem']['tabVal0']); ?>
                                                                    <?php $this->assign('tabSelect0', $this->_tpl_vars['wdsDataItem']['tabSelect0']); ?>
                                                                    <?php $this->assign('tabWdsVaps0', $this->_tpl_vars['wdsDataItem']['noOfWdsVaps']); ?>
																<li id="includeSubTab0<?php echo $this->_tpl_vars['cnt']; ?>
" style="width: <?php echo $this->_tpl_vars['widthArrMoz'][$this->_tpl_vars['cnt']]; ?>
%; _width: <?php echo $this->_tpl_vars['widthArrIE'][$this->_tpl_vars['cnt']]; ?>
%; text-align: left; white-space: nowrap;" <?php if ($this->_tpl_vars['wdsDataItem']['activeApMode0']): ?>class="Active"<?php endif; ?>><input name="apmod1" id="wdsMode0" type="radio" value=<?php echo $this->_tpl_vars['tabVal0']; ?>
 <?php if ($this->_tpl_vars['tabSelect0']): ?>checked="checked"<?php endif; ?>><b><?php echo $this->_tpl_vars['tabLabel']; ?>
</b></li>
                                                                <?php endforeach; endif; unset($_from); ?>
															</ul>
														</td>
													</tr>
													<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
													<tr id="macClone_0" style="display: none;">
														<td colspan="2">
														<table class="BlockContent Trans" style="border: 1px solid #CCCCCC; margin-top: -2px;">
															<?php $this->assign('macClone', $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['macClone']); ?>
															<?php echo smarty_function_input_row(array('label' => 'MAC Clone','id' => 'macClone0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['macClone'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => ($this->_tpl_vars['macClone']),'selectCondition' => "==".($this->_tpl_vars['macClone'])), $this);?>

															<?php $this->assign('macCloneAddr', ((is_array($_tmp=$this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['macCloneAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':'))); ?>
															<?php echo smarty_function_input_row(array('label' => 'MAC Clone Address','id' => 'macCloneAddr0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['macCloneAddr'],'type' => 'text','value' => ((is_array($_tmp=$this->_tpl_vars['macCloneAddr'])) ? $this->_run_mod_handler('replace', true, $_tmp, '00:00:00:00:00:00', '') : smarty_modifier_replace($_tmp, '00:00:00:00:00:00', '')),'masked' => 'true','onchange' => "this.setAttribute('masked',(this.value != '')?false:true)",'disableCondition' => "0==".($this->_tpl_vars['macClone']),'validate' => "MACAddress^Presence"), $this);?>
							<?php if ($productId == 'WN604'): ?>
							    <?php echo smarty_function_input_row(array('label' => "Roaming RSSI Threshold (0-95)",'id' => 'roamingRSSIThreshold0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['rssiThreshold'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['rssiThreshold'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 0, maximum: 95, onlyInteger: true ))^Presence"), $this);?>

                                                            <?php $this->assign('roamingRSSIVal', $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['rateThreshold']); ?>
                                                            <?php echo smarty_function_input_row(array('label' => 'Roaming Rate Threshold','id' => 'roamingRateThreshold0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['rateThreshold'],'type' => 'select','options' => $this->_tpl_vars['roamingRateThresholdList'],'selected' => $this->_tpl_vars['roamingRSSIVal']), $this);?>

                                                            <?php echo smarty_function_input_row(array('label' => "No Beacon Timeout (0-20000)",'id' => 'noBeaconTimeout0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['noBeacon'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['noBeacon'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 0, maximum: 20000, onlyInteger: true ))^Presence"), $this);?>

                                                            <?php echo smarty_function_input_row(array('label' => "Hardware TX Retries (1-15)",'id' => 'hwTXRetries0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['hardwareTxRetries'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['hardwareTxRetries'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 1, maximum: 15, onlyInteger: true ))^Presence"), $this);?>

								<?php echo smarty_function_input_row(array('label' => "Roam Debug Level (0-3)",'id' => 'roamDebugLevel0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['debugLevel'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['roamingSettings']['debugLevel'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 0, maximum: 3, onlyInteger: true ))^Presence"), $this);?>

                                							<?php $this->assign('backgrndScanStatus', $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanMode']); ?>
                                                            <?php echo smarty_function_input_row(array('label' => 'Background Scan','id' => 'backgrndScanRadio0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanMode'],'type' => 'radio','options' => "1-On,0-Off",'selectCondition' => "==".($this->_tpl_vars['backgrndScanStatus'])), $this);?>

                                                            <?php $this->assign('scanType', $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanType']); ?>
                                                            <?php echo smarty_function_input_row(array('label' => 'Scan Type','id' => 'scanTypeRadio0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanType'],'type' => 'radio','options' => "2-Active,3-Passive,4-Mixed",'selectCondition' => "==".($this->_tpl_vars['scanType'])), $this);?>

                                                            <?php echo smarty_function_input_row(array('label' => "Background Scan Interval (100-6000)",'id' => 'bkGrdScanInterval0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanInterval'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 100, maximum: 6000, onlyInteger: true ))^Presence"), $this);?>

                                                            <?php echo smarty_function_input_row(array('label' => "Background Scan Time Per Channel (5-100)",'id' => 'bkGrdScanTime0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanTimePerCha'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanTimePerCha'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 5, maximum: 100, onlyInteger: true ))^Presence"), $this);?>

                                                            <?php $this->assign('clearBSSList', $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['clearBssList']); ?>
                                                            <?php echo smarty_function_input_row(array('label' => 'Clear BSS List When Scan','id' => 'clearBSSListRadio0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['clearBssList'],'type' => 'radio','options' => "1-On,0-Off",'selectCondition' => "==".($this->_tpl_vars['clearBSSList'])), $this);?>

                                                            <?php echo smarty_function_input_row(array('label' => "BSS Aging Period (0-65536)",'id' => 'bssAgingPeriod0','name' => $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['bssAgeingPeriod'],'type' => 'text','value' => $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['bssAgeingPeriod'],'size' => '5','maxlength' => '5','validate' => "Numericality, (( minimum: 0, maximum: 65536, onlyInteger: true ))^Presence"), $this);?>

                                                             <tr>

                                                                <td class="DatablockLabel">Background Scan Channel List</td>
                                                                <td class="DatablockContent">
                                                                        <table width="80%">
                                                                                <tr align="center"><td "width=10%">1</td><td "width=10%">2</td><td "width=10%">3</td><td "width=10%">4</td><td "width=10%">5</td><td "width=10%">6</td><td "width=10%">7</td><td "width=10%">8</td><td "width=10%">9</td><td "width=10%">10</td><td "width=10%">11</td></tr>
                                                                                <tr align="center">
                                                                                    <td><Input type="checkbox" id="bgChanList00" onclick="setActiveContent();convertWeekScheduleToString();"<?php if ($this->_tpl_vars['scheduledWirelessStatus'] == '0'): ?>disabled="disabled"<?php endif; ?>>
                                                                                    <input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanChaList']; ?>
" id="scanChanListString0" value="<?php echo $this->_tpl_vars['data']['staSettings']['staSettingTable']['wlan0']['sta0']['scanSettings']['scanChaList']; ?>
">

                                                                                    <td><Input type="checkbox" id="bgChanList01" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList02" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList03" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList04" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList05" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList06" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList07" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList08" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList09" onclick="convertScanChanToString();">

                                                                                    <td><Input type="checkbox" id="bgChanList010" onclick="convertScanChanToString();">

                                                                                </tr>
                                                                        </table>

                                                                </td>
                                                        </tr>
                                                </tr>
						<?php endif; ?>
                                                            														</table>
														</td>
													</tr>
													<?php endif; ?>
													<tr>
														<td colspan="2">
															<table class="BlockContentTable Trans" style="border-top: 1px solid #CCCCCC; margin-top: -1px;" id="profilesList_0">
																<thead>
																	<tr id="WCArow_0">
																		<td class="DatablockLabel" colspan="3" style="border-top: 0px; border-right: 0px;">Enable Wireless Client Association</td>
																		<td class="DatablockContent" colspan="2" style="border-top: 0px; border-left: 0px;"><input name="WCAenabled0" id="WCAenabled0" type="checkbox" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == '2'): ?>checked="checked"<?php endif; ?>></td>
																	</tr>
																	<tr>
																		<th style="width:5%">&nbsp;</th>
																		<th style="width:5%">#</th>
																		<th style="width:45%">Profile Name</th>
																		<th style="width:35%">Security</th>
																		<th style="width:10%" class="Last">Enable</th>
																	</tr>
																</thead>
																<tbody id="tbody_0">
																	<?php $_from = $this->_tpl_vars['data']['wdsSettings']['wdsSettingTable']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profiles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profiles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['profiles']['iteration']++;
?>
																	<?php $this->assign('wdsIndex', $this->_tpl_vars['value']['wdsIndex']-1); ?>
																	<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 )): ?>
																	<tr  disabled="disabled">
																		<td ><input type="radio" id="profileid_0" name="profileid0" value="<?php echo $this->_tpl_vars['wdsIndex']; ?>
" <?php if ($this->_tpl_vars['value']['wdsIndex'] == '1'): ?>checked="checked"<?php endif; ?>></td>
																		<td ><?php echo $this->_tpl_vars['value']['wdsIndex']; ?>
</td>
																		<td><?php echo $this->_tpl_vars['value']['wdsProfileName']; ?>
</td>
																		<?php 
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		 ?>
																		<td><?php echo $this->_tpl_vars['wdsAuthType']; ?>
</td>
																		<td><input type="checkbox" id=cb_wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
 name="cb_wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
" onclick="$('wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
_0').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" <?php if ($this->_tpl_vars['value']['wdsProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan0]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus]; ?>" id="wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
_0" value="<?php echo $this->_tpl_vars['value']['wdsProfileStatus']; ?>
"></td>
																	</tr>
																	<?php else: ?>
																	<tr <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="radio" id="profileid_0" name="profileid0" value="<?php echo $this->_tpl_vars['wdsIndex']; ?>
" <?php if ($this->_tpl_vars['value']['wdsIndex'] == '1'): ?>checked="checked"<?php endif; ?>></td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['wdsIndex']; ?>
</td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['wdsProfileName']; ?>
</td>
																		<?php 
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		 ?>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['wdsAuthType']; ?>
</td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id=cb_wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
 name="cb_wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
" onclick="$('wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
_0').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" <?php if ($this->_tpl_vars['value']['wdsProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan0]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus]; ?>" id="wdsProfileStatus0<?php echo $this->_tpl_vars['wdsIndex']; ?>
_0" value="<?php echo $this->_tpl_vars['value']['wdsProfileStatus']; ?>
"></td>
																	</tr>
																	<?php endif; ?>

																	<?php endforeach; endif; unset($_from); ?>
																</tbody>
															</table>
														</td>
													</tr>
												</table>
											</div>
<?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
											<div  class="BlockContentTable" <?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>style="display:none;"<?php endif; ?> id="wlan2">
												<table style="<?php echo $this->_tpl_vars['widthString']; ?>
" id="table_wlan2">
													<?php echo smarty_function_input_row(array('label' => 'Enable Wireless Bridging','id' => 'wdsEnabled1','name' => 'wdsOnEnabled1','type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'],'selectCondition' => "!=0"), $this);?>

													<?php echo smarty_function_input_row(array('label' => 'Local MAC Address','type' => 'value','value' => ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['wdsMacAddress']['wlan1'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':'))), $this);?>

													<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">
													<input type="hidden" name="<?php echo $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']; ?>
" id="apMode1" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']; ?>
">
													<input type="hidden" name="dummy" id="currentApMode1" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']; ?>
">
													<tr>
														<td colspan="2" style="margin: 0px; padding: 0px; width: 100%; white-space: nowrap;">
															<ul class="inlineSubTabs">
                                                                <?php $_from = $this->_tpl_vars['wdsData']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['wdsDataLoop'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['wdsDataLoop']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['wdsDataItem']):
        $this->_foreach['wdsDataLoop']['iteration']++;
?>
                                                                    <?php if (! ( $this->_tpl_vars['wdsDataItem']['tabLabel'] == 'Client' )): ?>
                                                                    <?php $this->assign('cnt', $this->_foreach['wdsDataLoop']['iteration']); ?>
                                                                    <?php $this->assign('tabLabel', $this->_tpl_vars['wdsDataItem']['tabLabel']); ?>
                                                                    <?php $this->assign('tabVal1', $this->_tpl_vars['wdsDataItem']['tabVal1']); ?>
                                                                    <?php $this->assign('tabSelect1', $this->_tpl_vars['wdsDataItem']['tabSelect1']); ?>
                                                                    <?php $this->assign('tabWdsVaps1', $this->_tpl_vars['wdsDataItem']['noOfWdsVaps']); ?>
																<li id="includeSubTab1<?php echo $this->_tpl_vars['cnt']; ?>
" style="width: <?php echo $this->_tpl_vars['widthArrMoz'][$this->_tpl_vars['cnt']]; ?>
%; _width: <?php echo $this->_tpl_vars['widthArrIE'][$this->_tpl_vars['cnt']]; ?>
%; text-align: left; white-space: nowrap;" <?php if ($this->_tpl_vars['wdsDataItem']['activeApMode1']): ?>class="Active"<?php endif; ?>><input name="apmod2" id="wdsMode1" type="radio" value=<?php echo $this->_tpl_vars['tabVal1']; ?>
 <?php if ($this->_tpl_vars['tabSelect1']): ?>checked="checked"<?php endif; ?>><b><?php echo $this->_tpl_vars['tabLabel']; ?>
</b></li>
                                                                    <?php endif; ?>
                                                                <?php endforeach; endif; unset($_from); ?>
															</ul>
														</td>
													</tr>
													<tr>
														<td colspan="2">
															<table class="BlockContentTable Trans" style="border-top: 1px solid #CCCCCC; margin-top: -1px;" id="profilesList_1">
																<thead>
																	<tr id="WCArow_1">
																		<td class="DatablockLabel" colspan="3" style="border-top: 0px; border-right: 0px;">Enable Wireless Client Association</td>
																		<td class="DatablockContent" colspan="2" style="border-top: 0px; border-left: 0px;"><input name="WCAenabled1" id="WCAenabled1" type="checkbox" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == '2'): ?>checked="checked"<?php endif; ?>></td>
																	</tr>
																	<tr>
																		<th style="width:5%">&nbsp;</th>
																		<th style="width:5%">#</th>
																		<th style="width:45%">Profile Name</th>
																		<th style="width:35%">Security</th>
																		<th style="width:10%" class="Last">Enable</th>
																	</tr>
																</thead>
																<tbody id="tbody_1">
																	<?php $_from = $this->_tpl_vars['data']['wdsSettings']['wdsSettingTable']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['profiles'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['profiles']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['profiles']['iteration']++;
?>
																	<?php $this->assign('wdsIndex', $this->_tpl_vars['value']['wdsIndex']-1); ?>
																	<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == 5 )): ?>
																	<tr  disabled="disabled">
																		<td ><input type="radio" id="profileid_1" name="profileid1" value="<?php echo $this->_tpl_vars['wdsIndex']; ?>
" <?php if ($this->_tpl_vars['value']['wdsIndex'] == '1'): ?>checked="checked"<?php endif; ?>></td>
																		<td ><?php echo $this->_tpl_vars['value']['wdsIndex']; ?>
</td>
																		<td><?php echo $this->_tpl_vars['value']['wdsProfileName']; ?>
</td>
																		<?php 
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		 ?>
																		<td><?php echo $this->_tpl_vars['wdsAuthType']; ?>
</td>
																		<td><input type="checkbox" id=cb_wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
 name="cb_wdsProfileStatus1<?php echo $this->_tpl_vars['wdsIndex']; ?>
" onclick="$('wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
_1').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" <?php if ($this->_tpl_vars['value']['wdsProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan1]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus]; ?>" id="wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
_1" value="<?php echo $this->_tpl_vars['value']['wdsProfileStatus']; ?>
"></td>
																	</tr>
																	<?php else: ?>
																	<tr <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="radio" id="profileid_1" name="profileid1" value="<?php echo $this->_tpl_vars['wdsIndex']; ?>
" <?php if ($this->_tpl_vars['value']['wdsIndex'] == '1'): ?>checked="checked"<?php endif; ?>></td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['wdsIndex']; ?>
</td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['wdsProfileName']; ?>
</td>
																		<?php 
																			$this->_tpl_vars['wdsAuthType'] = $this->_tpl_vars['authenticationTypeList'][$this->_tpl_vars['value']['wdsAuthenticationtype']];
																		 ?>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['wdsAuthType']; ?>
</td>
																		<td <?php if ($this->_foreach['profiles']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id=cb_wdsProfileStatus1<?php echo $this->_tpl_vars['wdsIndex']; ?>
 name="cb_wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
" onclick="$('wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
_1').value=(this.checked)?'1':'0';setActiveContent();resetActiveContent();" value="1" <?php if ($this->_tpl_vars['value']['wdsProfileStatus'] == '1'): ?>checked="checked"<?php endif; ?>><input type="hidden" name="<?php echo $this->_tpl_vars[parentStr][wdsSettings][wdsSettingTable][wlan1]['wds'.$this->_tpl_vars[wdsIndex]][wdsProfileStatus]; ?>" id="wdsProfileStatus<?php echo $this->_tpl_vars['wdsIndex']; ?>
_1" value="<?php echo $this->_tpl_vars['value']['wdsProfileStatus']; ?>
"></td>
																	</tr>
																	<?php endif; ?>

																	<?php endforeach; endif; unset($_from); ?>
																</tbody>
															</table>
														</td>
													</tr>
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
	<script language="javascript">
	<!--
        var prevInterface = <?php echo ((is_array($_tmp=@$_POST['previousInterfaceNum'])) ? $this->_run_mod_handler('default', true, $_tmp, "''") : smarty_modifier_default($_tmp, "''")); ?>
;
		var buttons = new buttonObject();
		buttons.getStaticButtons(['edit']);

		<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
			<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channel'] == '0' && ( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 0 || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] == 5 )): ?>
				var disableWDSonChannel0 = true;
			<?php endif; ?>
			<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['radioStatus'] != '0'): ?>
				<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['channel'] == '0' )): ?>
					<?php $this->assign('checkWlan0ApStatus', "disabled='disabled'"); ?>
					<?php $this->assign('checkEditDisable', "disabled='disabled'"); ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
		<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
			<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['channel'] == '0' && ( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == 0 || $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode'] == 5 )): ?>
				var disableWDSonChannel1 = true;
			<?php endif; ?>
			<?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['radioStatus'] != '0'): ?>
				<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['channel'] == '0' )): ?>
					<?php $this->assign('checkWlan1ApStatus', "disabled='disabled'"); ?>
					<?php $this->assign('checkEditDisable', "disabled='disabled'"); ?>
				<?php endif; ?>
			<?php endif; ?>
		<?php endif; ?>

//<!--@@@FIVEGHZEND@@@-->
		<?php echo '
			function doEdit()
			{
				if(window.top.frames[\'action\'].$(\'edit\').src.indexOf(\'edit_on\')!== -1)
				{
					'; ?>

					prepareURL('<?php echo $this->_tpl_vars['navigation']['4']; ?>
','<?php echo $this->_tpl_vars['navigation']['3']; ?>
','<?php echo $this->_tpl_vars['navigation']['2']; ?>
','<?php echo $this->_tpl_vars['navigation']['1']; ?>
','profileid',String(parseInt(form.activeTab)-1),'wdsprofile');
					<?php echo '
					return false;
				}
				else
				{
					window.top.frames[\'action\'].$(\'edit\').disabled=true;
				}
			}
		'; ?>



		<?php if ($this->_tpl_vars['checkEditDisable'] != ''): ?>
			window.top.frames['action'].$('edit').disabled=true;
			window.top.frames['action'].$('edit').src="images/edit_off.gif";
		<?php endif; ?>
		var form = new formObject();
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
				<?php if (! $this->_tpl_vars['config']['TWOGHZ']['status']): ?>
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


		<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>

		<?php echo '
			$(\'cb_wdsEnabled0\').observe(\'click\',form.tab1.wdsOnEnable.bindAsEventListener(form.tab1));
			var i = 1;
			$RD(\'wdsMode0\').each( function(radio) {
         '; ?>

            <?php if ($this->_tpl_vars['data']['radioStatus0'] == '1'): ?>
         <?php echo '
                    $(radio).observe(\'click\',form.tab1.activateSubTab.bindAsEventListener(form.tab1, i++));
         '; ?>

            <?php endif; ?>
         <?php echo '
			});
        '; ?>

            <?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
                <?php echo '
                $RD(\'macClone0\').each( function(radio) {
                    $(radio).observe(\'click\',form.tab1.toggleMACCloneAddress.bindAsEventListener(form.tab1));
                });
                '; ?>

            <?php endif; ?>
         <?php echo '
			$(\'WCAenabled0\').observe(\'click\',form.tab1.setApModeWCA.bindAsEventListener(form.tab1));
		'; ?>

		<?php endif; ?>
//<!--@@@FIVEGHZSTART@@@-->
		<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
	<?php echo '
			$(\'cb_wdsEnabled1\').observe(\'click\',form.tab2.wdsOnEnable.bindAsEventListener(form.tab2));
			var i = 1;
			$RD(\'wdsMode1\').each( function(radio) {
     '; ?>

                <?php if ($this->_tpl_vars['data']['radioStatus1'] == '1'): ?>
     <?php echo '
                    $(radio).observe(\'click\',form.tab2.activateSubTab.bindAsEventListener(form.tab2, i++));
     '; ?>

                <?php endif; ?>
     <?php echo '
			});
        '; ?>

            <?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
            <?php echo '
                $RD(\'macClone1\').each( function(radio) {
                    $(radio).observe(\'click\',form.tab2.toggleMACCloneAddress.bindAsEventListener(form.tab2));
                });
            '; ?>

            <?php endif; ?>
        <?php echo '
			$(\'WCAenabled1\').observe(\'click\',form.tab2.setApModeWCA.bindAsEventListener(form.tab2));
		'; ?>

		<?php endif; ?>

//<!--@@@FIVEGHZEND@@@-->

<?php if ($this->_tpl_vars['config']['CLIENT']['status']): ?>
            <?php echo '
                convertbkgrdScanChanList();
            '; ?>

<?php endif; ?>
	-->
	</script>
