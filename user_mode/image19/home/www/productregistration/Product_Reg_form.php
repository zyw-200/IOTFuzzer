<?php 
if ((time()-@filemtime('/tmp/sessionid'))>300)
{
echo "<script>window.opener.location.href = window.opener.location.href</script>";  
echo "<script>window.close()</script>";  
}
$confdEnable = true;
	if ($confdEnable) {
		$productIdArr = explode(' ', conf_get('system:monitor:productId'));
		$productId = $productIdArr[1];
	}

?>
<script>
//check for dynamic checking for window openser location if not redirecting to index.php
if (window.opener == null || window.opener.closed)
parent.parent.document.location="/index.php"; 
</script>
<html><head><meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>
<link href='../include/css/default.css' rel='stylesheet' media='screen'/>
<link href='../include/css/style.css' rel='stylesheet' media='screen'/>
<link href='../include/css/layout.css' rel='stylesheet' media='screen'/>
<style type="text/css">
<!--
.style2 {
	font-size: 90%
}
#tabstyle td
{
padding:5px;
}
-->
</style>
</head>
<script  src="../include/scripts/common.js" type="text/javascript"></script>
<script src="../include/scripts/copyright.js" type="text/javascript"></script>
<title>NetGear</title>
<body height="100%">
<table class="tableStyle" height="100%" width="100%">
	<tr class="topAlign">
		<td valign="top" >&nbsp;</td>
		<td>
			<table class="tableStyle">
				<tr class="topAlign">
					<td class="leftNextBodyNotch"><img src="../images/clear.gif" width="11" height="16"/></td>
					<td class="middleBodyNotch spacer100Percent">&nbsp;</td>
					<td class="rightNextBodyNotch"><img src="../images/clear.gif" width="11"/></td>
				</tr>
			</table>
		</td>
		<td class="rightBodyNotch">&nbsp;</td>
	</tr>
	<tr>
		<td rowspan="2" class="leftEdge">&nbsp;</td>
		<td height="100%" align="center" valign="top" >
			<form onsubmit="return checkForm();" method="POST" action="https://my.netgear.com/myNETGEAR/register2.asp" name="formMain">
		<p class="font15Bold">Please complete the form below to register your product</p><br>
		<br>
		<table cellpadding="5" cellspacing="5" class="mainText style" id="tabstyle">
          <tbody>
            <tr> 
              <td width="200" class="DatablockLabel">Serial Number:&nbsp;</td>
              <td>
              <?
			  if(strcasecmp($productId,'WN802Tv2')==0)
			  {
			   	$serno=exec("printmd /dev/mtd4 | grep serno");
              	$serno = explode("=", $serno);
		      	$serno = trim($serno[1]," ");								
			  }	
			 else 
			  {
				$serno=exec("printmd /dev/mtd5 | grep serno");
              	$serno = explode("=", $serno);
		      	$serno = trim($serno[1]," ");				
			  }

              if($serno!="" && $serno!=" " && $serno!=null && $serno!=undefined && (!preg_match('#[^a-zA-Z0-9]#', $serno)) && (strlen($serno)==13) )
              echo "<input type='text' onkeypress='return checkInput(event);' readonly='readonly' value='$serno' name='serial' class='mainText' size='30'>";
              else
              echo "<input type='text' onkeypress='return checkInput(event,this);' value='' name='serial' class='mainText' size='30'>";
              ?>            
                <font size="1" color="red">*</font></td>
            </tr>
            <tr> 
              <td width="200" class="DatablockLabel">Model No:&nbsp;</td>
              <td>
              <?
			  if ($productId == "WN802tv2")
			  {
			   	$productid=exec("printmd /dev/mtd4 | grep ProductID");
			  }
			  else
			  {
              	$productid=exec("printmd /dev/mtd5 | grep ProductID");
			  }			  
              $productid = explode("=", $productid);
              $productid=trim($productid[1]," ");
              if($productid!="" || $productid!=null || $productid!=undefined)
              echo "<input type='text' onkeypress='return checkInput(event);' readonly='readonly' value='$productid' name='product' class='mainText' size='30'>";
              else
              echo "<input type='text' onkeypress='return checkInput(event);'  value='' name='product' class='mainText' size='30'>";
              ?>  
			  <font size="1" color="red">*</font>
            </tr>
            <tr> 
              <td width="200" class="DatablockLabel">Date Purchased:&nbsp;</td>
              <td>
              <input type="text" value="" name="" readonly="readonly" class="mainText" size="30" id="datedisplay"> <font size="1" color="red">*</font> 
			  <input type="hidden" value="" name="dateD" readonly="readonly" class="mainText" size="30" id="date">
			  <input type="hidden" value="" name="dateM" readonly="readonly" class="mainText" size="30" id="month">
			  <input type="hidden" value="" name="dateY" readonly="readonly" class="mainText" size="30" id="year">
              <script type="text/javascript">
                <!--
                var currentTime = new Date()
                var CurrMonth = currentTime.getMonth() + 1
                document.getElementById('month').value=CurrMonth
				var CurrentDate = currentTime.getDate()
                document.getElementById('date').value=CurrentDate
                var Currentyear = currentTime.getFullYear()
                document.getElementById('year').value=Currentyear
                var str=CurrMonth + "/" + CurrentDate + "/" + Currentyear;
                document.getElementById('datedisplay').value=str
                </script>
              </td>
            </tr> 
            <tr> 
              <td width="200" class="DatablockLabel">Country:&nbsp;</td>
              <td><input type="text" onkeypress="return checkInput(event);" value="" name="Country" class="mainText" size="30">
               <font size="1" color="red">*</font></td>
            </tr>
            <tr> 
              <td width="200" class="DatablockLabel">Email:&nbsp;</td>
              <td><input type="text" value="" name="email" class="mainText" size="30"> 
              <font size="1" color="red">*</font>
              </td>
            </tr>                                   
            <tr> 
              <td width="200"  class="DatablockLabel">First name:&nbsp;</td>
              <td><input type="text" value="" name="first_name" class="mainText" size="30"></td>
            </tr>
            <tr> 
              <td width="200" class="DatablockLabel">Last name:&nbsp;</td>
              <td><input type="text" value="" name="last_name" class="mainText" size="30"> 
            </tr>
            <tr> 
              <td width="200" class="DatablockLabel">Telephone:&nbsp;</td>
              <td><input type="text" value="" name="telephone" class="mainText" size="30"></td>
            </tr>
          </tbody>
        </table>
		<br>
		<br>
		
	</form>
		</td>
		<td rowspan="2" class="rightEdge">&nbsp;</td>
	</tr>
	<tr>
		<td>
			<table class="tableStyle">
				<tr>
					<td colspan="3" class="topBottomDivider" id="cloneTd"><img src="../images/clear.gif" height="3"/></td>
				</tr>
				<tr>
					<td colspan="3" class="footerBody">
						<table >
              <tr> 
                <td ><font color="red">*</font><i>Fields are mandatory</i> <br />
                    <font color="red">*</font> <i>If you enter 
                  a valid email address, you will be sent a username and password, 
                  giving you access to the NETGEAR customer support site, which 
                  will allow you to view your support history and purchase extended 
                  warranty options.</i> </td>
                <td id="ButtonsDiv"><form name="form1" method="post" action="">
										<label>
                                            <BUTTON name="btBack" type="button" class="orange11" id="btBack" onClick="Register()" >Register</BUTTON>
										</label>
									</form></td>
              </tr>
            </table>
					</td>
				</tr>
			</table>
		</td>
	</tr>
	<tr>
		<td class="leftEdgeFooter"><img src="../images/clear.gif" width="11" height="9"/></td>
		<td>
			<table class="tableStyle">
				<tr>
					<td class="leftBottomDivider"><img src="../images/clear.gif" width="11" height="9"/></td>
					<td class="middleBottomDivider spacer100Percent"><img src="../images/clear.gif" height="9"/></td>
					<td class="rightBottomDivider spacer1Percent"><img src="../images/clear.gif" height="9"/></td>
				</tr>
			</table>
		</td>
		<td class="rightEdgeFooter"><img src="../images/clear.gif" width="11" height="9"/></td>
	</tr>
	<tr>
		<td class="leftCopyrightFooter"><img src="../images/clear.gif" width="11" height="9"/></td>
		<td class="middleCopyrightDivider">
			<table class="blue10 tableStyle">
				<tr class="topAlign">
					<td>
					<script type='text/javascript'>getCopyright();</script>
				</tr>
			</table>
		</td>
		<td class="rightCopyrightFooter"><img src="../images/clear.gif" width="11" height="9"/></td>
	</tr>
</table>
</body>
</html>
<script>
//Submitting the form
function Register()
{
 	if (document.formMain.serial.value == '') {
			alert("You must enter a valid Serial");
			document.formMain.serial.focus();
			return(false);
		}	        
		if (document.formMain.product.value == '') {
			alert("You must enter a valid product");
			document.formMain.product.focus();
			return(false);
		}
		if (document.formMain.Country.value == '') {
			alert("You must enter a valid Country");
			document.formMain.Country.focus();
			return(false);
		}
    	var emailID=document.formMain.email
    	if ((emailID.value==null)||(emailID.value=="")){
    		alert("Please Enter your Email ID")
    		emailID.focus()
    		return false
    	}
    	if (echeck(emailID.value)==false){
    		emailID.value=""
    		emailID.focus()
    		return false
    	}
    //	return true

   document.forms["formMain"].submit();
}
//Serial No key input and Serial No restrict for 13 Chars
 function checkInput(e,val) {  
        var key;	
        if(window.event){
            key = e.keyCode;
        }else if(e.which){
            key = e.which;
        }   	
        if ( ((key >= 48 && key <= 57) ||(key >= 97 && key <= 122) ||(key >= 65 && key <= 90) || (key==13 || key==undefined || key==8)) && val.value.length<13)   
            return;
        else  
        {
            return false;
        }
     }
</script>

<script language = "Javascript">
//Email Validation
function echeck(str) {

	var at="@"
	var dot="."
	var lat=str.indexOf(at)
	var lstr=str.length
	var ldot=str.indexOf(dot)
	if (str.indexOf(at)==-1){
	   alert("Invalid E-mail ID")
	   return false
	}

	if (str.indexOf(at)==-1 || str.indexOf(at)==0 || str.indexOf(at)==lstr){
	   alert("Invalid E-mail ID")
	   return false
	}

	if (str.indexOf(dot)==-1 || str.indexOf(dot)==0 || str.indexOf(dot)==lstr){
	    alert("Invalid E-mail ID")
	    return false
	}

	 if (str.indexOf(at,(lat+1))!=-1){
	    alert("Invalid E-mail ID")
	    return false
	 }

	 if (str.substring(lat-1,lat)==dot || str.substring(lat+1,lat+2)==dot){
	    alert("Invalid E-mail ID")
	    return false
	 }

	 if (str.indexOf(dot,(lat+2))==-1){
	    alert("Invalid E-mail ID")
	    return false
	 }
	
	 if (str.indexOf(" ")!=-1){
	    alert("Invalid E-mail ID")
	    return false
	 }

	 return true					
}
</script>