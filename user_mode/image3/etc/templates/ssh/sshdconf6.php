Port 22
Protocol 2,1
#AddressFamily any

#anchor("/wan/rg/inf:1/static");

#ListenAddress query("ip");
ListenAddress ::
# HostKey for protocol version 1
HostKey /etc/ssh/ssh_host_key
# HostKeys for protocol version 2
HostKey /etc/ssh/ssh_host_rsa_key
HostKey /etc/ssh/ssh_host_dsa_key

# Lifetime and size of ephemeral version 1 server key
KeyRegenerationInterval 1h
#ServerKeyBits 768

# Logging
# obsoletes QuietMode and FascistLogging
# SyslogFacility AUTH
#LogLevel DEBUG3

# Authentication:
LoginGraceTime 10
PermitRootLogin yes
StrictModes yes
#MaxAuthTries 6

RSAAuthentication yes
PubkeyAuthentication yes
#AuthorizedKeysFile	.ssh/authorized_keys

# For this to work you will also need host keys in /etc/ssh/ssh_known_hosts
#RhostsRSAAuthentication no
# similar for protocol version 2
#HostbasedAuthentication no
# Change to yes if you don't trust ~/.ssh/known_hosts for
# RhostsRSAAuthentication and HostbasedAuthentication
#IgnoreUserKnownHosts no
# Don't read the user's ~/.rhosts and ~/.shosts files
#IgnoreRhosts yes

# To disable tunneled clear text passwords, change to no here!
PasswordAuthentication yes
PermitEmptyPasswords yes

# Change to no to disable s/key passwords
#ChallengeResponseAuthentication yes

# Kerberos options
#KerberosAuthentication no
#KerberosOrLocalPasswd yes
#KerberosTicketCleanup yes
#KerberosGetAFSToken no

# GSSAPI options
#GSSAPIAuthentication no
#GSSAPICleanupCredentials yes

# Set this to 'yes' to enable PAM authentication, account processing,
# and session processing. If this is enabled, PAM authentication will
# be allowed through the ChallengeResponseAuthentication mechanism.
# Depending on your PAM configuration, this may bypass the setting of
# PasswordAuthentication, PermitEmptyPasswords, and
# "PermitRootLogin without-password". If you just want the PAM account and
# session checks to run without PAM authentication, then enable this but set
# ChallengeResponseAuthentication=no
#UsePAM no

#AllowTcpForwarding yes
#GatewayPorts no
#X11Forwarding no
#X11DisplayOffset 10
#X11UseLocalhost yes
#PrintMotd yes
#PrintLastLog yes
TCPKeepAlive no
UseLogin no
UsePrivilegeSeparation no
#PermitUserEnvironment no
#Compression delayed
<?
$timeout=query("/sys/consoleprotocol/timeout");
if($timeout=="")
{
	echo "ClientAliveInterval 0";
}
else
{
	echo "ClientAliveInterval ".$timeout;
}
?>
ClientAliveCountMax 0
#UseDNS yes
#PidFile /var/run/sshd.pid
MaxStartups 1
#PermitTunnel no

# no default banner path
#Banner /some/path

# override default of no subsystems
#Subsystem	sftp	/usr/libexec/sftp-server

User1 <?query("/sys/user:1/name");?>
Passwd1 <?query("/sys/user:1/password");?>
shell /usr/sbin/cli

<?
$connect=query("/sys/consoleprotocol/connections");
if($connect=="")
{
	echo "MaxConnections 1";
}
else
{
	echo "MaxConnections ".$connect;
}
?>
