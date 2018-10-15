	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Hotspot Settings','hotspotSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{assign var="httpRedirectStatus" value=$data.httpRedirectSettings.httpRedirectStatus}
{if $config.WNDAP330.status}
                            {if $data.dhcpsSettings.dhcpServerStatus eq '0'}
								{assign var="onclickStr"  value="displayDHCPSError(this);"}
							{else}
								{assign var="onclickStr"  value="$('httpRedirectURL').disabled=(this.value==1?false:true);"}
							{/if}
{else}
                            {assign var="onclickStr"  value="$('httpRedirectURL').disabled=(this.value==1?false:true);"}
{/if}
							{input_row label="HTTP Redirect" id="httpRedirectStatus" name=$parentStr.httpRedirectSettings.httpRedirectStatus type="radio" options="1-Enable,0-Disable" onclick="$onclickStr" selectCondition="==$httpRedirectStatus"}

							{input_row label="Redirect URL" id="httpRedirectURL" name=$parentStr.httpRedirectSettings.httpRedirectURL type="text" value=$data.httpRedirectSettings.httpRedirectURL|replace:'\\':'' size="25" maxlength="120" disableCondition="1!=$httpRedirectStatus"  validate="IpAddress, (( allowURL: true, allowZero: false, allowGeneric: false ))^Presence"}
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
{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
	Form.disable(document.dataForm);
{/if}
-->
</script>