<style>
/* The CSS is only for this page.
 * Notice:
 *	If the items are few, we put them here,
 *	If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
.tzselect
{
	font-family: Tahoma, Helvetica, Geneva, Arial, sans-serif;
	font-size: 10px;
}
.timebox
{
	padding: 0 10px 10px 10px;
	width:   525px;
}
.timebox_item
{
  font-family: Arial, Helvetica, sans-serif;
}
td.timebox_item select
{
  font-size: 10px;
  width:     50px;
}
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "DEVICE.TIME, RUNTIME.TIME",
	OnLoad:    function() {},
	OnUnload:  function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		
		this.devtime_p = PXML.FindModule("DEVICE.TIME");
		this.runtime_p = PXML.FindModule("RUNTIME.TIME");
		if (!this.devtime_p || !this.runtime_p) { BODY.ShowAlert("<?echo i18n("InitValue ERROR!");?>"); return false; }

		OBJ("st_time").innerHTML = XG(this.runtime_p+"/runtime/device/uptime");	
		
		var tz = XG(this.devtime_p+"/device/time/timezone");
		COMM_SetSelectValue(OBJ("timezone"),COMM_ToNUMBER(tz));
		
		this.OnClicktimezone(COMM_ToNUMBER(tz));
		
		OBJ("ntp_enable").checked = (XG(this.devtime_p+"/device/time/ntp/enable")=="1");
		this.NtpEnDiSomething();
		OBJ("sync_msg").innerHTML = "";
		OBJ("sync_pc_msg").innerHTML = "";

		if(OBJ("ntp_enable").checked) this.UpdateCurrentTime(xml);
		else 
		{	
			this.UpdateSyncTime(xml);
			
			window.setTimeout("PAGE.GetSyncTimeStatus();", 1000);
		}

		OBJ("ntp_server").value = XG(this.devtime_p+"/device/time/ntp/server");

		this.InitManualBox();
		
		return true;
	},
	PreSubmit: function()
	{
		XS(this.devtime_p+"/device/time/timezone",	OBJ("timezone").value);
		XS(this.devtime_p+"/device/time/dst",		OBJ("daylight").checked ? "1":"0");
		
		if(OBJ("ntp_enable").checked)
		{
			XS(this.devtime_p+"/device/time/ntp/enable", "1");
			XS(this.devtime_p+"/device/time/ntp/server", OBJ("ntp_server").value);

			PXML.IgnoreModule("RUNTIME.TIME");
		}
		else
		{
			XS(this.devtime_p+"/device/time/ntp/enable", "0");
			var date = OBJ("month").value+"/"+OBJ("day").value+"/"+OBJ("year").value;
			var time = OBJ("hour").value+":"+OBJ("minute").value+":"+OBJ("second").value;
			XS(this.runtime_p+"/runtime/device/date", date);
			XS(this.runtime_p+"/runtime/device/time", time);
			
			PXML.ActiveModule("RUNTIME.TIME");
		}

		return PXML.doc;
	},
	IsDirty: function()
	{
		return OBJ("ntp_enable").checked ? false : true;
	},
	
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	devtime_p : null,
	runtime_p : null,
	dsFlag    : [''<?
				foreach ("/runtime/services/timezone/zone")
				{
					echo ",'";
					echo map("dst","","0","*","1");
					echo "'";
				}
				?>],

	GetDaysInMonth :function(year, mon)
	{
		var days;
		if (mon==1 || mon==3 || mon==5 || mon==7 || mon==8 || mon==10 || mon==12) days=31;
		else if (mon==4 || mon==6 || mon==9 || mon==11) days=30;
		else if (mon==2)
		{
			if (((year % 4)==0) && ((year % 100)!=0) || ((year % 400)==0)) { days=29; }
			else { days=28; }
		}
		return (days);
	},

	InitManualBox: function()
	{

		var dateObj = new Date();
		var year_var = dateObj.getFullYear();
		var  mon_var = dateObj.getMonth();

		// construct the day dropdown menu.
		mon_var  = S2I(mon_var);
		year_var = S2I(year_var);
		var days_InMonth = this.GetDaysInMonth(year_var, mon_var+1);
		for(var i=0;i<days_InMonth;i++){
			OBJ("day").options[i] = new Option(i+1, i+1);
		}
		OBJ("day").length = days_InMonth;

		COMM_SetSelectValue(OBJ("year"),	dateObj.getFullYear());
		COMM_SetSelectValue(OBJ("month"),	dateObj.getMonth()+1); 
		COMM_SetSelectValue(OBJ("day"),		dateObj.getDate());  
		COMM_SetSelectValue(OBJ("hour"),	dateObj.getHours());
		COMM_SetSelectValue(OBJ("minute"),	dateObj.getMinutes());
		COMM_SetSelectValue(OBJ("second"),	dateObj.getSeconds());
	},

	NtpEnDiSomething: function()
	{
		var dis = OBJ("ntp_enable").checked ? false : true;
		/* ntp part */
		OBJ("ntp_server").disabled  = OBJ("ntp_sync").disabled = dis;
		OBJ("manual_sync").disabled = !(dis);
		/* manual part */
		OBJ("year").disabled = OBJ("month").disabled  = OBJ("day").disabled = !(dis);
		OBJ("hour").disabled = OBJ("minute").disabled = OBJ("second").disabled = !(dis);
	},
	
	GetCurrentStatus: function()
	{
		COMM_GetCFG(false, "RUNTIME.TIME", PAGE.UpdateCurrentTime);
	},

	UpdateCurrentTime: function(xml)
	{
		var rt = xml.GetPathByTarget("/postxml", "module", "service", "RUNTIME.TIME", false);
				
		if (rt != "")
		{
			OBJ("st_time").innerHTML = xml.Get(rt+"/runtime/device/uptime");			
			var ntpstate = xml.Get(rt+"/runtime/device/ntp/state");

			switch (ntpstate)
			{
			case "SUCCESS":
				var msg_str = "<?echo i18n("The time has been successfully synchronized.");?>";
				msg_str += "<br>(<?echo i18n("NTP Server Used: ");?>" + xml.Get(rt+"/runtime/device/ntp/server") + "<?echo i18n(", Time: ");?>"+xml.Get(rt+"/runtime/device/ntp/uptime")+")";
				msg_str += "<br><?echo i18n("Next time synchronization: ");?>"+xml.Get(rt+"/runtime/device/ntp/nexttime");
				OBJ("st_time").innerHTML = xml.Get(rt+"/runtime/device/uptime");
				OBJ("sync_msg").innerHTML = msg_str;
				break;
			case "RUNNING":
				OBJ("sync_msg").innerHTML = "<?echo i18n("Synchronizing ...");?>";
				setTimeout("PAGE.GetCurrentStatus();", 1000);
				break;
			default:
				break;
			}
		}
	},
	
	PreClickSync: function()
	{
		
		
		XS(this.devtime_p+"/device/time/timezone",	OBJ("timezone").value);
		XS(this.devtime_p+"/device/time/dst",		OBJ("daylight").checked ? "1":"0");

		if(OBJ("ntp_enable").checked)
		{
			XS(this.devtime_p+"/device/time/ntp/enable", "1");
			XS(this.devtime_p+"/device/time/ntp/server", OBJ("ntp_server").value);
		}
		else
		{
			XS(this.devtime_p+"/device/time/ntp/enable", "0");
			var dateObj = new Date();
			var date = (dateObj.getMonth()+1)+"/"+dateObj.getDate()+"/"+dateObj.getFullYear();
			var time = dateObj.getHours()+":"+dateObj.getMinutes()+":"+dateObj.getMinutes();
			XS(this.runtime_p+"/runtime/device/date", date);
			XS(this.runtime_p+"/runtime/device/time", time);
		}
		
		this.Synchronize();
		var xml = PXML.doc;
		PXML.UpdatePostXML(xml);
		PXML.Post(function(code, result){BODY.SubmitCallback(code,result);});
	},

	OnClicktimezone : function(tz_no)
	{
		if(this.dsFlag[tz_no]==="1")
		{
			OBJ("daylight").disabled = false;
			OBJ("daylight").checked  = XG(this.devtime_p+"/device/time/dst")==="1";	
		}
		else
		{
			OBJ("daylight").disabled = true;
			OBJ("daylight").checked  = false;
		}
	},
	
	OnClickNTPSync: function()
	{
		if (OBJ("ntp_server").value==="" )
		{
			BODY.ShowAlert("<?echo i18n("Invalid NTP server !");?>");
			return false;
		}
		OBJ("sync_msg").innerHTML = "<?echo i18n("Synchronizing ...");?>";

		this.PreClickSync();
		
		var ajaxObj = GetAjaxObj("NTPUpdate");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function (xml) { ajaxObj.release(); PAGE.GetCurrentStatus(); }
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("service.cgi", "SERVICE=DEVICE.TIME&ACTION=RESTART");
	},
	
	GetSyncTimeStatus: function()
	{
		COMM_GetCFG(false, "RUNTIME.TIME", PAGE.UpdateSyncTime);
	},

	UpdateSyncTime: function(xml)
	{
		var rt = xml.GetPathByTarget("/postxml", "module", "service", "RUNTIME.TIME", false);
				
		if (rt != "")
		{
						
			var syncState = xml.Get(rt+"/runtime/device/timestate");

			switch (syncState)
			{
			case "SUCCESS":
				OBJ("st_time").innerHTML = xml.Get(rt+"/runtime/device/uptime");
				OBJ("sync_pc_msg").innerHTML = "<?echo i18n("");?>";
				break;
			case "RUNNING":
				OBJ("sync_pc_msg").innerHTML = "<?echo i18n("Synchronizing ...");?>";
				setTimeout("PAGE.GetSyncTimeStatus();", 1000);
				break;
			default:
				break;
			}
		}
	},
	
	onClickManualSync: function()
	{
		OBJ("sync_pc_msg").innerHTML = "<?echo i18n("Synchronizing ...");?>";
		this.PreClickSync();
		var ajaxObj = GetAjaxObj("SyncPC");
		ajaxObj.createRequest();
		ajaxObj.onCallback = function (xml) { ajaxObj.release(); PAGE.GetSyncTimeStatus(); }
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("service.cgi", "SERVICE=DEVICE.TIME&ACTION=RESTART");
	},
	
	OnClickNtpEnb: function()
	{
		this.NtpEnDiSomething();
	},

	DrawDayMenu: function()
	{
		var old_day_value = S2I(OBJ("day").value);

		var year = S2I(OBJ("year").value);
		var mon  = S2I(OBJ("month").value);
		var days = this.GetDaysInMonth(year, mon);
		for (var i=0;i<days;i++)
		{
			OBJ("day").options[i]=new Option(i+1, i+1);
		}
		OBJ("day").length=days;

		if( days>=old_day_value ) OBJ("day").value=old_day_value;
	},
	OnChangeMonth: function()	{ this.DrawDayMenu(); },
	OnChangeYear:  function()	{ this.DrawDayMenu(); }
}
</script>
