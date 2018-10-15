/* generate the radom string with specific length. */
function COMM_RandomStr(len)
{
	var c = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	var str = '';
	for (var i = 0; i < len; i+=1)
	{
		var rand_char = Math.floor(Math.random() * c.length);
		str += c.substring(rand_char, rand_char + 1);
	}
	return str;
}

function COMM_ChangePic(obj, pic)
{
	if (COMM_GetObj(obj) != false) COMM_GetObj(obj).src = pic;
}

function COMM_GetObj(id)
{
	if		(document.getElementById)	return document.getElementById(id);//.style;
	else if	(document.all)				return document.all[id].style;
	else if	(document.layers)			return document.layers[id];
	else								return false;
}

function COMM_EatAllSpace(str)
{
	var space = str.indexOf(" ");
	while (space != -1)
	{
		str = str.replace(" ", "");
		space = str.indexOf(" ");
	}
	return str;
}

function COMM_SetSelectValue(obj, value)
{
	for (var i=0; i < obj.length; i+=1)
		if (obj[i].value == value)
		{
			obj.selectedIndex = i;
			break;
		}
	return obj.selectedIndex;
}

function COMM_ToBOOL(val)
{
	if (val==null) return false;
	switch (typeof(val))
	{
	case 'boolean': return val;
	case 'string':	return (val=="true" || val=="TRUE" || val=="1" || val=="on") ? true:false;
	case 'number':	return (val == 1) ? true:false;
	}
	alert("COMM_ToBOOL: unsupported type "+typeof(val));
	return false;
}

function COMM_ToSTRING(val)
{
	if (val==null) return "";
	switch (typeof(val))
	{
	case 'boolean': return val ? "true":"false";
	case 'string':	return val;
	case 'number':	return val+"";
	}
	alert("COMM_ToSTRING: unsupported type "+typeof(val));
	return null;
}

function COMM_ToNUMBER(val)
{
	if (val==null) return 0;
	switch (typeof(val))
	{
	case 'boolean': return val ? 1 : 0;
	case 'string':	return parseInt(val, 10);
	case 'number':	return val;
	}
	alert("COMM_ToNUMBER: unsupported type "+typeof(val));
	return -1;
}

function COMM_EqBOOL(val1, val2)
{
	return (COMM_ToBOOL(val1) == COMM_ToBOOL(val2)) ? true:false;
}

function COMM_EqSTRING(val1, val2)
{
	return (COMM_ToSTRING(val1) == COMM_ToSTRING(val2)) ? true:false;
}

function COMM_EqNUMBER(val1, val2)
{
	return (COMM_ToNUMBER(val1) == COMM_ToNUMBER(val2)) ? true:false;
}

function COMM_Equal(val1, val2)
{
	if (typeof(val1)=='boolean' || typeof(val2)=='boolean')	return COMM_EqBOOL(val1, val2);
	if (typeof(val1)=='number'  || typeof(val2)=='number')	return COMM_EqNUMBER(val1, val2);
	return COMM_EqSTRING(val1, val2);
}

function COMM_DirtyCheckSetup()
{
	for (var i = 0; i < document.forms.length; i+=1)
	{
		var frmObj = document.forms[i];
		if (frmObj.getAttribute("modified") == "ignore") continue;

		for (var idx = 0; idx < frmObj.elements.length; idx+=1)
		{
			var obj = frmObj.elements[idx];
			if (obj.getAttribute("modified") == "ignore") continue;

			var name = obj.tagName.toLowerCase();
			if (name == "input")
			{
				var type = obj.type.toLowerCase();
				if (type == "text" || type == "textarea" || type == "password" || type == "hidden")
				{
					obj.setAttribute("default", obj.value);
					/* Workaround for FF error when calling focus() from an input text element. */
					if (type == "text") obj.setAttribute("autocomplete", "off");
				}
				else if (type == "checkbox" || type == "radio")
				{
					obj.setAttribute("default", obj.checked);
				}
				obj.setAttribute("modified", false);
			}
			else if (name == "select")
			{
				obj.setAttribute("default", obj.selectedIndex);
				obj.setAttribute("modified", false);
			}
		}
		frmObj.setAttribute("modified", false);
	}
}

function COMM_IsDirty(IgnoreForm)
{
	var dirty = false;

	for (var i = 0; i < document.forms.length; i+=1)
	{
		var frmObj = document.forms[i];
		if (frmObj.getAttribute("modified") == "ignore") continue;
		if (IgnoreForm === true)
		{
			frmObj.setAttribute("modified", false);
		}
		else if (COMM_Equal(frmObj.getAttribute("modified"), "true"))
		{
			//alert(frmObj.id+":"+frmObj.getAttribute("modified"));
			dirty = true;
		}

		for (var idx = 0; idx < frmObj.elements.length; idx+=1)
		{
			var obj = frmObj.elements[idx];
			if (obj.disabled) continue;
			if (obj.getAttribute("modified") == "ignore") continue;

			var name = obj.tagName.toLowerCase();
			if (name == "input")
			{
				var type = obj.type.toLowerCase();
				if (type == "text" || type == "textarea" || type == "password" || type == "hidden")
				{
					if (!COMM_Equal(obj.getAttribute("default"), obj.value))
					{
						frmObj.setAttribute("modified", true);
						obj.setAttribute("modified", true);
						//alert("input/"+type+"/"+obj.id+":"+obj.getAttribute("default")+"/"+obj.value);
						//alert("modified="+obj.getAttribute("modified"));
						dirty = true;
					}
				}
				else if (type == "checkbox" || type == "radio")
				{
					if (!COMM_Equal(obj.getAttribute("default"), obj.checked))
					{
						frmObj.setAttribute("modified", true);
						obj.setAttribute("modified", true);
						//alert("input/"+type+"/"+obj.id+":"+obj.getAttribute("default")+"/"+obj.checked);
						dirty = true;
					}
				}
			}
			else if (name == "select")
			{
				if (!COMM_Equal(obj.getAttribute("default"), obj.selectedIndex))
				{
					frmObj.setAttribute("modified", true);
					obj.setAttribute("modified", true);
					//alert("input/"+type+"/"+obj.id+":"+obj.getAttribute("default")+"/"+obj.selectedIndex);
					dirty = true;
				}
			}
		}
	}
	return dirty;
}

function COMM_Pow(a, b)
{
	var c = 1;
	for (var i = 0; i < b; i+=1) c = c*a;
	return c;
}

/* COMM_IPv4INT2ADDR(16843009) -> "1.1.1.1" */
function COMM_IPv4INT2ADDR(val)
{
	var nums = new Array();
	var str;

	nums[3] = val % 256; val = (val-nums[3])/256;
	nums[2] = val % 256; val = (val-nums[2])/256;
	nums[1] = val % 256; val = (val-nums[1])/256;
	nums[0] = val % 256;
	str = nums[0]+"."+nums[1]+"."+nums[2]+"."+nums[3];
	return str;
}

/* COMM_IPv4ADDR2INT("1.1.1.1") -> 16843009 */
function COMM_IPv4ADDR2INT(addr)
{
	var nums;
	var vals = new Array();
	var val;

	nums = addr.split(".");
	vals[0] = (parseInt(nums[0], [10]) % 256);
	vals[1] = (parseInt(nums[1], [10]) % 256);
	vals[2] = (parseInt(nums[2], [10]) % 256);
	vals[3] = (parseInt(nums[3], [10]) % 256);
	val = vals[0];
	val = val*256 + vals[1];
	val = val*256 + vals[2];
	val = val*256 + vals[3];
	return val;
}

/* COMM_IPv4INT2MASK(24) -> "255.255.255.0" */
function COMM_IPv4INT2MASK(val)
{
	var bits = 0;
	if (val < 32) bits = COMM_Pow(2,32) - COMM_Pow(2,32-val);
	else if (val == 32) return "255.255.255.255";
	return COMM_IPv4INT2ADDR(bits);
}

function count_bits(val)
{
	for (var i = 7; i >= 0; i-=1) if ((val & COMM_Pow(2, i))==0) break;
	return 7-i;
}

/* COMM_IPv4IPADDR("192.168.0.0", 24, 20) -> "192.168.0.20" */
function COMM_IPv4IPADDR(network, mask, host)
{
	network = COMM_IPv4NETWORK(network,mask);
	var m = Math.pow(2, parseInt(32-mask, 10))-1;
	host = parseInt((host & m), 10);
	return COMM_IPv4INT2ADDR(COMM_IPv4ADDR2INT(network)+host);
}

/* COMM_IPv4NETWORK("192.168.1.1", 24) -> "192.168.1.0" */
function COMM_IPv4NETWORK(addr, mask)
{
	var addrArray = addr.split(".");
	var maskArray = COMM_IPv4INT2MASK(mask).split(".");
	var networkArray = new Array();
	var str = "";
	for (var i=0; i<4; i+=1)
	{
		if (isNaN(addrArray[i])||addrArray[i].length==0||parseInt(addrArray[i],10)>255) return "0.0.0.0";
		networkArray[i] = eval(addrArray[i] & maskArray[i]);
		str += str?"."+networkArray[i]:networkArray[i];
	}
	return str;
}

/* COMM_IPv4HOST("192.168.0.1", 24) -> "1" */
function COMM_IPv4HOST(addr, mask)
{
	var addrArray = addr.split(".");
	var maskArray = COMM_IPv4INT2MASK(mask).split(".");
	var networkArray = new Array();
	var str = "";
	for (var i=0; i<4; i+=1)
	{
		networkArray[i] = eval(addrArray[i] & ~maskArray[i]);
		str += str?"."+networkArray[i]:networkArray[i];
	}
	return COMM_IPv4ADDR2INT(str);
}

/* COMM_IPv4MAXHOST(24) -> "255" */
function COMM_IPv4MAXHOST(mask)
{
	return COMM_IPv4HOST("255.255.255.255", mask);
}

/* COMM_IPv4MASK2INT("255.255.255.0") -> "24" */
function COMM_IPv4MASK2INT(mask)
{
	var nums = mask.split(".");
	var vals = new Array();
	var bits = 0;

	vals[0] = (parseInt(nums[0], [10]) % 256);
	vals[1] = (parseInt(nums[1], [10]) % 256);
	vals[2] = (parseInt(nums[2], [10]) % 256);
	vals[3] = (parseInt(nums[3], [10]) % 256);

	bits = count_bits(vals[0]);
	if (vals[0] == 255)
	{
		bits += count_bits(vals[1]);
		if (vals[1] == 255)
		{
			bits += count_bits(vals[2]);
			if (vals[2] == 255) bits += count_bits(vals[3]);
		}
	}
	if (mask != COMM_IPv4INT2MASK(bits)) return -1;
	return bits;
}

/* Convert int type seconds to readable time interval.
** input	: int type second
** return	: Array["day"]	= int days
**            Array["hour"]	= int hours
**            Array["min"]	= int minutes
**            Array["sec"]	= int seconds
*/
function COMM_SecToStr( secs )
{
	if( secs == "" )
		secs = 0;
	var str = new Array();
	str["day"]	= Math.round(secs/(24*60*60) - 0.5);
	str["hour"]	= Math.round((secs%(24*60*60))/(60*60) - 0.5 );
	str["min"]	= Math.round(((secs%(24*60*60))%(60*60))/60 - 0.5);
	str["sec"]	= ((secs%(24*60*60))%(60*60))%60;
	return str;
}
/* get the current config from xmldb
 *	Cache    : boolean, get the config from session cache or not.
 *	Services : comma seperated service name.
 *	Handler  : the callback function to hanlder the xml data. */
function COMM_GetCFG(Cache, Services, Handler)
{
	var ajaxObj = GetAjaxObj("getData");
	var payload = "";

	if (Cache) payload = "CACHE=true";
	if (payload!="") payload += "&";
	payload += "SERVICES="+escape(COMM_EatAllSpace(Services));

	ajaxObj.createRequest();
	ajaxObj.onCallback = function (xml)
	{
		ajaxObj.release();
		if (Handler!=null) Handler(xml);
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("getcfg.php", payload);
}

/* submit the config to hedwig, if the result is OK then calling CallPigwidgeon() */
function COMM_CallHedwig(xml, resultCallback)
{
	var ajaxObj = GetAjaxObj("setData");
	ajaxObj.createRequest();
	ajaxObj.onCallback =
	function (xml)
	{
		ajaxObj.release();
		resultCallback(xml);
	}
	ajaxObj.setHeader("Content-Type", "text/xml");
	ajaxObj.sendRequest("hedwig.cgi", xml.XDoc);
}

/* submit the action type to pigwidgeon.cgi for caching(saving) config or restarting service */
function COMM_CallPigwidgeon(Actions, resultCallback)
{
	var ajaxObj = GetAjaxObj("pigwidgeon");
	var payload = "ACTIONS=" + escape(COMM_EatAllSpace(Actions));
	ajaxObj.createRequest();
	ajaxObj.onCallback =
	function (xml)
	{
		ajaxObj.release();
		resultCallback(xml);
	}
	ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
	ajaxObj.sendRequest("pigwidgeon.cgi", payload);
}

function COMM_AddEntry(xml, path, prefix)
{
	var seqno = parseInt(xml.Get(path+"/seqno"), 10);
	var count = parseInt(xml.Get(path+"/count"), 10);

	xml.Set(path+"/seqno", seqno+1);
	count+=1;
	xml.Set(path+"/count", count);
	xml.Set(path+"/entry:"+count+"/uid", prefix+seqno);
	return path+"/entry:"+count;
}

function COMM_DelEntry(xml, path, uid)
{
	var count = parseInt(xml.Get(path+"/count"), 10);
	var entry = xml.GetPathByTarget(path, "entry", "uid", uid, 0);
	if (entry != "")
	{
		count -= 1;
		xml.Del(entry);
		xml.Set(path+"/count", count);
	}
}

function COMM_SetOpacity(obj, value)
{
	if (value == 1)
	{
		obj.style.opacity =
			(/Gecko/.test(navigator.userAgent) &&
			!/Konqueror|Safari|KHTML/.test(navigator/userAgent)) ?
			0x999999 : null;
		if (/MSIE/.test(navigator.userAgent))
		{
			var str = obj.style.filter;
			obj.style.filter = str.replace(/alpha\([^\)]*\)/gi,'');
		}
	}
	else
	{
		if (value < 0.00001) value = 0;
		obj.style.opacity = value;
		if (/MSIE/.test(navigator.userAgent))
		{
			var str = obj.style.filter;
			obj.style.filter = str.replace(/alpha\([^\)]*\)/gi,'');
			obj.style.filter += 'alpha(opacity='+value*100+')';
		}
	}
}

function COMM_GetStyle(obj)
{
	var attr = "class";
	if (/MSIE 5/.test(navigator.userAgent) ||
		/MSIE 6/.test(navigator.userAgent) ||
		/MSIE 7/.test(navigator.userAgent)) attr = "className";
	return obj.getAttribute(attr);
}

function COMM_SetStyle(obj, value)
{
	var attr = "class";
	if (/MSIE 5/.test(navigator.userAgent) ||
		/MSIE 6/.test(navigator.userAgent) ||
		/MSIE 7/.test(navigator.userAgent)) attr = "className";
	obj.setAttribute(attr, value);
}

function COMM_Event2Key(e)
{
	var keynum = 0;
	if (window.event) keynum = e.keyCode; // IE
	else if (e.which) keynum = e.which;   // Netscape/Firefox/Opera
	return keynum;
}

function COMM_EscapeHTMLSC(str)
{
	str = str.replace(/&/g,"&amp;");
	str = str.replace(/</g,"&lt;");
	str = str.replace(/>/g,"&gt;");
	str = str.replace(/"/g,"&quot;");
	str = str.replace(/'/g,"&acute;");
	str = str.replace(/ /g,"&nbsp;");
	return str;
}

function COMM_IsInteger(str)
{
	var y = parseInt(str);
	if (isNaN(y)) return false;
	return str===y.toString();
}
