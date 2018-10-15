<?php /* Smarty version 2.6.18, created on 2012-01-18 16:56:11
         compiled from AdvancedMACAuthentication.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'AdvancedMACAuthentication.tpl', 35, false),array('function', 'data_header', 'AdvancedMACAuthentication.tpl', 130, false),array('function', 'sortable_header_row', 'AdvancedMACAuthentication.tpl', 182, false),array('modifier', 'upper', 'AdvancedMACAuthentication.tpl', 198, false),array('modifier', 'replace', 'AdvancedMACAuthentication.tpl', 202, false),array('modifier', 'default', 'AdvancedMACAuthentication.tpl', 602, false),)), $this); ?>
<tr>

	<td>

		<table class="tableStyle">

			<tr>

				<td colspan="3"><script>tbhdr('MAC Authentication','wirelessMACAuthentication')</script></td>

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

<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>

									<div class="BlockContent" id="wlan1">

										<table class="BlockContent" id="table_wlan1">

											<?php echo smarty_function_input_row(array('label' => 'Turn Access Control On','id' => 'accessControlMode0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['accessControlMode'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['accessControlMode'],'selectCondition' => "!=0",'size' => '16','maxlength' => '15','dependent' => 'accesscontroldb'), $this);?>


											<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">

                                            <input type="hidden" name="dummyAPMode0" id="ApMode0" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']; ?>
">
											<?php if ($this->_tpl_vars['config']['WNDAP360']['status'] || $this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>
											<?php echo smarty_function_input_row(array('label' => 'Import MAC Address List from a file','id' => 'merge_mac_acl_list0','name' => 'merge_mac_acl_list0','type' => 'radio','options' => "1-Replace,2-Merge",'selected' => "==1"), $this);?>

											<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">
											<tr>
													<td class="DatablockLabel">&nbsp;</td>
													<td class="DatablockContent"><input class="input" id="uploadFile0" name="macListFile0" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
											</tr>
											<?php endif; ?>
											<tr>

												<td class="DatablockLabel">Select Access Control Database</td>

												<td class="DatablockContent">

													<select <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['accessControlMode'] == '0'): ?>disabled="disabled"<?php endif; ?> name="accesscontroldb0" id="accesscontroldb0" onchange="setActiveContent();$('accessControlMode0').value=this.value; showRadiusAlert(this);">

														<option value="1" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['accessControlMode'] == '1'): ?>selected="selected"<?php endif; ?>>Local MAC Address Database</option>

                                                                                                                <?php if (! $this->_tpl_vars['config']['WN604']['status']): ?>

                                                                                                                    <option value="2" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['accessControlMode'] == '2'): ?>selected="selected"<?php endif; ?>>Remote MAC Address Database</option>

                                                                                                                <?php endif; ?>

													</select>

												</td>

											</tr>

											<tr>

<?php if ($this->_tpl_vars['config']['CENTRALIZED_VLAN']['status']): ?>

                                                                                        <input type="hidden" name="vlanStatusvap0" id="vlanStatusvap0" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap0']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap0" id="securityStatusvap0" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap0']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap1" id="vlanStatusvap1" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap1']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap1" id="securityStatusvap1" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap1']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap2" id="vlanStatusvap2" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap2']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap2" id="securityStatusvap2" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap2']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap3" id="vlanStatusvap3" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap3']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap3" id="securityStatusvap3" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap3']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap4" id="vlanStatusvap4" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap4']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap4" id="securityStatusvap4" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap4']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap5" id="vlanStatusvap5" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap5']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap5" id="securityStatusvap5" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap5']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap6" id="vlanStatusvap6" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap6']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap6" id="securityStatusvap6" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap6']['authenticationType']; ?>
">

                                                                                        <input type="hidden" name="vlanStatusvap7" id="vlanStatusvap7" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap7']['vlanType']; ?>
">

                                                                                        <input type="hidden" name="securityStatusvap7" id="securityStatusvap7" value="<?php echo $this->_tpl_vars['data']['vapSettings']['vapSettingTable']['wlan0']['vap7']['authenticationType']; ?>
">















<?php endif; ?>

												<td colspan="2" style="padding: 0px; margin: 0px;">

													<table class="BlockContent Trans">

														<tr>

															<td style="vertical-align: top; width: 100%; padding: 0px;">

																<?php echo smarty_function_data_header(array('label' => 'Trusted Wireless Stations','backgroundColor' => 'transparent','headerType' => 'inline','actionButtons' => "<input type=\"image\" src=\"images/add_on.gif\" name=\"addmac\" id=\"addmac\" onclick=\"addTrustedStation(false,0, event);//openAddNewMacWin(event,0);\" value=\"Add\" style=\"margin-right: 2px;\"><input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('trustedTable0','0');setActiveContent(); return false;\" value=\"Delete\">"), $this);?>


																<input id="rowCount0" name="rowCount0" value="4" type="hidden">

															</td>

															<td style="vertical-align: middle; width:1%; text-align: center;">

																&nbsp;

															</td>

															<td style="vertical-align: top; width: 100%; padding: 0px;">

																	<?php echo smarty_function_data_header(array('label' => 'Available Wireless Stations','backgroundColor' => 'transparent','headerType' => 'inline'), $this);?>


															</td>

														</tr>

														<tr>

															<?php 

															$size = count($this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan0']);

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

																<div class="BlockContentTable" id="div_trustedTable0" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 300px;">

																	<table class="BlockContentTable Trans" id="trustedTable0">

																		<tr id="headRow">

																			<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'trustedTable0','rowid' => '0','content' => "<input type=\"checkbox\" id=\"trustStation0_main\" onclick=\"toggleCheckBoxes('trustStation0', this);\">"), $this);?>


																			<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'trustedTable0','rowid' => '1','last' => 'true','content' => 'MAC Address'), $this);?>


																		</tr>

																		<tr id="addMacRow0">

																			<th >&nbsp;</th>

																			<th ><input type="text" id="addNewMac0" class="input" value="" name="123" validate="MACAddress" label="New MAC Address"></th>

																		</tr>

																		<?php $_from = $this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['trustedStations'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['trustedStations']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['trustedStations']['iteration']++;
?>

																		<tr <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?> id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">

																				<td <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="trustStation0" onclick="if (!this.checked) $('trustStation0_main').checked=false;"></td>

																				<td <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')); ?>
</td>

																		</tr>
																		<?php 
																				$this->_tpl_vars['trustedList_0'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																		 ?>
																														

																		<?php endforeach; endif; unset($_from); ?>

																		<input id="rowCount0" name="rowCount0" value="<?php echo $this->_foreach['trustedStations']['total']; ?>
" type="hidden">

																		<input id="addedStations0" name="system[accessControlSettings][wlanAccessControlLocalTable][wlan0]" value="" type="hidden">

																		<input id="deletedStations0" name="delete[accessControlSettings][wlanAccessControlLocalTable][wlan0]" value="" type="hidden">
																		
																		<input id="oldStations0" name="accessControlSettings[wlanAccessControlLocalTable][wlan0]" value="<?php echo $this->_tpl_vars['trustedList_0']; ?>
" type="hidden">

																	</table>

																</div>

															</td>

															<td style="padding: 2px; vertical-align: middle; text-align: center;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addTrustedStation(true,'0',event);setActiveContent();return false;"></td>

															<?php 

															$size = count($this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan0']);

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

																<div class="BlockContentTable" id="div_avblStationList0" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 300px;">

																	<table class="BlockContentTable Trans" id="avblStationList0">

																		<thead>

																			<tr id="headRow">

																				<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'avblStationList0','rowid' => '0','content' => "<input type=\"checkbox\" id=\"avblStation0_main\" onclick=\"toggleCheckBoxes('avblStation0', this);\">"), $this);?>


																				<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'avblStationList0','rowid' => '1','content' => 'Station ID'), $this);?>


																				<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'avblStationList0','rowid' => '2','last' => 'true','content' => 'MAC Address'), $this);?>


																			</tr>

																		</thead>

																		<tbody id="offTblBdy">

																			<?php $_from = $this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan0']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['newStations'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['newStations']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['newStations']['iteration']++;
?>

																			<tr <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?> id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">

																				<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="avblStation0" onclick="if (!this.checked) $('avblStation0_main').checked=false;"></td>

																				<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_foreach['newStations']['iteration']; ?>
</td>

																				<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')); ?>
</td>

																			</tr>

																			<?php endforeach; endif; unset($_from); ?>

																		</tbody>

																	</table>

																</div>

															</td>

														</tr>

<?php if ($this->_tpl_vars['config']['MACACL_IMP_EXP']['status']): ?>

							<tr>

								<td class="DatablockContent" style="text-align: left;">

									<input type="button" name="saveas" id="saveas" value="Save as..." onclick="doSave(0)">

								</td>

							</tr>

<?php endif; ?>



													</table>

												</td>

											</tr>

										</table>

									</div>

<?php endif; ?>

<!--@@@FIVEGHZSTART@@@-->

<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>

									<div class="BlockContent" id="wlan2">

										<table class="BlockContent"  id="table_wlan2">

										<?php echo smarty_function_input_row(array('label' => 'Turn Access Control On','id' => 'accessControlMode1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['accessControlMode'],'type' => 'checkbox','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['accessControlMode'],'selectCondition' => "!=0",'size' => '16','maxlength' => '15','dependent' => 'accesscontroldb'), $this);?>


                                        <input type="hidden" name="dummyAPMode1" id="ApMode1" value="<?php echo $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['apMode']; ?>
">
										<?php if ($this->_tpl_vars['config']['WNDAP360']['status'] || $this->_tpl_vars['config']['WNDAP660']['status'] || $this->_tpl_vars['config']['JWAP603']['status']): ?>
										<?php echo smarty_function_input_row(array('label' => 'Import MAC Address List from a file','id' => 'merge_mac_acl_list1','name' => 'merge_mac_acl_list1','type' => 'radio','options' => "1-Replace,2-Merge",'selected' => "==1"), $this);?>

											<input type="hidden" id="rogueApDetection" value="<?php echo $this->_tpl_vars['interface']; ?>
">
											<tr>
													<td class="DatablockLabel">&nbsp;</td>
													<td class="DatablockContent"><input class="input" id="uploadFile1" name="macListFile1" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false" onchange="setActiveContent();"></td>
											</tr>
											<?php endif; ?>
											<tr>

												<td class="DatablockLabel">Select Access Control Database</td>

												<td class="DatablockContent">

													<select <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['accessControlMode'] == '0'): ?>disabled="disabled"<?php endif; ?> name="accesscontroldb1" id="accesscontroldb1" onchange="setActiveContent();$('accessControlMode1').value=this.value; showRadiusAlert(this);">

														<option value="1" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['accessControlMode'] == '1'): ?>selected="selected"<?php endif; ?>>Local MAC Address Database</option>

														<option value="2" <?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['accessControlMode'] == '2'): ?>selected="selected"<?php endif; ?>>Remote MAC Address Database</option>

													</select>

												</td>

											</tr>

										<tr>

											<td colspan="2" style="padding: 0px; margin: 0px;">

												<table class="BlockContent Trans">

													<tr>

														<td style="vertical-align: top; width: 100%; padding: 0px;">

															<?php echo smarty_function_data_header(array('label' => 'Trusted Wireless Stations','backgroundColor' => 'transparent','headerType' => 'inline','actionButtons' => "<input type=\"image\" src=\"images/add_on.gif\" name=\"addmac\" id=\"addmac\" onclick=\"addTrustedStation(false,1, event);//openAddNewMacWin(event,1);\" value=\"Add\" style=\"margin-right: 2px;\"><input type=\"image\" src=\"images/delete_on.gif\" name=\"delete\" id=\"delete\" onclick=\"deleteRows('trustedTable1','1'); return false;\" value=\"Delete\">"), $this);?>


															<input id="rowCount" name="rowCount" value="4" type="hidden">

														</td>

														<td style="vertical-align: middle; width:37px; text-align: center;">

															&nbsp;

														</td>

														<td style="vertical-align: top; width: 100%; padding: 0px;">

															<?php echo smarty_function_data_header(array('label' => 'Available Wireless Stations','backgroundColor' => 'transparent','headerType' => 'inline'), $this);?>


														</td>

													</tr>

													<tr>

														<?php 

														$size = count($this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan1']);

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

															<div class="BlockContentTable" id="div_trustedTable1" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 300px;">

																<table class="BlockContentTable Trans" id="trustedTable1">

																	<tr id="headRow">

																		<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'trustedTable1','rowid' => '0','content' => "<input type=\"checkbox\" id=\"trustStation1_main\" onclick=\"toggleCheckBoxes('trustStation1', this);\">"), $this);?>


																		<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'trustedTable1','rowid' => '1','last' => 'true','content' => 'MAC Address'), $this);?>


																	</tr>

																	<tr id="addMacRow1">

																		<th >&nbsp;</th>

																		<th ><input type="text" id="addNewMac1" class="input" value="" name="123" validate="MACAddress" label="New MAC Address"></th>

																	</tr>

																	<?php $_from = $this->_tpl_vars['data']['accessControlSettings']['wlanAccessControlLocalTable']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['trustedStations'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['trustedStations']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['trustedStations']['iteration']++;
?>

																	<tr <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?> id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">

																		<td <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="trustStation1" onclick="if (!this.checked) $('trustStation1_main').checked=false;"></td>

																		<td <?php if ($this->_foreach['trustedStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')); ?>
</td>

																	</tr>
																	
																	<?php 
																				$this->_tpl_vars['trustedList_1'].= str_replace('-',':',strtoupper($this->_tpl_vars['key'])) . ',';
																		 ?>

																	<?php endforeach; endif; unset($_from); ?>

																	<input id="rowCount1" name="rowCount1" value="<?php echo $this->_foreach['trustedStations']['total']; ?>
" type="hidden">

																	<input id="addedStations1" name="system[accessControlSettings][wlanAccessControlLocalTable][wlan1]" value="" type="hidden">

																	<input id="deletedStations1" name="delete[accessControlSettings][wlanAccessControlLocalTable][wlan1]" value="" type="hidden">
																																			
																	<input id="oldStations1" name="accessControlSettings[wlanAccessControlLocalTable][wlan1]" value="<?php echo $this->_tpl_vars['trustedList_1']; ?>
" type="hidden">


																</table>

															</div>

														</td>

														<td style="padding: 2px; vertical-align: middle; text-align: left;"><input type="image" src="images/move_on.gif" name="add" id="add" value="< Move" onclick="addTrustedStation(true,'1',event);setActiveContent(); return false;"></td>

														<?php 

														$size = count($this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan1']);

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

															<div class="BlockContentTable" id="div_avblStationList1" style="height:<?php echo $this->_tpl_vars['heightStr']; ?>
; overflow-y: <?php echo $this->_tpl_vars['overflowStr']; ?>
; width: 300px;">

																<table class="BlockContentTable Trans" id="avblStationList1">

																	<thead>

																		<tr id="headRow">

																			<?php echo smarty_function_sortable_header_row(array('sortable' => 'false','tableid' => 'avblStationList1','rowid' => '0','content' => "<input type=\"checkbox\" onclick=\"toggleCheckBoxes('avblStation1', this);\">"), $this);?>


																			<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'avblStationList1','rowid' => '1','content' => 'Station ID'), $this);?>


																			<?php echo smarty_function_sortable_header_row(array('sortable' => 'true','tableid' => 'avblStationList1','rowid' => '2','last' => 'true','content' => 'MAC Address'), $this);?>


																		</tr>

																	</thead>

																	<tbody id="offTblBdy">

																		<?php $_from = $this->_tpl_vars['data']['monitor']['stationList']['newStaList']['wlan1']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }$this->_foreach['newStations'] = array('total' => count($_from), 'iteration' => 0);
if ($this->_foreach['newStations']['total'] > 0):
    foreach ($_from as $this->_tpl_vars['key'] => $this->_tpl_vars['value']):
        $this->_foreach['newStations']['iteration']++;
?>

																		<tr <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?> id="<?php echo ((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)); ?>
">

																			<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><input type="checkbox" id="avblStation1" onclick="if (!this.checked) $('avblStation1_main').checked=false;"></td>

																			<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo $this->_foreach['newStations']['iteration']; ?>
</td>

																			<td <?php if ($this->_foreach['newStations']['iteration']%2 == 0): ?>class="Alternate"<?php endif; ?>><?php echo ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['key'])) ? $this->_run_mod_handler('upper', true, $_tmp) : smarty_modifier_upper($_tmp)))) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')); ?>
</td>

																		</tr>

																		<?php endforeach; endif; unset($_from); ?>

																	</tbody>

																</table>

															</div>

														</td>

													</tr>

<?php if ($this->_tpl_vars['config']['MACACL_IMP_EXP']['status']): ?>

							<tr>

								<td class="DatablockContent" style="text-align: left;">

									<input type="button" name="saveas2" id="saveas2" value="Save as..." onclick="doSave(1)">

								</td>

							</tr>

<?php endif; ?>



												</table>

											</td>

										</tr>

									</table>

								</div>

<?php endif; ?>

<!--@@@FIVEGHZEND@@@-->

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

<input type="hidden" id="radiusEnabled" value="<?php if ($this->_tpl_vars['data']['info802dot1x']['authinfo']['priRadIpAddr'] == '0.0.0.0'): ?>false<?php else: ?>true<?php endif; ?>">



<script language="javascript">

<!--

        var prevInterface = <?php echo ((is_array($_tmp=@$_POST['previousInterfaceNum'])) ? $this->_run_mod_handler('default', true, $_tmp, "''") : smarty_modifier_default($_tmp, "''")); ?>
;

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

			$(\'cb_accessControlMode0\').observe(\'click\',form.tab1.toggleFields.bindAsEventListener(form.tab1,\'accessControlMode\'));

		'; ?>


		<?php endif; ?>

//<!--@@@FIVEGHZSTART@@@-->

		<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>

		<?php echo '

			$(\'cb_accessControlMode1\').observe(\'click\',form.tab2.toggleFields.bindAsEventListener(form.tab2,\'accessControlMode\'));

		'; ?>


		<?php endif; ?>

//<!--@@@FIVEGHZEND@@@-->

<?php echo '

        function doSave(interface)

        {

                doSave_MacAuth(interface);

		$(\'knownApListForm\').submit();

		return false;





        }

'; ?>


-->

</script>



</form>

<form name="knownApListForm" id="knownApListForm" action="saveTable.php" method="post">

<input type="hidden" id = "ApList" name="ApList" value="">

</form>
