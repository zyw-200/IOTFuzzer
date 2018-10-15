<?xml version="1.0"?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Netgear</title>
<script type="text/javascript" src="include/scripts/prototype.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/effects.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/common.js?code={$random}"></script>
<script type="text/javascript" src="include/scripts/control-modal.js?code={$random}"></script>
</head>
<body style="background-color: #FFFFFF; margin: 0px; padding: 0px;">
<form name="navForm" action="{$ipAddress}index.php" method="post" target="_top">
	<input type="hidden" id="logout">
</form>
<script language="javascript">
<!--
{literal}
if (typeof(ModalWindow) == 'function') {
var progressBar = new ModalWindow(false, {
			contents: function() { return "<img src='images/loading.gif'>"; },
			overlayCloseOnClick: true,
			overlayClassName: 'ProgressBar_overlay',
			containerClassName: 'ProgressBar_container',
			opacity: 100,
			iframe: true
		});
}
if (progressBar != undefined) {
	progressBar.open();
}
{/literal}
{if $restoringDefaults}
setTimeout("window.top.location.href = '{$ipAddress}';",{$redirectTime});
{else}
window.setTimeout(processLogout,{$redirectTime});
{/if}
{literal}

var _disableAll = true;
var pingID = null;
var oOptions = {
                method: "post",
                asynchronous: false,
                timeoutDelay: 5,
                onSuccess: function (oXHR, oJson) {
                    processLogout();
                },
                onFailure: function (oXHR, oJson) {
		      		window.clearTimeout(pingID);
                    pingID = window.setTimeout(pingAP,1);
                },
                onTimeout: function(request) {
                    window.clearTimeout(pingID);
					pingID = window.setTimeout(pingAP,1);
                },
                onException: function(request, exception) {
                	if (request.abort) request.abort();
                	if (pingID) window.clearTimeout(pingID);
					pingID = window.setTimeout(pingAP,5000);
                }
            };

function pingAP()
{
	window.clearTimeout(pingID);
	pingID = null;
    new Ajax.Request('{/literal}{$ipAddress}{literal}test.php?id={/literal}{$random}{literal}', oOptions);
}
pingID = window.setTimeout(pingAP,60000);
{/literal}
-->
</script>
</body>
</html>
