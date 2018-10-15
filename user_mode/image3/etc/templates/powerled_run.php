#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$ledon = query("/sys/led/power");
if($ledon==1) { echo "mfc led status\n"; }
else { echo "mfc led off\n"; }

?>
