<?
include "/htdocs/phplib/xnode.php";
include "/htdocs/phplib/phyinf.php";
?>
#-----------------------------------------------------------------------------
# tspc.conf
#-----------------------------------------------------------------------------
#
# This source code copyright (c) Hexago Inc. 2002-2004.
#
# This program is free software; you can redistribute it and/or modify it 
# under the terms of the GNU General Public License (GPL) Version 2, 
# June 1991 as published by the Free  Software Foundation.
#
# This program is distributed in the hope that it will be useful, 
# but WITHOUT ANY WARRANTY;  without even the implied warranty of 
# MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  
# See the GNU General Public License for more details.
#
# You should have received a copy of the GNU General Public License 
# along with this program; see the file GPL_LICENSE.txt. If not, write 
# to the Free Software Foundation, Inc., 59 Temple Place, Suite 330, Boston, 
# MA 02111-1307 USA
#------------------------------------------------------------------------------
tsp_dir=<?=$TSPC_DIR?>
#
# authentication method:
#  auth_method=any|digest-md5|anonymous|plain
#   any is the prefered one, since the most secure mechanism common to
#    the client and the broker will be used.
#   digest-md5 is sending the username in clear, but no password is sent.
#   plain is sending both username and password in clear.
#   anonymous sends no username and no password
#  recommended: any
auth_method=any

#
# IPv4 address of the client for its tunnel endpoint:
#  client_v4=auto|A.B.C.D (valid ipv4 address)
#  auto = tspc will find the primary ipv4 address
#   on the operating system
#
<?
$stsp = XNODE_getpathbytarget("/runtime", "inf", "uid", "WAN-1", 1);
echo "# ".$stsp."\n";
$ip = query($stsp."/inet/ipv4/ipaddr");
if ($ip != "") {echo "client_v4=".$ip."\n";}
else           {echo "client_v4=auto\n";}
?>
#
# user identification:
#  userid=anonymous|your_userid
#  anonymous means no userid. With anonymous, you don't need to register
#   to get an userid from the broker. However, prefixes (router mode) nor
#   permanent ipv6 address are available in anonymous mode.
#  your_userid means the userid you registered to the broker. The userid
#   must be using only legal dns label names (eg: [a-zA-Z0-9-] ) since
#   the userid is used inside your user hostname.
userid=<?=$USERID?>

#
# password:
# passwd=your_password
#  leave empty if userid=anonymous
#  your_password means the password you have been assigned with your
#   userid
passwd=<?=$PASSWD?>

#
# Name of the script:
# template=checktunnel|setup
#
# the value is the file name of the script in the tsp_dir/template directory
# The script will be executed after the TSP session is completed. The script
#  is configuring the tunnel interface and routes.
# checktunnel is only printing information and does not configure any tunnel
# setup will do the actual work
# you could customize your own script, name it and put the filename in 
#  the template variable.
# on unix, '.sh' is added to the name of the script. 
# on windows, '.bat' is added to the name of the script.
# 
template=<?=$HELPER?>

#
# 'server' is the tunnel broker identifier
#   Value is the tunnel broker IP address or FQDN and an optional port number
#   The default port number is 3653.
#  
# Examples:
# server=hostname # FQDN
# server=A.B.C.D  # IPv4 address
# server=hostname:port_number  
# server=A.B.C.D:port_number
#
# For users with accounts, 'server' should be set to the Freenet6 
# tunnel broker with authenticated accounts (broker.freenet6.net)
#server=broker.freenet6.net
#
# The default value is the Freenet6 tunnel broker for 
# anonymous accounts (anon.freenet6.net)
server=<?=$REMOTE?>

#
#
# retry_delay=time
# retry tells the client to retry connection after time (seconds) in case of 
# failure or tunnel keepalive timeout (0 = no retry)
retry_delay=30

#
# Tunnel encapsulation mode:
# tunnel_mode can take the following values
# "v6v4"  request an IPv6 in IPv4 tunnel
# "v6udpv4" request an IPv6 in UDP in IPv4 tunnel (for clients behind a NAT)
# "v6anyv4"   Let the broker choose the tunnel mode appropriate for my client
#  with v6anyv4, the broker will discover if the client is behind a NAT or not
#   and will offer to the TSP client the correct tunnel mode.
# recommended is: v6anyv4
tunnel_mode=v6anyv4

#
# Tunnel Interface name:
# Interface name to use to create the tunnel. This is OS dependent
# and the default is choosen based on the OS. 
# if_tunnel_v6v4 is the tunnel interface name for the v6v4 encapsulation mode
# if_tunnel_v6udpv4 is the tunnel interface name for the v6udpv4 encap mode
if_tunnel_v6v4=sit1
if_tunnel_v6udpv4=tun

#
# proxy_client indicates that this client acts as a TSP proxy for
# some remote client tunnel endpoint machine. Typically, this is set to "yes" if
# we are running this client on a machine that will NOT be configuring
# the tunnel endpoint (for example, using the cisco template).
# This should be used with a static IPv4 address in client_v4 variable.
# NOTE: proxy_client=yes is incompatible with tunnel_mode=v6udpv4
# The default is "no"
proxy_client=no

#
# Keepalive for v6udpv4 tunnels:
#  keepalive indicates that this client will send keepalives to keep the
#   tunnel active (v6udpv4 tunnel) and detect inactive tunnel (no response from
#   server). When a tunnel is determined to be inactive, the TSP client
#   automatically reconnects to the server.
# keepalive_interval is a suggestion from the TSP client to the broker
#  for the interval between two keepalive messages. The broker
#  may impose a different interval value to the client if the interval 
#  value is too low.
# keepalive is "yes" by default
# keepalive_interval is 30 seconds by default
keepalive=yes
keepalive_interval=30

#
# Logging facility uses syslog on Unix platforms
#syslog_facility=DAEMON
#syslog_level=INFO

#
#---------------------
# Router configuration
#
# In order to configure the machine as a router, a prefix must be requested
# and an interface must be specified.  The prefix will be advertised
# through that interface.
#
# host_type=host|router
#  default = host.
#host_type=router
<?
$layout = query("/runtime/device/layout");
if ($layout != "router") {echo "host_type=host\n";}
else                     {echo "host_type=router\n";}
?>
#
# prefixlen specifies the required prefix length for the TSP client 
#  network. Valid values are 64 or 48. 64 is for one link. 48 is for
#  a whole enterprise network (65K links).
prefixlen=<?=$PRELEN?>

#
# if_prefix is the name of the OS interface that will be configured
#  with the first /64 of the received prefix from the broker and the
#  router advertisement daemon is started to advertise that prefix
#  on the if_prefix interface.
if_prefix=<?=$HOMEIF?>

#
# For reverse DNS delegation of the prefix, define the following:
# Example: dns_server=mydnsserver.domain
#dns_server=

# end of tspc.conf
#-----------------------------------------------------------------------------
