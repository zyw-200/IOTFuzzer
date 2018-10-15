<? /* vi: set sw=4 ts=4: */
include "/htdocs/phplib/xnode.php";
include "/htdocs/webinc/feature.php";
include "/htdocs/webinc/menu.php";		/* The menu definitions */

function is_label($group)
{
	if ($_GLOBALS["TEMP_MYGROUP"]==$group)
		echo ' class="label"';
}

function draw_menu($menuString, $menuLink, $delimiter)
{
	if($menuString != "")
	{
		$menuItems = cut_count($menuString,$delimiter);
		if($menuItems == 0) $menuItems = 1;
		$i = 0;
		while( $i < $menuItems )
		{
			if ($menuItems == 1)
			{
				$item = $menuString;
				$link = $menuLink;
			}
			else
			{
				$item = cut($menuString, $i, $delimiter);
				$link = cut($menuLink,   $i, $delimiter);
			}
			if ($link==$_GLOBALS["TEMP_MYNAME"].".php")
				echo '\t\t\t\t<li><a class="label" href="'.$link.'">'.$item.'</a></li>\n';
			else if ($link == "bsc_internet.php" && $_GLOBALS["TEMP_MYNAME"]=="bsc_wan")
				echo '\t\t\t\t<li><a class="label" href="'.$link.'">'.$item.'</a></li>\n';
			else if ($link == "bsc_wlan_main.php" && $_GLOBALS["TEMP_MYNAME"]=="bsc_wlan")
				echo '\t\t\t\t<li><a class="label" href="'.$link.'">'.$item.'</a></li>\n';
			else if ($link == "bsc_internetv6.php" && $_GLOBALS["TEMP_MYNAME"]=="bsc_ipv6")
				echo '\t\t\t\t<li><a class="label" href="'.$link.'">'.$item.'</a></li>\n';
			else
				echo '\t\t\t\t<li><a href="'.$link.'">'.$item.'</a></li>\n';
			$i++;
		}
	}
}
?><!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
	<link rel="shortcut icon" href="/favicon.ico" >
	<link rel="stylesheet" href="/css/general.css" type="text/css">
<?
	if ($TEMP_STYLE=="support") echo '\t<link rel="stylesheet" href="/css/support.css" type="text/css">\n';
?>	<meta http-equiv="Content-Type" content="no-cache">
	<meta http-equiv="Pragma" content="no-cache" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>D-LINK SYSTEMS, INC. | WIRELESS ROUTER | HOME</title>		
<?
	//---For Widget, Joseph Chao
	if (query("/runtime/services/http/server/widget") > 0)
	{
		$salt = query("/runtime/widget/salt");
		echo "	<script>";
		echo "var salt = \"".$salt."\";";
		echo "</script>";
	}
	//---For Widget, Joseph Chao
?>		
	<script type="text/javascript" charset="utf-8" src="./js/comm.js"></script>
	<script type="text/javascript" charset="utf-8" src="./js/libajax.js"></script>
	<script type="text/javascript" charset="utf-8" src="./js/postxml.js"></script>
<?
	if (isfile("/htdocs/webinc/js/".$TEMP_MYNAME.".php")==1)
		dophp("load", "/htdocs/webinc/js/".$TEMP_MYNAME.".php");
?>
	<script type="text/javascript">
	var OBJ	= COMM_GetObj;
	var XG	= function(n){return PXML.doc.Get(n);};
	var XS	= function(n,v){return PXML.doc.Set(n,v);};
	var XD	= function(n){return PXML.doc.Del(n);};
	var XA	= function(n,v){return PXML.doc.Add(n,v);};
	var GPBT= function(r,e,t,v,c){return PXML.doc.GetPathByTarget(r,e,t,v,c);};
	var S2I	= function(str) {return isNaN(str)?0:parseInt(str, 10);}

	function TEMP_IsDigit(no)
	{
		if (no==""||no==null)
			return false;
		if (no.toString()!=parseInt(no, 10).toString())
			return false;

	    return true;
	}
	function TEMP_CheckNetworkAddr(ipaddr, lanip, lanmask)
	{
		if (lanip)
		{
			var network = lanip;
			var mask = lanmask;
		}
		else
		{
			var network = "<?$inf = XNODE_getpathbytarget("/runtime", "inf", "uid", "LAN-1", 0); echo query($inf."/inet/ipv4/ipaddr");?>";
			var mask = "<?echo query($inf."/inet/ipv4/mask");?>";
		}
		var vals = ipaddr.split(".");

		if (vals.length!=4)
			return false;

		for (var i=0; i<4; i++)
			if (!TEMP_IsDigit(vals[i]) || vals[i]>255)	return false;

		if (COMM_IPv4NETWORK(ipaddr, mask)!=COMM_IPv4NETWORK(network, mask))
			return false;

		return true;
	}
	function TEMP_RulesCount(path, id)
	{
		var max = parseInt(XG(path+"/max"), 10);
		var cnt = parseInt(XG(path+"/count"), 10);
		var rmd = max - cnt;
		OBJ(id).innerHTML = rmd;
	}

	function Body() {}
	Body.prototype =
	{
		ShowLogin: function()
		{
			OBJ("loginpwd").value	= "";
			OBJ("captcha").value	= "";

			if (this.enCaptcha)
			{
				OBJ("GAC").style.display	= "inline";
				OBJ("noGAC").style.display	= "none";
				this.RefreshCaptcha();
			}
			else
			{
				OBJ("noGAC").style.display	= "inline";
				OBJ("GAC").style.display	= "none";
			}
			OBJ("menu").style.display	= "none";
			OBJ("content").style.display= "none";
			OBJ("mbox").style.display	= "none";
			OBJ("login").style.display	= "block";
			if (OBJ("loginusr").tagName.toLowerCase()=="input")
			{
				OBJ("loginusr").value = "";
				OBJ("loginusr").focus();
			}
			else
			{
				OBJ("loginpwd").focus();
			}
		},
		ShowContent: function()
		{
			OBJ("login").style.display	= "none";
			OBJ("mbox").style.display	= "none";
			<?if ($TEMP_STYLE!="simple") echo 'OBJ("menu").style.display	= "block";';?>
			OBJ("content").style.display= "block";
		},
		ShowMessage: function(banner, msgArray)
		{
			var str = '<h1>'+banner+'</h1>';
			for (var i=0; i<msgArray.length; i++)
			{
				str += '<div class="emptyline"></div>';
				str += '<div class="centerline">'+msgArray[i]+'</div>';
			}
			str += '<div class="emptyline"></div>';
			OBJ("message").innerHTML = str;
			OBJ("login").style.display	= "none";
			OBJ("menu").style.display	= "none";
			OBJ("content").style.display= "none";
			OBJ("mbox").style.display	= "block";
		},
		rtnURL: null,
		seconds: null,
		timerId: null,
		Countdown: function()
		{
			this.seconds--;
			OBJ("timer").innerHTML = this.seconds;
			if (this.seconds < 1) this.GotResult();
			else this.timerId = setTimeout('BODY.Countdown()',1000);
		},
		GotResult: function()
		{
			clearTimeout(this.timerId);
			if (this.rtnURL)	self.location.href = this.rtnURL;
			else				this.ShowContent();
		},
		ShowCountdown: function(banner, msgArray, sec, url)
		{
			this.rtnURL = url;
			this.seconds = sec;
			var str = '<h1>'+banner+'</h1>';
			for (var i=0; i<msgArray.length; i++)
			{
				str += '<div class="emptyline"></div>';
				str += '<div class="centerline">'+msgArray[i]+'</div>';
			}
			str += '<div class="emptyline"></div>';
			str += '<div class="centerline"><?echo i18n("Waiting time");?> : ';
			str += '<span id="timer" style="color:red;"></span></div>';
			str += '<div class="emptyline"></div>';
			OBJ("message").innerHTML	= str;
			OBJ("login").style.display	= "none";
			OBJ("menu").style.display	= "none";
			OBJ("content").style.display= "none";
			OBJ("mbox").style.display	= "block";
			this.Countdown();
		},
		ShowAlert: function(msg)
		{
			alert(msg);
		},
		DisableCfgElements: function(type)
		{
			for (var i = 0; i < document.forms.length; i+=1)
		    {
				var frmObj = document.forms[i];
				for (var idx = 0; idx < frmObj.elements.length; idx+=1)
				{
					if (frmObj.elements[idx].getAttribute("usrmode")=="enable") continue;
					frmObj.elements[idx].disabled = type;
				}
			}
		},
		//////////////////////////////////////////////////
		LoginCallback: null,
		//////////////////////////////////////////////////
		enCaptcha: COMM_ToBOOL(<?echo query("/device/session/captcha");?>),
		RefreshCaptcha: function()
		{
			var self = this;
			var obj = OBJ("auth_img");
			obj.innerHTML = "<font color=red>(<?echo i18n("Wait a moment");?> ...)</font>";
			AUTH.Captcha(function(xml)
			{
				switch (xml.Get("/captcha/result"))
				{
				case "OK":
					self.captcha = xml.Get("/captcha/message");
					obj.innerHTML = "<img src="+self.captcha+"?"+COMM_RandomStr(6)+" />";
					break;
				case "FAIL":
					obj.innerHTML = "<?echo i18n("No room for new session, please try again later !");?>";
					break;
				default:
					obj.innerHTML = "Internel error ("+xml.Get("/captcha/result")+")";
					break;
				}
			});
		},
		LoginSubmit: function()
		{
			var self = this;
			if (OBJ("loginusr").value=="")
			{
				this.ShowAlert("<?echo i18n("Please input the User Name.");?>")
				OBJ("loginusr").focus();
				return false;
			}
			else if (this.enCaptcha&&OBJ("captcha").value=="")
			{
				this.ShowAlert("<?echo i18n("Please enter the graphical authentication code.");?>");
				OBJ("captcha").focus();
				return false;
			}
			AUTH.Login(
				function(xml)
				{
					switch (xml.Get("/report/RESULT"))
					{
					case "SUCCESS":
						if (self.LoginCallback) self.LoginCallback();
						self.ShowContent();
						break;
					case "FAIL":
					case "INVALIDUSER":
					case "INVALIDPASSWD":
						var msgArray =
						[
							'<?echo i18n("User Name or Password is incorrect.");?>',
							'<input id="relogin" type="button" value="<?echo i18n("Login Again");?>" onClick="BODY.ShowLogin();" />'
						];
						self.ShowMessage('<?echo i18n("Login fail");?>', msgArray);
						OBJ("relogin").focus();
						break;
					case "INVALIDCAPTCHA":
						var msgArray =
						[
							'<?echo i18n("The graphical verification code is incorrect.");?>',
							'<input id="relogin" type="button" value="<?echo i18n("Login Again");?>" onClick="BODY.ShowLogin();" />'
						];
						self.ShowMessage('<?echo i18n("Login fail");?>', msgArray);
						OBJ("relogin").focus();
						break;
					case "SESSFULL":
						var msgArray =
						[
							'<?echo i18n("Too many session connected, please try again later.");?>',
							'<input id="relogin" type="button" value="<?echo i18n("Login Again");?>" onClick="BODY.ShowLogin();" />'
						];
						self.ShowMessage('<?echo i18n("Login fail");?>', msgArray);
						OBJ("relogin").focus();
						break;
					case "BAD REQUEST":
						self.ShowAlert("Internal error, BAD REQUEST.");
						break;
					}
				},
				OBJ("loginusr").value,
				OBJ("loginpwd").value,
				OBJ("captcha").value.toUpperCase()
				);
		},
		Login: function(callback)
		{
			if (callback)	this.LoginCallback = callback;
			if (AUTH.AuthorizedGroup >= 0) { AUTH.UpdateTimeout(); return true; }
			return false;
		},
		Logout: function()
		{
			AUTH.Logout(function(){AUTH.TimeoutCallback();});
		},
		Reboot: function()
		{
			if (!confirm("<?echo i18n("Reboot Router ?");?>"))	return;
			self.location.href = "./reboot.php";
		},
		//////////////////////////////////////////////////
		GetCFG: function()
		{
			var self = this;
			if (!this.Login(function(){self.GetCFG();})) return;
			if (AUTH.AuthorizedGroup >= 100) this.DisableCfgElements(true);
			if (PAGE&&PAGE.services!=null)
			{
				COMM_GetCFG(
					false,
					PAGE.services,
					function(xml) {
						PAGE.InitValue(xml);
						PAGE.Synchronize();
						COMM_DirtyCheckSetup();
						if (AUTH.AuthorizedGroup >= 100) BODY.DisableCfgElements(true);
						}
					);
			}
			return;
		},
		OnSubmit: function()
		{
			if (PAGE === null) return;
			PAGE.Synchronize();
			var dirty = COMM_IsDirty(false);
			if (!dirty && PAGE.IsDirty) dirty = PAGE.IsDirty();
			if (!dirty)
			{
				var msgArray =
				[
					'<?echo i18n("Settings have not changed.");?>',
					'<input id="nochg" type="button" value="<?echo i18n("Continue");?>" onClick="BODY.ShowContent();" />'
				];
				this.ShowMessage('<?echo i18n("no change");?>', msgArray);
				OBJ("menu").style.display	= "none";
				OBJ("content").style.display= "none";
				OBJ("mbox").style.display	= "block";
				OBJ("nochg").focus();
				return;
			}

			var xml = PAGE.PreSubmit();
			if (xml === null) return;

			if('<?echo $_GLOBALS["TEMP_MYNAME"];?>' != 'bsc_sms_send')
            {
            var msgArray =
            [
                '<?echo i18n("The settings are being saved and are taking effect.");?>',
                '<?echo i18n("Please wait");?> ...'
            ];
            }
            else
            {
                var msgArray = ['<?echo i18n("Sending Message, please wait...");?>'];
            }

			this.ShowMessage('<?echo i18n("Saving");?>', msgArray);
			AUTH.UpdateTimeout();

			var self = this;
			PXML.UpdatePostXML(xml);
			PXML.Post(function(code, result){self.SubmitCallback(code,result);});
		},
		SubmitCallback: function(code, result)
		{
			if (PAGE.OnSubmitCallback(code, result)) return;
			this.ShowContent();
			switch (code)
			{
			case "OK":
				this.OnReload();
				break;
			case "BUSY":
				this.ShowAlert("<?echo i18n("Someone is configuring the device, please try again later.");?>");
				break;
			case "HEDWIG":
				this.ShowAlert(result.Get("/hedwig/message"));
				if (PAGE.CursorFocus) PAGE.CursorFocus(result.Get("/hedwig/node"));  
				break;
			case "PIGWIDGEON":
				if (result.Get("/pigwidgeon/message")=="no power")
				{
					BODY.NoPower();
				}
				else
				{
					this.ShowAlert(result.Get("/pigwidgeon/message"));
				}
				break;
			}
		},
		NoPower: function()
		{
			BODY.ShowAlert("<?echo i18n("Your connection session is invalid, please re-login.");?>");
			AUTH.Logout();
			BODY.ShowLogin();
		},
		OnReload: function()
		{
			if (PAGE) PAGE.OnLoad();
			this.GetCFG();
		},
		//////////////////////////////////////////////////
		OnLoad: function()
		{
			var self = this;
			if (AUTH.AuthorizedGroup < 0)	this.ShowLogin();
			else							this.ShowContent();
			AUTH.TimeoutCallback = function()
			{
				var msgArray =
				[
					'<?echo i18n("You have successfully logged out.");?>',
					'<input id="tologin" type="button" value="<?echo i18n("Return to login page");?>" onClick="BODY.ShowLogin();" />'
				];
				self.ShowMessage('<?echo i18n("Logout");?>', msgArray);
				self.DisableCfgElements(false);
				if (PAGE) PAGE.OnLoad();
				OBJ("tologin").focus();
			};

			if (PAGE) PAGE.OnLoad();
			this.GetCFG();
		},
		OnUnload: function() { if (PAGE) PAGE.OnUnload(); OnunloadAJAX(); },
		OnKeydown: function(e)
		{
			switch (COMM_Event2Key(e))
			{
			case 13: this.LoginSubmit();
			default: return;
			}
		},
		InjectTable: function(tblID, uid, data, type)
		{
			var rows = OBJ(tblID).getElementsByTagName("tr");
			var tagTR = null;
			var tagTD = null;
			var i;
			var str;
			var found = false;
			
			/* Search the rule by UID. */
			for (i=0; !found && i<rows.length; i++) if (rows[i].id == uid) found = true;
			if (found)
			{
				for (i=0; i<data.length; i++)
				{
					tagTD = OBJ(uid+"_"+i);
					switch (type[i])
					{
					case "checkbox":
						str = "<input type='checkbox'";
						if (COMM_ToBOOL(data[i])) str += " checked";
						str += " disabled>";
						tagTD.innerHTML = str;
						break;
					case "text":
						str = data[i];
						if(typeof(tagTD.innerText) !== "undefined")	tagTD.innerText = str;
						else if(typeof(tagTD.textContent) !== "undefined")	tagTD.textContent = str;
						else	tagTD.innerHTML = str;
						break;	
					default:
						str = data[i];
						tagTD.innerHTML = str;
						break;
					}
				}
				return;
			}

			/* Add a new row for this entry */
			tagTR = OBJ(tblID).insertRow(rows.length);
			tagTR.id = uid;
			/* save the rule in the table */
			for (i=0; i<data.length; i++)
			{
				tagTD = tagTR.insertCell(i);
				tagTD.id = uid+"_"+i;
				tagTD.className = "content";
				switch (type[i])
				{
				case "checkbox":
					str = "<input type='checkbox'";
					if (COMM_ToBOOL(data[i])) str += " checked";
					str += " disabled>";
					tagTD.innerHTML = str;
					break;
				case "text":
					str = data[i];
					if(typeof(tagTD.innerText) !== "undefined")	tagTD.innerText = str;
					else if(typeof(tagTD.textContent) !== "undefined")	tagTD.textContent = str;
					else	tagTD.innerHTML = str;
					break;
				default:
					str = data[i];
					tagTD.innerHTML = str; 
					break;
				}
			}
		},
		CleanTable: function(tblID)
		{
			table = OBJ(tblID);
			var rows = table.getElementsByTagName("tr");
			while (rows.length > 1) table.deleteRow(rows.length - 1);
		}
	};
	/**************************************************************************/

	var AUTH = new Authenticate(<?=$AUTHORIZED_GROUP?>, <?echo query("/device/session/timeout");?>);
	var PXML = new PostXML();
	var BODY = new Body();
	var PAGE = <? if (isfile("/htdocs/webinc/js/".$TEMP_MYNAME.".php")==1) echo "new Page();"; else echo "null;"; ?>
<?
	/* generate cookie */
	if ($_SERVER["HTTP_COOKIE"] == "")
		echo 'if (navigator.cookieEnabled) document.cookie = "uid="+COMM_RandomStr(10)+"; path=/";\n';
?>	</script>
</head>

<body class="mainbg" onload="BODY.OnLoad();" onunload="BODY.OnUnload();">
<div class="maincontainer">
	<div class="headercontainer">
		<span class="product"><?echo i18n("Product Page");?> : <a href="http://support.dlink.com" target="_blank"><?echo query("/runtime/device/modelname");?></a></span>
		<span class="version"><?echo i18n("Firmware Version");?> : <?echo query("/runtime/device/firmwareversion");?></span>
	</div>
	<div class="bannercontainer">
		<span class="bannerhead"><a href="<?echo query("/runtime/device/producturl");?>"><img src="/pic/head_01.gif" width="162" height="92"></a></span>
		<span class="bannertail"><img src="/pic/head_03.gif"></span>
	</div>
	<div id="menu" class="topmenucontainer" style="display:none;">
		<div class="modelname"><?echo query("/runtime/device/modelname");?></div>
		<div>
			<a<?is_label("basic");?> href="setup.php"><?echo i18n("setup");?></a>
			<a<?is_label("advanced");?> href="advanced.php"><?echo i18n("advanced");?></a>
			<a<?is_label("tools");?> href="tools.php"><?echo i18n("tools");?></a>
			<a<?is_label("status");?> href="status.php"><?echo i18n("status");?></a>
			<a<?is_label("support");?> href="support.php"><?echo i18n("support");?></a>
		</div>
	</div>
<?
if ($TEMP_STYLE=="complex")
{
	echo '	<div id="content" class="complexcontainer" style="display:none;">\n'.
		 '		<div class="leftmenu">\n'.
		 '			<ul>\n';
	draw_menu($menu, $link, "|");
	echo '			</ul>\n'.
		 '		</div>\n'.
		 '		<div class="mainbody">\n'.
		 '<!-- Start of Page Depedent Part. -->\n';
	echo '<!-- '.isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php").$_GLOBALS["TEMP_MYNAME"].' -->\n';
	if (isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php")==1)
		dophp("load", "/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php");
	echo '<!-- End of Page Dependent Part. -->\n'.
		 '		</div>\n'.
		 '		<div class="tips">\n'.
		 '<!-- Start of Help Depedent Part. -->\n';
	if (isfile("/htdocs/webinc/help/".$_GLOBALS["TEMP_MYNAME"].".php")==1)
		dophp("load", "/htdocs/webinc/help/".$_GLOBALS["TEMP_MYNAME"].".php");
	echo '<!-- End of Help Dependent Part. -->\n'.
		 '		</div>\n'.
		 '	</div>';
}
// this simple style is used for wizard.
else if ($TEMP_STYLE=="simple")
{
	echo '	<div id="content" class="simplecontainer" style="display:none;">\n'.
		 '		<div class="simplebody">\n'.
		 '<!-- Start of Page Depedent Part. -->\n';
	if (isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php")==1)
		dophp("load", "/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php");
	echo '<!-- End of Page Dependent Part. -->\n'.
		 '		</div>\n'.
		 '	</div>';
}
else if ($TEMP_STYLE=="support")
{
	echo '	<div id="content" class="complexcontainer" style="display:none;">\n'.
		 '		<div class="leftmenu">\n'.
		 '			<ul>\n';
	draw_menu($menu, $link, "|");
	echo '			</ul>\n'.
		 '		</div>\n'.
		 '		<div class="menubody">\n'.
		 '<!-- Start of Page Depedent Part. -->\n';
	echo '<!-- '.isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php").$_GLOBALS["TEMP_MYNAME"].' -->\n';
	if (isfile("/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php")==1)
		dophp("load", "/htdocs/webinc/body/".$_GLOBALS["TEMP_MYNAME"].".php");
	echo '<!-- End of Page Dependent Part. -->\n'.
		 '		</div>\n'.
		 '	</div>';
}
?>
	<!-- Start of Login Body -->
	<div id="login" class="simplecontainer" style="display:none;">
		<div class="simplebody">
			<div class="orangebox">
			    <h1><?echo i18n("Login");?></h1>
				<div class="message"><?echo i18n("Login to the router");?> : </div>
				<div class="loginbox">
					<span class="name"><?echo i18n("User Name");?></span>
					<span class="delimiter">:</span>
					<span class="value">
<?
	$cnt = query("/device/account/count");
	if ($cnt > 1)
	{
		echo '\t\t\t\t\t\t<select id="loginusr">\n';
		foreach ("/device/account/entry")
		{
			$act = query("name");
			echo '\t\t\t\t\t\t\t<option value="'.$act.'"';
			if ($InDeX==1) echo ' selected';
			echo '>'.toupper($act).'</option>\n';
		}
		echo '\t\t\t\t\t\t</select>\n';
	}
	else
	{
		echo '\t\t\t\t\t\t<input type="text" id="loginusr" onkeydown="BODY.OnKeydown(event);" />';
	}
?>
					</span>
				</div>
				<div class="loginbox">
					<span class="name"><?echo i18n("Password");?></span>
					<span class="delimiter">:</span>
					<span class="value">
						<input type="password" id="loginpwd" onkeydown="BODY.OnKeydown(event);" />&nbsp;&nbsp;
						<input type="button" id="noGAC" value="<?echo i18n("Login");?>" onClick="BODY.LoginSubmit();" />
					</span>
				</div>
				<div id="GAC" class="centerline">
					<center>
					<table width="260px" style="text-align:left;">
					<tr>
						<td colspan="2">
							<strong><?echo i18n("Enter the correct password above and then type the characters you see in the picture below.");?></strong>
							<input type="text" id="captcha" class="uppercase" onkeydown="BODY.OnKeydown(event);" />
						</td>
					</tr>
					<tr>
						<td height="50px" width="190px" align="center"><span id="auth_img"></span></td>
						<td><input type="button" onClick="BODY.RefreshCaptcha();" value="<?echo i18n("Regenerate");?>" valign="middle" /></td>
					</tr>
					<tr>
						<td colspan="2" align="center">
							<input type="button" value="<?echo i18n("Login");?>" onClick="BODY.LoginSubmit();" style="width:90px;" />
						</td>
					</tr>
					</table>
					</center>
				</div>
				<div class="emptyline"></div>
			</div>
		</div>
	</div>
	<!-- End of Login Body -->
	<!-- Start of Message Box -->
	<div id="mbox" class="simplecontainer" style="display:none;">
		<div class="simplebody">
			<div class="orangebox"><span id="message"></span></div>
		</div>
	</div>
	<!-- End of Message Box -->
	<div class="footercontainer">
		<span class="footermark"><img src="/pic/tail.gif"></span>
	</div>
</div>
<div class="copyright">Copyright &copy; 2008-2010 D-Link Systems, Inc.</div>
</body>
</html>
