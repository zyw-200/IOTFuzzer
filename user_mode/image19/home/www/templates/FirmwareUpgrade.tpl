{php}
if ($_SERVER['SERVER_PORT']=='443') {
{/php}
	<script>
	<!--
		$('br_head').innerHTML = "Please switch to HTTP mode for upgrading firmware!";
		$('errorMessageBlock').show();
	-->
	</script>
{php}
} else {
{/php}

	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Firmware Upgrade','upgradeFirmware')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Select file" id="firmwareFile" class="input" name="firmwareFile" type="file" oncontextmenu="return false" onkeydown="this.blur()" onpaste="return false"}
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
{php}
}
{/php}
