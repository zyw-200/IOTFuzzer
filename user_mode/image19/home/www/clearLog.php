<?php
@include('sessionCheck.inc');
if ($_REQUEST['product'] == 'aries')
	$res = `echo '' > /tmp/log/messages`;
else
	$res = `echo '' > /var/log/messages`;
?>