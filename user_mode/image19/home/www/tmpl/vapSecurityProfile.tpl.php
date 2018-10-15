<?php /* Smarty version 2.6.18, created on 2012-08-22 10:09:53
         compiled from vapSecurityProfile.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'vapSecurityProfile.tpl', 84, false),array('function', 'ip_field', 'vapSecurityProfile.tpl', 146, false),array('modifier', 'regex_replace', 'vapSecurityProfile.tpl', 186, false),array('modifier', 'explode', 'vapSecurityProfile.tpl', 557, false),)), $this); ?>

<script language="javascript">

<!--

    var twoGHzEmpty = 0;

-->

</script>
<?php 
	function special($str){
			include('specialChars.php');
			return $str;
	}

?>


	<?php if (( $this->_tpl_vars['config']['TWOGHZ']['status'] && ! $this->_tpl_vars['config']['FIVEGHZ']['status'] )): ?>

            
        <?php if (( $this->_tpl_vars['data']['wdsMode'] == '1' || $this->_tpl_vars['data']['wdsMode'] == '5' || $this->_tpl_vars['data']['wdsMode'] == '4' )): ?>

            <script language="javascript">

            <!--

                twoGHzEmpty = 1;

            -->

            </script>

        <?php endif; ?>

	<?php endif; ?>



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

<?php if ($this->_tpl_vars['config']['MBSSID']['status']): ?>

							<tr >

                                                                <td class="DatablockLabel">Profile Name</td>

								<td class="DatablockContent">

									<input class="input" size="20" maxlength="32" id="vapProfileName" name="<?php echo $this->_tpl_vars['parentStr']['vapProfileName']; ?>
" value="<?php echo $this->_tpl_vars['data']['vapProfileName']; ?>
" type="text" label="Profile Name"  validate="Presence, <?php echo '{ allowQuotes: true,allowSpace: true}'; ?>
" onkeydown="setActiveContent();">

								</td>

							</tr>



<?php endif; ?>

<?php 

//$this->_tpl_vars['data']['ssid'] = urlencode($this->_tpl_vars['data']['ssid']);

 ?>

							<?php echo smarty_function_input_row(array('label' => "Wireless Network Name (SSID)",'id' => 'ssid','name' => $this->_tpl_vars['parentStr']['ssid'],'type' => 'text','size' => '20','maxlength' => '32','validate' => "Presence, ((allowQuotes: true,allowSpace: true, allowTrimmed: false))^Length, (( minimum: 2 ))",'value' => $this->_tpl_vars['data']['ssid']), $this);?>




							<?php $this->assign('hideNetworkName', $this->_tpl_vars['data']['hideNetworkName']); ?>

							<?php echo smarty_function_input_row(array('label' => "Broadcast Wireless Network Name (SSID)",'id' => 'broadcastSSID','name' => $this->_tpl_vars['parentStr']['hideNetworkName'],'type' => 'radio','options' => "0-Yes,1-No",'selectCondition' => "==".($this->_tpl_vars['hideNetworkName'])), $this);?>


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

							<?php echo smarty_function_input_row(array('label' => 'Network Authentication','id' => 'authenticationType','name' => $this->_tpl_vars['parentStr']['authenticationType'],'type' => 'select','options' => $this->_tpl_vars['authenticationTypeList'],'selected' => $this->_tpl_vars['data']['authenticationType'],'onchange' => "DisplaySettings(this.value);"), $this);?>




							<?php echo smarty_function_input_row(array('label' => 'Data Encryption','id' => 'key_size_11g','name' => 'encryptionType','type' => 'select','options' => $this->_tpl_vars['encryptionTypeList'],'selected' => $this->_tpl_vars['encryptionSel'],'onchange' => "if ($('authenticationType').value=='0') DisplaySettings('1',this.value,1); setEncryption(this.value,$('authenticationType').value);"), $this);?>




							<?php echo smarty_function_ip_field(array('id' => 'encryption','name' => $this->_tpl_vars['parentStr']['encryption'],'type' => 'hidden','value' => $this->_tpl_vars['data']['encryption']), $this);?>


<?php if ($this->_tpl_vars['config']['CENTRALIZED_VLAN']['status']): ?>

                                                        <?php echo smarty_function_ip_field(array('id' => 'vapMacACLStatus','name' => 'vapMacACLStatus','type' => 'hidden','value' => $this->_tpl_vars['data']['macACLStatus']), $this);?>


<?php endif; ?>

							<?php if (! ( $this->_tpl_vars['data']['authenticationType'] == 1 || ( $this->_tpl_vars['data']['authenticationType'] == 0 && $this->_tpl_vars['data']['encryption'] != 0 ) )): ?>

								<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['wepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wepKeyType'],'disabled' => 'true'), $this);?>


							<?php else: ?>

								<?php echo smarty_function_ip_field(array('id' => 'wepKeyType','name' => $this->_tpl_vars['parentStr']['wepKeyType'],'type' => 'hidden','value' => $this->_tpl_vars['data']['wepKeyType']), $this);?>


							<?php endif; ?>

							<?php if (! ( $this->_tpl_vars['data']['authenticationType'] == 1 || ( $this->_tpl_vars['data']['authenticationType'] == 0 && $this->_tpl_vars['data']['encryption'] != 0 ) )): ?>

								<?php $this->assign('hideWepRow', "style=\"display: none;\" disabled='true'"); ?>

								<?php $this->assign('disableWepRow', "disabled='true'"); ?>

							<?php endif; ?>



							<?php if (! ( $this->_tpl_vars['data']['authenticationType'] == 16 || $this->_tpl_vars['data']['authenticationType'] == 32 || $this->_tpl_vars['data']['authenticationType'] == 48 )): ?>

								<?php $this->assign('hideWPARow', "style=\"display: none;\" disabled='true'"); ?>

							<?php endif; ?>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Passphrase</td>

								<td class="DatablockContent">

									<input class="input" size="20" maxlength="39" id="wepPassPhrase" name="<?php echo $this->_tpl_vars['parentStr']['wepPassPhrase']; ?>
" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['wepPassPhrase'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="Passphrase"  onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();" validate="Presence, <?php echo '{ isMasked: \'wepPassPhrase\', allowQuotes: true, allowSpace: true, allowTrimmed:false }'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true); $('wepPassPhrase_hidden').value='';">&nbsp;
<?php $y=special(conf_decrypt($this->_tpl_vars['data']['wepPassPhrase']));
								
								?>
									<input name="szPassphrase_button" style="text-align: center;" value="Generate Keys" onclick="gen_11g_keys()" type="button">

									<input type="hidden" id="wepPassPhrase_hidden" value="<?php echo $y; ?>
">

								</td>

							</tr>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Key 1&nbsp;<input id="keyno_11g1" name="<?php echo $this->_tpl_vars['parentStr']['wepKeyNo']; ?>
" value="1" <?php if ($this->_tpl_vars['data']['wepKeyNo'] == '1'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>
></td>

								<td class="DatablockContent">
									<input class="input" size="20" id="wepKey1" name="system['vapSettings']['vapSettingTable']['wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
']['vap<?php echo $this->_tpl_vars['navigation']['7']; ?>
']['wepKey1']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wepKey1']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WEP Key 1" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g1\' }^HexaDecimal,{ isMasked: \'wepKey1\' }^Length,{ isWep: true }'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Key 2&nbsp;<input id="keyno_11g2" name="<?php echo $this->_tpl_vars['parentStr']['wepKeyNo']; ?>
" value="2" <?php if ($this->_tpl_vars['data']['wepKeyNo'] == '2'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>
></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey2" name="system['vapSettings']['vapSettingTable']['wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
']['vap<?php echo $this->_tpl_vars['navigation']['7']; ?>
']['wepKey2']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wepKey2']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WEP Key 2" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g2\' }^HexaDecimal,{ isMasked: \'wepKey2\' }^Length,{ isWep: true }'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Key 3&nbsp;<input id="keyno_11g3" name="<?php echo $this->_tpl_vars['parentStr']['wepKeyNo']; ?>
" value="3" <?php if ($this->_tpl_vars['data']['wepKeyNo'] == '3'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>
></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey3" name="system['vapSettings']['vapSettingTable']['wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
']['vap<?php echo $this->_tpl_vars['navigation']['7']; ?>
']['wepKey3']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wepKey3']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WEP Key 3" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g3\' }^HexaDecimal,{ isMasked: \'wepKey3\' }^Length,{ isWep: true }'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Key 4&nbsp;<input id="keyno_11g4" name="<?php echo $this->_tpl_vars['parentStr']['wepKeyNo']; ?>
" value="4" <?php if ($this->_tpl_vars['data']['wepKeyNo'] == '4'): ?>checked="checked"<?php endif; ?> type="radio" onclick="setActiveContent();" <?php echo $this->_tpl_vars['disableWepRow']; ?>
></td>

								<td class="DatablockContent">

									<input class="input" size="20" id="wepKey4" name="system['vapSettings']['vapSettingTable']['wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
']['vap<?php echo $this->_tpl_vars['navigation']['7']; ?>
']['wepKey4']" value="<?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wepKey4']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" type="text" label="WEP Key 4" validate="<?php echo 'Presence, { onlyIfChecked: \'keyno_11g4\' }^HexaDecimal,{ isMasked: \'wepKey4\' }^Length,{ isWep: true }'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

								</td>

							</tr>

<?php if ($this->_tpl_vars['config']['WN604']['status']): ?>

							<tr mode="16" <?php echo $this->_tpl_vars['hideWPARow']; ?>
>

								<td class="DatablockLabel">WPA Type</td>

								<td class="DatablockContent">

                                                                    <?php $this->assign('wpaMethod', $this->_tpl_vars['data']['wpaPresharedKeyType']); ?>

                                                                    <?php echo smarty_function_ip_field(array('id' => 'wpaPresharedKeyType','name' => $this->_tpl_vars['parentStr']['wpaPresharedKeyType'],'type' => 'radio','options' => "1-PSK,0-Passphrase",'selectCondition' => "==".($this->_tpl_vars['wpaMethod']),'value' => $this->_tpl_vars['wpaMethod'],'onclick' => "toggleWPAMethods(this.value);"), $this);?>
</td>

								</td>

							</tr>

<?php endif; ?>



<?php if ($this->_tpl_vars['config']['PASSPHRASE_CHAR']['status']): ?>

							<tr mode="1" <?php echo $this->_tpl_vars['hideWepRow']; ?>
>

								<td class="DatablockLabel">Show Passphrase in Clear Text</td>

								<td class="DatablockContent">

                                                                    <?php echo smarty_function_ip_field(array('id' => 'showPassphrase1','name' => 'showPassphrase1','type' => 'radio','options' => "0-No,1-Yes",'value' => '0','selectCondition' => "==0",'onclick' => "showPassPhrase(this.value,this.id);"), $this);?>


								</td>

							</tr>

<?php endif; ?>

							<tr mode="16" <?php echo $this->_tpl_vars['hideWPARow']; ?>
>

								<td class="DatablockLabel">WPA Passphrase (Network Key)</td>

								<td class="DatablockContent">
								<?php $presharedKey=special(conf_decrypt($this->_tpl_vars['data']['presharedKey']));
								?>

                                                                <input type="hidden" id="wpa_psk_hidden" value="<?php echo $presharedKey; ?>
">

<?php if ($this->_tpl_vars['config']['WN604']['status']): ?>

									<input id="wpa_psk" class="input" size="28" maxlength="63" name="<?php echo $this->_tpl_vars['parentStr']['presharedKey']; ?>
" value="<?php if ($this->_tpl_vars['wpsDisable'] == '1'): ?><?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['presharedKey'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
<?php else: ?><?php echo special(conf_decrypt($this->_tpl_vars['data']['presharedKey'])); ?>
<?php endif; ?>" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="" onblur="setPassPhraseTouched();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

<?php else: ?>

    <?php if ($this->_tpl_vars['config']['WPS']['status']): ?>

                                                                        <input id="wpa_psk" class="input" size="28" maxlength="63" name="<?php echo $this->_tpl_vars['parentStr']['presharedKey']; ?>
" value="<?php if ($this->_tpl_vars['wpsDisable'] == '1'): ?><?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['presharedKey'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
<?php else: ?><?php echo special(conf_decrypt($this->_tpl_vars['data']['presharedKey'])); ?>
<?php endif; ?>" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="Presence,<?php echo ' {allowQuotes: true, allowSpace: true, allowTrimmed: false }'; ?>
^Length,<?php echo '{minimum: 8}'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

    <?php else: ?>

                                                                        <input id="wpa_psk" class="input" size="28" maxlength="63" name="<?php echo $this->_tpl_vars['parentStr']['presharedKey']; ?>
" value="<?php echo ((is_array($_tmp=special(conf_decrypt($this->_tpl_vars['data']['presharedKey'])))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA Passphrase (Network Key)" validate="Presence,<?php echo ' {allowQuotes: true, allowSpace: true, allowTrimmed: false }'; ?>
^Length,<?php echo '{minimum: 8}'; ?>
" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);$('wpa_psk_hidden').value='';" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();">

    <?php endif; ?>

<?php endif; ?>								</td>

							</tr>

<?php if ($this->_tpl_vars['config']['WN604']['status']): ?>

                                                        <tr mode="16" <?php echo $this->_tpl_vars['hideWPARow']; ?>
>

								<td class="DatablockLabel">WPA PSK</td>

								<td class="DatablockContent">

                                                                        <input id="wpa_psk_2" class="input" size="28" maxlength="64" name="<?php echo $this->_tpl_vars['parentStr']['wpaPsk']; ?>
" value="<?php if ($this->_tpl_vars['wpsDisable'] == '1'): ?><?php echo ((is_array($_tmp=conf_decrypt($this->_tpl_vars['data']['wpaPsk']))) ? $this->_run_mod_handler('regex_replace', true, $_tmp, "/(.)/", '*') : smarty_modifier_regex_replace($_tmp, "/(.)/", '*')); ?>
<?php else: ?><?php echo conf_decrypt($this->_tpl_vars['data']['wpaPsk']); ?>
<?php endif; ?>" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';this.setAttribute('masked',false)" type="text" label="WPA PSK" validate="" onblur="setPSKTouched();" onkeydown="setActiveContent();" masked="true" onchange="this.setAttribute('masked',false);" onfocus="if(/^\*<?php echo '{1,}$'; ?>
/.test(this.value)) this.value='';setActiveContent();" disabled="disabled">

								</td>

							</tr>



                                                        <?php if (( $this->_tpl_vars['data']['authenticationType'] == 16 || $this->_tpl_vars['data']['authenticationType'] == 32 || $this->_tpl_vars['data']['authenticationType'] == 48 )): ?>

                                                            <script language="javascript">

                                                            <!--

                                                                <?php echo '

                                                                toggleWPAMethods();

                                                                '; ?>


                                                            -->

                                                            </script>

							<?php endif; ?>

<?php endif; ?>

<?php if ($this->_tpl_vars['config']['PASSPHRASE_CHAR']['status']): ?>

							<tr mode="16" <?php echo $this->_tpl_vars['hideWPARow']; ?>
>

								<td class="DatablockLabel">Show Passphrase in Clear Text</td>

								<td class="DatablockContent">

                                                                    <?php echo smarty_function_ip_field(array('id' => 'showPassphrase2','name' => 'showPassphrase2','type' => 'radio','options' => "0-No,1-Yes",'value' => '0','selectCondition' => "==0",'onclick' => "showPassPhrase(this.value,this.id);"), $this);?>


								</td>

							</tr>

<?php endif; ?>

							<?php $this->assign('clientSeparation', $this->_tpl_vars['data']['clientSeparation']); ?>

							<?php echo smarty_function_input_row(array('label' => 'Wireless Client Security Separation','id' => 'clientSeparation','name' => $this->_tpl_vars['parentStr']['clientSeparation'],'type' => 'select','options' => $this->_tpl_vars['clientSeparationList'],'selected' => $this->_tpl_vars['clientSeparation']), $this);?>


<?php if ($this->_tpl_vars['config']['MBSSID']['status']): ?>

    <?php if ($this->_tpl_vars['config']['CENTRALIZED_VLAN']['status']): ?>

                                                        <?php $this->assign('vlanType', $this->_tpl_vars['data']['vlanType']); ?>

                                                        <?php $this->assign('vlanAccessControl', $this->_tpl_vars['data']['vlanAccessControl']); ?>

                                                        <?php $this->assign('vlanAccessControlPolicy', $this->_tpl_vars['data']['vlanAccessControlPolicy']); ?>

                                                        <?php echo smarty_function_input_row(array('label' => 'Dynamic VLAN','id' => 'vlanType','name' => $this->_tpl_vars['parentStr']['vlanType'],'type' => 'select','options' => $this->_tpl_vars['VLANTypeList'],'selected' => $this->_tpl_vars['vlanType'],'onchange' => "updateDynVLANControls()"), $this);?>


							<?php echo smarty_function_input_row(array('label' => 'VLAN ID','id' => 'vlan_id','name' => $this->_tpl_vars['parentStr']['vlanID'],'type' => 'text','value' => $this->_tpl_vars['data']['vlanID'],'size' => '4','maxlength' => '4','validate' => "Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence",'disableCondition' => "0!=".($this->_tpl_vars['vlanType'])), $this);?>


                                                        <?php echo smarty_function_input_row(array('label' => 'Access Control','id' => 'vlanAccessControl','name' => $this->_tpl_vars['parentStr']['vlanAccessControl'],'type' => 'radio','options' => "0-Disable,1-Enable",'selectCondition' => "==".($this->_tpl_vars['vlanAccessControl']),'disableCondition' => "0!=".($this->_tpl_vars['vlanType'])), $this);?>


                                                        <?php echo smarty_function_input_row(array('label' => 'Access Control Policy','id' => 'vlanAccessControlPolicy','name' => $this->_tpl_vars['parentStr']['vlanAccessControlPolicy'],'type' => 'radio','options' => "0-Disable,1-Enable",'selectCondition' => "==".($this->_tpl_vars['vlanAccessControlPolicy']),'disableCondition' => "0!=".($this->_tpl_vars['vlanType'])), $this);?>




    <?php else: ?>

                                                        <?php echo smarty_function_input_row(array('label' => 'VLAN ID','id' => 'vlan_id','name' => $this->_tpl_vars['parentStr']['vlanID'],'type' => 'text','value' => $this->_tpl_vars['data']['vlanID'],'size' => '4','maxlength' => '4','validate' => "Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"), $this);?>


    <?php endif; ?>



<?php endif; ?>

<?php if ($this->_tpl_vars['config']['PRIORITY8021P']['status']): ?>

							<?php echo smarty_function_input_row(array('label' => "802.1P Priority",'id' => 'priority_8021P','name' => $this->_tpl_vars['parentStr']['Priority8021P'],'type' => 'text','value' => $this->_tpl_vars['data']['Priority8021P'],'size' => '4','maxlength' => '1','validate' => "Numericality, (( minimum:0, maximum: 7, onlyInteger: true ))^Presence"), $this);?>


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

		<tr>

		<td class="spacerHeight21"></td>

	</tr>

	</tr>

	

<?php if ($this->_tpl_vars['config']['DHCP_SNOOPING']['status'] && $this->_tpl_vars['config2']['WG102']['status']): ?>

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
	<!--@@@ARADA_QOSSTART@@@-->
	<?php if ($this->_tpl_vars['config']['ARADA_QOS']['status']): ?>	
	<tr>
	<td><table class="tableStyle">
	    <tr>
	      <td colspan="3"><script>tbhdr('QoS Policies ','qospolicies')</script></td>
	    </tr>
	    <tr style="display:none">
	      <td class="subSectionBodyDot">&nbsp;</td>
	      <td >QoS Policies</td>
	      <td class="subSectionBodyDotRight">&nbsp;</td>
	    </tr>
	    <tr>
	      <td class="subSectionBodyDot">&nbsp;</td>
	      <td ><table  class="tableStyle">
	          <tr >
	            <td class="DatablockLabel"></td>
	            <td class="DatablockLabel" >Incoming</td>
	            <td class="DatablockLabel">Outgoing</td>
	          </tr>
			  	<script type='text/javascript'>
				<!--
				var curringressval="<?php echo $this->_tpl_vars['data']['ingress']; ?>
";
				var curregressval="<?php echo $this->_tpl_vars['data']['egress']; ?>
";
				<?php echo '
				function updateClassification(currmode,selpolicy,currrules)
				{
					var flag=true;
					if(currrules == "ingress")
					{
						if(curringressval != \'x\' && curringressval != \'8\' && curringressval != selpolicy && selpolicy != \'8\' )
						{
						flag = false;	
						}
					}
					else
					{
						if(curregressval != \'x\' && curregressval != \'8\' && curregressval != selpolicy && selpolicy != \'8\' )
						flag = false;
					}
					if(flag == true)
					{
						hideMessage();
						var currpolicy="vap"+selpolicy;				
						setActiveContent();
						new Ajax.Request(\'QoSClassifications.php\',
						  {
						  method:\'get\',
						  parameters: {opmode: currmode,policy: currpolicy,id: Math.floor(Math.random()*10005) },    
						  onSuccess: function(QosPolicyName){
						  var response = QosPolicyName.responseText;
						  var tmpstr=response.toString().split("@");
						  $(("show_classiffication")).innerHTML="<select size=\'10\' style=\'width:270px; height:60px;font-size:11px\' class=\'smallfix2\' id=\'Classific_policy_"+currmode+"\'>"+tmpstr[0]+"</select>";
							}
						});
					}
					else
					{
						$(("show_classiffication")).innerHTML="";
						showMessage(\'Delete the QoS policy from the VAP before applying it\');
						setActiveContent(false);
						activeCancel();
					}
				}
				
				'; ?>

				-->
				</script>
	          <tr >
	            <td class="DatablockLabel">Apply Policy</td>
	            <td class="DatablockContent">
				<input type="hidden" value="wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
" id="curropmode"/>
				<input type="hidden" value="vap<?php echo $this->_tpl_vars['navigation']['7']; ?>
" id="currpolicy"/>
				<?php echo smarty_function_ip_field(array('id' => 'egress','name' => $this->_tpl_vars['parentStr']['egress'],'type' => 'hidden','value' => $this->_tpl_vars['data']['egress']), $this);?>

				<?php echo smarty_function_ip_field(array('id' => 'ingress','name' => $this->_tpl_vars['parentStr']['ingress'],'type' => 'hidden','value' => $this->_tpl_vars['data']['ingress']), $this);?>

				<?php $this->assign('policyName', ((is_array($_tmp="&")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['vapQoSPolicyIdx']) : explode($_tmp, $this->_tpl_vars['vapQoSPolicyIdx']))); ?>
				<?php $this->assign('wlan0', ((is_array($_tmp=",")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['policyName'][0]) : explode($_tmp, $this->_tpl_vars['policyName'][0]))); ?>
				<?php $this->assign('wlan1', ((is_array($_tmp=",")) ? $this->_run_mod_handler('explode', true, $_tmp, $this->_tpl_vars['policyName'][1]) : explode($_tmp, $this->_tpl_vars['policyName'][1]))); ?>		
				<select name="policy2input0" style="width:100px"  defaultSelectedIndex="0" onchange="updateClassification('wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
',this.value,'ingress');$('ingress').value=this.value">
				<option value="8">None</option>
				<?php if ($this->_tpl_vars['navigation']['8'] == '0'): ?>
				<script type='text/javascript'>
					<!--
					var QoSPolicies="<?php echo $this->_tpl_vars['vapQoSPolicyIdx']; ?>
";
					<?php echo '
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode1=opmode1.toString().split(",");
					for(i=0;i<opmode1.length;i++)
					{
						var tmp=opmode1[i].toString().split(\'+\')
						if(tmp[0]==1)
						{
						var str="<option value=\'"+i+"\'"
							if(($(\'ingress\').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					'; ?>

					-->
				</script>	
				<?php endif; ?>
				<?php if ($this->_tpl_vars['navigation']['8'] == '1'): ?>
				<script type='text/javascript'>
					<!--
					var QoSPolicies="<?php echo $this->_tpl_vars['vapQoSPolicyIdx']; ?>
";
					<?php echo '
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode2=opmode2.toString().split(",");
					for(i=0;i<opmode2.length;i++)
					{
						var tmp=opmode2[i].toString().split(\'+\')
						if(tmp[0]==1)
						{
						var str="<option value=\'"+i+"\'"
							if(($(\'ingress\').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					'; ?>

					-->
				</script>	
				</select>
				<?php endif; ?>
				  </td>
	            <td class="DatablockContent">
				<select name="policy2input0" style="width:100px"  defaultSelectedIndex="0" onchange="updateClassification('wlan<?php echo $this->_tpl_vars['navigation']['8']; ?>
',this.value,'egress');$('egress').value=this.value">
				<option value="8">None</option>
				<?php if ($this->_tpl_vars['navigation']['8'] == '0'): ?>
				<script type='text/javascript'>
					<!--
					var QoSPolicies="<?php echo $this->_tpl_vars['vapQoSPolicyIdx']; ?>
";
					<?php echo '
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode1=opmode1.toString().split(",");
					for(i=0;i<opmode1.length;i++)
					{
						var tmp=opmode1[i].toString().split(\'+\')
						if(tmp[0]==1)
						{
						var str="<option value=\'"+i+"\'"
							if(($(\'egress\').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					'; ?>

					-->
				</script>	
				<?php endif; ?>
				<?php if ($this->_tpl_vars['navigation']['8'] == '1'): ?>
				<script type='text/javascript'>
					<!--
					var QoSPolicies="<?php echo $this->_tpl_vars['vapQoSPolicyIdx']; ?>
";
					<?php echo '
					var splitopmode=QoSPolicies.toString().split("&");
					var opmode1=splitopmode[0];
					var opmode2=splitopmode[1];
					opmode2=opmode2.toString().split(",");
					for(i=0;i<opmode2.length;i++)
					{
						var tmp=opmode2[i].toString().split(\'+\')
						if(tmp[0]==1)
						{
						var str="<option value=\'"+i+"\'"
							if(($(\'egress\').value)==i)
							str+=" selected ";
						str+=" >"+tmp[1]
						str+="</option>"
						document.write(str);
						}
					}
					'; ?>

					-->
				</script>	
				</select>
				<?php endif; ?>
				</td>
	          </tr>
	          <tr >
	            <td class="DatablockLabel" style="height:60px" valign="top">Policy Details</td>
	            <td class="DatablockContent" colspan="2" >
				<div style="width:270px" id="show_classiffication" >
				
				</div>
				</td>
	          </tr>
	        </table></td>
	      <td class="subSectionBodyDotRight">&nbsp;</td>
	    </tr>
	    <tr>
	      <td colspan="3" class="subSectionBottom"></td>
	    </tr>
	  </table></td>
	</tr>
	<?php endif; ?>
	<!--@@@ARADA_QOSEND@@@-->	

	<input type="hidden" id="radiusEnabled" value="<?php if ($this->_tpl_vars['data']['priRadIpAddr'] == '0.0.0.0'): ?>false<?php else: ?>true<?php endif; ?>">
	
    <input type="hidden" name="previousInterfaceNum" id="previousInterfaceNum" value="<?php echo $_POST['previousInterfaceNum']; ?>
">

<?php if ($this->_tpl_vars['config']['WPS']['status']): ?>

        <input type="hidden" id="wpsDisableStatus" name="wpsDisableStatus" value="<?php echo $this->_tpl_vars['wpsDisable']; ?>
">

<?php endif; ?>

<?php if ($this->_tpl_vars['config']['WN604']['status']): ?>

        <input type="hidden" name="pskTouched" id="pskTouched">

        <input type="hidden" name="passPhraseTouched" id="passPhraseTouched">

<?php endif; ?>

	<script language="javascript">

	<!--

        <?php echo '

        if(($(\'errorMessageBlock\').style.display != \'none\') && ($(\'br_head\').innerHTML == \'Wireless Radio is turned off!\')){

            Form.disable(document.dataForm);

        }

        '; ?>


		var buttons = new buttonObject();

		buttons.getStaticButtons(['back']);

        if(!(config.MBSSID.status))

            top.action.$('back').style.display = 'none';

		<?php echo '

		function doBack()

		{

			$(\'mode7\').value=\'\';

			doSubmit(\'cancel\');

		}

		'; ?>




	<?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && ! $this->_tpl_vars['config']['FIVEGHZ']['status']): ?>

        <?php echo '

            if(twoGHzEmpty == 1){

                Form.disable(document.dataForm);

            }

        '; ?>


    <?php endif; ?>

	-->

	</script>