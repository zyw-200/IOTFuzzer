<? /* vi: set sw=4 ts=4: */
echo $G_TAG_SCRIPT_START."\n";
?>
// ************************ ip.js start *************************************
// convert to MAC address string to array.
// myMAC[0] contains the orginal ip string. (dot spereated format).
// myMAC[1~6] contain the 6 parts of the MAC address.
function get_mac(m)
{
	var myMAC=new Array();
	if (m.search(":") != -1)    var tmp=m.split(":");
	else                        var tmp=m.split("-");

	for (var i=0;i <= 6;i++) myMAC[i]="";
	if (m != "")
	{
		for (var i=1;i <= tmp.length;i++) myMAC[i]=tmp[i-1];
		myMAC[0]=m;
	}
	return myMAC;
}

// convert to ip address string to array.
// myIP[0] contains the orginal ip string. (dot spereated format).
// myIP[1~4] contain the 4 parts of the ip address.
function get_ip(str_ip)
{
	var myIP=new Array();

	myIP[0] = myIP[1] = myIP[2] = myIP[3] = myIP[4] = "";
	if (str_ip != "")
	{
		var tmp=str_ip.split(".");
		for (var i=1;i <= tmp.length;i++) myIP[i]=tmp[i-1];
		myIP[0]=str_ip;
	}
	else
	{
		for (var i=0; i <= 4;i++) myIP[i]="";
	}
	return myIP;
}

function get_ipv6(str_ip)
{
	var myIP=new Array();

	myIP[0] = myIP[1] = myIP[2] = myIP[3] = myIP[4] = myIP[5] = myIP[6] = myIP[7] = myIP[8] = "";
	if(str_ip != "")
	{
		var tmp=str_ip.split(":");	
		for (var i=1;i <= tmp.length;i++) myIP[i]=tmp[i-1];
		myIP[0]=str_ip;
	}
	else
	{
		for (var i=0; i <= 8;i++) myIP[i]="";
	}
	return myIP;	
}	

// return netmask array according to the class of the ip address.
function generate_mask(str)
{
	var mask = new Array();
	var IP1 = decstr2int(str);

	mask[0] = "0.0.0.0";
	mask[1] = mask[2] = mask[3] = mask[4] = "0";

	if		(IP1 > 0 && IP1 < 128)
	{
		mask[0] = "255.0.0.0";
		mask[1] = "255";
	}
	else if	(IP1 > 127 && IP1 < 191)
	{
		mask[0] = "255.255.0.0";
		mask[1] = "255";
		mask[2] = "255";
	}
	else
	{
		mask[0] = "255.255.255.0";
		mask[1] = "255";
		mask[2] = "255";
		mask[3] = "255";
	}
	return mask;
}

// construct a IP array
function generate_ip(str1, str2, str3, str4)
{
	var ip = new Array();

	ip[1] = (str1=="") ? "0" : decstr2int(str1.value);
	ip[2] = (str2=="") ? "0" : decstr2int(str2.value);
	ip[3] = (str3=="") ? "0" : decstr2int(str3.value);
	ip[4] = (str4=="") ? "0" : decstr2int(str4.value);
	ip[0] = ip[1]+"."+ip[2]+"."+ip[3]+"."+ip[4];
	return ip;
}

// return IP array of network ID
function get_network_id(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] & subnet[1];
	id[2] = ipaddr[2] & subnet[2];
	id[3] = ipaddr[3] & subnet[3];
	id[4] = ipaddr[4] & subnet[4];
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

// return IP array of host ID
function get_host_id(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] & (subnet[1] ^ 255);
	id[2] = ipaddr[2] & (subnet[2] ^ 255);
	id[3] = ipaddr[3] & (subnet[3] ^ 255);
	id[4] = ipaddr[4] & (subnet[4] ^ 255);
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

// return IP array of Broadcast IP address
function get_broadcast_ip(ip, mask)
{
	var id = new Array();
	var ipaddr = get_ip(ip);
	var subnet = get_ip(mask);

	id[1] = ipaddr[1] | (subnet[1] ^ 255);
	id[2] = ipaddr[2] | (subnet[2] ^ 255);
	id[3] = ipaddr[3] | (subnet[3] ^ 255);
	id[4] = ipaddr[4] | (subnet[4] ^ 255);
	id[0] = id[1]+"."+id[2]+"."+id[3]+"."+id[4];
	return id;
}

function is_valid_port_str(port)
{
	return is_in_range(port, 1, 65535);
}

// return true if the port is valid.
function is_valid_port(obj)
{
	if (is_valid_port_str(obj.value)==false)
	{
		field_focus(obj, '**');
		return false;
	}
	return true;
}

function is_valid_port_range_str(port1, port2)
{
	if (is_blank(port1)) return false;
	if (!is_valid_port_str(port1)) return false;
	if (is_blank(port2)) return true;
	if (!is_valid_port_str(port2)) return false;
	var i = parseInt(port1, [10]);
	var j = parseInt(port2, [10]);
	if (i > j) return false;
	return true;
}

// return true if the port range is valid.
function is_valid_port_range(obj1, obj2)
{
	return is_valid_port_range_str(obj1.value, obj2.value);
}

// return true if the IP address is valid.
function is_valid_ip(ipaddr, optional)
{
	var ip = get_ip(ipaddr);

	if (optional != 0)
	{
		if (ip[1]=="" && ip[2]=="" && ip[3]=="" && ip[4]=="") return true;
	}

	if (is_in_range(ip[1], 1, 223)==false) return false;
	if (decstr2int(ip[1]) == 127) return false;
	if (is_in_range(ip[2], 0, 255)==false) return false;
	if (is_in_range(ip[3], 0, 255)==false) return false;
	if (is_in_range(ip[4], 1, 254)==false) return false;

	ip[0] = parseInt(ip[1],[10])+"."+parseInt(ip[2],[10])+"."+parseInt(ip[3],[10])+"."+parseInt(ip[4],[10]);
	if (ip[0] != ipaddr) return false;

	return true;
}

function is_valid_ip3(ipaddr, optional)
{
    var ip = get_ip(ipaddr);

    if (optional != 0)
    {
        if (ip[1]=="" && ip[2]=="" && ip[3]=="" && ip[4]=="") return true;
    }

    if (is_in_range(ip[1], 1, 223)==false) return false;
    if (decstr2int(ip[1]) == 127) return false;
    if (is_in_range(ip[2], 0, 255)==false) return false;
    if (is_in_range(ip[3], 0, 255)==false) return false;
    if (is_in_range(ip[4], 0, 254)==false) return false;

    ip[0] = parseInt(ip[1],[10])+"."+parseInt(ip[2],[10])+"."+parseInt(ip[3],[10])+"."+parseInt(ip[4],[10]);
    if (ip[0] != ipaddr) return false;

    return true;
}

function invalid_ip_mask(ipaddr, mask)
{
    var tmp_ip=get_ip(ipaddr);
    var tmp_mask=get_ip(mask);

    var sub=new Array();
    var tmp_sub=new Array();
    for(var i=1; i<5; i++)
    {
        sub[i]=tmp_ip[i] & tmp_mask[i];
        tmp_sub[i]=sub[i] | tmp_mask[i];
    }

    var tmp_sub_mask = tmp_sub[1]+"."+tmp_sub[2]+"."+tmp_sub[3]+"."+tmp_sub[4];
    if(tmp_sub_mask != mask)
    {
        return false;
    }

    var dirty=0;
    var dirty2=0;
    for(var i=1; i<5; i++)
    {
        if((sub[i] ^ tmp_mask[i]) == 0)
        {
            dirty++;
        }
        if((sub[i] & tmp_mask[i]) == 0)
        {
            dirty2++;
        }
    }
    if(dirty == 4 || dirty2 == 4){return false;}

    var tmp_i_m=new Array();
    for(var i=1; i<5; i++)
    {
        tmp_i_m[i] =tmp_ip[i] | tmp_mask[i];
    }
    var ip_or_mask = tmp_i_m[1]+"."+tmp_i_m[2]+"."+tmp_i_m[3]+"."+tmp_i_m[4];
    if(ip_or_mask == mask || ip_or_mask == "255.255.255.255"){return false;}
}

function is_valid_ipv6(ipaddr, optional)
{
	var tmp_sign=0;
	var tmp_2sign=0;
	var with_ipv4=0;
	var with_prefix=0;
	var sub_ip,sub_dec_ip;

	if (ipaddr == "::")
	{
		if(optional == 1){return true;} // "::" is valid
		else {return false;}
	}

	for(var m=0; m < ipaddr.length; m++)  
	{
		if(ipaddr.charAt(m) == ":")
		{
			tmp_sign++;
			if(m < ipaddr.length-1 && ipaddr.charAt(m+1) == ":"){tmp_2sign++;}  //whether has "::"
		}
		if(ipaddr.charAt(m) == "."){with_ipv4++;}

		if(ipaddr.charAt(m) != ":" && ipaddr.charAt(m) != ".")	//charactors must 0~9,A~F,a~f
		{
			if((ipaddr.charCodeAt(m) < 48 || ipaddr.charCodeAt(m) > 57) && (ipaddr.charCodeAt(m) < 65 || ipaddr.charCodeAt(m) > 70) && (ipaddr.charCodeAt(m) < 97 || ipaddr.charCodeAt(m) > 102)) return false;
		}
	}

	if((ipaddr.charAt(0)=="F" || ipaddr.charAt(0)=="f") && (ipaddr.charAt(1)=="F" || ipaddr.charAt(1)=="f")) return false;	//multicast address:start with FF

	if(tmp_sign < 2 || tmp_2sign > 1) return false;
	if((ipaddr.charAt(0)==":" && ipaddr.charAt(1)!=":") || (ipaddr.charAt(ipaddr.length-1)==":" && ipaddr.charAt(ipaddr.length-2)!=":")) return false;
	if(with_ipv4 == 0)	
	{
		if(tmp_sign > 7 || (tmp_sign < 7 && tmp_2sign == 0)) return false;
	}
	else
	{
		if(tmp_sign > 6 || (tmp_sign < 6 && tmp_2sign == 0)) return false;	
	}

	//get ip
	var myIP = new Array();
	myIP[1]=myIP[2]=myIP[3]=myIP[4]=myIP[5]=myIP[6]=myIP[7]=myIP[8]="";
	var tmp=ipaddr.split(":");
	for (var i=1;i <= tmp.length;i++) myIP[i]=tmp[i-1];
	var valid_flag = 0;

	if(with_ipv4 == 0) //x:x:x:x:x:x:x:x
	{
		for(var i=1; i <= tmp.length; i++)  //whether each myIP[] is valid
		{
			if(myIP[i] != "")
			{
				if(myIP[i].length > 4) return false;
				sub_ip=eval("myIP["+i+"]");
				sub_dec_ip=hexstr2int(sub_ip);
				if(sub_dec_ip > 65535 || sub_dec_ip < 0) return false;
			}
		}
		for(var i=1; i <= tmp.length-1; i++) //all 0 and 0:...:0:1 is not valid
		{
			if(myIP[i] != "" && myIP[i] != "0" && myIP[i] != "00" && myIP[i] != "000" && myIP[i] != "0000") {valid_flag++;}
		}
		if(valid_flag == 0)
		{
			if(myIP[tmp.length] == "" || myIP[tmp.length] == "0" || myIP[tmp.length] == "00" || myIP[tmp.length] == "000" || myIP[tmp.length] == "0000" || myIP[tmp.length] == "1" || myIP[tmp.length] == "01" || myIP[tmp.length] == "001" || myIP[tmp.length] == "0001")
			{return false;}
		}
	}
	else   //x:x:x:x:x:x:d.d.d.d
	{
		for(var i=1; i < tmp.length; i++)  //whether each myIP[] is valid except the last one	
		{
			if(myIP[i] != "")
			{
				if(myIP[i].length > 4) return false;
				sub_ip=eval("myIP["+i+"]");
				sub_dec_ip=hexstr2int(sub_ip);
				if(sub_dec_ip > 65535 || sub_dec_ip < 0) return false;
			}
		}	
		if(is_valid_ip(myIP[tmp.length], 0)==false) return false;
	}
	return true;
}

// return false if the value is not a valid netmask value.
function __is_mask(str)
{
	d = decstr2int(str);
	if (d==0 || d==128 || d==192 || d==224 || d==240 || d==248 || d==252 || d==254 || d==255) return true;
	return false;
}

// return true if the netmask is valid.
function is_valid_mask(mask)
{
	var IP = get_ip(mask);

	if (__is_mask(IP[1])==false) return false;
	if (IP[1] != "255")
	{
		if (IP[2]=="0" && IP[3]=="0" && IP[4]=="0") return true;
		return false;
	}

	if (__is_mask(IP[2])==false) return false;
	if (IP[2] != "255")
	{
		if (IP[3]=="0" && IP[4]=="0") return true;
		return false;
	}

	if (__is_mask(IP[3])==false) return false;
	if (IP[3] != "255")
	{
		if (IP[4]=="0") return true;
		return false;
	}

	if (__is_mask(IP[4])==false) return false;
	return true;
}

function is_valid_mac(mac)
{
	return is_hexdigit(mac);
}

function is_valid_mac_str(mac)
{
	var tmp_mac=get_mac(mac);
	var cmp_mac="";
	var cmp_mac1="";
	var i, sub_mac, sub_dec_mac;
	for(i=1;i<=6;i++)
	{
		sub_mac=eval("tmp_mac["+i+"]");
		sub_dec_mac=hexstr2int(sub_mac);
		if(sub_dec_mac>255 ||sub_dec_mac<0)	return false;
		else if(sub_dec_mac<=15)
		{
			cmp_mac +="0"+sub_dec_mac.toString(16);
			cmp_mac1+="0"+sub_dec_mac.toString(16);
		}
		else
		{
			cmp_mac +=sub_dec_mac.toString(16);
			cmp_mac1+=sub_dec_mac.toString(16);
		}
		if(i!=6)
		{
			cmp_mac +=":";
			cmp_mac1+="-";
		}
	}
	if(cmp_mac!=mac.toLowerCase() && cmp_mac1!=mac.toLowerCase())	return false;
	return true;
}

function macCheck(str)
{
	// type 1 xx:xx:xx:xx:xx:xx
	// type 2 xx xx xx xx xx xx
	var len = str.length;
	var substring1 = str.split(":");
	//var substring2 = new Array(str.substring(0,2),str.substring(3,5),str.substring(6,8),
	//                           str.substring(9,11),str.substring(12,14),str.substring(15,17));
	var substring2 = str.split(" ");
	var len1 = substring1.length;
	var len2 = substring2.length;

	if(len == 0)
	{
		//alert("The MAC is empty.");
		return false;
	}
	else
	{
		if(len1 == 6)
		{
			for(var i=0 ; i<6 ; i++)
			{
				//alert(substring1[i]);
				if(!HexCheck2(substring1[i]))
				{
					//alert("false1");
					return false;
				}
			}
			return true;
		}

		if(len2 == 6)
		{
			for(var i=0 ; i<6 ; i++)
			{
				if(!HexCheck2(substring2[i]))
				{
					//alert("false2");
					return false;
				}
			}
			return true;
		}
		//alert(false);
		return false;
	}
}

////////////////////////////////////////////////////////////////////
// not accept ip 255.255.255.255
//////////////////////////////////////////////////////////////////////
function is_valid_ip2(textValue)
 {

       var i,j=0;
       for(i=0;i<textValue.length;i++)
       {
           ch=textValue.charAt(i);
           if(ch==".")
              j++;
        }
            if(j!=3)
             return false;       // the number of '.' must be three

       ipSplit=textValue.split('.');

       if(ipSplit.length!=4) return false;   //// the number of ipSplit must be four

       for(i=0; i<ipSplit.length; i++)
       {
          if(isNaN(ipSplit[i]) || ipSplit[i]==null || ipSplit[i]==""|| ipSplit[i]==" ")
          return false;
        } //check if ipSplit[i] is invaild number

       for(i=0; i<ipSplit.length; i++)
       {
         if(ipSplit[i]>255)  return false;
       }
       if(ipSplit[0]==255 && ipSplit[1]==255 && ipSplit[2]==255 && ipSplit[3]==255)  return false; // case 2

       return true;
 }
// *********************************** ip.js stop *************************************
<?
echo $G_TAG_SCRIPT_END."\n";
?>
