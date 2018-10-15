<form id="mainform" onsubmit="PAGE.OnClick_Ping();return false;">
<div class="orangebox">
	<h1><?echo i18n("Ping Test");?></h1>
	<p>
		<?echo i18n('Ping Test sends "ping" packets to test a computer on the Internet.');?>
	</p>
</div>
<div class="blackbox">
	<h2><?echo i18n("Ping Test");?></h2>
	<div class="textinput">
		<span class="name"><?echo i18n("Host Name or IP Address");?></span>
		<span class="delimiter">:</span>
		<span class="value">
			<input type="text" id="dst" maxlength="63" size="20">
			<input type="button" id="ping" value="Ping" onclick="PAGE.OnClick_Ping();">
		</span>
	</div>
	<div class="gap"></div>
</div>
<div class="blackbox">
        <h2><?echo i18n("IPv6 Ping Test");?></h2>
        <div class="textinput">
                <span class="name"><?echo i18n("Host Name or IPv6 Address");?></span>
                <span class="delimiter">:</span>
                <span class="value">
                        <input type="text" id="dst_ipv6" maxlength="63" size="20">
                        <input type="button" id="ping_ipv6" value="Ping" onclick="PAGE.OnClick_Ping_ipv6();">
                </span>
        </div>
        <div class="gap"></div>
</div>
<div class="blackbox">
	<h2><?echo i18n("Ping Result");?></h2>
	<p id="report">
		<?echo i18n("Enter a host name or IP address above and click 'Ping'");?>
	</p>
	<div class="gap"></div>
</div>
</form>

