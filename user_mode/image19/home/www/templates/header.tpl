<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="include/scripts/prototype.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/prototype-ext.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/browser-ext.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/common.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/{if $sessionEnabled neq true}login_{/if}menu.js?code={$random}"></script>
<link rel="stylesheet" href="include/css/default.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/style.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/fonts.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/layout.css?code={$random}" type="text/css">
<script>
var _initiateMenu = true;
</script>
</head>
<body style="background: #FFFFFF; margin: 0px;">
<form name="navForm" action="header.php" method="post">
	<input type="hidden" name="menu1" id="menu1" value="{$navigation.1}">
	<input type="hidden" name="menu2" id="menu2" value="{$navigation.2}">
	<input type="hidden" name="menu3" id="menu3" value="{$navigation.3}">
	<input type="hidden" name="menu4" id="menu4" value="{$navigation.4}">
	<input type="hidden" name="mode7" id="mode7" value="">
	<input type="hidden" name="mode8" id="mode8" value="">
	<input type="hidden" name="mode9" id="mode9" value="">
	<input type="hidden" id="logout">
  <!-- start main Frame -->
<table class="tableStyle" style="margin: 0px;">
	<tr class="topAlign">
    	<td class="leftEdge"></td>
		<td valign="top">
			<table class="tableStyle">
				<tr>
					<td style="width: 8px;"><img src="images/clear.gif" width="8"/></td>
					<td>
						<table class="tableStyle" style="margin-top: 8px;">
							<tr>
								<td class="logoNetGear space50Percent topAlign"><img src="images/clear.gif" width="150" height="50"/></td>
								<td class="vertionImage spacer50Percent topAlign rightHAlign"><img src="images/clear.gif" width="205" height="50"/></td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td><img src="images/clear.gif" width="8"/></td>
					<td>
						<table class="tableStyle">
							<tr>
								<td class="bottomAlign">
									<table class="tableStyle">
										<tr>
											<td class="spacer80Percent bottomAlign" id="tabs">
												<ul class="tabs" id="primaryNav">
												{foreach name=mainTabs item=label from=$mainMenu}
													<li {if $navigation.1 eq $label}style="background: url(../../images/tab_r_A.gif) no-repeat right top;"{/if}><a href="javascript:prepareURL('','','','{$label}','');" target="master" {if $navigation.1 eq $label} style="background: url(../../images/tab_l_A.gif) no-repeat left top; color: #FFFFFF;"{/if}>{$label}</a></li>
												{/foreach}
												</ul>
											</td>
											<td class="loginActionCell spacer20Percent rightHAlign padding5LeftRight">{if $sessionEnabled}<a href="javascript:void(0);"><img src="images/logout_button.gif" style="border:0px; margin-right: 7px; margin-bottom: 4px; float:right;" onclick="processLogout();"></a>{/if}</td>
										</tr>
									</table>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr class="background-blue">
					<td><img src="images/clear.gif" width="8"/></td>
					<td class="padding7Top spacer100Percent">
						<div class="submenu" submenu="yes">
							<ul id="secondaryNav">
								{foreach from=$secondMenu item=secondMenuItem name=secondMenu}
								<li><a href="javascript:prepareURL('','','{$secondMenuItem}','{$navigation.1}','');" target="master" {if $navigation.2 eq $secondMenuItem} style="color: #ffa400;"{/if}>{$secondMenuItem}</a>{if !$smarty.foreach.secondMenu.last}&nbsp;<img src="images/tab_separator.gif" class="separatorImage">&nbsp;{/if}</li>
								{/foreach}
							</ul>
						</div>
					</td>
				</tr>
			</table>
		</td>
		<td class="rightEdge"></td>
	</tr>
	<tr class="topAlign">
    	<td valign="top" class="leftBodyNotch topAlign"></td>
	    <td>
	    	<table class="tableStyle">
				<tr class="topAlign">
					<td class="leftNextBodyNotch"><img src="images/clear.gif" width="11" height="16"/></td>
					<td class="middleBodyNotch spacer100Percent"></td>
			        <td class="rightNextBodyNotch"><img src="images/clear.gif" width="11"/></td>
				</tr>
	    	</table>
	    </td>
	    <td class="rightBodyNotch"></td>
	  </tr>	
</table>
</form>
</body>
</html>