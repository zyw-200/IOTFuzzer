<strong><?echo i18n("Helpful Hints");?>...</strong>
<br/><br/>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Enable").":</strong><br/>";
	echo i18n("Specifies whether the entry will be enabled or disabled.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Interface").":</strong><br/>";
	echo i18n("Specifies the interface -- WAN or WAN Physical -- that the IP packet must use to transit out of the router, when this route is used.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Destination").":</strong><br/>";
	echo i18n("The IP address of packets that will take this route.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Subnet Mask").":</strong><br/>";
	echo i18n("One bit in the mask specifies which bits of the IP address must match.");
?></p>
<p>&nbsp;&#149;&nbsp;&nbsp;<?
	echo "<strong>".i18n("Gateway").":</strong><br/>";
	echo i18n("Specifies the next hop to be taken if this route is used. A gateway of 0.0.0.0 implies there is no next hop, and the IP address matched is directly connected to the router on the interface specified: WAN or WAN Physical.");
?></p>
