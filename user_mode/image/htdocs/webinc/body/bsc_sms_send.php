<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("Message Service");?></h1>
		<p>
			<?echo i18n("Message Service provides the useful tools for message management.");?>
		</p>
		<p>
                        <input type="button" id="topsend" value="<?echo i18n("Send message");?>" onclick="BODY.OnSubmit();" />
                        <input type="button" id="topcancel" value="<?echo i18n("Cancel");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();" />
                </p>
</div>
<div class="blackbox" id="message_send">
	<h2><?echo i18n("Create Message");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("To");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input id="receiver" type="text" size="50" maxlength="15" value="<? echo $_GET["receiver"]; ?>"/>
		</span>
	</div>
	<div class="textinput">
                <span class="name">&nbsp;</span>
                <span class="delimiter">&nbsp;</span>
                <span class="value"><?echo i18n("Add '+' for international format of the phone number.");?></span>
        </div>
	<div style="clear:both">
                <span style="width:35%;float:left;text-align:right;font-weight:bold;margin-top:4px"><?echo i18n("Text Message");?></span>
                <span style="width:3%;float: left;text-align: center;font-weight: bold;margin-top: 4px">:</span>
                <span style="width: 61%;text-align: left">
                	<textarea id="sms_content" cols="50" rows="10" onpropertychange="onmyinput(this)" oninput="return onmyinput(this)"  onchange="(function(){ OBJ('tmp_change').value+='c';})();"></textarea>
			<input id="tmp_change" type="hidden"/>
		</span>
        </div>
	<div class="textinput">
		<span class="name">&nbsp;</span>
                <span class="delimiter">&nbsp;</span>
                <span class="value">
                        <?echo i18n("Current Quantity");?>:<input id="current_quantity" type="text" size="3" maxlength="3" readonly="true"/>
			<?echo i18n("Maximum");?>:<input id="maximum" type="text" size="3" maxlength="3" readonly="true"/>
                </span>
	</div>
	<div class="emptyline"></div>
	<div class="gap"></div>
</div>
<div class="centerline">
	<p>
	<input type="button" id="bottomsend" value="<?echo i18n("Send message");?>" onclick="BODY.OnSubmit();" />
	<input type="button" id="bottomcancel" value="<?echo i18n("Cancel");?>" onclick="(function(){self.location='<?=$TEMP_MYNAME?>.php?r='+COMM_RandomStr(5);})();" />
	</p>
</div>
</form>
