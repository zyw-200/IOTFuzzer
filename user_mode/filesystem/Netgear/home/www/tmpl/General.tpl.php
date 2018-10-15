<?php /* Smarty version 2.6.18, created on 2010-06-23 06:01:32
         compiled from BasicGeneral.tpl */ ?>
<?php require_once(SMARTY_CORE_DIR . 'core.load_plugins.php');
smarty_core_load_plugins(array('plugins' => array(array('function', 'input_row', 'General.tpl', 11, false),array('function', 'ip_field', 'General.tpl', 13, false),)), $this); ?>

<?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']):
$fwVersionArr = explode(' ', conf_get('system:monitor:sysVersion'));
$fwVersion = $fwVersionArr[1];
//$upTime = `uptime | cut -f2 -d "p" | cut -f1 -d "l" | sed -e "s/ //g"`;
//$upTimeTrim = substr($upTime,0,strlen($upTime)-2);
$upTime = `cat /proc/uptime | awk '{print $1}' | cut -f1 -d "."`;
endif;
?>


<script language="javascript">

//Method to hide/show the link that redirects to the Cloud server page.

function hideLink(flag){

	if(document.getElementById('cloudLink')){

		if(flag==0)

			document.getElementById('cloudLink').style.display = "none";

		else

			document.getElementById('cloudLink').style.display = "block";

	}

}

</script>



	<tr>

		<td>	

			<table class="tableStyle">

				<tr>

					<!-- Table header: Cloud Settings -->

					<td colspan="3"><script>tbhdr('Cloud Settings','CloudSettings')</script></td>

				</tr>

				<tr>

					<td class="subSectionBodyDot">&nbsp;</td>

					<td class="spacer100Percent paddingsubSectionBody">

						<table class="tableStyle" >

							<!-- Radio Button to Enable and Disable the Cloud Mode -->

							<!--<?php $this->assign('cloudStatus', $this->_tpl_vars['data']['basicSettings']['cloudStatus']); ?>
<?php if (($this->_tpl_vars['data']['basicSettings']['cloudStatus']) && ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus'])): ?> 
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "ENABLED"), $this);?>
<?php elseif (($this->_tpl_vars['data']['basicSettings']['cloudStatus']) && !($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus'])): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "DISCONNECTED"), $this);?>
<?php else: ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "DISABLED"), $this);?>
<?php endif; ?>-->

							<!--<?php echo smarty_function_input_row(array('label' => 'Cloud Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Enable,0-Disable", 'selectCondition' => "==".($this->_tpl_vars['cloudStatus']), 'disabled' => "true", 'onclick' => "grayOutForCloud(this);hideLink(this.value);"), $this);?>

							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hiddenCloud','name' => 'hiddenCloud','type' => 'hidden','value' => $this->_tpl_vars['cloudStatus']), $this);?>

							<?php echo smarty_function_input_row(array('label' => 'Cloud Mode','id' => 'enableMode','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Enable,0-Disable", 'selectCondition' => "==".($this->_tpl_vars['data']['basicSettings']['cloudStatus'])), $this);?> -->
							<?php echo smarty_function_input_row(array('label' => 'Cloud Enabled','id' => 'enableMode','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudStatus'],'type' => 'radio','options' => "1-Yes,0-No", 'selectCondition' => "==".($this->_tpl_vars['data']['basicSettings']['cloudStatus']), 'onclick' => "updateCloudStatus(this.value);"), $this);?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'enableMode1','name' => 'enableMode1','type' => 'hidden','value' => $this->_tpl_vars['cloudStatus']), $this);?>
							
							<!--------VVDN CODE START --------->
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hidden_cloudStatus','name' => 'hidden_cloudStatus','type' => 'hidden'), $this);?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'hiddenAP_StandaloneMode','name' => 'hiddenAP_StandaloneMode','type' => 'hidden'), $this);?>
							<?php $serno=explode(' ',exec("grep serno /etc/board"));?>
							<?php echo smarty_function_ip_field(array('label' => "&nbsp;",'id' => 'serno','name' => 'serno','type' => 'hidden','value' => $serno[2]), $this);?>
							<!--------VVDN CODE ENDS -------->
							
							<?php if ($this->_tpl_vars['data']['basicSettings']['cloudStatus']): ?>
							<?php if ($this->_tpl_vars['data']['basicSettings']['cloudConnectivityStatus']): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "Activated"), $this);?>
							<?php else: ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Activation Status','id' => 'enableCloud','name' => $this->_tpl_vars['parentStr']['basicSettings']['cloudConnectivityStatus'],'type' => 'text', 'disabled' => "true", 'class' => 'input','value' => "Pending"), $this);?>
							<?php endif;?>
							
							<?php $connectionStatus=explode(' ',exec("grep opswat.connection_state: /var/pal.cfg"));?>
							<?php if ($connectionStatus[1] =='disconnected' || $connectionStatus[1] ==''): ?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'connStatus','name' => 'connStatus','type' => 'text', 'disabled' => "true", 'value' => "Disconnected"), $this);?>
							<?php else:?>
							<?php echo smarty_function_input_row(array('label' => 'Cloud Connectivity Status','id' => 'connStatus','name' => 'connStatus','type' => 'text' , 'disabled' => "true", 'value' => "Connected"), $this);?>
							<?php endif;?>
							<?php endif;?>
							<tr>          <td class="spacerHeight21"></td>      </tr>
										<td id="cloudLink" class="font10Bold padding4Top" style="text-align: left;"><a href="http://wireless.netgear.com" target="_blank">Click here to open Cloud Management</td>

						 <tr>          <td class="spacerHeight12"></td>      </tr> </table>      <table class="tableStyle">               <tr>                  <td>                          <textarea name="activewin" draggable="false" id="activewin" class="smallfix2" cols="60" style="resize: none;font-size:11px;" rows="4" wrap="on" readonly="readonly">NOTE: If Cloud Enabled is set to Yes, this Access Point will periodically attempt to contact NETGEAR Cloud Wireless Server to check if this Access Point has been registered for cloud management.</textarea> 
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
	<tr>
		<td class="spacerHeight21"></td>
	</tr>

	
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Access Point Information','accessPointInformation')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr>
								<td class="DatablockLabel">Access Point Name</td>
								<td class="DatablockContent"><?php echo $this->_tpl_vars['data']['basicSettings']['apName']; ?>
</td>
							</tr>
							<tr>
								<td class="DatablockLabel">Ethernet MAC Address</td>
								<td class="DatablockContent"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['ethernetMacAddress'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", ":") : smarty_modifier_replace($_tmp, "-", ":")); ?>
</td>
							</tr>
							<?php if (! $this->_tpl_vars['config']['DUAL_CONCURRENT']['status']): ?>
							<tr>
								<td class="DatablockLabel">Wireless MAC Address</td>
                                <?php if ($this->_tpl_vars['config']['TWOGHZ']['status'] && $this->_tpl_vars['data']['radioStatus0'] == '1'): ?>
                                    <td class="DatablockContent"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['macAddress']['wlan0'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", ":") : smarty_modifier_replace($_tmp, "-", ":")); ?>
</td>
                                <?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
                                <?php if ($this->_tpl_vars['config']['FIVEGHZ']['status'] && $this->_tpl_vars['data']['radioStatus1'] == '1'): ?>
                                    <td class="DatablockContent"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['macAddress']['wlan1'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", ":") : smarty_modifier_replace($_tmp, "-", ":")); ?>
</td>
                                <?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
							</tr>
<!--@@@DUAL_CONCURRENTSTART@@@-->
							<?php else: ?>
							<?php if ($this->_tpl_vars['config']['TWOGHZ']['status']): ?>
							<tr>
								<td class="DatablockLabel">Wireless MAC Address for 2.4GHz</td>
								<td class="DatablockContent"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['macAddress']['wlan0'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", ":") : smarty_modifier_replace($_tmp, "-", ":")); ?>
</td>
							</tr>
							<?php endif; ?>
<!--@@@FIVEGHZSTART@@@-->
							<?php if ($this->_tpl_vars['config']['FIVEGHZ']['status']): ?>
							<tr>
								<td class="DatablockLabel">Wireless MAC Address for 5GHz</td>
								<td class="DatablockContent"><?php echo ((is_array($_tmp=$this->_tpl_vars['data']['monitor']['macAddress']['wlan1'])) ? $this->_run_mod_handler('replace', true, $_tmp, "-", ":") : smarty_modifier_replace($_tmp, "-", ":")); ?>
</td>
							</tr>
							<?php endif; ?>
<!--@@@FIVEGHZEND@@@-->
<!--@@@DUAL_CONCURRENTEND@@@-->

							<?php endif; ?>
							<?php if (( $this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['apMode'] != 5 ) && ( $this->_tpl_vars['config']['ARADA_LLDP']['status'] )): ?>
							<tr>
								<td class="DatablockLabel">Ethernet LLDP</td>
								<td class="DatablockContent"><?php if ($this->_tpl_vars['data']['basicSettings']['lldpStatus'] == 0): ?>Disabled<?php else: ?>Enabled<?php endif; ?></td>
							</tr>
							<?php endif; ?>
							<?php if ($this->_tpl_vars['config']['BAND_STEERING']['status']): ?>
							<tr>
								<td class="DatablockLabel">Band Steering to 5GHz </td>
								<td class="DatablockContent"><?php if ($this->_tpl_vars['data']['wlanSettings']['wlanSettingTable']['wlan0']['bandSteeringStatus'] == 0): ?>Disabled<?php else: ?>Enabled<?php endif; ?></td>
							</tr>							
							<?php endif; ?>
							<tr>
								<td class="DatablockLabel">Country / Region</td>
								<td class="DatablockContent"><?php echo $this->_tpl_vars[countryList][$this->_tpl_vars[data][basicSettings][sysCountryRegion]]; ?></td>
							</tr>
							<tr>
								<td class="DatablockLabel">Firmware Version</td>
								<td class="DatablockContent"><?php echo $this->_tpl_vars['data']['monitor']['sysVersion']; ?>
</td>
							</tr>
							<tr>
                              <td class="DatablockLabel">Serial Number</td>
							  <td class="DatablockContent">
							  <?php 
							  $confdEnable = true;
							  if ($confdEnable) 
							  {
								  if ($productId == "WN604")
								  {
									$serno=exec("printmd /dev/mtd5 | grep serno");
									$serno = explode("=", $serno);
									$serno = trim($serno[1]," ");
									if($serno!="" && $serno!=" " && $serno!=null && $serno!=undefined && (!preg_match('#[^a-zA-Z0-9]#', $serno)) && (strlen($serno)=="13") )
									  {
									echo $serno;								
									}
								  }
								  else
								  {
								  $this->assign('SerialNo', $this->_tpl_vars['data']['monitor']['sysSerialNumber']);
								  if($this->_tpl_vars['SerialNo']!="" && $this->_tpl_vars['SerialNo']!=" " && $this->_tpl_vars['SerialNo']!=null && 
									 $this->_tpl_vars['SerialNo']!=undefined && (!preg_match('#[^a-zA-Z0-9]#', $this->_tpl_vars['SerialNo'])) && 
									 (strlen($this->_tpl_vars['SerialNo'])=="13") )
									  {
									  echo ($this->_tpl_vars['SerialNo']);
									  }
								  }
								}
							   ?>
							  </td>
                            </tr>
							<tr>
								<td class="DatablockLabel" >Current Time</td>
								<td class="DatablockContent"><?php  echo `/usr/local/bin/date.sh `; ?></td>
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
		
<script type="text/javascript">
									<!--
									<?php echo '
									if(document.addEventListener){
				
document.addEventListener(\'DOMContentLoaded\', function() {
   cloudSta();
});
}else{
document.attachEvent(\'onreadystatechange\', function() {
   cloudSta();
});
}

										'?>
										<?php echo '
										if(document.addEventListener){
document.addEventListener(\'DOMContentLoaded\', function() {
   cloudUI();
});
}else{
document.attachEvent(\'onreadystatechange\', function() {
   cloudUI();
});
}
										'?>
										<?php echo '
										if(document.addEventListener){
document.addEventListener(\'DOMContentLoaded\', function() {
   cloudConnection();
});
}else{
document.attachEvent(\'onreadystatechange\', function() {
   cloudConnection();
});
}
										'?>
									-->
									</script>
