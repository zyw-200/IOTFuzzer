<html>
<head>
<script type="text/javascript" src="include/scripts/prototype.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/prototype-ext.js?code={$random}"></script>
<link rel="stylesheet" href="include/css/default.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/style.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/fonts.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/layout.css?code={$random}" type="text/css">
</head>
<body onload="{if $sessionEnabled eq true}loadThirdMenu(){/if}">
<table class="tableStyle" height="100%">
	<tr>
		<td class="leftEdge" width="11px" height="100%"><img src="images/clear.gif" width="11"><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /><br /></td>
		<td valign="top">	
			<table class="tableStyle" height="100%" width="100%">
				<tr>
					<td class="leftInside" style="width: 8px;"><img src="images/clear.gif" width="8"/></td>
					<td class="padding2Top topAlign">
						<table border="0" cellspacing="0" cellpadding="0" class="1" id="Tree" width="144">
			            	<tr>
								<td class="thardNavContainerTopLeftImg"><img src="images/clear.gif" width="5" height="5"/></td>
								<td class="thardNavContainerTopMiddleImg spacer100Percent"><img src="images/clear.gif" width="5"/></td>
								<td class="thardNavContainerTopRightImg"><img src="images/clear.gif" width="5"/></td>
				            </tr>
							<tr>
								<td class="thardNavContainerSecondTopLeftImg"><img src="images/clear.gif" width="5"/></td>
								<td class="leftNavBg padding1Top topAlign" id="TreeFrame">
                                    {if $sessionEnabled eq true}
						           {* <table cellpadding=0 cellspacing=0 border=0 align="left">
					            	{foreach item=menuArr key=menuitem from=$thirdMenu}
										{php}
										if ((is_array($this->_tpl_vars['menuArr']) && count($this->_tpl_vars['menuArr']) > 1)) {
											$this->_tpl_vars['menuArray'] = array_keys($this->_tpl_vars['menuArr']);
											//var_dump(array_search($this->_tpl_vars[navigation][4], $this->_tpl_vars['menuArray']));
											if (array_search($this->_tpl_vars[navigation][4], $this->_tpl_vars['menuArray']) !== false && $this->_tpl_vars['navigation'][3] == $this->_tpl_vars['menuitem'] ) {
												$this->_tpl_vars['displayBlock'] = 'inline';
											}
											else
												$this->_tpl_vars['displayBlock'] = 'none';
										{/php}
										<tr>
											<td align='left' valign='top' style="width: 10%; padding: 3px 2px; _padding: 0px;"><img src="images/arrow_{if $displayBlock eq 'none'}right{else}down{/if}.gif" id="img_{$menuitem}" style="border: 0px; margin: 0px; float: both; vertical-align: middle;"></td>
											<td align='left' valign='top' style="width: 90%; padding: 3px 2px; _padding: 0px;"><A id="third_{$menuitem}" onclick="toggleMenu('{$menuitem}');" href='javascript:void(0)' class='TertiaryNav' style="{if $menuitem eq $navigation.3}color:#FFA400;{/if}"><strong>{$menuitem}</strong></A></td>
										</tr>
										<tr>
											<td align='left' valign='top' style="width: 10%;"></td>
											<td align='left' valign='top' style="padding: 0px;">
												<ul id="div_{$menuitem}" name="div_{$menuitem}" style="display:{$displayBlock}; width: 100%; margin: 0px; padding: 0px;">
												{foreach name=fourthLoop item=fourthMenu from=$menuArray}
													<li id="div_{$menuitem}_{$smarty.foreach.fourthLoop.iteration}" onclick="prepareURL('{$fourthMenu}','{$menuitem}','{$navigation.2}','{$navigation.1}');" class="FourthLevelNav fourthLevelItem" style="list-style-type: none; {if $fourthMenu eq $navigation.4 AND $menuitem eq $navigation.3}color:#FFA400;{/if}"><a href="#" style="text-decoration: none; {if $fourthMenu eq $navigation.4 AND $menuitem eq $navigation.3}color:#FFA400;{else}color: #46008F;{/if}">&raquo;&nbsp;{$fourthMenu}</a></li>
												{/foreach}
												</ul>
											</td>
										</tr>
										{php}
										} else {
										{/php}
										<tr id='blueLinkBold11'>
											<td align='left' valign='top' style="width: 10%; padding: 3px 2px; _padding: 0px;"><img src="images/arrow_right.gif" style="border: 0px; margin: 0px; float: both; vertical-align: middle;"></td>
											<td align='left' valign='top' style="width: 90%; padding: 3px 2px; _padding: 0px;"><A href="javascript:prepareURL('','{$menuitem}','{$navigation.2}','{$navigation.1}');" class="TertiaryNav" style="text-decoration: none;{if $menuitem eq $navigation.3}color:#ffa400;{/if}"><strong>{$menuitem}</strong></A></td>
										</tr>
										{php}
										}
										{/php}
									{/foreach}
									
									</table>*}
                                    {/if}
									</td>
								<td class="thardNavContainerSecondTopRightImg"><img src="images/clear.gif" width="5"/></td>
							</tr>
							<tr>
								<td class="thardNavContainerBottomLeftImg"><img src="images/clear.gif" width="5" height="5"/></td>
								<td class="thardNavContainerBottomMiddleImg"><img src="images/clear.gif" height="5"/></td>
								<td class="thardNavContainerBottomRightImg"><img src="images/clear.gif" width="5"/></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
</table>
</body>
</html>