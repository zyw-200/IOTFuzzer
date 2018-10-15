<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Message Service");?></h1>
		<p>
			<?echo i18n("Message Service provides the useful tools for message management.");?>
		</p>
		<p>
                        <input type="button" value="<?echo i18n("Delete message");?>" onclick="BODY.OnSubmit();" />
			<input type="button" id="topreply" value="<?echo i18n("Reply message");?>" onclick="ReplyMessage()" />
                        <input type="button" id="toprefresh" value="<?echo i18n("Refresh");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();" />
                </p>
</div>
<div id="sms_inbox_head" class="blackbox">
	<h2><?echo i18n("Inbox");?></h2>
	<div id="sms_inbox" class="centerline" align="center">
        </div>
	<div class="emptyline"></div>
</div>
<div class="blackbox" id="message_inbox">
	<h2><?echo i18n("SMS");?></h2>
	<div class="centerline">
		<textarea id="sms_content" cols="100" rows="10" readonly="ture" style="overflow-y:auto"></textarea>
        	<input type="hidden" id="sms_number">
	</div>
	<div class="emptyline"></div>
</div>
<div class="centerline">
<p>
	<input type="button" id="bottomdelete" value="<?echo i18n("Delete message");?>" onclick="BODY.OnSubmit();" />
	<input type="button" id="bottomreply" value="<?echo i18n("Reply message");?>" onclick="ReplyMessage()" />
	<input type="button" id="bottomrefresh" value="<?echo i18n("Refresh");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();" />
</p>
</div>
</form>
