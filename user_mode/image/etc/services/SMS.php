<?
fwrite("w",$START, "#!/bin/sh\n");
fwrite("w", $STOP, "#!/bin/sh\n");

fwrite("a", $START, "echo Config Change event to Call Monitor ... > /dev/console\n");
fwrite("a",$START, "cmc cfg_change\n");
fwrite("a", $STOP, "echo Call Manager do nothing... > /dev/console\n");

?>
