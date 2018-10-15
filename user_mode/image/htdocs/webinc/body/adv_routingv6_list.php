		<tr>
			<td width="20" rowspan="2"!!class="centered">
				<input id="enable_<?=$INDEX?>" type="checkbox" />
			</td>
			<td class="left" colspan="2" width="120px">
				<?echo i18n("Name");?><br>
				<input id="dsc_<?=$INDEX?>" type="text" size="26" maxlength="31" />
			</td>
			<td class="left" width="240px">
				<?echo i18n("Destination IPv6 / Prefix Length");?><br>
				<input id="dest1_<?=$INDEX?>" type="text" size="45" maxlength="64" /> /
				<input id="dest2_<?=$INDEX?>" type="text" size="6" maxlength="64" />
			</td>
		</tr>
		<tr>
			<td class="centered">
				<?echo i18n("Metric");?><br />
				<input id="metric_<?=$INDEX?>" type="text" size="9" maxlength="12" />
			</td>
			<td class="centered">
				<?echo i18n("Interface");?><br />
				<select id="inf_<?=$INDEX?>">
					<option value="NULL">NULL</option>
					<option value="LAN-4">LAN</option>
					<option value="WAN-4">WAN</option>
				</select>
			</td>
			<td>
			<?echo i18n("Gateway");?><br>
			<input id=gateway_<?=$INDEX?> type="text" size="45" maxlangth="64">
			</td>
		</tr>

