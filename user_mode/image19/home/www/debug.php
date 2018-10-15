<?php
	@include('sessionCheck.inc');
	$productIdArr = explode(' ', conf_get('system:monitor:productId'));
	$productId = $productIdArr[1];
	$fn = "/tmp/checkfile.txt";	
if (isset($_POST['writeData']))
{
$renable=$_POST['debuginfo'];
$chk0=$_POST['chk0'];
$chk1=$_POST['chk1'];
$chk2=$_POST['chk2'];
$chk3=$_POST['chk3'];
$chk4=$_POST['chk4'];
$chk5=$_POST['chk5'];
$chk6=$_POST['chk6'];
$chk7=$_POST['chk7'];
$chk8=$_POST['chk8'];
$chk9=$_POST['chk9'];
$chk10=$_POST['chk10'];
//$fn = "/tmp/checkfile.txt";
	$file = fopen($fn, "w");
$size = filesize($fn);
$savestring=$renable.','.$chk0.','.$chk1.','.$chk2.','.$chk3.','.$chk4.','.$chk5.','.$chk6.','.$chk7.','.$chk8.','.$chk9.','.$chk10;
fwrite($file, $savestring);
fclose($file);
}
if(file_exists($fn)){
$fp = fopen("/tmp/checkfile.txt", "r");
$data = file("/tmp/checkfile.txt");
foreach($data as $value) {
//echo "$value<br>";
}
$x1=$value;
fclose($fp);
}

$flag=false;	
	$msg='';
			if($_REQUEST['chk0']==1){
				exec("80211debug -i wifi0vap0 0x00080000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	
			if($_REQUEST['chk1']==2){
				exec("80211debug -i wifi0vap0 0x00080000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
		if($_REQUEST['chk2']==3){
				exec("80211debug -i wifi0vap0 0x00800000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	if($_REQUEST['chk3']==4){
				exec("80211debug -i wifi0vap0 0x00400000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	if($_REQUEST['chk4']==5){
				exec("80211debug -i wifi0vap0 0x08000000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
		
	if($_REQUEST['chk5']==6){
				exec("80211debug -i wifi0vap0 0x00200000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}		
		
	if($_REQUEST['chk6']==7){
				exec("80211debug -i wifi0vap0 0x01000000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
		
	if($_REQUEST['chk7']==8){
				exec("athdebug -i wifi0 0x20000000",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	if($_REQUEST['chk8']==9){
				exec("athdebug -i wifi0 0x00000020",$dummy,$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	if($_REQUEST['chk9']==10){
				exec("athdebug -i wifi0 0x00040000",$dummy,$res);
			//echo("res = ".$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
	if($_REQUEST['chk10']==11){
				exec("athdebug -i wifi0 0x00010000",$dummy,$res);
			//echo("res = ".$res);
			if ($res!=0) {
				$msg = 'Update Success!';
				$flag = true;
			}
		}
       
?>
<html>
	<head>
		<title>Netgear</title>
		<style>
			<!--
				TABLE {
					margin-left: auto;
					margin-right: auto;
				}
				TD {
					padding: 5px;
					text-align: left;
					vertical-align: top;
				}
				.right {
					text-align: right;
				}
			-->
		</style>
		
	</head>
	<body align="center">
		<form name="hiddenForm" action="" method="post" align="center">
			<div align="center">
			<table align="center" style="margin: 20px; width: 50%; text-align: center; border: 1px solid #46008F">
				<tr>
					<td width="100%" colspan="2" align="center">
						<div align="center" style="margin:auto;">
							<table id="errorMessageBlock" align="center" style="margin: 4px auto 10px auto;">
								<tr>
									<!--<td style="padding: 5px; vertical-align: top;"><img src="images/alert.gif" style="border: 0px; padding: 0px; margin: 0px;"></td>-->
									<td style="padding: 5px 5px 5px 0px; vertical-align: middle;"><b id="br_head" style="color: #CC0000;"><?php if ($flag == true) echo ($msg=='')?"Invalid Data!":$msg; ?></b></td>
								</tr>							
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td width="30%" class="right"><label for="debuginfo"><b>Debug</b></label></td>
					<td width="70%">
						<input type="radio" id="debuginfo1" name="debuginfo" value="1"   <?php if($_REQUEST['debuginfo']==1){ ?> checked<?} ?> onchange="changeRadioStatus('debuginfo1')"><small>Enable</small>
						<input type="radio" id="debuginfo" name="debuginfo" value="0" <?php if($_REQUEST['debuginfo']==0){ ?> checked<?} ?> onchange="changeRadioStatus('debuginfo')" ><small>Disable</small>
		
					</td>
				</tr>
				<tr>
					<td width="30%" class="right"><label for="hostapdinfo"><b>Hostapd</b></label></td>
					<td width="70%">
						<input type="checkbox" id="chk0" name="chk0" value ="1"/>Hostapd
					</td>
				</tr>
				<tr>
					<td width="30%" class="right"><label for="debuginfo"><b>net802.11</b></label></td>
					<td width="70%">
						<input type="checkbox" id="chk1" name="chk1" value ="2"/>State
						<input type="checkbox" id ="chk2" name="chk2" value ="3"/>Assoc
                        <input type="checkbox" id="chk3" name="chk3" value ="4"/>Auth
						<input type="checkbox" id="chk4" name="chk4" value ="5"/>Input
						<input type="checkbox" id="chk5" name="chk5" value ="6"/>Scan
						<input type="checkbox" id="chk6" name="chk6" value ="7"/>Node
					</td>
				</tr>

				<tr>
					<td width="30%" class="right"><label for="athinfo"><b>Ath</b></label></td>
					<td width="70%">
						<input type="checkbox" id="chk7" name="chk7" value = "8"/>Warning
						<input type="checkbox" id="chk8" name="chk8" value ="9"/>Reset
                        <input type="checkbox" id="chk9" name="chk9" value = "10"/>State
						<input type="checkbox" id="chk10" name="chk10" value="11"/>Calibration
					</td>
				</tr>
				<tr>
					<td width="30%" class="right"><input type="submit" id="btnStatus" name="writeData" value="Submit" ></td>
					<td width="70%"><input type="reset" name="reset" value="Reset Form"></td>
				</tr>
			</table>
			</div>
		</form>
		<script language="javascript" type="text/javascript">
		<!--	
		
		function init()
		{	
		var $x = "<?php echo $x1;?>";
		var a = $x.split(","); // Delimiter is a string		
		for(var i=0;i<=a.length;i++)
		{
			if(a[i]==i+1)
			document.getElementById('debuginfo1').checked=true;
		if(a[i+1]==i+1)
			document.getElementById('chk'+i).checked=true;
		}
		}
		
		function changeRadioStatus(obj){
		if(obj=='debuginfo')
		{ 
				for(i =0;i<12;i++)
				document.getElementById('chk'+i).disabled=true;
				//document.getElementById('chk2').disabled=true;
		}
		else if(obj=='debuginfo1')
		{
		for(i=0;i<12;i++)
		document.getElementById('chk'+i).disabled=false;	
		}
		}	
	
	init();
			-->
		
		</script>
	</body>
</html>
