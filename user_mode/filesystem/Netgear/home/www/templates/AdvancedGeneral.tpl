{if $config.STP.status}
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Spanning Tree Protocol','spanningTreeProtocol')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="spanTreeStatus" value=$data.basicSettings.spanTreeStatus}
							{input_row label="Spanning Tree Protocol" id="chkSTP" name=$parentStr.basicSettings.spanTreeStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$spanTreeStatus"}
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
{/if}
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('802.1Q VLAN','802_1QVLAN')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							<tr>
								{assign var="untaggedVlanStatus" value=$data.basicSettings.untaggedVlanStatus}
								<td class="DatablockLabel">{ip_field id="untaggedvlan" name=$parentStr.basicSettings.untaggedVlanStatus type="checkbox" value=$untaggedVlanStatus selectCondition="=$untaggedVlanStatus" onclick="fetchObjectById('untaggedvlanid').disabled!=this.checked;"}&nbsp;Untagged VLAN</td>
								<td class="DatablockContent"><input class="input" id="untaggedvlanid" name="{$parentStr.basicSettings.untaggedVlanID}" value="{$data.basicSettings.untaggedVlanID}" size="6" maxlength="4" type="text" {if $data.basicSettings.untaggedVlanStatus neq 1}disabled="disabled"{/if} label="Untagged VLAN" onkeydown="setActiveContent();" validate="Presence^Numericality,{literal}{ minimum:1, maximum: 4094, onlyInteger: true }{/literal}"></td>
							</tr>
							{input_row label="Management VLAN" id="mgmtvlan" name=$parentStr.basicSettings.managementVlanID type="text" class="input" value=$data.basicSettings.managementVlanID size="6" maxlength="4" validate="Numericality, (( minimum:1, maximum: 4094, onlyInteger: true ))^Presence"}
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
	
	<script language="javascript">
	<!--
			{if $config.CLIENT.status}
				{if ($config.TWOGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5) OR ($config.FIVEGHZ.status AND $data.wlanSettings.wlanSettingTable.wlan1.apMode eq 5)}
					Form.disable(document.dataForm);
				{/if}
			{/if}
			
	-->
	</script>
	