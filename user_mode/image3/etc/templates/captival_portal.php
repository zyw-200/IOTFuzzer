#!/bin/sh
echo [$0] ... > /dev/console
<? /* vi: set sw=4 ts=4: */

$captival_state=0;

for("/captival/ssid")
{
	if(query("state") >0 && query("state") != "") 
	{
		$captival_state++;
	}
}

if ($generate_start == 1)
{
	echo "echo captival_state ".$captival_state."\n";
    if($captival_state > 0)
    {
        echo "echo captival portal is enable \n";
        echo "rm -rf /var/captival \n";
        echo "cp -rf /usr/local/etc/captival /var/ \n";
        echo "cd /var/captival \n";
        echo "./captival_portal & \n";
        echo "cd / \n";
    }
    else
    {
        echo "echo captival portal is disable \n";
    }

}
else
{
    $start = query("/runtime/captival/start");
    if($start == 1)
    {
        echo "echo stop captival portal \n";
        echo "rgdb -is /runtime/captival/start 0 \n";
        echo "captival_usockc br_exit \n";
        echo "sleep 2 \n";
        echo "rm -rf /var/captival \n";
        echo "sleep 2 \n";
    }
    else
    {
        echo "echo captival portal  already stop \n";
    }
}
?>
