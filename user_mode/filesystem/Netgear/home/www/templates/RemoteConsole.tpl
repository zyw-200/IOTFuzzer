	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Remote Console','remoteConsole')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
{if $config.SSH.status}
							{assign var="sshStatus" value=$data.remoteSettings.sshStatus}
							{input_row label="Secure Shell (SSH)" id="sshStatus" name=$parentStr.remoteSettings.sshStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$sshStatus"}
{/if}
{if $config.TELNET.status}
							{assign var="telnetStatus" value=$data.remoteSettings.telnetStatus}
							{input_row label="Telnet" id="telnetStatus" name=$parentStr.remoteSettings.telnetStatus type="radio" options="1-Enable,0-Disable" selectCondition="==$telnetStatus"}
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