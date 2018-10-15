	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Syslog Settings','syslogSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="syslogStatus" value=$data.logSettings.syslogStatus}
							{input_row label="Enable Syslog" id="enableSyslog" name=$parentStr.logSettings.syslogStatus type="checkbox" value="1" selectCondition="==$syslogStatus" onclick="toggleSyslog(this);"}
							
							{if $config.WNDAP660.status || $config.WNDAP620.status || $config.JWAP603.status}
							{input_row label="Syslog Server IP Address" id="syslogSrvIp" name=$parentStr.logSettings.syslogSrvIp type="ipfield" value=$data.logSettings.syslogSrvIp|replace:'0.0.0.0':''|replace:'-':':' masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" validate="IpAddress, (( allowZero: false, allowIpV6: true ))"}
							{else}
							{input_row label="Syslog Server IP Address" id="syslogSrvIp" name=$parentStr.logSettings.syslogSrvIp type="ipfield" value=$data.logSettings.syslogSrvIp|replace:'0.0.0.0':'' size="16" maxlength="15" masked="true" onchange="this.setAttribute('masked',(this.value != '')?false:true)" validate="IpAddress, (( allowZero: false ))"}	
							{/if}						

							{input_row label="Port Number" id="apName" name=$parentStr.logSettings.syslogSrvPort type="text" value=$data.logSettings.syslogSrvPort size="5" maxlength="5"  validate="Numericality, (( minimum:1, maximum: 65535, onlyInteger: true ))^Presence"}
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
		toggleSyslog($('cb_enableSyslog'));
		{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
			Form.disable(document.dataForm);
		{/if}
	-->
	</script>