{if $config.DHCP_SNOOPING.status}
		<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('DHCP Snooping','dhcpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="dhcpSnooping" value=$data.basicSettings.dhcpSnooping}
							{input_row label="DHCP Snooping" id="chkDHCP" name=$parentStr.basicSettings.dhcpSnooping type="radio" options="1-Enable,0-Disable" selectCondition="==$dhcpSnooping"}							
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
	{if $config.IGMP_SNOOPING.status}
	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('IGMP Snooping','igmpSnooping')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="igmpSnooping" value=$data.basicSettings.igmpSnooping}
							{input_row label="IGMP Snooping" id="chkIGMP" name=$parentStr.basicSettings.igmpSnooping type="radio" options="1-Enable,0-Disable" selectCondition="==$igmpSnooping"}
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