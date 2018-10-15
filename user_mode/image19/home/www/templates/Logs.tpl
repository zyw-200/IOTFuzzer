	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3">
						<table class='tableStyle'>
							<tr>
								<td colspan='2' class='subSectionTabTopLeft spacer60Percent font12BoldBlue'>Logs</td>
								<td class='subSectionTabTopRight spacer40Percent'>
									<a href='javascript: void(0);' onclick="showHelp('Activity Log Window','activityLogWindow');"><img src='images/help_icon.gif' width='12' height='12' title='Click for help'/></a></td>
							</tr>
							<tr>
								<td colspan='3' class='subSectionTabTopShadow'></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td class="subSectionBodyDot">&nbsp;</td>
					<td class="spacer100Percent paddingsubSectionBody" style="padding: 0px;">
						<table class="tableStyle">
							<tr>
								<td class="DatablockContent" style="text-align: center; padding: 5px;">
									<textarea name="activewin" id="activewin" class="smallfix2" cols="65" rows="12" wrap="off" readonly="readonly">{$LogMessages}</textarea>
								</td>
							</tr>
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
            $this->_tpl_vars['LogMessages'] = trim($this->_tpl_vars['LogMessages']);
            {/php}
            <script type="text/javascript">
                <!--
    {if $LogMessages eq ''}
                    window.top.frames['action'].$('saveas').disabled=true;
                    window.top.frames['action'].$('saveas').src = 'images/save_as_off.gif';
                    window.top.frames['action'].$('clear').disabled=true;
                    window.top.frames['action'].$('clear').src = 'images/clear_off.gif';
	{else}
                    window.top.frames['action'].$('saveas').disabled=false;
                    window.top.frames['action'].$('saveas').src = 'images/save_as_on.gif';
                    window.top.frames['action'].$('clear').disabled=false;
                    window.top.frames['action'].$('clear').src = 'images/clear_on.gif';
    {/if}
{literal}
                                        function doClear()
                                        {
                                            if(window.top.frames['action'].$('clear').src.indexOf('clear_on')!== -1)
                                            {
						$('activewin').value='';
                                                if(config.ARIES.status)
                                                    new Ajax.Request('clearLog.php?product=aries');
                                                else
                                                    new Ajax.Request('clearLog.php?product=notaries');
						window.top.frames['action'].$('saveas').disabled=true;
						window.top.frames['action'].$('saveas').src = 'images/save_as_off.gif';
						window.top.frames['action'].$('clear').disabled=true;
						window.top.frames['action'].$('clear').src = 'images/clear_off.gif';
						return false;
                                            }
					}

					function doSave()
					{
						if(window.top.frames['action'].$('saveas').src.indexOf('save_as_on')!== -1)
						{
                                                if(config.ARIES.status)
							document.location.href='downloadFile.php?file=log&product=aries';
                                                else
							document.location.href='downloadFile.php?file=log&product=notaries';

							return false;
						}
						else
						{
							window.top.frames['action'].$('saveas').disabled=true;
						}
					}

	window.onload=function doScroll()
					{
					if($('activewin').disabled!== true)
					{
						$('activewin').focus();
						$('activewin').scrollTop = $('activewin').scrollHeight
					}
					};
{/literal}
{if ($data.wlanSettings.wlanSettingTable.wlan0.apMode eq 5)}
	Form.disable(document.dataForm);
	window.top.frames['action'].$('saveas').disabled=true;
	window.top.frames['action'].$('saveas').src = 'images/save_as_off.gif';
	window.top.frames['action'].$('clear').disabled=true;
	window.top.frames['action'].$('clear').src = 'images/clear_off.gif';
	window.top.frames['action'].$('refresh').disabled=true;
	window.top.frames['action'].$('refresh').src = 'images/refresh_off.gif';
{/if}
                -->
            </script>
