<? include "/htdocs/webinc/body/draw_elements.php"; ?>
		<tr>
			<td rowspan="2" class="centered">
				<!-- the uid of PFWD -->
				<input type="hidden" id="uid_<?=$INDEX?>" value="">
				<input id="en_<?=$INDEX?>" type="checkbox" />
			</td>
			<td><?echo i18n("Name");?><br /><input id="dsc_<?=$INDEX?>" type="text" size="20" maxlength="15" /></td>
			<td class="bottom">
				<input type="button" value="<<" class="arrow" onClick="OnClickAppArrow('<?=$INDEX?>');" />
				<span id="span_app_<?=$INDEX?>"></span>
			</td>
			<td>
				<?echo i18n("TCP");?><br />
				<input id="port_tcp_<?=$INDEX?>" type="text" size="18" />
			</td>
			<?
			if ($FEATURE_NOSCH != "1")
			{
				echo '<td class="centered">\n'.i18n("Schedule").'<br />\n';
				DRAW_select_sch("sch_".$INDEX, i18n("Always"), "-1", "", "0", "narrow");
				echo '</td>\n';
			}
			?>
		</tr>
		<tr>
			<td>
				<?echo i18n("IP Address");?><br />
				<input id="ip_<?=$INDEX?>" type="text" size="20" maxlength="15" />
			</td>
			<td class="bottom">
				<input type="button" value="<<" class="arrow" onClick="OnClickPCArrow('<?=$INDEX?>');" />
				<? DRAW_select_dhcpclist("LAN-1","pc_".$INDEX, i18n("Computer Name"), "",  "", "1", "broad"); ?>
			</td>
			<td>
				<?echo i18n("UDP");?><br />
				<input id="port_udp_<?=$INDEX?>" type="text" size="18" /> 
			</td>
		</tr>

