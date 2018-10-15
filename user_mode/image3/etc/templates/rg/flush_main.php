# flush_main.php >>>
<? /* vi: set sw=4 ts=4: */ ?>
iptables -t mangle -F PREROUTING
iptables -t nat -F PREROUTING
iptables -t nat -F POSTROUTING
iptables -F FORWARD
iptables -F INPUT
<?
anchor("/runtime/func");

/* Update main chains */

/* mangle PREROUTING */
if (query("vrtsrv") > 0 && $wanip!="")
{ echo "iptables -t mangle -A PREROUTING -i ".$lanif." -d ".$wanip." -j PRE_MARK\n"; }

/* PREROUTINE */
if (query("macfilter") > 0)
{ echo "iptables -t nat -A PREROUTING -i ".$lanif." -j PRE_MACFILTER\n"; }

if (query("portt") > 0 && $wanip!="")
{ echo "iptables -t nat -A PREROUTING -d ".$wanip." -j PRE_PORTT\n"; }

$dos = query("/security/dos/enable");
if ($dos==1 && $wanif!="")
{
	echo "iptables -t nat -A PREROUTING -i ".$wanif." -j PRE_DOS\n";
	echo "iptables -t nat -A PREROUTING -i ".$wanif." -j PRE_SPI\n";
}

if (query("vrtsrv") > 0)
{ echo "iptables -t nat -A PREROUTING -j PRE_VRTSRV\n"; }

if (query("/upnp/enable") == 1)
{ echo "iptables -t nat -A PREROUTING -j PRE_UPNP\n"; }

echo "iptables -t nat -A PREROUTING -j PRE_MISC\n"; 

if (query("dmz") > 0 && $wanip!="")
{ echo "iptables -t nat -A PREROUTING -d ".$wanip." -j PRE_DMZ\n"; }

echo "iptables -t nat -A PREROUTING -i ! ".$lanif." -j PRE_DEFAULT\n"; 

/* FORWARD */
if ($wanmode == 3)
{ echo "iptables -A FORWARD -p tcp --tcp-flags SYN SYN -j TCPMSS --set-mss 1400\n"; }

if (query("portt") > 0 && $wanif!="")
{	echo "iptables -A FORWARD -o ".$wanif." -j FOR_PORTT\n"; }

if (query("firewall") > 0) 
{ echo "iptables -A FORWARD -j FOR_FIREWALL\n"; }

if (query("ipfilter") > 0)
{ echo "iptables -A FORWARD -j FOR_IPFILTER\n"; }

if (query("vrtsrv") > 0 || query("dmz") > 0 || query("upnp") > 0)
{ echo "iptables -A FORWARD -j FOR_DNAT\n"; }

if (query("urlfilter") > 0)
{ echo "iptables -A FORWARD -i ".$lanif." -p tcp --dport 80 -j FOR_BLOCKING\n"; }

$dben = query("/security/domainblocking/enable");
if($dben != 0)
{ echo "iptables -A FORWARD -i ".$lanif." -p udp --dport 53 -j FOR_BLOCKING\n";
  echo "iptables -A INPUT -i "  .$lanif." -p udp --dport 53 -j INP_BLOCKING\n"; }

$pptp = query("/nat/passthrough/pptp");
$ipsec = query("/nat/passthrough/ipsec");
if ($ipsec != 1 || $pptp != 1)
{ echo "iptables -A FORWARD -j FOR_VPN\n"; }

/* POSTROUTING */
if (query("vrtsrv") > 0)
{ echo "iptables -t nat -A POSTROUTING -m mark --mark 1 -j PST_VRTSRV\n"; }

/* POSTROUTING MASQUERADE */
if ($wanif!="")
{
  /*echo "iptables -t nat -A POSTROUTING -o ".$wanif." -j MASQUERADE\n";*/
  echo "iptables -t nat -A POSTROUTING -o ".$wanif." -j STUN --type 2\n";
}
?>
# flush_main.php <<<
