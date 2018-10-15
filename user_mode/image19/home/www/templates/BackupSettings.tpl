	<tr>
		<td>	
			<table class="tableStyle">
				<tr>
					<td colspan="3"><script>tbhdr('Backup Settings','backupSettings')</script></td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody">
						<table class="tableStyle">
							{input_row label="Backup a copy of the current settings to a file" id="backupSettings" name="backupSettings" type="image" src="images/backup_on.gif" value="Backup" onclick="$('backupSettingsForm').submit();return false;" style="text-align:center; border: 0px;"}
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
</table>
</form>
<form name="backupSettingsForm" id="backupSettingsForm" action="downloadFile.php?file=config" method="post">
	<input type="hidden" id="dummy" name="dummy" value="">
