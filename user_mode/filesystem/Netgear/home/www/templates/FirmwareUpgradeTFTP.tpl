{php}
if($_REQUEST['tftpfail']=='1') {
{/php}
	<script>
	<!--
		$('br_head').innerHTML = "TFTP failed to get the file!";
		$('errorMessageBlock').show();
	-->
	</script>
{php}
}
{/php}

	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Firmware Upgrade TFTP','upgradeFirmwareTFTP')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Firmware File Name" id="firwareFileName" name="firwareFileName" type="text" value="" size="20" maxlength="64" validate="Presence^Ascii"}
                                                        {input_row label="TFTP Server IP" id="tftpServerIP" name="tftpServerIP" type="text" value="" size="20" maxlength="32" validate="Presence^Ascii"}
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
