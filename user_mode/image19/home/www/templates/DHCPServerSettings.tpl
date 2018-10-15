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
					<td colspan="3"><script>tbhdr('DHCPv4 Server Settings','dhcpServerConfiguration')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" id="DHCPv4">
						<table class="tableStyle">
							{assign var="dhcpServerStatus" value=$data.dhcpsSettings.dhcpServerStatus}
							{if $config.WNDAP330.status}
								{if $data.httpRedirectSettings.httpRedirectStatus eq '1'}
									{assign var="onclickStr"  value="displayHotspotError(this);"}
								{else}
									{assign var="onclickStr"  value="IPgraysomething(this,true,'DHCPv4');"}
								{/if}
							{else}
								{assign var="onclickStr"  value="IPgraysomething(this,true,'DHCPv4');"}
							{/if}
							{input_row label="DHCPv4 Server" id="dhcpServerStatus" name="system[dhcpsSettings][dhcpServerStatus]" type="radio" options="1-Enable,0-Disable" onclick="$onclickStr" selectCondition="==$dhcpServerStatus"}
{if $config.MBSSID.status}
							{input_row label="DHCP Server VLAN ID" id="dhcpsVlanId" name="system[dhcpsSettings][dhcpsVlanId]" type="text"  value=$data.dhcpsSettings.dhcpsVlanId disableCondition="1!=$dhcpServerStatus" validate="Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"}
{/if}
							{input_row label="Starting IPv4 Address" id="dhcpsIpStart" name="system[dhcpsSettings][dhcpsIpStart]" type="ipfield"  value=$data.dhcpsSettings.dhcpsIpStart disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))^Presence"}

							{input_row label="Ending IPv4 Address" id="dhcpsIpEnd" name="system[dhcpsSettings][dhcpsIpEnd]" type="ipfield"  value=$data.dhcpsSettings.dhcpsIpEnd disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))^Presence"}

							{input_row label="Subnet Mask" id="dhcpsNetMask" name="system[dhcpsSettings][dhcpsNetMask]" type="ipfield"  value=$data.dhcpsSettings.dhcpsNetMask disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( onlyNetMask: true ))^Presence"}

							{input_row label="Gateway IPv4 Address" id="dhcpsGateway" name="system[dhcpsSettings][dhcpsGateway]" type="ipfield"  value=$data.dhcpsSettings.dhcpsGateway disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))^Presence"}

							{input_row label="Primary DNS Server" id="dhcpsPriDns" name="system[dhcpsSettings][dhcpsPriDns]" type="ipfield" value=$data.dhcpsSettings.dhcpsPriDns|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))"}

							{input_row label="Secondary DNS Server" id="dhcpsSndDns" name="system[dhcpsSettings][dhcpsSndDns]" type="ipfield"  value=$data.dhcpsSettings.dhcpsSndDns|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))"}

							{input_row label="Primary WINS Server" id="dhcpsPriWins" name="system[dhcpsSettings][dhcpsPriWins]" type="ipfield"  value=$data.dhcpsSettings.dhcpsPriWins|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))"}

							{input_row label="Secondary WINS Server" id="dhcpsSndWins" name="system[dhcpsSettings][dhcpsSndWins]" type="ipfield"  value=$data.dhcpsSettings.dhcpsSndWins|replace:'0.0.0.0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerStatus" validate="IpAddress, (( allowZero: false ))"}

							<tr>
								<td class="DatablockLabel">Lease</td>
								<td class="DatablockContent">
									<input class="input" size="2" maxlength="2" id="dhcpsLeaseDays" label="Lease Days" value="" type="text" onblur="convertLeaseTime2Seconds()" {if $dhcpServerStatus neq 1}disabled="disabled"{/if} validate="Numericality^Presence" onkeydown="setActiveContent();"> <small>days</small>
									<input class="input" size="2" maxlength="2" id="dhcpsLeaseHours" label="Lease Hours" value="" type="text" onblur="convertLeaseTime2Seconds()" {if $dhcpServerStatus neq 1}disabled="disabled"{/if} validate="Numericality, {literal}{ minimum:0, maximum: 23, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();"> <small>hours</small>
									<input class="input" size="2" maxlength="2" id="dhcpsLeaseMinutes" label="Lease Minutes" value="" type="text" onblur="convertLeaseTime2Seconds()" {if $dhcpServerStatus neq 1}disabled="disabled"{/if} validate="Numericality, {literal}{ minimum:0, maximum: 59, onlyInteger: true }^Presence"{/literal} onkeydown="setActiveContent();"> <small>minutes</small>
									<input type="hidden" name="{$parentStr.dhcpsSettings.dhcpsLeaseTime}" id="dhcpsLeaseTime" value="{$data.dhcpsSettings.dhcpsLeaseTime}">
									<script type="text/javascript">
									<!--
										$('dhcpsLeaseDays').value = convertLeaseTime($('dhcpsLeaseTime').value,'days');
										$('dhcpsLeaseHours').value = convertLeaseTime($('dhcpsLeaseTime').value,'hours');
										$('dhcpsLeaseMinutes').value = convertLeaseTime($('dhcpsLeaseTime').value,'minutes');
									-->
									</script>
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
	<tr><td>&nbsp;</td></tr>
	<!--@@@IPV6START@@@-->
	{if $config.IPV6.status}	
	{php}
		$DHCPv6ServerState = array ( '0' => 'State Less', '1' => 'State Full' );
		$this->_tpl_vars['DHCPv6ServerState']=$DHCPv6ServerState;
	{/php}
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCPv6 Server Settings','dHCPv6ServerSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" id="DHCPv6">
						<table class="tableStyle">
							{assign var="dhcpv6ServerStatus" value=$data.dhcpv6sSettings.dhcpServerStatus}
							{assign var="dhcpv6ServerState" value=$data.dhcpv6sSettings.dhcpServerState}
							{if $dhcpv6ServerStatus eq '1' && $dhcpv6ServerState eq '1'}
								{assign var="dhcpServerconfigdisable" value="1"}
							{else}
								{assign var="dhcpServerconfigdisable" value="0"}
							{/if}
							{if $data.httpRedirectSettings.httpRedirectStatus eq '1'}
								{assign var="onclickStr"  value="displayHotspotError(this);"}
							{else}
								{assign var="onclickStr"  value="IPgraysomething(this,true,'DHCPv6');"}
							{/if}
							{input_row label="DHCPv6 Server" id="ipv6dhcpServerStatus" name="system[dhcpv6sSettings][dhcpServerStatus]" type="radio" options="1-Enable,0-Disable" onclick="$onclickStr;disableDHCPv6state(this.value);" selectCondition="==$dhcpServerStatus"}
							{input_row label="State" id="dhcpServerState" name="system[dhcpv6sSettings][dhcpServerState]" type="select" options="$DHCPv6ServerState" selected="$data.dhcpv6sSettings.dhcpServerState" onclick="$onclickStr"}
{if $config.MBSSID.status}
							{input_row label="DHCP Server VLAN ID" id="ipv6dhcpsVlanId" name="system[dhcpv6sSettings][dhcpsVlanId]" type="text"  value=$data.dhcpv6sSettings.dhcpsVlanId disableCondition="1!=$dhcpServerconfigdisable" validate="Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"}
{/if}
							{input_row label="Starting IPv6 Address" id="ipv6dhcpsIpStart" name="system[dhcpv6sSettings][dhcpsIpStart]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsIpStart disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))^Presence"}

							{input_row label="Ending IPv6 Address" id="ipv6dhcpsIpEnd" name="system[dhcpv6sSettings][dhcpsIpEnd]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsIpEnd disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))^Presence"}

							{input_row label="Prefix Length" id="ipv6prefixlength" name="system[dhcpv6sSettings][dhcpsPrefixLen]" type="text" class="input" value=$data.dhcpv6sSettings.dhcpsPrefixLen size="6" maxlength="4" disableCondition="1!=$dhcpServerconfigdisable" validate="Numericality, (( is:64, onlyInteger: true ))^Presence"}

							{input_row label="Gateway IPv6 Address" id="ipv6dhcpsGateway" name="system[dhcpv6sSettings][dhcpsGateway]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsGateway disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))^Presence"}

							{input_row label="Primary DNS Server" id="ipv6dhcpsPriDns" name="system[dhcpv6sSettings][dhcpsPriDns]" type="ipv6field" value=$data.dhcpv6sSettings.dhcpsPriDns|replace:'--0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))"}

							{input_row label="Secondary DNS Server" id="ipv6dhcpsSndDns" name="system[dhcpv6sSettings][dhcpsSndDns]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsSndDns|replace:'--0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))"}

							{input_row label="Primary WINS Server" id="ipv6dhcpsPriWins" name="system[dhcpv6sSettings][dhcpsPriWins]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsPriWins|replace:'--0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))"}

							{input_row label="Secondary WINS Server" id="ipv6dhcpsSndWins" name="system[dhcpv6sSettings][dhcpsSndWins]" type="ipv6field"  value=$data.dhcpv6sSettings.dhcpsSndWins|replace:'--0':'' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" disableCondition="1!=$dhcpServerconfigdisable" validate="IpV6, (( allowZero: false ))"}

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
	{/if}
	<!--@@@IPV6END@@@-->
	<script language="javascript">
	<!--
	{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
		Form.disable(document.dataForm);
	{/if}
	
	{literal}
	function disableDHCPv6state(id){
		if(id == "0")
			$('dhcpServerState').disabled=true;
		else
			$('dhcpServerState').disabled=false;
	}
	{/literal}
	-->
	</script>
