<?
/* vi: set sw=4 ts=4: ---------------------------------------------------------*/
$MY_NAME      = "adv_partition";
$MY_MSG_FILE  = $MY_NAME.".php";
$MY_ACTION    = $MY_NAME;
$NEXT_PAGE    = "adv_partition";
set("/runtime/web/help_page",$MY_NAME);
/* --------------------------------------------------------------------------- */
if($ACTION_POST != "")
{
    require("/www/model/__admin_check.php");
    require("/www/__action_adv.php");
    $ACTION_POST = "";
    exit;
}

/* --------------------------------------------------------------------------- */
require("/www/model/__html_head.php");
set("/runtime/web/next_page",$first_frame);
/* --------------------------------------------------------------------------- */
// get the variable value from rgdb.
echo "<!--debug\n";
if($band_reload == 0 || $band_reload == 1) // change band
{
	$cfg_band = $band_reload;
}
else // first init
{
	$cfg_band = query("/wlan/ch_mode");
}

if($cfg_band == 0) // 2.4G
{
	anchor("/wlan/inf:1");
}
else        //5G
{
	anchor("/wlan/inf:2");
}

$switch = query("/runtime/web/display/switchable");
if($switch == 1)
{
	anchor("/wlan/inf:1");
}

$cfg_mssid_state = query("multi/state");
$cfg_mode = query("ap_mode");
$w_partition = query("w_partition");
$w_partition1 = query("multi/index:1/w_partition");
$w_partition2 = query("multi/index:2/w_partition");
$w_partition3 = query("multi/index:3/w_partition");
$w_partition4 = query("multi/index:4/w_partition");
$w_partition5 = query("multi/index:5/w_partition");
$w_partition6 = query("multi/index:6/w_partition");
$w_partition7 = query("multi/index:7/w_partition");
$e_partition = query("e_partition");
$cfg_link_integrality =query("ethlink");
$check_band = query("/wlan/inf:2/ap_mode");
echo "-->";
/* --------------------------------------------------------------------------- */
?>

<?
echo $G_TAG_SCRIPT_START."\n";
require("/www/model/__wlan.php");
?>

function on_change_band(s)
{
	var f = get_obj("frm");
	self.location.href = "adv_partition.php?band_reload=" + s.value;
}

/* page init functoin */
function initradio(s,id)
{
    var f=get_obj("frm");
    if(s == 0)
        id[0].checked = true;
    else if(s == 1)
        id[1].checked = true;
    else
        id[2].checked = true;
}
function oppositeRaidoEnableDisable(id, func)
{
    var str = "";
    str += "<input type=\"radio\" id=\"enable_" + id + "\" name=\"" + id + "\" value=\"0\" onClick=\"" + func + "\">";
    str += "<?=$m_enable?>";
    str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
    str += "<input type=\"radio\" id=\"disable_" + id + "\" name=\"" + id + "\" value=\"1\" onClick=\"" + func + "\">";
    str += "<?=$m_disable?>";
    str += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp";
    str += "<input type=\"radio\" id=\"disable_" + id + "\" name=\"" + id + "\" value=\"2\" onClick=\"" + func + "\">";
    str += "<?=$m_guest?>";
    document.write(str);
}
function init()
{
    var f=get_obj("frm");
    select_index(f.band, "<?=$cfg_band?>");
	if("<?=$check_band?>" == "")
		f.band.disabled = true;
	select_index(f.link_integrality, "<?=$cfg_link_integrality?>");
    select_index(f.ewa, "<?=$e_partition?>");
    initradio("<?=$w_partition?>",f.isc);
    initradio("<?=$w_partition1?>",f.isc1);
    initradio("<?=$w_partition2?>",f.isc2);
    initradio("<?=$w_partition3?>",f.isc3);
    initradio("<?=$w_partition4?>",f.isc4);
    initradio("<?=$w_partition5?>",f.isc5);
    initradio("<?=$w_partition6?>",f.isc6);
    initradio("<?=$w_partition7?>",f.isc7);
    if ("<?=$cfg_mode?>"==1)
    {
        for(i=0;i<3;i++)
        {
            f.isc[i].disabled=true;
            f.isc1[i].disabled=true;
            f.isc2[i].disabled=true;
            f.isc3[i].disabled=true;
           	f.isc4[i].disabled=true;
           	f.isc5[i].disabled=true;
           	f.isc6[i].disabled=true;
           	f.isc7[i].disabled=true;
        }
        f.ewa.disabled=true;
		f.link_integrality.disabled=true;
    }
    if("<?=$cfg_mssid_state?>"!=1)
    {
        for(i=0;i<3;i++)
        {
            f.isc1[i].disabled=true;
            f.isc2[i].disabled=true;
            f.isc3[i].disabled=true;
           	f.isc4[i].disabled=true;
           	f.isc5[i].disabled=true;
           	f.isc6[i].disabled=true;
           	f.isc7[i].disabled=true;
        }
    }
	AdjustHeight();
}

function on_change_ewa()
{
    var f=get_obj("frm");
    if(f.ewa.value==1)
    {
        alert("<?=$a_will_block_packets?>");
    }
}
/* parameter checking */
function checkradio(cid,id)
{
    var f=get_obj("frm");
    if(cid[1].checked)
    {
        id.value = 1;
    }
    else if (cid[0].checked)
    {
        id.value = 0;
    }
    else
    {
        id.value = 2;
    }
}
function check()
{
    var f=get_obj("frm");
    checkradio(f.isc,f.f_isc);
    checkradio(f.isc1,f.f_isc1);
    checkradio(f.isc2,f.f_isc2);
    checkradio(f.isc3,f.f_isc3);
    checkradio(f.isc4,f.f_isc4);
    checkradio(f.isc5,f.f_isc5);
    checkradio(f.isc6,f.f_isc6);
    checkradio(f.isc7,f.f_isc7);
    fields_disabled(f, false);
    return true;
}

function submit()
{
    if(check()) get_obj("frm").submit();
}

</script>

<body <?=$G_BODY_ATTR?> onload="init();">

<form name="frm" id="frm" method="post" action="<?=$MY_NAME?>.php" onsubmit="return check();">

<input type="hidden" name="ACTION_POST" value="<?=$MY_ACTION?>">
<input type="hidden" name="f_isc"       value="">
<input type="hidden" name="f_isc1"      value="">
<input type="hidden" name="f_isc2"      value="">
<input type="hidden" name="f_isc3"      value="">
<input type="hidden" name="f_isc4"      value="">
<input type="hidden" name="f_isc5"      value="">
<input type="hidden" name="f_isc6"      value="">
<input type="hidden" name="f_isc7"      value="">
<input type="hidden" name="f_wlmode"        value="">

<table id="table_frame" border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
    <tr>
        <td valign="top">
            <table id="table_header" <?=$G_TABLE_ATTR_CELL_ZERO?>>
            <tr>
                <td id="td_header" valign="middle"><?=$m_context_title?></td>
            </tr>
            </table>
<!-- ________________________________ Main Content Start ______________________________ -->
            <table id="table_set_main"  border="0"<?=$G_TABLE_ATTR_CELL_ZERO?>>
                <tr>
                    <td width="35%" id="td_left">
                        <?=$m_band?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>genSelect("band", [0,1], ["<?=$m_band_2.4G?>","<?=$m_band_5G?>"], "on_change_band(this);");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">
                        <?=$m_link_integrality?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>genSelect("link_integrality", [0,1], ["<?=$m_disable?>","<?=$m_enable?>"], "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td width="35%" id="td_left">
                        <?=$m_ewa?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>genSelect("ewa", [1,0], ["<?=$m_disable?>","<?=$m_enable?>"], "on_change_ewa();");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">
                        <?=$m_isc?>
                    </td>
                    <td id="td_right">
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                                   &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_pri_ssid?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">
&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid1?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc1", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid2?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc2", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid3?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc3", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid4?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc4", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid5?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc5", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid6?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc6", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td id="td_left">                       &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <?=$m_ms_ssid7?>
                    </td>
                    <td id="td_right">
                        <?=$G_TAG_SCRIPT_START?>oppositeRaidoEnableDisable("isc7", "");<?=$G_TAG_SCRIPT_END?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
<?=$G_APPLY_BUTTON?>
                    </td>
                </tr>
            </table>
<!-- ________________________________  Main Content End _______________________________ -->
        </td>
    </tr>
</table>
</form>
</body>
</html>

