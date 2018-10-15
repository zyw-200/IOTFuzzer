		<tr>
			<td rowspan="2" class="centered">
				<input id="en_<?=$INDEX?>" type="checkbox" />
			</td>
			<td class="centered">
				<?echo i18n("Name");?><br>
				<input id="dsc_<?=$INDEX?>" type="text" size="8" maxlength="31" />
			</td>
			<td class="centered">
				<select id="src_inf_<?=$INDEX?>">
					<option value=""><?echo i18n("Source");?></option>
					<option value="LAN-1">LAN</option>
					<option value="WAN-1">WAN</option>
				</select>
			</td>
			<td class="centered">
				<input id="src_startip_<?=$INDEX?>" type="text" maxlength="15" size="16" /><br>
				<input id="src_endip_<?=$INDEX?>" type="text" maxlength="15" size="16" />
			</td>
			<td class="centered">
				<?echo i18n("Protocol");?><br>
				<select id="pro_<?=$INDEX?>" onchange="PAGE.OnChangeProt(<?=$INDEX?>)">
					<option value="ALL"><?echo i18n("All");?></option>
					<option value="TCP">TCP</option>
					<option value="UDP">UDP</option>
					<option value="ICMP">ICMP</option>
				</select>
			</td>
			<?
			if ($FEATURE_NOSCH!="1")
			{
				echo '<td rowspan="2" class="centered">\n';
				DRAW_select_sch("sch_".$INDEX, i18n("Always"), "-1", "", 0, "narrow");
				echo '<br>\n';
				echo '<input type="button" id=sch_'.$INDEX.'_btn value="'.i18n("New Schedule").'"'.
					 ' onclick="javascript:self.location.href=\'tools_sch.php\'">\n';
				echo '</td>\n';
			}
			?>
		</tr>
		<tr>
			<td class="centered">
				<?echo i18n("Action");?><br>
				<select id="action_<?=$INDEX?>">
					<option value="ACCEPT"><?echo i18n("Allow");?></option>
					<option value="DROP"><?echo i18n("Deny");?></option>
				</select>
			</td>
			<td class="centered">
				<select id="dst_inf_<?=$INDEX?>">
					<option value=""><?echo i18n("Dest");?></option>
					<option value="LAN-1">LAN</option>
					<option value="WAN-1">WAN</option>
				</select>
			</td>
			<td class="centered">
				<input id="dst_startip_<?=$INDEX?>" type="text" maxlength="15" size="16" /><br>
				<input id="dst_endip_<?=$INDEX?>" type="text" maxlength="15" size="16" />
			</td>
			<td class="centered">
				<?echo i18n("Port Range");?><br>
				<input id="dst_startport_<?=$INDEX?>" type="text"  maxlength="5" size="6"/><br>
				<input id="dst_endport_<?=$INDEX?>" type="text" maxlength="5" size="6" />
			</td>
		</tr>
