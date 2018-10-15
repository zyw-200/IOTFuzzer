	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Time Settings','timeSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Time Zone" id="timeZone" name=$parentStr.timeSettings.timeZone type="select" options=$timeZones selected=$data.timeSettings.timeZone style="width: 240px;"}
							<tr>
								<td class="DatablockLabel" >Current Time</td>
								 <td class="DatablockContent">{php} echo `/usr/local/bin/date.sh `;{/php}</td>	
							</tr>
{if $config.NTP.status}
							{assign var="ntpClientStatus" value=$data.timeSettings.ntpClientStatus}
							{input_row label="NTP Client" id="ntpServerRadio" name=$parentStr.timeSettings.ntpClientStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$ntpClientStatus" onclick="toggleNTPServer(this.value);"}

							{assign var="customNtpServer" value=$data.timeSettings.customNtpServer}
							{input_row label="Use Custom NTP Server" id="customntp" name=$parentStr.timeSettings.customNtpServer type="checkbox" value="$customNtpServer" selectCondition="==1" disableCondition="1!=$ntpClientStatus" onclick="checkCustomNTPServer(this.checked);"}

							{if $config.WNDAP660.status || $config.WNDAP620.status || $config.JWAP603.status}
							{assign var="HostnameIPAddress" value=$data.timeSettings.ntpAddr}
							{assign var="tmpv4" value=$data.timeSettings.ntpAddr}
							{assign var="tmpv6" value=$data.timeSettings.ntpAddr|replace:'-':':'}
							{php}
							if (preg_match("/--/i", $this->_tpl_vars['HostnameIPAddress']))
							$this->_tpl_vars['HostnameIPAddress']=$this->_tpl_vars['tmpv6'];
							else
							$this->_tpl_vars['HostnameIPAddress']=$this->_tpl_vars['tmpv4'];
							{/php}
							{input_row label="Hostname / IP Address" id="ntpservername" name=$parentStr.timeSettings.ntpAddr type="text" value=$HostnameIPAddress disableCondition="1!=$ntpClientStatus || 1!=$customNtpServer" size="16" maxlength="128" validate="Presence^IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false, allowIpV6: true ))"}
							{else}
							{input_row label="Hostname / IP Address" id="ntpservername" name=$parentStr.timeSettings.ntpAddr type="text" value=$data.timeSettings.ntpAddr disableCondition="1!=$ntpClientStatus || 1!=$customNtpServer" size="16" maxlength="128" validate="Presence^IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false ))"}							
							{/if}
							{input_row label="" name=$parentStr.timeSettings.ntpAddr type="hidden" value=$ntpservername.value id="hiddenntpservername" disabled="true"}
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
