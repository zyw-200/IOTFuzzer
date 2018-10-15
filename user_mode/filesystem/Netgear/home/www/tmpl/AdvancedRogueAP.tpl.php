<?php /* Smarty version 2.6.18, created on 2010-12-09 10:45:06
         compiled from AdvancedRogueAP.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'AdvancedRogueAP.tpl', 19, false),array('function', 'data_header', 'AdvancedRogueAP.tpl', 32, false),array('function', 'sortable_header_row', 'AdvancedRogueAP.tpl', 56, false),array('modifier', 'upper', 'AdvancedRogueAP.tpl', 66, false),array('modifier', 'wordwrap', 'AdvancedRogueAP.tpl', 67, false),array('modifier', 'default', 'AdvancedRogueAP.tpl', 249, false),)), $this);

$rogueAp_detection_policy = array ( '10' => 'Mild', '5' => 'Moderate', '1' => 'Aggressive' );
 ?>

		<tr>
				<td>
						<table class="tableStyle">
								<tr>
										<td colspan="3"><script>tbhdr('Rogue AP','rogueAPDetection')</script></td>
								</tr>
								<tr>
										<td class="subSectionBodyDot">&nbsp;</td>
										<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
												<table class="tableStyle">
										<tr>
												<td>
														<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "bandStrip.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
														<div id="IncludeTabBlock">
                                                                                                                    <input type="hidden" name="apMode" id="apMode" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
																<div class="BlockContent" id="wlan1">
																		<table class="BlockContent" id="table_wlan1">
																				<?php echo smarty_function_input_row(array('label' => 'Turn Rogue AP Detection On','id' => 'rogueApDetection0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetection'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetection'],'selectCondition' => "!=0",'size' => '16','maxlength' => '15'), $this);?>
                                                        
						<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>										<?php echo smarty_function_input_row(array('label' => 'Rogue AP Detection Policy','id' => 'rogueApDetectionPolicy0','onchange' => "showMessage('Previous policy will be stopped and newly configured policy will take effect');$('qosDetecPolicy0').value=this.value",'name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetectionPolicy'],'type' => 'select', 'options' => $rogueAp_detection_policy, 'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetectionPolicy'] ), $this);?> 
						<input type="hidden" id="qosDetecPolicy0" name="system['ipsSettings']['ipsSettingTable']['wlan0']['ipsDetectionPolicy']" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetectionPolicy']; ?>"
						<?php else: ?>
						<?php echo smarty_function_input_row(array('label' => 'Rogue AP Detection Policy','id' => 'rogueApDetectionPolicy0','onchange' => "showMessage('Previous policy will be stopped and newly configured policy will take effect');",'name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetectionPolicy'],'type' => 'select', 'options' => $rogueAp_detection_policy, 'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rogueApDetectionPolicy'] ), $this);?> 						
						<?php endif; ?>

																				<?php echo smarty_function_input_row(array('label' => 'Import AP List from a file','id' => 'merge_rogue_ap_list0','name' => 'merge_rogue_ap_list0','type' => 'radio','options' => "1-Replace,2-Merge",'selected' => "==1"), $this);?>

																				<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">
																				<tr>
																						<td class="DatablockLabel">&nbsp;</td>
																						<td class="DatablockContent"><input class="input" id="uploadFile0" name="macListFile0" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
																				</tr>
											<tr>
												<td colspan="2" style="padding: 0px; margin: 0px;">
																		<table class="BlockContent Trans">
																				<tr>
																						<td style="vertical-align: top; width: 100%;">
																								<?php echo smarty_function_data_header(array('label' => 'Known AP List','backgroundColor' => 'transparent','headerType' => 'inline','actionButtons' => "<input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('knownAPList0','0');setActiveContent(); return false;\" value=\"Delete\">"), $this);?>

																						</td>
																						<td style="padding: 0px; margin: 0px;vertical-align: middle; width: 37px; text-align: center;">&nbsp;</td>
																						<td style="vertical-align: top; width: 100%;">
																						<?php echo smarty_function_data_header(array('label' => 'Unknown AP List','backgroundColor' => 'transparent','headerType' => 'inline'),$this);?>
																						</td>	
																					</tr>
																				<tr>
																						<?php 
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan0']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																						}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																							}
																						 ?>
																						<td style="vertical-align: top; padding: 0px;">
																								<div  class="BlockContentTable" id="div_knownAPList0" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 370px;">
																										<table class="BlockContentTable" id="knownAPList0">
																												<thead>
																														<tr id="headRow">
																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'knownAPList0','rowid' => '0','content' => "<input type=\"checkbox\" id=\"knownAPitem0_main\" onclick=\"toggleCheckBoxes('knownAPitem0', this);\">"), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList0','rowid' => '1','content' => 'MAC Address'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList0','rowid' => '2','content' => 'SSID'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList0','rowid' => '3','last' => 'true','content' => 'Channel'), $this);?>

																														</tr>
																												</thead>
																												<tbody>
																														<?php $_from = $this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['knownAP'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['knownAP']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['knownAP']['iteration']++;
?>
																																<tr <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="knownAPitem0" onclick="if (!this.checked) $('knownAPitem0_main').checked=false;"></td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php if ($this->_tpl_vars['value']['knownApSsid'] != '0'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['value']['knownApSsid'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 16, '<br />', true) : smarty_modifier_wordwrap($_tmp, 16, '<br />', true)); ?>
<?php endif; ?>&nbsp;</td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php if ($this->_tpl_vars['value']['knownApChannel'] != '0'): ?><?php echo $this->_tpl_vars['value']['knownApChannel']; ?>
<?php endif; ?>&nbsp;</td>
																																</tr>
																														<?php 
																																$this->_tpl_vars['knownApList_0'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																														 ?>
																														<?php endforeach; endif; unset($_from); ?>
																														<input id="rowCount0" name="rowCount0" value="<?php echo $this->_foreach['knownAP']['total']; ?>
" type="hidden">
																														<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>
																														<input id="Deauth0" name="Deauth0" value="" type="hidden">
																														<?php endif; ?>
																														<input id="addedAPs0" name="system[apList][knownApTable][wlan0]" value="" type="hidden">
																														<input id="deletedAPs0" name="delete[apList][knownApTable][wlan0]" value="" type="hidden">
																														<input id="oldKnownAps0" name="apList[knownApTable][wlan0]" value="<?php echo $this->_tpl_vars['knownApList_0']; ?>
" type="hidden">
																												</tbody>
																										</table>
																								</div>
																						</td>
																							
																						<td style="padding: 2px;vertical-align: middle; width: 37px; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addKnownAP(true,0);setActiveContent();return false;"></td>
																						<?php 
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan0']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																							}
																						 ?>
																						<td style="vertical-align: top;padding: 0px;">
																								<div  class="BlockContentTable" id="div_unknownAPList0" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 370px;">
																										<table class="BlockContentTable" id="unknownAPList0">
																												<thead>
																														<tr id="headRow">
																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'unknownAPList0','rowid' => '0','content' => "<input type=\"checkbox\" id=\"unknownAPitem0_main\" onclick=\"toggleCheckBoxes('unknownAPitem0', this);\">"), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList0','rowid' => '1','content' => 'MAC Address'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList0','rowid' => '2','content' => 'SSID'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList0','rowid' => '3','last' => 'true','content' => 'Channel'), $this);?>

																														</tr>
																												</thead>
																												<tbody id="offTblBdy">
																														<?php $_from = $this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['unknownAP'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['unknownAP']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['unknownAP']['iteration']++;
?>
																																<tr <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="unknownAPitem0" onclick="if (!this.checked) $('unknownAPitem0_main').checked=false;"></td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['value']['unknownApSsid'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 16, '<br />', true) : smarty_modifier_wordwrap($_tmp, 16, '<br />', true)); ?>
</td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['unknownApChannel']; ?>
</td>
																																</tr>
																														<?php endforeach; endif; unset($_from); ?>
																												</tbody>
																										</table>
																								</div>
																						</td>
																				</tr>
																		</table>
												</td>
											</tr>
										</table>
																</div>
<?php endif; ?>
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
																<div  class="BlockContent" id="wlan2">
																		<table class="BlockContent" id="table_wlan2">
																				<?php echo smarty_function_input_row(array('label' => 'Turn Rogue AP Detection On','id' => 'rogueApDetection1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetection'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetection'],'selectCondition' => "!=0",'size' => '16','maxlength' => '15'), $this);?>
													<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>																														
                                                    <?php echo smarty_function_input_row(array('label' => 'Rogue AP Detection Policy','id' => 'rogueApDetectionPolicy1','onchange' => "showMessage('Previous policy will be stopped and newly configured policy will take effect');$('qosDetecPolicy1').value=this.value",'name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetectionPolicy'],'type' => 'select', 'options' => $rogueAp_detection_policy, 'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetectionPolicy'] ), $this);?>
												<input type="hidden" id="qosDetecPolicy1" name="system['ipsSettings']['ipsSettingTable']['wlan1']['ipsDetectionPolicy']" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetectionPolicy']; ?>"
												<?php else: ?>
                                                <?php echo smarty_function_input_row(array('label' => 'Rogue AP Detection Policy','id' => 'rogueApDetectionPolicy1','onchange' => "showMessage('Previous policy will be stopped and newly configured policy will take effect');",'name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetectionPolicy'],'type' => 'select', 'options' => $rogueAp_detection_policy, 'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rogueApDetectionPolicy'] ), $this);?>													
												<?php endif; ?>

																				<?php echo smarty_function_input_row(array('label' => 'Import AP List from a file','id' => 'merge_rogue_ap_list1','name' => 'merge_rogue_ap_list1','type' => 'radio','options' => "1-Replace,2-Merge",'selected' => "==1"), $this);?>


																				<tr>
																						<td class="DatablockLabel">&nbsp;</td>
																						<td class="DatablockContent"><input class="input" id="uploadFile1" name="macListFile1" type="file" onchange="setActiveContent();"></td>
																				</tr>
											<tr>
												<td colspan="2" style="padding: 0px; margin: 0px;">
																		<table class="BlockContent Trans">
																				<tr>
																						<td style="vertical-align: top; width: 100%;">
																								<?php echo smarty_function_data_header(array('label' => 'Known AP List','backgroundColor' => 'transparent','headerType' => 'inline','actionButtons' => "<input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('knownAPList1','1');setActiveContent();return false;\" value=\"Delete\">"), $this);?>

																						</td>
																						
																						<td style="padding: 0px; margin: 0px;vertical-align: middle; width: 37px; text-align: center;">&nbsp;</td>
																						<td style="vertical-align: top; width: 100%;">
																								<?php echo smarty_function_data_header(array('label' => 'Unknown AP List','backgroundColor' => 'transparent','headerType' => 'inline'), $this);?>			
																						</td>	
																					</tr>
																				<tr>
																						<?php 
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan1']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																								}
																						 ?>
																						<td style="vertical-align: top; padding: 0px;">
																								<div  class="BlockContentTable" id="div_knownAPList1" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 370px;">
																										<table class="BlockContentTable" id="knownAPList1">
																												<thead>
																														<tr id="headRow">
																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'knownAPList1','rowid' => '0','content' => "<input type=\"checkbox\" id=\"knownAPitem1_main\" onclick=\"toggleCheckBoxes('knownAPitem1', this);\">"), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList1','rowid' => '1','content' => 'MAC Address'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList1','rowid' => '2','content' => 'SSID'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'knownAPList1','rowid' => '3','last' => 'true','content' => 'Channel'), $this);?>

																														</tr>
																												</thead>
																												<tbody>
																														<?php $_from = $this->_tpl_vars['data']['monitor']['apList']['knownApTable']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['knownAP'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['knownAP']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['knownAP']['iteration']++;
?>
																																<tr <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="knownAPitem1" onclick="if (!this.checked) $('knownAPitem1_main').checked=false;"></td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php if ($this->_tpl_vars['value']['knownApSsid'] != '0'): ?><?php echo ((is_array($_tmp=$this->_tpl_vars['value']['knownApSsid'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 16, '<br />', true) : smarty_modifier_wordwrap($_tmp, 16, '<br />', true)); ?>
<?php endif; ?>&nbsp;</td>
																																		<td <?php if ($this->_foreach['knownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php if ($this->_tpl_vars['value']['knownApChannel'] != '0'): ?><?php echo $this->_tpl_vars['value']['knownApChannel']; ?>
<?php endif; ?>&nbsp;</td>
																																</tr>
																														<?php 
																																$this->_tpl_vars['knownApList_1'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																														 ?>
																														<?php endforeach; endif; unset($_from); ?>
																														<input id="rowCount1" name="rowCount1" value="<?php echo $this->_foreach['knownAP']['total']; ?>
" type="hidden">
																														<?php if ($this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['WNDAP620']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>
																														<input id="Deauth1" name="Deauth1" value="" type="hidden">
																														<?php endif; ?>
																														<input id="addedAPs1" name="system[apList][knownApTable][wlan1]" value="" type="hidden">
																														<input id="deletedAPs1" name="delete[apList][knownApTable][wlan1]" value="" type="hidden">
																														<input id="oldKnownAps1" name="apList[knownApTable][wlan1]" value="<?php echo $this->_tpl_vars['knownApList_1']; ?>
" type="hidden">
																												</tbody>
																										</table>
																								</div>
																						</td>
																						<td style="padding: 2px;vertical-align: middle; width: 37px; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addKnownAP(true,1);setActiveContent();return false;"></td>
																						<?php 
																						$size = count($this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan1']);
																						if ($size > 5) {
																								$this->_tpl_vars['heightStr']='140px';
																								$this->_tpl_vars['overflowStr']='scroll';
																								}
																						else {
																								$this->_tpl_vars['heightStr']='auto';
																								$this->_tpl_vars['overflowStr']='auto';
																								}
																						 ?>
																						<td style="vertical-align: top;padding: 0px;">
																								<div  class="BlockContentTable" id="div_unknownAPList1" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 370px;">
																										<table class="BlockContentTable" id="unknownAPList1">
																												<thead>
																														<tr id="headRow">
																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'unknownAPList1','rowid' => '0','content' => "<input type=\"checkbox\" id=\"unknownAPitem1_main\" onclick=\"toggleCheckBoxes('unknownAPitem1', this);\">"), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList1','rowid' => '1','content' => 'MAC Address'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList1','rowid' => '2','content' => 'SSID'), $this);?>

																																<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'unknownAPList1','rowid' => '3','last' => 'true','content' => 'Channel'), $this);?>

																														</tr>
																												</thead>
																												<tbody id="offTblBdy">
																														<?php $_from = $this->_tpl_vars['data']['monitor']['apList']['unknownApTable']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['unknownAP'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['unknownAP']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['unknownAP']['iteration']++;
?>
																																<tr <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="unknownAPitem1" onclick="if (!this.checked) $('unknownAPitem1_main').checked=false;"></td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
</td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=$this->_tpl_vars['value']['unknownApSsid'])) ? $this->_run_mod_handler('wordwrap', true, $_tmp, 16, '<br />', true) : smarty_modifier_wordwrap($_tmp, 16, '<br />', true)); ?>
</td>
																																		<td <?php if ($this->_foreach['unknownAP']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_tpl_vars['value']['unknownApChannel']; ?>
</td>
																																</tr>
																														<?php endforeach; endif; unset($_from); ?>
																												</tbody>
																										</table>
																								</div>
																						</td>
																				</tr>
																		</table>
												</td>
											</tr>
										</table>
																</div>
<?php endif; ?>
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
		var form = new formObject();
		form.tab1.activate();
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
                <?php if ($this->_tpl_vars['data']['radioStatus0'] == '1'): ?>
                    form.tab1.activate();
                <?php endif; ?>
            <?php endif; ?>
            <?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
		<?php if (!$this->_tpl_vars['config']['TWOGHZ']['status'] ): ?>
                    form.tab2.activate();
                <?php endif; ?>
                <?php if ($this->_tpl_vars['data']['radioStatus1'] == '1' && $this->_tpl_vars['data']['radioStatus0'] != '1'): ?>
                    form.tab2.activate();
                <?php endif; ?>
            <?php endif; ?>
<?php echo '
            }
'; ?>

        <?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
		<?php echo '
			$(\'cb_rogueApDetection0\').observe(\'click\',form.tab1.toggleFields.bindAsEventListener(form.tab1,\'rogueApDetection\'));

		'; ?>

		<?php endif; ?>
		<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
		<?php echo '
			$(\'cb_rogueApDetection1\').observe(\'click\',form.tab2.toggleFields.bindAsEventListener(form.tab2,\'rogueApDetection\'));
		'; ?>

		<?php endif; ?>

        <?php echo '
        if($(\'apMode\').value == "5"){
            Form.disable(document.dataForm);
            disableButtons(["refresh"]);
        }
        '; ?>


-->
</script>
