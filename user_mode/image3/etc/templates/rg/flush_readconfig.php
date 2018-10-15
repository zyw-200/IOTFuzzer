<?
/* vi: set sw=4 ts=4:
 *
 * Get configuration
 */
$wanmode=query("/wan/rg/inf:1/mode");
$lanif  =query("/runtime/layout/lanif");
$lanip  =query("/lan/ethernet/ip");
$lanmask=query("/lan/ethernet/netmask");
$wanif=query("/runtime/wan/inf:1/interface");
$wanip=query("/runtime/wan/inf:1/ip");
echo "# wanmode=".$wanmode."\n";
echo "# wan=".$wanif."/".$wanip."\n";
echo "# lan=".$lanif."/".$lanip."/".$lanmask."\n";
?>
