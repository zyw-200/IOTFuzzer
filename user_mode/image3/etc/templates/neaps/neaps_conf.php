<?
/* vi: set sw=4 ts=4: */
$VENDOR=query("/sys/vendor");
$MODEL=query("/sys/modelname");
$DESCRIPTION=query("/sys/modeldescription");
$VERSION=query("/runtime/sys/info/firmwareversion");
$SECRET=query("/sys/user:1/password");
echo "vendor=".$VENDOR."\n";
echo "model=".$MODEL."\n";
echo "description=".$DESCRIPTION."\n";
echo "version=".$VERSION."\n";
echo "secret=".$SECRET."\n";
?>
