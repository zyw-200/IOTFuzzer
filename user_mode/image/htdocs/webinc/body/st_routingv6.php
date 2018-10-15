<form id="mainform" onsubmit="return false;">
<div class="orangebox">
	<h1><?echo i18n("IPV6 ROUTING");?></h1>
	<!--<span class="name"><?echo i18n("IPv6 Routing Table");?></span>>-->
	<h3><?echo i18n("IPv6 Routing Table");?></h3>
	<p>
		<?echo i18n("This page display IPv6 routing details configured for your router.");?>
	</p>
	<div class="gap"></div>
</div>
<div class="blackbox" id="ipv6_routing">
	<h2><?echo i18n("IPV6 ROUTING TABLE");?></h2>
	<table id="routingv6_list" class="general">
                <tr>
                        <th><?echo i18n("Destination IP");?></th>
                        <th><?echo i18n("Gateway");?></th>
                        <th><?echo i18n("Metric");?></th>
                        <th><?echo i18n("Interface");?></th>
                </tr>
     </table>
</div>
</form>

