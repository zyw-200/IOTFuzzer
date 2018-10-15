<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<script type="text/javascript" src="include/scripts/prototype.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/browser-ext.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/common.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/wirelessnew.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/prototype-ext.js?code={$random}"></script>
<link rel="stylesheet" href="include/css/default.css?code={$random}" type="text/css">
<link rel="stylesheet" href="include/css/layout.css?code={$random}" type="text/css">
</head>
<body style="background: #FFA400">
<table class="tableStyle" height="100%"> 
	<tr>
	    <td class="leftEdge"></td>
		<td>
		<table class="tableStyle">
			<tr>
				<td class="footerBody topBottomDivider">
				<table class="tableStyle rightHAlign" align="right">
					<tr style="height: 23px">
						<td id="ButtonsDiv">
							<span id="extraButtons" style=""></span>
							<span id="standardButtons">{if $sessionEnabled eq true}<a href="javascript:parent.frames['master'].doSubmit(this,'cancel',_isCancellable);" style="padding: 0px; margin-right: 5px; height: 23px;"><img class="actionImg" id="cancelButton" src="images/cancel_off.gif"></a><a href="javascript:parent.frames['master'].doSubmit(this,'apply',_isChanged);" style="padding: 0px; height: 23px;"><img class="actionImg" id="applyButton" src="images/apply_off.gif"></a>{/if}</span>
						</td>
						<td style="width: 40px;height: 24px;overflow: hidden;"><img src="images/clear.gif" height="24" width="1" /></td>
					</tr>
				</table>
				</td>
			</tr>
		</table>
		</td>
		<td class="rightEdge"></td>
	</tr>
	<tr>
		<td class="leftEdgeFooter"><img src="images/clear.gif" width="11" height="9" /></td>
		<td>
		<table class="tableStyle">
			<tr>
				<td class="leftBottomDivider"><img src="images/clear.gif" width="11" height="9" /></td>
				<td class="middleBottomDivider spacer100Percent"><img src="images/clear.gif" height="9" /></td>
				<td class="rightBottomDivider spacer1Percent"><img src="images/clear.gif" height="9" /></td>
			</tr>
		</table>
		</td>
		<td class="rightEdgeFooter"><img src="images/clear.gif" width="11" height="9" /></td>
	</tr>
	<tr>
		<td class="leftCopyrightFooter"><img src="images/clear.gif" width="11" height="9"/></td>
		<td class="middleCopyrightDivider blue10 topAlign" style="padding-left: 0px; padding-bottom: 7px;">Copyright &copy; 1996-2007 Netgear &reg;</td>
		<td class="rightCopyrightFooter"><img src="images/clear.gif" width="11" height="9"/></td>
	</tr>
</table>
<script language="javascript">
<!--
	var cancelButton = $('cancelButton');
	var applyButton = $('applyButton');
	var _isChanged = false;
	var _isCancellable = false;
-->
</script>
</body>
</html>