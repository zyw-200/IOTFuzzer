		<tr>
			<td width="25" rowspan="3"¡¡class="centered">
				<input id="en_<?=$INDEX?>" type="checkbox" />
			</td>
			<td class="left" colspan="2" width="160px">
				<?echo i18n("Name");?><br>
				<input id="dsc_<?=$INDEX?>" type="text" size="26" maxlength="31" />
			</td>
			<td class="left" width="200px">
				<?echo i18n("Schedule");?><br>
			<?
			if ($FEATURE_NOSCH!="1")
			{
				DRAW_select_sch("sch_".$INDEX, i18n("Always"), "-1", "", 0, "narrow");
			}
			?>
			</td>
			<td></td>
		</tr>
		<tr>
			<td class="centered">
				<?echo i18n("Source");?>
			</td>
			<td>
				<?echo i18n("Interface");?><br />
				<select id="src_inf_<?=$INDEX?>">
					<option value="LAN-4">LAN</option>
					<option value="WAN-4">WAN</option>
				</select>
			</td>
			<td>
			<?echo i18n("IP Address Range");?><br>
			<input id=src_startip_<?=$INDEX?> type="text" maxlangth="35" size="35">-
			<input id=src_endip_<?=$INDEX?> type="text" maxlangth="35" size="35">
			</td>
			</td>
			<td class="left">
				<?echo i18n("Protocol");?><br>
				<select id="pro_<?=$INDEX?>" onchange="PAGE.OnChangeProt(<?=$INDEX?>)">
					<option value="ALL">ALL</option>
					<option value="TCP">TCP</option>
					<option value="UDP">UDP</option>
					<option value="ICMPv6">ICMP</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="centered">
				<?echo i18n("Dest");?>
			</td>
			<td>
				<?echo i18n("Interface");?><br />
				<select id="dst_inf_<?=$INDEX?>">
					<option value="LAN-4">LAN</option>
					<option value="WAN-4">WAN</option>
				</select>
			</td>
			<td>
			<?echo i18n("IP Address Range");?><br>
			<input id=dst_startip_<?=$INDEX?> type="text" maxlangth="35" size="35">-
			<input id=dst_endip_<?=$INDEX?> type="text" maxlangth="35" size="35">
			</td>
			<td class="left">
				<?echo i18n("Port Range");?><br>
				<input id="dst_startport_<?=$INDEX?>" type="text"  maxlength="5" size="6"/>~
				<input id="dst_endport_<?=$INDEX?>" type="text" maxlength="5" size="6" />
			</td>
		</tr>

