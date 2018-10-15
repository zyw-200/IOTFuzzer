<? include "/htdocs/webinc/body/draw_elements.php"; ?>		
		<tr>
			<td rowspan="2" class="centered">
				<input id="en_<?=$INDEX?>" type="checkbox" />
			</td>
			<td rowspan="2" class="centered"><?echo i18n("Name");?><br /><input id="name_<?=$INDEX?>" type="text" size="15" maxlength="15" /></td>
			<td class="centered" rowspan="2">
				<?echo i18n("Application");?><br />
				<input type="button" value="<<" class="arrow" onclick="OnClickAppArrow('<?=$INDEX?>');" modified="ignore" />
				<span id="span_app_<?=$INDEX?>"></span>
			</td>
			
			<td><?echo i18n("Trigger");?><br /><input id="priport_<?=$INDEX?>" type="text" size="15" maxlength="11" /></td>
			<td class="centered"><?echo i18n("Protocol");?><br />
				<select id="pripro_<?=$INDEX?>">
				    <option value="TCP+UDP"><?echo i18n("All");?></option>
					<option value="TCP"><?echo i18n("TCP");?></option>
					<option value="UDP"><?echo i18n("UDP");?></option>
				</select>
			</td>
			<td class="centered" rowspan="2"><?echo i18n("Schedule");?><br />
				<? DRAW_select_sch("sch_".$INDEX, i18n("Always"), "-1", "", 0, "narrow"); ?>
			</td>
		</tr>
		<tr>
			<td><?echo i18n("Firewall ");?><br /><input id="pubport_<?=$INDEX?>" type="text" size="15" maxlength="60" /></td>
			<td class="centered"><?echo i18n("Protocol");?><br/>
				<select id="pubpro_<?=$INDEX?>">
				        <option value="TCP+UDP"><?echo i18n("All");?></option>
						<option value="TCP"><?echo i18n("TCP");?></option>
						<option value="UDP"><?echo i18n("UDP");?></option>
				</select>
			</td>
		</tr>
		

