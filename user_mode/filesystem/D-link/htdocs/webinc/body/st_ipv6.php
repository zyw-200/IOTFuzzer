<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("IPv6 Network Information");?></h1>
	<p>
		<?echo i18n("All of your Internet and network connection details are displayed on this page. The firmware version is also displayed here.");?>
	</p>
</div>
<div class="blackbox" id="ll_ipv6" style="display:none">
    <h2><?echo i18n("IPv6 Connection Information");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("IPv6 Connection Type");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="ll_type"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("IPv6 Default Gateway");?></span>
        <span class="delimiter">:</span>
	<span class="value" id="ll_gateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("LAN IPv6 Link-Local Address");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="ll_lan_ll_address"></span>
		<span id="ll_lan_ll_pl"></span>
	</span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("DHCP-PD");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="ll_enable_pd"></span>
	</span>
    </div>
	<div class="gap"></div>
</div>
 <div class="blackbox" id="ipv6" style="display:none">
    <h2><?echo i18n("IPv6 Connection Information");?></h2>
    <div class="textinput">
        <span class="name"><?echo i18n("IPv6 Connection Type");?></span>
	    <span class="delimiter">:</span>
	    <span class="value" id="type"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Network Status");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="status"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("WAN IPv6 Address");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="wan_address"></span>
		<span id="wan_address_pl"></span>
	</span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("IPv6 Default Gateway");?></span>
        <span class="delimiter">:</span>
	<span class="value" id="gateway"></span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("Primary IPv6 DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns1"></span>
    </div>
    <div class="textinput" >
        <span class="name"><?echo i18n("Secondary IPv6 DNS Server");?></span>
        <span class="delimiter">:</span>
        <span class="value" id="br_dns2"></span>
    </div>    
    <div class="textinput">
        <span class="name"><?echo i18n("LAN IPv6 Link-Local Address");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="lan_ll_address"></span>
		<span id="lan_ll_pl"></span>
	</span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("DHCP-PD");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="enable_pd"></span>
	</span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("IPv6 Network assigned by DHCP-PD");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="pd_prefix"></span>
		<span id="pd_pl"></span>
	</span>
    </div>
    <div class="textinput">
        <span class="name"><?echo i18n("LAN IPv6 Address");?></span>
        <span class="delimiter">:</span>
	<span class="value">
        	<span id="lan_address"></span>
		<span id="lan_pl"></span>
	</span>
    </div>
	<div class="gap"></div>
</div>
<div class="blackbox" id="ipv6_client">
	<h2><?echo i18n("LAN IPv6 Computers");?></h2>
	<table id="client6_list" class="general">
                <tr>
                        <th><?echo i18n("IPv6 Address");?></th>
                        <th><?echo i18n("Name(if any)");?></th>
                </tr>
     </table>
</div>	
<div class="blackbox" id="ipv6_bridge" style="display:none">
    <h2><?echo i18n("IPv6 Connection Information");?></h2>
    <table height="100px" align="center">
    	<tr><td><?echo i18n("Now is an Access Point Mode.");?></td></tr>
    </table>
</div>
</form>

