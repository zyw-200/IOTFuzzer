<?
/* setcfg is used to move the validated session data to the configuration database.
 * The variable, 'SETCFG_prefix',  will indicate the path of the session data. */

include "/htdocs/phplib/setcfg/libs/dmz.php";
dmz_setcfg($SETCFG_prefix, "DMZ.NAT-1");
?>
