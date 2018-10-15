<?php /* Smarty version 2.6.18, created on 2012-03-03 13:59:52
         compiled from AdvancedWirelessSettings.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'AdvancedWirelessSettings.tpl', 41, false),array('function', 'ip_field', 'AdvancedWirelessSettings.tpl', 78, false),array('modifier', 'default', 'AdvancedWirelessSettings.tpl', 140, false),)), $this); ?>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Wireless Settings','wirelessLANParameters')</script></td>
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
                            <?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
                                <?php if ($this->_tpl_vars['data']['radioStatus0'] == '0'): ?>
                                    <?php 
                                    $this->_tpl_vars['data']['activeMode0'] = 2;
                                    $this->_tpl_vars['data']['activeMode'] = 2;
                                     ?>
                                <?php endif; ?>
                            <?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
                            <?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
                                <?php if ($this->_tpl_vars['data']['radioStatus1'] == '0'): ?>
                                    <?php 
                                    $this->_tpl_vars['data']['activeMode1'] = 4;
                                    $this->_tpl_vars['data']['activeMode'] = 4;
                                     ?>
                                <?php endif; ?>
                            <?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
							<div id="IncludeTabBlock">
<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
								<?php $this->assign('apMode', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']); ?>
								<div  class="BlockContent" id="wlan1">
									<table class="BlockContent Trans" id="table_wlan1">
<!--@@@SUPERGSTART@@@-->
	<?php if ($this->_tpl_vars['config']['MODE11G']['status'] && $this->_tpl_vars['config']['MODE11G']['SUPERG']['status']): ?>
											<?php $this->assign('supergMode', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['supergMode']); ?>
											<?php $this->assign('operateMode', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['operateMode']); ?>
											<?php echo smarty_function_input_row(array('label' => "Super-G Mode",'id' => 'idsupergMode0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['supergMode'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['supergMode'],'selectCondition' => "==".($this->_tpl_vars['supergMode']),'disableCondition' => "1!=".($this->_tpl_vars['operateMode'])), $this);?>

	<?php endif; ?>
<!--@@@SUPERGEND@@@-->
											<?php echo smarty_function_input_row(array('label' => "RTS Threshold (0-2347)",'id' => 'iRTSThreshold0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rtsThreshold'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rtsThreshold'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 0, maximum: 2347, onlyInteger: true ))^Presence"), $this);?>

											<?php echo smarty_function_input_row(array('label' => "Fragmentation Length (256-2346)",'id' => 'fragmentThreshold0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['fragLength'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['fragLength'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 256, maximum: 2346, onlyInteger: true ))^Presence"), $this);?>


                                            <?php if ($this->_tpl_vars['config']['WN604']['status']): ?>
											<?php echo smarty_function_input_row(array('label' => "Beacon Interval (20-100)",'id' => 'beaconInterval0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['beaconInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['beaconInterval'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 20, maximum: 100, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php else: ?>
											<?php echo smarty_function_input_row(array('label' => "Beacon Interval (100-1000)",'id' => 'beaconInterval0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['beaconInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['beaconInterval'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 100, maximum: 1000, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php endif; ?>
											<?php if ($this->_tpl_vars['config']['MODE11N']['status'] && ( $this->_tpl_vars['data']['activeMode'] == '2' || $this->_tpl_vars['data']['activeMode0'] == '2' )): ?>
											<?php echo smarty_function_input_row(array('label' => "Aggregation Length (1024-65535)",'id' => 'aggregationLength0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['ampduAggrLength'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['ampduAggrLength'],'size' => '6','maxlength' => '5','validate' => "Numericality, (( minimum: 1024, maximum: 65535, onlyInteger: true ))^Presence"), $this);?>

											<?php $this->assign('ampdu', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['ampdu']); ?>
											<?php echo smarty_function_input_row(array('label' => 'AMPDU','id' => 'idampdu0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['ampdu'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['ampdu'],'selectCondition' => "==".($this->_tpl_vars['ampdu'])), $this);?>

											<?php $this->assign('rifsTransmission', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rifsTransmission']); ?>
											<?php echo smarty_function_input_row(array('label' => 'RIFS Transmission','id' => 'idrfis0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rifsTransmission'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['rifsTransmission'],'selectCondition' => "==".($this->_tpl_vars['rifsTransmission']),'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php endif; ?>
											<?php echo smarty_function_input_row(array('label' => "DTIM Interval (1-255)",'id' => 'dtimInterval0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['dtimInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['dtimInterval'],'size' => '5','maxlength' => '3','validate' => "Numericality, (( minimum:1, maximum: 255, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php $this->assign('preambleType', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['preambleType']); ?>
                                            <?php echo smarty_function_input_row(array('label' => 'Preamble Type','id' => 'idpreamble0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['preambleType'],'type' => 'radio','options' => "0-Auto,1-Long",'value' => $this->_tpl_vars['preambleType'],'selectCondition' => "==".($this->_tpl_vars['preambleType']),'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

<!--@@@ANTENNA_SELECTIONSTART@@@-->
    <?php if ($this->_tpl_vars['config']['ANTENNA_SELECTION']['status']): ?>
											<?php $this->assign('antenna', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['antenna']); ?>
											<?php echo smarty_function_input_row(array('label' => 'Antenna','id' => 'idantenna0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['antenna'],'type' => 'radio','options' => "0-Internal,1-External",'value' => $this->_tpl_vars['antenna'],'selectCondition' => "==".($this->_tpl_vars['antenna'])), $this);?>

    <?php endif; ?>
<!--@@@ANTENNA_SELECTIONEND@@@-->
                                            <?php $this->assign('elevenDSupport', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['11dSupport']); ?>
                                            <?php echo smarty_function_input_row(array('label' => "802.11d",'id' => 'eDSupport','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['11dSupport'],'type' => 'checkbox','value' => ($this->_tpl_vars['elevenDSupport']),'selectCondition' => "==1",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

<!--@@@CLIENT_SEPARATIONSTART@@@-->
	<?php if ($this->_tpl_vars['config']['CLIENT_SEPARATION']['status']): ?>
											<?php echo smarty_function_input_row(array('label' => 'Client Isolation','id' => 'clientSeparation0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['clientSeparation'],'type' => 'select','options' => $this->_tpl_vars['clientSeparationList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['clientSeparation'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

	<?php endif; ?>
<!--@@@CLIENT_SEPARATIONEND@@@-->
	<?php if ($this->_tpl_vars['config']['MAXSTATION_FEATURE']['status']): ?>
											<?php $this->assign('maxStationCount', $this->_tpl_vars['config']['MAXSTATION']['count']); ?>
											<?php echo smarty_function_input_row(array('label' => "Max. Wireless Clients",'id' => 'maxWirelessClients0','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['maxWirelessClients'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['maxWirelessClients'],'size' => '4','maxlength' => '3','validate' => "Numericality, (( minimum:1, maximum: ".($this->_tpl_vars['maxStationCount']).", onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'dbMaxWirelessClients0','name' => 'dbMaxWirelessClients0','type' => 'hidden','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['maxWirelessClients']), $this);?>

											<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'dbApMode0','name' => 'dbApMode0','type' => 'hidden','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode']), $this);?>
											
	<?php endif; ?>
										<?php if ($this->_tpl_vars['config']['BAND_STEERING']['status']): ?>
											<?php if (( $this->_tpl_vars['data']['radioStatus0'] == 1 ) && ( $this->_tpl_vars['data']['radioStatus1'] == 1 )): ?>	
											<?php $this->assign('BSstatus', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['bandSteeringStatus']); ?>
											<?php echo smarty_function_input_row(array('label' => 'Band Steering to 5GHz','id' => 'BandSteering','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['bandSteeringStatus'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['BSstatus'],'onclick' => "toggleBandSteering(this.value);",'selectCondition' => "==".($this->_tpl_vars['BSstatus'])), $this);?>

											<?php echo smarty_function_input_row(array('label' => "Rssi Threshold 2.4GHz",'id' => 'RssiThreshold24GHz','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rssi24GHz'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rssi24GHz'],'size' => '4','maxlength' => '3','validate' => "Numericality, (( minimum:0, maximum: 255, onlyInteger: true ))",'disableCondition' => "1!=".($this->_tpl_vars['BSstatus'])), $this);?>

											<?php echo smarty_function_input_row(array('label' => 'Rssi Threshold 5GHz','id' => 'RssiThreshold5GHz','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan0']['rssi50GHz'],'disableCondition' => "0!=".($this->_tpl_vars['BSstatus']),'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['rssi50GHz'],'size' => '4','maxlength' => '3','validate' => "Numericality, (( minimum:0, maximum: 255, onlyInteger: true ))"), $this);?>

											<?php endif; ?>
											<?php endif; ?>
									</table>
								</div>
<?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
								<div  class="BlockContent" id="wlan2">
									<table class="BlockContent Trans" id="table_wlan2">
											<?php echo smarty_function_input_row(array('label' => "RTS Threshold (0-2347)",'id' => 'iRTSThreshold1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['rtsThreshold'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rtsThreshold'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 0, maximum: 2347, onlyInteger: true ))^Presence"), $this);?>

											<?php echo smarty_function_input_row(array('label' => "Fragmentation Length (256-2346)",'id' => 'fragmentThreshold1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['fragLength'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['fragLength'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 256, maximum: 2346, onlyInteger: true ))^Presence"), $this);?>

                                            <?php if ($this->_tpl_vars['config']['WN604']['status']): ?>
											<?php echo smarty_function_input_row(array('label' => "Beacon Interval (20-100)",'id' => 'beaconInterval1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['beaconInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['beaconInterval'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 20, maximum: 100, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

                                           	<?php else: ?>
											<?php echo smarty_function_input_row(array('label' => "Beacon Interval (100-1000)",'id' => 'beaconInterval1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['beaconInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['beaconInterval'],'size' => '5','maxlength' => '4','validate' => "Numericality, (( minimum: 100, maximum: 1000, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php endif; ?>
											<?php if ($this->_tpl_vars['config']['MODE11N']['status'] && ( $this->_tpl_vars['data']['activeMode'] == '4' || $this->_tpl_vars['data']['activeMode1'] == '4' )): ?>
											<?php echo smarty_function_input_row(array('label' => "Aggregation Length (1024-65535)",'id' => 'aggregationLength1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['ampduAggrLength'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['ampduAggrLength'],'size' => '6','maxlength' => '5','validate' => "Numericality, (( minimum: 1024, maximum: 65535, onlyInteger: true ))^Presence"), $this);?>

											<?php $this->assign('ampdu', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['ampdu']); ?>
											<?php echo smarty_function_input_row(array('label' => 'AMPDU','id' => 'idampdu1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['ampdu'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['ampdu'],'selectCondition' => "==".($this->_tpl_vars['ampdu'])), $this);?>

											<?php $this->assign('rifsTransmission', $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['rifsTransmission']); ?>
											<?php echo smarty_function_input_row(array('label' => 'RIFS Transmission','id' => 'idrfis1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['rifsTransmission'],'type' => 'radio','options' => "1-Enable,0-Disable",'value' => $this->_tpl_vars['rifsTransmission'],'selectCondition' => "==".($this->_tpl_vars['rifsTransmission']),'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

	<?php endif; ?>
											<?php echo smarty_function_input_row(array('label' => "DTIM Interval (1-255)",'id' => 'dtimInterval1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['dtimInterval'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['dtimInterval'],'size' => '5','maxlength' => '3','validate' => "Numericality, (( minimum:1, maximum: 255, onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

	<!--@@@CLIENT_SEPARATIONSTART@@@-->
    <?php if ($this->_tpl_vars['config']['CLIENT_SEPARATION']['status']): ?>
											<?php echo smarty_function_input_row(array('label' => 'Client Isolation','id' => 'clientSeparation1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['clientSeparation'],'type' => 'select','options' => $this->_tpl_vars['clientSeparationList'],'selected' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['clientSeparation'],'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

	<?php endif; ?>
<!--@@@CLIENT_SEPARATIONEND@@@-->
	<?php if ($this->_tpl_vars['config']['MAXSTATION_FEATURE']['status']): ?>
											<?php $this->assign('maxStationCount', $this->_tpl_vars['config']['MAXSTATION']['count']); ?>
											<?php echo smarty_function_input_row(array('label' => "Max. Wireless Clients",'id' => 'maxWirelessClients1','name' => $this->_tpl_vars['parentStr']['wlanSettings']['wlanSettingTable']['wlan1']['maxWirelessClients'],'type' => 'text','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['maxWirelessClients'],'size' => '4','maxlength' => '3','validate' => "Numericality, (( minimum:1, maximum: ".($this->_tpl_vars['maxStationCount']).", onlyInteger: true ))^Presence",'disableCondition' => "5==".($this->_tpl_vars['apMode'])), $this);?>

											<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'dbMaxWirelessClients1','name' => 'dbMaxWirelessClients1','type' => 'hidden','value' => $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan1']['maxWirelessClients']), $this);?>

	<?php endif; ?>
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


				<?php if (( ( $this->_tpl_vars['BSstatus'] ) == 0 )): ?>
		<?php echo '		
			$(\'RssiThreshold24GHz\').disabled=true;		
			$(\'RssiThreshold5GHz\').disabled=true;				
		$(\'inlineTab1\').observe(\'click\', disableRssi);
		function disableRssi()
		{
			$(\'RssiThreshold24GHz\').disabled=true;		
			$(\'RssiThreshold5GHz\').disabled=true;				
		}
		'; ?>


		<?php endif; ?>

-->
</script>