<script language="javascript">
<!--
{literal}
	var spaceMask=/^\s{0,}$/g
{/literal}
-->
</script>
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('IP Settings','ipConfiguration')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
{if $config.DHCPCLIENT.status}
							{assign var="dhcpClientStatus" value=$data.basicSettings.dhcpClientStatus}
							{input_row label="DHCP Client" id="enabledhcp" name="system[basicSettings][dhcpClientStatus]" type="radio" options="1-Enable,0-Disable" value=$dhcpClientStatus selectCondition="==$dhcpClientStatus" onclick="graysomething(this,false);"}
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
