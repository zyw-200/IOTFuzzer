<div class="orangebox">
	<h1><?echo i18n("Message Service");?></h1>
		<p>
			<?echo i18n("Message Service provides the useful tools for message management.");?>
		</p>
</div>
<div id="sms_inbox" class="blackbox">
	<h2><?echo i18n("SMS Inbox");?></h2>
	<p>
		<?echo i18n("If you would like to view SMS message,click on the button below.");?>
	</p>
	
	<div class="centerline">
			<input id="sms_inbox" type="button" value="<?echo i18n("SMS Inbox");?>" onclick="self.location.href='bsc_sms_inbox.php';" />
        </div>
	<div class="emptyline"></div>	
</div>
<div class="blackbox" id="create_message">
	<h2><?echo i18n("Create Message");?></h2>
        <p>
                <?echo i18n("If you would like to create and send SMS message,then click on the button below.");?>
        </p>

        <div class="centerline">
                        <input id="create_message" type="button" value="<?echo i18n("Create Message");?>" onclick="self.location.href='bsc_sms_send.php';" />
        </div>
	<div class="emptyline"></div>
</div>
<?
	$total = 0;
	$used = 0;
	$free = 0;
	$total = query("/runtime/callmgr/voice_service/mobile/sms/total");
	$used = query("/runtime/callmgr/voice_service/mobile/sms/used");
	
	if($total == "")
	{
		$total = 0;
	}

	if($used == "")
	{
		$used = 0;
	}

	$free = $total - $used;
	if($used >= $total)
	{
		$free = 0;
	}	
?>
<div class="blackbox" id="message_info">
        <h2><?echo i18n("SMS Information");?></h2>
	<div class="textinput">
                <span class="name"><?echo i18n("Inbox total");?></span>
                <span class="delimiter">:</span>
                <span class="value">
			<? echo $total; ?>
                </span>
        </div>
	<div class="textinput">
                <span class="name"><?echo i18n("Inbox used");?></span>
                <span class="delimiter">:</span>
                <span class="value">
                        <? echo $used; ?>
                </span>
        </div>
	<div class="textinput">
                <span class="name"><?echo i18n("Inbox free");?></span>
                <span class="delimiter">:</span>
                <span class="value">
                        <? echo $free; ?>
                </span>
        </div>

        <div class="emptyline"></div>
	<div class="gap"></div>
</div>
