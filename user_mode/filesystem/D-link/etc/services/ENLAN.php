<?
fwrite("w",$START, "#!/bin/sh\n");
fwrite("a",$START, "echo [$0]: TODO ................... > /dev/console\n");

fwrite("w",$STOP, "#!/bin/sh\n");
fwrite("a",$START, "echo [$0]: TODO ................... > /dev/console\n");
?>
