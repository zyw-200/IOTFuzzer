		<tr>
			<td class="centered"><input type="checkbox" id="<? echo 'en_'.$INDEX;?>" /></td>
			<td class="centered">
				<input type="text" id="<? echo 'desc_'.$INDEX;?>" size="15" maxlength="20" />
				<input type="hidden" id="<? echo 'name_'.$INDEX;?>" size="20" maxlength="20" />
			</td>
			<td class="centered"><input type="text" id="<? echo 'ip_'.$INDEX;?>" size="15" maxlength="15" /></td>
			<td class="centered"><input type="text" id="<? echo 'mac_'.$INDEX;?>" size="17" maxlength="17" /></td>
			<td class="centered">
				<input type="button" value="<<" class="arrow" onclick="PAGE.OnChangeGetClient(<?=$INDEX?>);" />
				<? DRAW_select_dhcpclist("LAN-1","pc_".$INDEX, i18n("Computer Name"), "",  "", "1", "broad"); ?>
			</td>
		</tr>
