<?php /* Smarty version 2.6.18, created on 2010-10-15 10:09:44
         compiled from wdsSecurityProfile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'wdsSecurityProfile.tpl', 34, false),array('function', 'ip_field', 'wdsSecurityProfile.tpl', 62, false),array('modifier', 'replace', 'wdsSecurityProfile.tpl', 34, false),array('modifier', 'regex_replace', 'wdsSecurityProfile.tpl', 81, false),)), $this); ?>
<?php 
if(!empty($_REQUEST['wdsLinkMode'])) {
echo "<input type='hidden' id='wdsLinkMode' value ='".$_REQUEST['wdsLinkMode']."'>";
 ?>
	<script>
	<!--
		//$('br_head').innerHTML = "TFTP failed to get the file!";
		//$('errorMessageBlock').show();
//alert('hit');
                //alert($('wdsLinkMode').value);
	-->
	</script>
<?php 
}
 ?>
 <?php
	$productIdArr = explode(' ', conf_get('system:monitor:productId'));
    $productId = $productIdArr[1];
?>
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
					<td colspan="3"><script>tbhdr('Profile Definition','profileDefinition')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
                                             <table class="tableStyle">
							<tr >
                                                                <td class="DatablockLabel">Profile Name</td>
								<td class="DatablockContent">
									<input class="input" size="20" maxlength="32" id="wdsProfileName" name="<?php echo $this->_tpl_vars['parentStr']['wdsProfileName']; ?>
" value="<?php echo $this->_tpl_vars['data']['wdsProfileName']; ?>
" type="text" label="Profile Name"  validate="Presence, <?php echo '{ allowQuotes: true,allowSpace: true}'; ?>
" onkeydown="setActiveContent();">
								</td>
							</tr>


                                     <?php if(!$this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>  		
                                           <?php echo smarty_function_input_row(array('label' => 'Remote MAC Address','id' => 'remoteMacAddress','name' => $this->_tpl_vars['parentStr']['remoteMacAddress'],'type' => 'text','masked' => 'true','onblur' => "this.setAttribute('masked',(this.value != '')?false:true)",'value' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['remoteMacAddress'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')))) ? $this->_run_mod_handler('replace', true, $_tmp, '00:00:00:00:00:00', '') : smarty_modifier_replace($_tmp, '00:00:00:00:00:00', '')),'validate' => "MACAddress, (( checkSameMac: '".($this->_tpl_vars['macValue'])."'))"), $this); ?>
                                     <?php else: ?>                                           
                                           <?php echo smarty_function_input_row(array('label' => 'Remote MAC Address','id' => 'remoteMacAddress','name' => $this->_tpl_vars['parentStr']['remoteMacAddress'],'type' => 'text','masked' => 'true','onblur' => "this.setAttribute('masked',(this.value != '')?false:true)",'value' => ((is_array($_tmp=((is_array($_tmp=$this->_tpl_vars['data']['remoteMacAddress'])) ? $this->_run_mod_handler('replace', true, $_tmp, '-', ':') : smarty_modifier_replace($_tmp, '-', ':')))) ? $this->_run_mod_handler('replace', true, $_tmp, '00:00:00:00:00:00', '') : smarty_modifier_replace($_tmp, '00:00:00:00:00:00', '')),'validate' => "MACAddress, (( checkSameMac: '".($this->_tpl_vars['macValue0'])."'))^ MACAddress, (( checkSameMac: '".($this->_tpl_vars['macValue1'])."' ))"), $this); ?>
                                     <?php endif; ?>
                                     
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
	<tr>
		<td class="spacerHeight21">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Authentication Settings','authenticationSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php echo smarty_function_input_row(array('label' => 'Network Authentication','id' => 'authenticationType','name' => $this->_tpl_vars['parentStr']['wdsAuthenticationtype'],'type' => 'select','options' => $this->_tpl_vars['authenticationTypeList'],'selected' => $this->_tpl_vars['data']['wdsAuthenticationtype'],'onchange' => "wdsDisplaySettings(this.value);"), $this);?>


							<?php echo smarty_function_input_row(array('label' => 'Data Encryption','id' => 'key_size_11g','name' => 'encryptionType','type' => 'select','options' => $this->_tpl_vars['encryptionTypeList'],'selected' => $this->_tpl_vars['encryptionSel'],'onchange' => "if ($('authenticationType').value=='0') wdsDisplaySettings('1',this.value,1); setEncryption(this.value,$('authenticationType').value);"), $this);?>


							<?php echo smarty_function_ip_field(array('id' => 'encryption','name' => $this->_tpl_vars['parentStr']['wdsEncryption'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wdsEncryption']), $this);?>


							<?php if (! ( $this->_tpl_vars['data']['wdsAuthenticationtype'] == 1 || ( $this->_tpl_vars['data']['wdsAuthenticationtype'] == 0 && $this->_tpl_vars['data']['wdsEncryption'] != 0 ) )): ?>
								<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['wdsWepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wdsWepKeyType'],'disabled' => 'true'), $this);?>

							<?php else: ?>
								<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['wdsWepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wdsWepKeyType']), $this);?>

							<?php endif; ?>

							<?php if (! ( $this->_tpl_vars['data']['wdsAuthenticationtype'] == 1 || ( $this->_tpl_vars['data']['wdsAuthenticationtype'] == 0 && $this->_tpl_vars['data']['wdsEncryption'] != 0 ) )): ?>
								<?php $this->assign('hideWepRow', "style=\"display: none;\" disabled='true'"); ?>
								<?php $this->assign('disableWepRow', "disabled='true'"); ?>
							<?php endif; ?>

							<?php if (! ( $this->_tpl_vars['data']['wdsAuthenticationtype'] == 2 || $this->_tpl_vars['data']['wdsAuthenticationtype'] == 4 )): ?>
								<?php $this->assign('hideWPARow', "style=\"display: none;\" disabled='true'"); ?>
							<?php endif; ?>
							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>
								<td class="DatablockLabel">Passphrase</td>
								<td class="DatablockContent">
									<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="<?php echo $this->_tpl_vars['parentStr']['wdsWepPassPhrase']; ?>
" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['wdsWepPassPhrase'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="Passphrase" validate="Presence, <?php echo '{ isMasked: \'wepPassPhrase\', allowQuotes: true, allowSpace: true, allowTrimmed: false }'; ?>
" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);">&nbsp;
									<input name="szPassphrase_button" style="text-align: center;" value="Generate Key" onclick="gen_11g_keys()" type="button">
									<input type="hidden" id="wepPassPhrase_hidden" value="<?php echo special(conf_decrypt($this->_tpl_vars['data']['wdsWepPassPhrase'])); ?>
">
								</td>
							</tr>
							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>
								<td class="DatablockLabel">WEP Key</td>
								<td class="DatablockContent">
									<input class="input" size="20" id="wepKey" name="<?php echo $this->_tpl_vars['parentStr']['wdsWepKey']; ?>
" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wdsWepKey']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WEP Key" validate="<?php echo 'Presence^HexaDecimal,{ isMasked: \'wepKey\' }^Length,{ isWep: true}'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value!='')?false:true);" <?php echo $this->_tpl_vars['disableWepRow']; ?>
 onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">&nbsp;
								</td>
							</tr>
							<tr mode="4" <?php echo $this->_tpl_vars['hideWPARow']; ?>
>
								<td class="DatablockLabel">WPA Passphrase (Network Key)</td>
								<td class="DatablockContent">
									<input id="wpa_psk" class="input" size="28" maxlength="63" name="<?php echo $this->_tpl_vars['parentStr']['wdsPresharedkey']; ?>
" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['wdsPresharedkey'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WPA Passphrase (Network Key)" validate="Presence, <?php echo '{ allowQuotes: true, allowSpace: true,  allowTrimmed: false }'; ?>
^Length,<?php echo '{minimum: 8}'; ?>
"  onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);">
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
		<tr>
		<td class="spacerHeight21"></td>
	</tr>
	</tr>
<?php if ($productId=='WNDAP360') {?>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Link Test','linkTest')</script></td>
				</tr> 
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
                                                        <?php  echo smarty_function_input_row(array('label' => 'IP Address','id' => 'linkIpAddr','name' => 'linkIpAddr','type' => 'ipfield','value' => "",'validate' => 'IpAddress,((allowZero: false, allowEmpty: true ))'), $this); ?>

                                                        <tr><td></td>
                                                            <td class="DatablockContent" style="text-align: left;">
<!-- if remote mac address is null then don't start the link test -->
                                                                    <input type="button" name="linktest" id="linktest" value="Link Test" onclick="startLinkTest();" >
                                                            </td>
                                                        </tr>
                                                        <tr><td class="DatablockLabel">Link Test Process Status</td>
                                                                <td class="DatablockContent" style="text-align: left;">
                                                                        <div id="showbarValue" style="font-size:10pt;padding:2px;black 1px;text-align: left">Uninitialized</div>
                                                                </td>
                                                        </tr>
                                                        <tr><td></td>
                                                                <td class="DatablockContent" style="text-align: left;">
                                                                        <div id="linktestonoffimage"><img src="images/pushButton_On.gif"</div><div id="showbar" style="font-size:8pt;padding:2px;"></div>
                                                                </td>
                                                        </tr>
						</table>
					</td>
					<td class="subSectionBodyDotRight">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="subSectionBottom"></td>
				</tr>
			</table>
		</td>
	</tr> 
<?php } ?>
	<?php if ($this->_tpl_vars['config']['DHCP_SNOOPING']['status'] && $this->_tpl_vars['config2']['WG102']['status']): ?>}

		<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCP Snooping','dhcpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<?php $this->assign('dhcpTrustedInterface', $this->_tpl_vars['data']['dhcpTrustedInterface']); ?>
							<?php echo smarty_function_input_row(array('label' => 'DHCP Trusted Interface','id' => 'dhcpTrustedInterface','name' => $this->_tpl_vars['parentStr']['dhcpTrustedInterface'],'type' => 'radio','options' => "1-Yes,0-No",'selectCondition' => "==".($this->_tpl_vars['dhcpTrustedInterface'])), $this);?>

						</table>
					</td>
					<td class="subSectionBodyDotRight">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="3" class="subSectionBottom"></td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="spacerHeight21"></td>
	</tr>
	<?php endif; ?>
	
	
    <input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum" value="<?php echo $_POST['previousInterfaceNum']; ?> 
">
	<script language="javascript">
	<!--
		var buttons = new buttonObject();
		buttons.getStaticButtons(['back']);
		<?php echo '
		function doBack()
		{
			$(\'mode7\').value=\'\';
			doSubmit(\'cancel\');
		}
		'; ?>

	-->
 	</script>
