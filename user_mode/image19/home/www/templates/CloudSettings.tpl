<script language="javascript">
<!--
function hideLink(flag){

	if(document.getElementById('cloudLink')){

		if(flag==0)

			document.getElementById('cloudLink').style.display = "none";

		else

			document.getElementById('cloudLink').style.display = "block";

	}

}
-->
</script>
{if $data.basicSettings.cloudStatus}
$fwVersionArr = explode(' ', conf_get('system:monitor:sysVersion'));
$fwVersion = $fwVersionArr[1];
//$upTime = `uptime | cut -f2 -d "p" | cut -f1 -d "l" | sed -e "s/ //g"`;
//$upTimeTrim = substr($upTime,0,strlen($upTime)-2);
$upTime = `cat /proc/uptime | awk '{print $1}' | cut -f1 -d "."`;
{/if}
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Cloud Settings','CloudSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
						{assign var="cloudStatus" value=$data.basicSettings.cloudStatus}
						{if $data.basicSettings.cloudStatus && $data.basicSettings.cloudConnectivityStatus}
							
							{input_row label="Cloud Status" id="enableCloud" name="parentStr[basicSettings][cloudStatus]" type="text" class ="input" disabled="true" value="ENABLED"}
						{elseif $data.basicSettings.cloudStatus && !$data.basicSettings.cloudConnectivityStatus}
							{input_row label="Cloud Status" id="enableCloud" name="parentStr[basicSettings][cloudStatus]" type="text" class ="input" disabled="true" value="DISCONNECTED"}
						{else}
							{input_row label="Cloud Status" id="enableCloud" name="parentStr[basicSettings][cloudStatus]" type="text" class ="input" disabled="true" value="DISABLED"}
						{/if}
						
						{if $data.basicSettings.cloudStatus}
						{if $config.DHCPCLIENT.status}
						{assign var="dhcpClientStatus" value=$data.basicSettings.dhcpClientStatus}
							{input_row label="DHCP Client" id="enabledhcp" name="system[basicSettings][dhcpClientStatus]" type="radio" options="1-Enable,0-Disable" value=$dhcpClientStatus selectCondition="==$dhcpClientStatus" onclick="grayOutForCloud(this);"}
{else}
							{assign var="dhcpClientStatus" value="0"}
{/if}
						
							{input_row label="IP Address" id="ipaddr" name="system[basicSettings][ipAddr]" type="ipfield" value=$data.monitor.ipAddress disableCondition="1==$dhcpClientStatus" validate="IpAddress, (( allowZero: false ))^Presence"}

							{input_row label="IP Subnet Mask" id="subnetmask" name="system[basicSettings][netmaskAddr]" type="ipfield" value=$data.monitor.subNetMask disableCondition="1==$dhcpClientStatus" validate="IpAddress, (( onlyNetMask: true ))^Presence"}

{if $config.NETWORK_INTEGRALITY.status}
							{assign var="presenceString" value="Presence, (( onlyIfChecked: 'cb_networkintegrality' ))^"}
{/if}
							{input_row label="Default Gateway" id="gateway" name="system[basicSettings][gatewayAddr]" type="ipfield" value=$data.monitor.defaultGateway|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true);" disableCondition="1==$dhcpClientStatus" validate="$presenceString IpAddress, (( allowZero: false, allowEmpty: true, isMasked: 'gateway' ))"}

							{input_row label="Primary DNS Server" id="primarydns" name="system[basicSettings][priDnsAddr]" type="ipfield" value=$data.monitor.primaryDNS|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1==$dhcpClientStatus" validate="IpAddress, (( allowZero: false ))"}

							{input_row label="Secondary DNS Server" id="secondarydns" name="system[basicSettings][sndDnsAddr]" type="ipfield" value=$data.monitor.secondaryDNS|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1==$dhcpClientStatus" validate="IpAddress, (( allowZero: false ))"}
{if $config.NETWORK_INTEGRALITY.status}
							{input_row label="Network Integrity Check" id="networkintegrality" name="system[basicSettings][networkIntegralityCheck]" type="checkbox" value=$data.basicSettings.networkIntegralityCheck selectCondition="!=0" onclick="integralityOnEnable();"}
{/if}
							<td id="cloudLink" class="font10Bold padding4Top" style="text-align: left;"><a href="http://wms.opswat.com/cwm" target="_blank">Click here to open Cloud Management</td>
							{if !$data.basicSettings.cloudConnectivityStatus}
								{assign var="cloudConnectivityUI" value=$data.basicSettings.cloudConnectivityUI}
								{input_row label="Show All Menu" id="enableUi" name="system[basicSettings][cloudConnectivityUI]" type="radio" options="1-Yes,0-No" selectCondition="==$cloudConnectivityUI"}
							{/if}
							{input_row label="Reboot" id="rebootAP" name ="rebootAP" type="radio" options="1-Yes,0-No" selectCondition="==0"}
							
							{input_row label="Firmware Version" id="fwVer" name="fwVer" type="text" value="$fwVersion" diabled="true"}
							{input_row label="Up Time" id="upTime" name="upTime" type="text" diabled="true" value="$upTime"}
							<tr>
								<td class="DatablockLabel">Up Time</td>
								<td class="DatablockContent">
									<input class="input" size="2" maxlength="2" id="upTimeDays" label="Up Time Days" value="" type="text", disabled="disabled"> <small>days</small>
									<input class="input" size="2" maxlength="2" id="upTimeHours" label="Up Time Hours" value="" type="text", disabled="disabled"> <small>hours</small>
									<input class="input" size="2" maxlength="2" id="upTimeMinutes" label="Up Time Minutes" value="" type="text", disabled="disabled"> <small>minutes</small>
									<input type="hidden" name="upTime" id="upTime" value="<?php echo $upTime ?>">
									<script type="text/javascript">
									<!--
										$('upTimeDays').value =  convertLeaseTime($('upTime').value,'days');
										$('upTimeHours').value = convertLeaseTime($('upTime').value,'hours');
										$('upTimeMinutes').value = convertLeaseTime($('upTime').value,'minutes');
									-->
									</script>
								</td>
							</tr> 
{/if}
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
