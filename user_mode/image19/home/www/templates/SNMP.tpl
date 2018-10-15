	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('SNMP Settings','snmpSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">	
								
							{assign var="snmpStatus" value=$data.remoteSettings.snmpStatus}
							{input_row label="SNMP" id="snmpStatus" name=$parentStr.remoteSettings.snmpStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$snmpStatus" onclick="checkTR069Status(this, true)"}

							{input_row label="Read-Only Community Name" id="readOnlyCommunity" name=$parentStr.remoteSettings.readOnlyCommunity type="text" value=$data.remoteSettings.readOnlyCommunity disableCondition="0==$snmpStatus" size="32" maxlength="31" validate="Presence^AlphaNumericWithHU"} 
							
							{input_row label="Read-Write Community Name" id="readWriteCommunity" name=$parentStr.remoteSettings.readWriteCommunity type="text" value=$data.remoteSettings.readWriteCommunity disableCondition="0==$snmpStatus" size="32" maxlength="31" validate="Presence^AlphaNumericWithHU"} 
							
							{input_row label="Trap Community Name" id="trapServerCommunity" name=$parentStr.remoteSettings.trapServerCommunity type="text" value=$data.remoteSettings.trapServerCommunity disableCondition="0==$snmpStatus" size="32" maxlength="31" validate="Presence^AlphaNumericWithHU"} 
							
							{if $config.WNDAP660.status || $config.WNDAP620.status || $config.JWAP603.status}
							{input_row label="IP Address to Receive Traps" id="trapServerIP" name=$parentStr.remoteSettings.trapServerIP type="ipfield" value=$data.remoteSettings.trapServerIP|replace:'0.0.0.0':''|replace:'-':':' disableCondition="0==$snmpStatus"  masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" validate="IpAddress, (( allowZero: false, allowIpV6: true ))"} 
							{else}
							{input_row label="IP Address to Receive Traps" id="trapServerIP" name=$parentStr.remoteSettings.trapServerIP type="ipfield" value=$data.remoteSettings.trapServerIP|replace:'0.0.0.0':'' disableCondition="0==$snmpStatus"  masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" validate="IpAddress, (( allowZero: false ))"} 
							{/if}
							
{if $config.TRAP_PORT_CONFIG.status}
							{input_row label="Trap Port" id="trapPort" name=$parentStr.remoteSettings.trapPort type="text" value=$data.remoteSettings.trapPort disableCondition="0==$snmpStatus" size="5" maxlength="5" validate="Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence"}
{/if}
{if $config.MANAGERIP_CONFIG.status}
							{input_row label="SNMP Manager IP" id="managerIP" name=$parentStr.remoteSettings.managerIP type="ipfield" value=$data.remoteSettings.managerIP|replace:'0.0.0.0':'' disableCondition="0==$snmpStatus"  masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" validate="IpAddress, (( allowZero: false , allowBcastAll: true ))"}
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
	<script language="javascript">
	<!--
	{if $config.TR69.status}
					{if $data.tr069CpeConfiguration.tr069Status eq '1'}
									     var tr069OnStatus=true;
				{/if}
			
	{/if}		
	-->
	</script>