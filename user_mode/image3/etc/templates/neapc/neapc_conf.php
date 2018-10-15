<?
/* vi: set sw=4 ts=4: */
$VENDOR=query("/sys/vendor");
$MODEL=query("/sys/modelname");
$DESCRIPTION=query("/sys/modeldescription");
$VERSION=query("/runtime/sys/info/firmwareversion");
$SECRET=query("/wlan/inf:1/aparray_password");
echo "vendor=".$VENDOR."\n";
echo "model=".$MODEL."\n";
echo "description=".$DESCRIPTION."\n";
echo "version=".$VERSION."\n";
echo "secret=".$SECRET."\n";
?>
