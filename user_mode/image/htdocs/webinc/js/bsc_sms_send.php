<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "SMS",
	OnLoad: function(){},
	OnUnload: function() {},
	OnSubmitCallback: function(code, result)	
	{
		if(code == "OK")
		{
			getstatus();
			return true;
		}
		else
		{
			return false;
		}
	},
	InitValue: function(xml)
	{
		PXML.doc = xml;
		return true;
	},
	PreSubmit: function()
	{
		var sms = PXML.FindModule("SMS");
		XS(sms+ "/runtime/callmgr/voice_service/mobile/sms/send_state",1);

		XS(sms+ "/runtime/callmgr/voice_service/mobile/sms/send_coding_method",		escape(OBJ("sms_content").value).indexOf("%u") < 0 ?0:2);
		XS(sms+	"/runtime/callmgr/voice_service/mobile/sms/send_address",		OBJ("receiver").value);
		XS(sms+	"/runtime/callmgr/voice_service/mobile/sms/send_content",		Unicode(OBJ("sms_content").value));
		
		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {}

	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
}

var check5 = 0;
function getstatus()
{
	var ajaxObj = GetAjaxObj("SERVICE");
	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		if(xml.Get("/status/code") == "1")
                {
			if(check5 < 20)
                	{
                    		check5++;
                    		setTimeout('getstatus()',1000);
                	}
                	else
                	{
                    		check5 = 0;
                		alert('<?echo i18n("Send SMS message timeout,please try again later");?>');
				BODY.ShowContent();
			}			
                }
                else if(xml.Get("/status/code") == "2")
                {
			check5 = 0;
			alert('<?echo i18n("Send SMS message successfully.");?>');

			OBJ("receiver").value = "";
			OBJ("sms_content").value = "";
			OBJ("current_quantity").value = "";
			OBJ("maximum").value = "";
	
			BODY.ShowContent();
                }
                else if(xml.Get("/status/code") == "3")
                {
			check5 = 0;
                        alert('<?echo i18n("Send SMS message unsuccessfully,please try again later");?>');
			BODY.ShowContent();
                }

        }
        ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxObj.sendRequest("check_stats.php","CHECK_NODE=/runtime/callmgr/voice_service/mobile/sms/send_state");
}

function Unicode(s)
{ 
	var len=s.length; 
	var rs="";
	var tmp=""; 
	for(var i=0;i<len;i++)
	{ 
		var k=s.substring(i,i+1);
		tmp = (s.charCodeAt(i)).toString(16);
		if(escape(OBJ("sms_content").value).indexOf("%u") < 0)
		{ rs+= tmp; }
		else
		{
	  		if(tmp.length == 2)
	  		{
	  			tmp = "00" + tmp;
	  		}
	  		else if(tmp.length == 3)
	  		{
	  			tmp = "0" + tmp;
	  		}
      			rs+= tmp; 
		}
   	} 
   	return rs; 
}

function onmyinput(o)
{
	if(escape(o.value).indexOf('%u') <0)
	{ 
		if(o.value.length > 511)
		{
			o.value=(o.value).substring(0,511);
		}
		if(o.value.length == 0)
		{
			OBJ("maximum").value = "";
                        OBJ("current_quantity").value = "";
		}
		else
		{
			OBJ("maximum").value = 511;
			OBJ("current_quantity").value = o.value.length;
		}
	}
	else
	{
		if(o.value.length > 255)
		{
			o.value=(o.value).substring(0,255);
		}
		if(o.value.length == 0)
                {
                        OBJ("maximum").value = "";
                        OBJ("current_quantity").value = "";
                }
                else
                {
                        OBJ("maximum").value = 255;
                        OBJ("current_quantity").value = o.value.length;
                }
	}
}
</script>
