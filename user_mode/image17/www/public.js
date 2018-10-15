var subnet_mask = new Array(0, 128, 192, 224, 240, 248, 252, 254, 255);
var key_num_array = new Array("64", "128");
var Week = new Array(get_words('_Sun'), get_words('_Mon'), get_words('_Tue'), get_words('_Wed'), get_words('_Thu'), get_words('_Fri'), get_words('_Sat'));

function rule_obj(name, prot, public_port, private_port){
	this.name = name;
	this.prot = prot;		// TCP, UDP
	this.public_port = public_port;
	this.private_port = private_port;
}

function appl_obj(name, trigger_prot, trigger_port, public_prot, public_port){
	this.name = name;
	this.trigger_prot = trigger_prot;		// TCP, UDP
	this.trigger_port = trigger_port;
	this.public_prot = public_prot;
	this.public_port = public_port;
}

//20130121 pascal add for QoS - Application
function Qosappl_obj(id, name, desc, tcp, udp){
	this.id = id;
	this.name = name;		
	this.desc = desc;
	this.tcp = tcp;
	this.udp = udp;
}

function set_application_option(obj_value, obj_array){
	for (var i = 0; i < obj_array.length; i++){
		var temp_rule = obj_array[i];
		obj_value += "<option>" + temp_rule.name + "</option>";
	}
	return obj_value;
}

function addr_obj(addr, e_msg, allow_zero, is_network){
	this.addr = addr;
	this.e_msg = e_msg;
	this.allow_zero = allow_zero;
	this.is_network = is_network;
}

function isp_obj(isp_name, dial_number, apn, username, password){
	this.isp_name = isp_name;
	this.dial_number = dial_number;		
	this.apn = apn;
	this.username = username;
	this.password = password;
}

function set_isp_option(obj_value, obj_array){
	for (var i = 0; i < obj_array.length; i++){
		var temp_isp = obj_array[i];
		obj_value += "<option>" + temp_isp.isp_name + "</option>";
}
	return obj_value;
}

function varible_obj(var_value, e_msg, min, max, is_even){
	this.var_value = var_value;
	this.e_msg = e_msg;
	this.min = min;
	this.max = max;
	this.is_even = is_even;
}

function raidus_obj(ip, port, secret){
	this.ip = ip;
	this.port = port;
	this.secret = secret;
}

function ip4_obj(ip, min_range, max_range, e_msg1, e_msg2){
	this.ip = ip;
	this.min_range = min_range;
	this.max_range = max_range;
	this.e_msg1 = msg[e_msg1];
	this.e_msg2 = msg[e_msg2];
}
function change_wan(){
    var html_file;
	var value = get_by_id("wan_proto").options[get_by_id("wan_proto").selectedIndex].value;
	switch(value){
		case '0' :
	    	html_file = "wan_static.asp";
	    	break;	   	
		case '1' :
	    	html_file = "wan_dhcp.asp";
	    	break;
		case '2' :
	    	html_file = "wan_poe.asp";
	    	break;
	    case '3' :
	    	html_file = "wan_pptp.asp";
	    	break;
		case '4' :
			html_file = "wan_l2tp.asp";
	    	break
	    case '5' :
			html_file = "wan_3G.asp";
	    	break;
	    case '10' :
			html_file = "wan_dslite.asp";
	    	break;
	}
	setTimeout(function() {
		location.href = html_file;
	}, 0);
}

function change_filter(which_filter){
    var html_file;

    switch(which_filter){
		case 0 :
	    	html_file = "adv_filters.asp";
	    	break;
		case 1 :
	    	html_file = "adv_filters_mac.asp";
	    	break;
		case 2 :
	    	html_file = "adv_filters_url.asp";
	    	break;
		case 3 :
	    	html_file = "adv_filters_domain.asp";
	    	break;
	}
	
	setTimeout(function() {
		location.href = html_file;
	}, 0);
}

function change_routing(which_routing){
    var html_file;

    switch(which_routing){
        case 0 :
            html_file = "adv_routing.asp";
            break;
        case 1 :
            html_file = "adv_routing_dynamic.asp";
            break;
        case 2 :
            html_file = "adv_routing_table.asp";
            break;
    }

	setTimeout(function() {
		location.href = html_file;
	}, 0);
}

function check_network_address(my_obj, mask_obj){
	var count_zero = 0;
	var ip = my_obj.addr;
	var mask;
	var allow_cast = false;

	if (my_obj.addr.length == 4){
		// check the ip is not multicast IP (127.x.x.x && 224.x.x.x ~ 239.x.x.x)
		if(my_obj.addr[0] == "127"){
			alert(my_obj.e_msg[MULTICASE_IP_ERROR]);
			return false;
		}

		// check the ip is "0.0.0.0" or not
		for(var i = 0; i < ip.length; i++){
			if (ip[i] == "0"){
				count_zero++;
			}
		}

		if (!my_obj.allow_zero && count_zero == 4){	// if the ip is not allowed to be 0.0.0.0
			alert(my_obj.e_msg[ZERO_IP]);			// but we check the ip is 0.0.0.0
			return false;
		}else if (count_zero != 4){		// when IP is not 0.0.0.0, checking range. Otherwise no need to check
				mask = mask_obj.addr;
				for(var i = 0; i < mask.length; i++){
					if (mask[i] != "255"){
					if (ip[i] != (mask[i] & ip[i])){
						alert(my_obj.e_msg[FIRST_RANGE_ERROR + i] + (mask[i] & ip[i]));
						return false;
					}
				}
			}
		}
	}else{	// if the length of ip is not correct, show invalid ip msg
		alert(my_obj.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}

function check_domain(ip, mask, gateway){
	var temp_ip = ip.addr;
	var temp_mask = mask.addr;
	var temp_gateway = gateway.addr;
	var temp_str = "";

	for (var i = 0; i < 4; i++){
		temp_str += temp_gateway[i];

		if (i < 3){
			temp_str += ".";
		}
	}

	if (gateway.allow_zero && (temp_str == "0.0.0.0" || temp_str == "...")){
		return true;
	}

	for (var i = 0; i < temp_ip.length - 1; i++){
		if ((temp_ip[i] & temp_mask[i]) != (temp_gateway[i] & temp_mask[i])){
			return false;		// when not in the same subnet mask, return false
		}
	}

	return true;
}

function check_ip_order(start_ip, end_ip){
	var temp_start_ip = start_ip.addr;
	var temp_end_ip = end_ip.addr;
	var total1 = ip_num(temp_start_ip);
	var total2 = ip_num(temp_end_ip);

    if(total1 > total2)
        return false;
	return true;
}

function check_lanip_order(ip,start_ip, end_ip){
	var temp_start_ip = start_ip.addr;
	var temp_end_ip = end_ip.addr;
	var temp_ip = ip.addr;
	var total1 = ip_num(temp_start_ip);
	var total2 = ip_num(temp_end_ip);
    var total3 = ip_num(temp_ip);
    if(total1 <= total3 && total3 <= total2)
         return true;
	return false;
}

function check_resip_order(reserved_ip,start_ip, end_ip){
	var temp_start_ip = start_ip.addr;
	var temp_end_ip = end_ip.addr;
	var temp_res_ip = reserved_ip.addr;
	var total1 = ip_num(temp_start_ip);
	var total2 = ip_num(temp_end_ip);
    var total3 = ip_num(temp_res_ip);
    if(total1 <= total3 && total3 <= total2)
        return false;
	return true;
}

function check_ip4(ip4){
	var temp_ip = (ip4.ip).split(" ");

	if (ip4.ip == ""){
		alert(ip4.e_msg1);
		return false;
	}else if (isNaN(ip4.ip) || temp_ip.length > 1 || parseInt(ip4.ip) < ip4.min_range || parseInt(ip4.ip) > ip4.max_range){
		alert(ip4.e_msg2);
		return false;
	}
	return true;
}

function check_key(){
	var key;
	var def_key = get_by_id("wep_def_key").value;
	var wep_def_key = get_by_id("wep_def_key");
	var wep_key_len = parseInt(get_by_id("wep_key_len").value);
	var hex_len = wep_key_len * 2;

	for(var i = 1; i < 5; i++){
			key = get_by_id("key" + i).value;
			if (wep_def_key[i-1].selectedIndex){
		        if (key == ''){
		    	alert(LangMap.which_lang['YM122']);	
					return false;
    	        }
		  }else{
		    	if (key.length != wep_key_len && key.length != hex_len){
			    		//alert(TEXT041_1+" " + i + " "+TEXT041_2+" " + wep_key_len + " "+TEXT041_3+" " + hex_len + " "+TEXT041_4);//TEXT041
			    		if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
							continue;
						alert(LangMap.which_lang['TEXT041_1']+" " + i + " "+LangMap.which_lang['TEXT041_2']+" " + wep_key_len + " "+LangMap.which_lang['TEXT041_3']+" " + hex_len + " "+LangMap.which_lang['TEXT041_4']);//TEXT041			    		
			    		return false;
		    	}else if(key.length == hex_len){
			      	for (var j = 0; j < key.length; j++){
			      		if (!check_hex(key.substring(j, j+1))){
			      			//alert(TEXT042_1+" " + i + " "+TEXT042_2);//TEXT042
			      			alert(LangMap.which_lang['TEXT042_1']+" " + i + " "+LangMap.which_lang['TEXT042_2']);//TEXT042			      			
			      			if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;
			      			return false;
			      		}
			      	}
			      	if(i == def_key)
			      		get_by_id("wlan0_wep_display").value = "hex";
		    	}else{
		    		if(i == def_key)
		    			get_by_id("wlan0_wep_display").value = "ascii";
						
					if(wep_key_len == 5)
						get_by_id("key"+i).value = a_to_hex(key);
					else
						get_by_id("key"+i).value = a_to_hex(key);
		    	}
		  }
	}
	return true;
}

function check_key_0(){
	var key;
	var def_key = get_by_id("wep0_def_key").value;
	var wep_def_key = get_by_id("wep0_def_key");
	var wep_key_len = parseInt(get_by_id("wep0_key_len").value);
	var hex_len = wep_key_len * 2;

	for(var i = 1; i < 5; i++){
			key = get_by_id("key" + i).value;
			if (wep_def_key[i-1].selectedIndex){
		        if (key == ''){
		    	alert(LangMap.which_lang['YM122']);	
					return false;
    	        }
		  }else{
		    	if (key.length != wep_key_len && key.length != hex_len){
			    		//alert(TEXT041_1+" " + i + " "+TEXT041_2+" " + wep_key_len + " "+TEXT041_3+" " + hex_len + " "+TEXT041_4);//TEXT041
			    		if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
							continue;
						alert(LangMap.which_lang['KR16'] +" " + LangMap.which_lang['wwl_WK'] + " "+LangMap.which_lang['TEXT041_2']+" " + wep_key_len + " "+LangMap.which_lang['TEXT041_3']+" " + hex_len + " "+LangMap.which_lang['TEXT041_4']);//TEXT041			    		
			    		return false;
		    	}else if(key.length == hex_len){
			      	for (var j = 0; j < key.length; j++){
			      		if (!check_hex(key.substring(j, j+1))){
			      			//alert(TEXT042_1+" " + i + " "+TEXT042_2);//TEXT042
			      			alert(LangMap.which_lang['KR16'] +" " + LangMap.which_lang['wwl_WK']+" " + LangMap.which_lang['KR22'] + " "+LangMap.which_lang['TEXT042_2']);//TEXT042			      			
			      			if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;
			      			return false;
			      		}
			      	}
			      	if(i == def_key)
			      		get_by_id("wlan0_wep_display").value = "hex";
		    	}else{
		    		if(i == def_key)
		    			get_by_id("wlan0_wep_display").value = "ascii";
						
					//get_by_id("key"+i).value = a_to_hex(key);
		    	}
		  }
	}
	return true;
}

function check_key_1()
{
	var key;
	var def_key = get_by_id("wep1_def_key").value;
	var wep_def_key = get_by_id("wep1_def_key");
	var wep_key_len = parseInt(get_by_id("wep1_key_len").value);
	var hex_len = wep_key_len * 2;
	for(var i = 1; i < 2; i++){
		key = get_by_id("key" + (i+4)).value;
		if (wep_def_key[i-1].selectedIndex){
			if (key == ''){
				alert(LangMap.msg['WEP_KEY_EMPTY']);
				return false;
			}
		}else{
			if (key.length != wep_key_len && key.length != hex_len){
				alert(LangMap.which_lang['KR17'] +" " + LangMap.which_lang['wwl_WK'] + " "+LangMap.which_lang['TEXT041_2']+" " + wep_key_len + " "+LangMap.which_lang['TEXT041_3']+" " + hex_len + " "+LangMap.which_lang['TEXT041_4']);//TEXT041			    		
				return false;
			}else if(key.length == hex_len){
				for (var j = 0; j < key.length; j++){
					if (!check_hex(key.substring(j, j+1))){
					alert(LangMap.which_lang['KR17'] +" " + LangMap.which_lang['wwl_WK'] + " "+LangMap.which_lang['TEXT042_2']);//TEXT042			      			
						return false;
					}
				}
				if(i == def_key)
					get_by_id("wlan1_wep_display").value = "hex";
			}else{
				if(i == def_key)
					get_by_id("wlan1_wep_display").value = "ascii";

				//get_by_id("key"+i).value = a_to_hex(key);
			}
		}
	}
	return true;
}

function check_vap1_key_1(){
	var key;
	var def_key = get_by_id("wep1_def_key").value;
	var wep_def_key = get_by_id("wep1_def_key");
	var wep_key_len = parseInt(get_by_id("wep1_key_len").value);
	var hex_len = wep_key_len * 2;

	for(var i = 1; i <2; i++){
			key = get_by_id("key" + (i+4)).value;
			if (wep_def_key[i-1].selectedIndex){
		        if (key == ''){
		    	alert(LangMap.msg['WEP_KEY_EMPTY']);
					return false;
    	        }
		  }else{
		    	if (key.length != wep_key_len && key.length != hex_len){
			    		alert(LangMap.which_lang['KR17'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT041_2']+" " + wep_key_len + " "+LangMap.which_lang['TEXT041_3']+" " + hex_len + " "+LangMap.which_lang['TEXT041_4']);//TEXT041
			    		return false;
		    	}else if(key.length == hex_len){
			      	for (var j = 0; j < key.length; j++){
			      		if (!check_hex(key.substring(j, j+1))){
			      			alert(LangMap.which_lang['KR17'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT042_2']);//TEXT042
							if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;

			      			return false;
			      		}
			      	}
			      	if(i == def_key)
			      		get_by_id("wlan1_vap1_wep_display").value = "hex";
		    	}else{
		    		if(i == def_key)
		    			get_by_id("wlan1_vap1_wep_display").value = "ascii";
					
/*					for (var j = 0; j < key.length; j++){		//20120112 silvia add
			      		if (!check_hex(key.substring(j, j+1))){
			      			alert(LangMap.which_lang['KR17'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT042_2']);//TEXT042
							if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;
			      			return false;
			      		}
					}
*/					//get_by_id("key"+(i+4)).value = a_to_hex(key);
		    	}
		  }
	}
	return true;
}

function check_vap1_key(){
	var key;
	var def_key = get_by_id("wep0_def_key").value;
	var wep_def_key = get_by_id("wep0_def_key");
	var wep_key_len = parseInt(get_by_id("wep0_key_len").value);
	var hex_len = wep_key_len * 2;

	for(var i = 1; i < 2; i++){
			key = get_by_id("key" + i).value;
			if (wep_def_key[i-1].selectedIndex){
		        if (key == ''){
		    	alert(LangMap.msg['WEP_KEY_EMPTY']);
					return false;
    	        }
		  }else{
		    	if (key.length != wep_key_len && key.length != hex_len){
			    		alert(LangMap.which_lang['KR16'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT041_2']+" " + wep_key_len + " "+LangMap.which_lang['TEXT041_3']+" " + hex_len + " "+LangMap.which_lang['TEXT041_4']);//TEXT041
			    		return false;
		    	}else if(key.length == hex_len){
			      	for (var j = 0; j < key.length; j++){
			      		if (!check_hex(key.substring(j, j+1))){
			      			alert(LangMap.which_lang['KR16'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT042_2']);//TEXT042
							if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;

			      			return false;
			      		}
			      	}
			      	if(i == def_key)
			      		get_by_id("wlan0_vap1_wep_display").value = "hex";
		    	}else{
		    		if(i == def_key)
		    			get_by_id("wlan0_vap1_wep_display").value = "ascii";
						
/*			      	for (var j = 0; j < key.length; j++){
			      		if (!check_hex(key.substring(j, j+1))){
			      			alert(LangMap.which_lang['KR16'] +" " + LangMap.which_lang['wwl_WK']+ " "+LangMap.which_lang['TEXT042_2']);//TEXT042
							if(def_key != i)	//add by Vic for avoid check wep key when the index is not selected
								continue;

			      			return false;
			      		}
			      	}
*/					//get_by_id("key"+i).value = a_to_hex(key);
		    	}
		  }
	}
	return true;
}

function check_integer(which_value, min, max){
	var temp_obj = (which_value).split(" ");

	if (temp_obj == "" || temp_obj.length > 1 || isNaN(which_value)){
		return false;
	}else if (parseInt(which_value,10) < min || parseInt(which_value,10) > max){
		return false;
	}

	return true;
}

function get_seq(index){
	var seq;

	switch(index){
		case 0:
			seq = "1st";
			break;
		case 1:
			seq = "2nd";
			break;
		case 2:
			seq = "3rd";
			break;
		case 3:
			seq = "4th";
			break;
	}
	return seq;
}

function check_ip_range(order, my_obj, mask){
	var which_ip = (my_obj.addr[order]).split(" ");
	var start, end;

	if (isNaN(which_ip) || which_ip == "" || which_ip.length > 1 || (which_ip[0].length > 1 && which_ip[0].substring(0,1) == "0")){	// if the address is invalid
		alert(my_obj.e_msg[FIRST_IP_ERROR + order]);
		return false;
	}

	if (order == 0){				// the checking range of 1st address
		start = 1;
	}else{
				start = 0;
			}

	if (mask[order] != 255){
		if (parseInt(which_ip) >= 0 && parseInt(which_ip) <= 255){
			end = (~mask[order]+256);
			start = mask[order] & which_ip;
			end += start;

			if (end > 255){
				end = 255;
			}
		}else{
			end = 255;
		}
	}else{
		end = 255;
	}


	if (order == 3){
		if ((mask[0] == 255) && (mask[1] == 255) && (mask[2] == 255)){
			start += 1;
			end -= 1;
		}else{
			if (((mask[0] | (~my_obj.addr[0]+256)) == 255) && ((mask[1] | (~my_obj.addr[1]+256)) == 255) && ((mask[2] | (~my_obj.addr[2]+256)) == 255)){
				start += 1;
			}

			if (((mask[0] | my_obj.addr[0]) == 255) && ((mask[1] | my_obj.addr[1]) == 255) && ((mask[2] | my_obj.addr[2]) == 255)){
				end -= 1;
			}
		}
		}

	if (parseInt(which_ip) < start || parseInt(which_ip) > end){
		alert(my_obj.e_msg[FIRST_RANGE_ERROR + order] + " " + start + " ~ " + end + ".");
		return false;
	}

	return true;
}

function check_current_range(order, my_obj, checking_ip, mask){
	var which_ip = (my_obj.addr[order]).split(" ");
	var start, end;

	if (isNaN(which_ip) || which_ip == "" || which_ip.length > 1 || (which_ip[0].length > 1 && which_ip[0].substring(0,1) == "0")){	// if the address is invalid
		alert(my_obj.e_msg[FIRST_IP_ERROR + order]);
		return false;
	}

	if (order == 0){				// the checking range of 1st address
		start = 1;
	}else{
		start = 0;
	}

	if (mask[order] != 255){
		if (parseInt(checking_ip[order]) >= 0 && parseInt(checking_ip[order]) <= 255){
			end = (~mask[order]+256);
			start = mask[order] & checking_ip[order];
			end += start;

			if (end > 255){
				end = 255;
			}
		}else{
			end = 255;
		}
	}else{
		end = 255;
	}

	if (order == 3){
		if ((mask[0] == 255) && (mask[1] == 255) && (mask[2] == 255)){
			start += 1;
			end -= 1;
		}else{
			if (((mask[0] | (~my_obj.addr[0]+256)) == 255) && ((mask[1] | (~my_obj.addr[1]+256)) == 255) && ((mask[2] | (~my_obj.addr[2]+256)) == 255)){
				start += 1;
			}

			if (((mask[0] | my_obj.addr[0]) == 255) && ((mask[1] | my_obj.addr[1]) == 255) && ((mask[2] | my_obj.addr[2]) == 255)){
				end -= 1;
			}
		}
	}

	if (parseInt(which_ip) < start || parseInt(which_ip) > end){
		alert(my_obj.e_msg[FIRST_RANGE_ERROR + order] + " " + start + " ~ " + end + ".");
		return false;
	}

	return true;
}

function check_hex(data){
	data = data.toUpperCase();

	if (!(data >= 'A' && data <= 'F') && !(data >= '0' && data <= '9')){
		return false;
	}
	return true;
}

function check_lan_setting(ip, mask, gateway, obj_word){

	 if (!check_mask(mask)){
		return false;   // when subnet mask is not in the subnet mask range
	}else if (!check_address(ip, mask)){
		return false;		// when ip is invalid
	}else if (!check_address(gateway, mask, ip)){
		return false;	// when gateway is invalid
	}else if (!check_domain(ip, mask, gateway)){		// check if the ip and the gateway are in the same subnet mask or not
		var gateway_ipaddr_1 = gateway.addr[0]+"."+gateway.addr[1]+"."+gateway.addr[2]+"."+gateway.addr[3];
		alert(addstr(LangMap.msg['NOT_SAME_DOMAIN'], obj_word, gateway_ipaddr_1));
		return false;
	}
	return true;
}
function check_mac(mac){
    var temp_mac = mac.split(":");
    var error = true;
    var mac_val = parseInt(temp_mac[0],16);
	var iszero = 0;
    if (temp_mac.length == 6){
	    if(mac_val%=2){
	    	return false;
	    }
	    for (var i = 0; i < 6; i++){
	        var temp_str = temp_mac[i];

	        if (temp_str == ""){
	            error = false;
			}else if (temp_str == "00"){
				iszero++;
	        }else{
	            if (!check_hex(temp_str.substring(0,1)) || !check_hex(temp_str.substring(1))){
	                error = false;
	            }
	        }

	        if (!error){
	            break;
	        }
	    }
		if (iszero == 6)
			error = false;
	}else{
		error = false;
	}
    return error;
}
function check_mac_00(mac){
    var temp_mac = mac.split(":");
    var error = true;
    var mac_val = parseInt(temp_mac[0],16);
	var iszero = 0;
    if (temp_mac.length == 6){
	    if(mac_val%=2){
	    	return false;
	    }
	    for (var i = 0; i < 6; i++){
	        var temp_str = temp_mac[i];

	        if (temp_str == ""){
	            error = false;
			}else if (temp_str == "00"){
				iszero++;
	        }else{
	            if (!check_hex(temp_str.substring(0,1)) || !check_hex(temp_str.substring(1))){
	                error = false;
	            }
	        }

	        if (!error){
	            break;
	        }
	    }
		if (iszero == 6)
			error = true;
	}else{
		error = false;
	}
    return error;
}

function check_address(my_obj, mask_obj, ip_obj){
	var count_zero = 0;
	var count_bcast = 0;
	var ip = my_obj.addr;
	var mask;

	if (my_obj.addr.length == 4){
		// check the ip is not multicast IP (127.x.x.x && 224.x.x.x ~ 239.x.x.x)
		if((my_obj.addr[0] == "127") || ((my_obj.addr[0] >= 224) && (my_obj.addr[0] <= 239))){
			alert(my_obj.e_msg[MULTICASE_IP_ERROR]);
			return false;
		}
		// check the ip is not broadcast IP (255.255.255.255)/not reserved IP (240.0.0.0/4)[RFC 5735] 2013-10-23 moa modified.
		if (((my_obj.addr[0] == "255") && (my_obj.addr[1] == "255") && (my_obj.addr[2] == "255") && (my_obj.addr[3] == "255")) ||
			((my_obj.addr[0] >= 240) && (my_obj.addr[0] <= 255))
		)
		{
			alert(my_obj.e_msg[INVALID_IP]);
			return false;
		}
		// check the ip is "0.0.0.0" or not
		for(var i = 0; i < ip.length; i++){
			if (ip[i] == "0"){
				count_zero++;
			}
		}

		if (!my_obj.allow_zero && count_zero == 4){	// if the ip is not allowed to be 0.0.0.0
			alert(my_obj.e_msg[ZERO_IP]);			// but we check the ip is 0.0.0.0
			return false;
		}else if (count_zero != 4){		// when IP is not 0.0.0.0, checking range. Otherwise no need to check
			count_zero = 0;

			if (check_address.arguments.length >= 2 && mask_obj != null){
				mask = mask_obj.addr;
			}else{
				mask = new Array(255,255,255,0);
			}

			for(var i = 0; i < ip.length; i++){

				if (check_address.arguments.length == 3 && ip_obj != null){
					if (!check_current_range(i, my_obj, ip_obj.addr, mask)){
						return false;
					}
				}else{
					if (!check_ip_range(i, my_obj, mask)){
						return false;
					}
				}
			}

			for (var i = 0; i < 4; i++){	// check the IP address is a network address or a broadcast address
				if (((~mask[i] + 256) & ip[i]) == 0){	// (~mask[i] + 256) = reverse mask[i]
					count_zero++;
				}

				if ((mask[i] | ip[i]) == 255){
					count_bcast++;
				}
			}

			if ((count_zero == 4 && !my_obj.is_network) || (count_bcast == 4)){
				alert(my_obj.e_msg[INVALID_IP]);
				return false;
			}
		}
	}else{	// if the length of ip is not correct, show invalid ip msg
		alert(my_obj.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}

//20120924 pascal add for ip can set x.x.x.0 - x.x.x.255
function check_route_address(my_obj, mask_obj, ip_obj){
	var count_zero = 0;
	var count_bcast = 0;
	var ip = my_obj.addr;
	var mask;

	if (my_obj.addr.length == 4){
		// check the ip is not multicast IP (127.x.x.x && 224.x.x.x ~ 239.x.x.x)
		if((my_obj.addr[0] == "127") || ((my_obj.addr[0] >= 224) && (my_obj.addr[0] <= 239))){
			alert(my_obj.e_msg[MULTICASE_IP_ERROR]);
			return false;
		}
		// check the ip is not broadcast IP (255.x.x.x) 2009.8.10 graceyang add.
		if((my_obj.addr[0] == "255")){
			alert(my_obj.e_msg[INVALID_IP]);
			return false;
		}
		// check the ip is "0.0.0.0" or not
		for(var i = 0; i < ip.length; i++){
			if (ip[i] == "0"){
				count_zero++;
			}
		}

		if (!my_obj.allow_zero && count_zero == 4){	// if the ip is not allowed to be 0.0.0.0
			alert(my_obj.e_msg[ZERO_IP]);			// but we check the ip is 0.0.0.0
			return false;
		}else if (count_zero != 4){		// when IP is not 0.0.0.0, checking range. Otherwise no need to check
			count_zero = 0;

			if (check_route_address.arguments.length >= 2 && mask_obj != null){
				mask = mask_obj.addr;
			}else{
				mask = new Array(255,255,255,0);
			}

			for(var i = 0; i < ip.length-1; i++){
				if (check_route_address.arguments.length == 3 && ip_obj != null){
					if (!check_current_range(i, my_obj, ip_obj.addr, mask)){
						return false;
					}
				}else{
					if (!check_ip_range(i, my_obj, mask)){
						return false;
					}
				}
			}
			if (!check_integer(my_obj.addr[3], 0, 255)) {
						alert(my_obj.e_msg[FIRST_RANGE_ERROR + 3] + "0 ~ 255.");
					return false;
				}

		}
	}else{	// if the length of ip is not correct, show invalid ip msg
		alert(my_obj.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}

function check_mask(my_mask){
	var temp_mask = my_mask.addr;

	if (temp_mask.length == 4){
		for (var i = 0; i < temp_mask.length; i++){
			var which_ip = temp_mask[i].split(" ");
			var mask = parseInt(temp_mask[i]);
			var in_range = false;
			var j = 0;

			if (isNaN(which_ip) || which_ip == "" || which_ip.length > 1 || (which_ip[0].length > 1 && which_ip[0].substring(0,1) == "0")){	// if the address is invalid
				alert(my_mask.e_msg[FIRST_IP_ERROR + i]);
				return false;
			}

			if (i == 0){	// when it's 1st address
				j = 1;		// the 1st address can't be 0
			}

			for (; j < subnet_mask.length; j++){
				if (mask == subnet_mask[j]){
					in_range = true;
					break;
				}else{
					in_range = false;
				}
			}

			if (!in_range){
				alert(my_mask.e_msg[FIRST_RANGE_ERROR + i]);
				return false;
			}

			if (i != 0 && mask != 0){ // when not the 1st range and the value is not 0
				if (parseInt(temp_mask[i-1]) != 255){  // check the previous value is 255 or not
					alert(my_mask.e_msg[INVALID_IP]);
					return false;
				}
			}

			if (i == 3 && (parseInt(mask) == 254 || parseInt(mask) == 255)){	// when the last mask address is 255
				alert(my_mask.e_msg[FOURTH_RANGE_ERROR]);
				return false;
			}
		}
	}else{
		alert(my_mask.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}

//20120918 add for routing can set 255.255.255.255
function check_route_mask(my_mask){
	var temp_mask = my_mask.addr;

	if (temp_mask.length == 4){
		for (var i = 0; i < temp_mask.length; i++){
			var which_ip = temp_mask[i].split(" ");
			var mask = parseInt(temp_mask[i]);
			var in_range = false;
			var j = 0;

			if (isNaN(which_ip) || which_ip == "" || which_ip.length > 1 || (which_ip[0].length > 1 && which_ip[0].substring(0,1) == "0")){	// if the address is invalid
				alert(my_mask.e_msg[FIRST_IP_ERROR + i]);
				return false;
			}

			if (i == 0){	// when it's 1st address
				j = 1;		// the 1st address can't be 0
			}

			for (; j < subnet_mask.length; j++){
				if (mask == subnet_mask[j]){
					in_range = true;
					break;
				}else{
					in_range = false;
				}
			}

			if (!in_range){
				alert(my_mask.e_msg[FIRST_RANGE_ERROR + i]);
				return false;
			}

			if (i != 0 && mask != 0){ // when not the 1st range and the value is not 0
				if (parseInt(temp_mask[i-1]) != 255){  // check the previous value is 255 or not
					alert(my_mask.e_msg[INVALID_IP]);
					return false;
				}
			}
/*
			if (i == 3 && (parseInt(mask) == 254 || parseInt(mask) == 255)){	// when the last mask address is 255
				alert(my_mask.e_msg[FOURTH_RANGE_ERROR]);
				return false;
			}
*/		}
	}else{
		alert(my_mask.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}

function check_pwd(pwd1, pwd2){
	if (get_by_id(pwd1).value != get_by_id(pwd2).value){
		 alert(get_words('YM102'));
		return false;
	}
	return true;
}

function check_port(port){
    var temp_port = port.split(" ");

    if ( isNaN(port) || port == "" || temp_port.length > 1
    		|| (parseInt(port,10) < 1 || parseInt(port,10) > 65535))
	{
		return false;
	}
	return true;
}

function check_radius(radius){
	if (!check_address(radius.ip)){
		return false;
	}else if (!check_port(radius.port)){
        alert(radius.ip.e_msg[RADIUS_SERVER_PORT_ERROR]);
        return false;
    }else if (radius.secret == ""){
        alert(radius.ip.e_msg[RADIUS_SERVER_SECRET_ERROR]);
        return false;
	}

	return true;
}

function check_ssid(id){
	if (get_by_id(id).value == ""){
	    //alert(msg[SSID_EMPTY_ERROR]);
		alert(get_words('SSID_EMPTY_ERROR'));
	    return false;
	}
	return true;
}

//for different language table
function check_ssid_0(id){
	if (get_by_id(id).value == ""){
	    alert(get_words('SSID_EMPTY_ERROR',LangMap.msg));
		return false;
	}
	return true;
}

function check_varible(obj){
	var temp_obj = obj.var_value.split(" ");

	if (temp_obj == "" || temp_obj.length > 1){
		alert(obj.e_msg[EMPTY_VARIBLE_ERROR]);
		return false;
	}else if (isNaN(parseInt(obj.var_value))){
		alert(obj.e_msg[INVALID_VARIBLE_ERROR]);
		return false;
	}else if (parseInt(obj.var_value) < obj.min || parseInt(obj.var_value) > obj.max || !/^\d+$/.test(obj.var_value)){
		alert(obj.e_msg[VARIBLE_RANGE_ERROR]);
		return false;
	}else if (obj.is_even && (parseInt(obj.var_value) % 2 != 0)){
		alert(obj.e_msg[EVEN_NUMBER_ERROR]);
        return false;
    }
    return true;
}

function check_pf_port(port){
    var temp_port = port.split(" ");

    if (isNaN(port) || port == "" || temp_port.length > 1
    		|| (parseInt(port) <= 0 || parseInt(port) > 65535)){
        return false;
    }
    return true;
}

function check_multi_port(remote_port, obj_port){
	//multi-port: 25,80,110,443,50000-65535
	var port_info = obj_port + ",";
	var port = port_info.split(",");

	for(var i = 0; i < port.length; i++){
		var port_range = port[i].split("-");
		if(port_range.length > 1){
			if(parseInt(port_range[0]) <= parseInt(remote_port) && parseInt(port_range[1]) >= parseInt(remote_port)){
				return false;
			}
		}else{
			if(port[i] == remote_port){
				return false;
			}
		}
	}
	return true;
}

function change_color(table_name, row){
    var obj = get_by_id(table_name);
    for (var i = 1; i < obj.rows.length; i++){
        if (row == i){
            obj.rows[i].style.backgroundColor = "#FFFF00";
        }else{
            obj.rows[i].style.backgroundColor = "#FFFFFF";
        }
    }
}

function exit_wizard(){
    if (confirm(LangMap.which_lang['_wizquit'])){
        window.close();
    }
}

function exit_access(){
    if (confirm(LangMap.which_lang['_wizquit'])){
		setTimeout(function() {
			window.location.href = "adv_access_control.asp";
		}, 0);
    }
}

function get_by_id(id){
	with(document){
		return getElementById(id);
	}
}

function get_by_name(name){
	with(document){
		return getElementsByName(name);
	}
}

function openwin(url,w,h) {
	var winleft = (screen.width - w) / 2;
	var wintop = (screen.height - h) / 2;
	window.open(url,"popup",'width='+w+',height='+h+',top='+wintop+',left='+winleft+',scrollbars=yes,status=no,location=no,resizable=yes')
}

function send_submit(which_form){
	get_by_id(which_form).submit();
}

function set_server(is_enable){
	var enable = get_by_id("enable");

    if (is_enable == "1"){
        enable[0].checked = true;
    }else{
        enable[1].checked = true;
    }
}

function set_protocol(which_value, obj){
    for (var i = 0; i < 3; i++){
        if (which_value == obj.options[i].value){
            obj.selectedIndex = i;
            break;
        }
    }
}

function set_schedule(data, index){
	var schd = get_by_name("schd");

    if (data[index] == "0"){
        schd[0].checked = true;
    }else{
        schd[1].checked = true;
    }

    get_by_id("hour1").selectedIndex = data[index+1];
    get_by_id("min1").selectedIndex = data[index+2];
    get_by_id("am1").selectedIndex = data[index+3];
    get_by_id("hour2").selectedIndex = data[index+4];
    get_by_id("min2").selectedIndex = data[index+5];
    get_by_id("am2").selectedIndex = data[index+6];
    get_by_id("day1").selectedIndex = data[index+7];
    get_by_id("day2").selectedIndex = data[index+8];
}

function set_selectIndex(which_value, obj){
    for (var pp=0; pp<obj.options.length; pp++){
        if (which_value == obj.options[pp].value){
            obj.selectedIndex = pp;
            break;
        }
    }
}

function set_checked(which_value, obj){
	if(obj.length > 1){
		obj[0].checked = true;
		for(var pp=0;pp<obj.length;pp++){
			if(obj[pp].value == which_value){
				obj[pp].checked = true;
			}
		}
	}else{
		obj.checked = false;
		if(obj.value == which_value){
			obj.checked = true;
		}
	}
}

function get_radio_value(obj)
{
	var i=0;
	for(i=0; i<obj.length; i++)
	{
		if(obj[i].checked)
			return obj[i].value;
	}
	return 0;
} 

function get_checked_value(obj){
	if (obj == null) {
		alert('obj is null');
		return;
	}
	if(obj.length > 1){
		for(pp=0;pp<obj.length;pp++){
			if(obj[pp].checked){
				return obj[pp].value;
			}
		}
	}else{
		if(obj.checked){
			return obj.value;
		}else{
			return 0;
		}
	}
}

function set_schedule_option(){
	for (var i = 0; i < 32; i++){
		var temp_sch = get_by_id("schedule_rule_" + i).value;
		var temp_data = temp_sch.split("/");

		if (temp_data.length > 1){
			document.write("<option value=" + temp_data[0] + ">" + temp_data[0] + "</option>");
		}
	}
}

function set_inbound_option(obj_value, idx){
	for (var i = 0; i < 24; i++){
		var k=i;
		if(parseInt(i,10)<10){
			k="0"+i;
		}
		var temp_inb = get_by_id("inbound_filter_name_" + k).value;
		var temp_data = temp_inb.split("/");

		if (temp_data.length > 1){
			obj_value += "<option value='" + temp_data[0] + "'>" + temp_data[0] + "</option>";
			load_inbound_used(k, temp_data, idx);
		}else{
			break;
		}
	}
	return obj_value;
}

function load_inbound_used(jj, obj_array, idx){
	if(obj_array[2].charAt(idx) == "1"){
		var is_used = "";
		if(idx == 0){
			is_used = "0"+ obj_array[2].substring(1,obj_array[2].length);
		}else if(idx == 1){
			is_used = obj_array[2].charAt(0) + "0"+ obj_array[2].substring(2,obj_array[2].length);
		}else if(idx == 2){
			is_used = obj_array[2].substring(0,2) + "0"+ obj_array[2].charAt(obj_array[2].length-1);
		}else if(idx == 3){
			is_used = obj_array[2].substr(0,obj_array[2].length-1) + "0";
		}
		get_by_id("inbound_filter_name_" + jj).value = obj_array[0] +"/"+ obj_array[1] +"/"+ is_used;
	}
}

function save_inbound_used(chk_value, idx){
	for (var i = 0; i < 24; i++){
		var k=i;
		if(parseInt(i,10)<10){
			k="0"+i;
		}
		var temp_inb = get_by_id("inbound_filter_name_" + k).value;
		var temp_data = temp_inb.split("/");

		if (temp_data.length > 1){
			var is_used = temp_data[2];
			if(temp_data[0] == chk_value){
				if(idx == 0){
					is_used = "1"+ temp_data[2].substring(1,temp_data[2].length);
				}else if(idx == 1){
					is_used = temp_data[2].charAt(0) + "1"+ temp_data[2].substring(2,temp_data[2].length);
				}else if(idx == 2){
					is_used = temp_data[2].substring(0,2) + "1"+ temp_data[2].charAt(temp_data[2].length-1);
				}else if(idx == 3){
					is_used = temp_data[2].substr(0,temp_data[2].length-1) + "1";
				}
			}
			get_by_id("inbound_filter_name_" + k).value = temp_data[0] +"/"+ temp_data[1] +"/"+ is_used;
		}else{
			break;
		}
	}
}

function set_dhcp_list(){
	var temp_dhcp_list = get_by_id("dhcp_list").value.split(",");

	for (var i = 0; i < temp_dhcp_list.length; i++){
		var temp_data = temp_dhcp_list[i].split("/");
		if(temp_data.length > 1){
		document.write("<option value='" + temp_data[1] + "'>" + temp_data[0] + "</option>");
		}
	}
}

function set_mac_list(parameter){
	var temp_dhcp_list = get_by_id("dhcp_list").value.split(",");

	for (var i = 0; i < temp_dhcp_list.length; i++){
		var temp_data = temp_dhcp_list[i].split("/");
		if(temp_data.length > 1){
			if(parameter == "mac"){
				document.write("<option value='" + temp_data[2] + "'>" + temp_data[0] + " (" + temp_data[2] + " )" + "</option>");
			}else if(parameter == "ip"){
				document.write("<option value='" + temp_data[1] + "'>" + temp_data[0] + " (" + temp_data[1] + " )" + "</option>");
			}else{
				document.write("<option value='" + temp_data[2] + "'>" + temp_data[0] + "</option>");
			}
		}
	}
}

function get_mac_list(parameter){
	var temp_dhcp_list = get_by_id("dhcp_list").value.split(",");
	var msg = '';
	
	for (var i = 0; i < temp_dhcp_list.length; i++){
		var temp_data = temp_dhcp_list[i].split("/");
		if(temp_data.length > 1){
			if(parameter == "mac"){
				msg += "<option value='" + temp_data[2] + "'>" + temp_data[0] + " (" + temp_data[2] + " )" + "</option>";
			}else if(parameter == "ip"){
				msg += "<option value='" + temp_data[1] + "'>" + temp_data[0] + " (" + temp_data[1] + " )" + "</option>";
			}else{
				msg += "<option value='" + temp_data[2] + "'>" + temp_data[0] + "</option>";
			}
		}
	}
	return msg;
}

function set_mac(mac){
	var temp_mac = mac.split(":");
	for (var i = 0; i < 6; i++){
		var obj = get_by_id("mac" + (i+1));
		obj.value = temp_mac[i];
	}
}

function show_dns(type){
    if (type){
        get_by_id("dns1").value = "0.0.0.0";
        get_by_id("dns2").value = "0.0.0.0";
    }
}

function show_wizard(name){
	window.open(name,"Wizard","width=450,height=370");
}

function show_window(name){
	window.open(name,"Window","width=500,height=600,scrollbar=yes");
}

function show_schedule_detail(idx){
	var temp_rule, detail;
	temp_rule = get_by_id("schedule_rule_" + idx).value;

	var rule = temp_rule.split("/");
	var s = new Array();

	for(var j = 0; j < 8; j++){
		if(rule[1].charAt(j) == "1"){
			s[j] = "1";
		}else{
			s[j] = "0";
		}
	}

	var s_day = "", count = 0;
	for(var j = 0; j < 8; j++){
		if(s[j] == "1"){
			s_day = s_day + " " + Week[j];
			count++;
		}
	}

	if(count == 7){
		s_day = "All week";
	}

	var temp_time_array = rule[2] + "~" + rule[3];
	if(rule[2] == "00:00" && rule[3] == "24:00"){
		temp_time_array = "All Day";
	}

	detail = s_day + " " + temp_time_array;
	return detail;
}

function get_row_data(obj, index){

    try {
    	return obj.cells[index].childNodes[0].data;
    }catch(e) {
        return ("");
    }
}

function copy_virtual(){
	var data;

	if (get_by_id("application").selectedIndex > 0){
		data = default_virtual[get_by_id("application").selectedIndex - 1];
		get_by_id("name").value = data.name;
		get_by_id("public_portS").value = data.public_port;
		get_by_id("private_portS").value = data.private_port;
		get_by_id("protocol").value = data.prot;
		set_vs_protocol(data.prot, get_by_id("protocol_select"));
	}else{
		alert(LangMap.which_lang['GW_NAT_NAME_INVALID']);
	}
}

function copy_portforward(){
	var data;

	if (get_by_id("application").selectedIndex > 0){
		data = default_rule[get_by_id("application").selectedIndex - 1];
		get_by_id("name").value = data.name;
		get_by_id("tcp_ports").value = data.public_port;
		//get_by_id("public_portE" + index).value = data.public_port;
		get_by_id("udp_ports").value = data.private_port;
		//get_by_id("private_portE" + index).value = data.private_port;
		//set_protocol(data.prot, get_by_id("protocol" + index));
	}else{
		alert(LangMap.which_lang['GW_NAT_NAME_INVALID']);
	}
}

function copy_special_appl(){
	var name = get_by_id("name");
	var trigger_port = get_by_id("trigger_port");
	var trigger_type = get_by_id("trigger");
	var public_port = get_by_id("public_port");
	var public_type = get_by_id("public");
	var application = get_by_id("application");
	var data;

	if (application.selectedIndex > 0){
		data = default_appl[application.selectedIndex - 1];
		name.value = data.name;
		trigger_port.value = data.trigger_port;
		public_port.value = data.public_port;
		set_protocol(data.trigger_prot, trigger_type);
		set_protocol(data.public_prot, public_type);
	}else{
		alert(LangMap.which_lang['GW_NAT_NAME_INVALID']);
	}

}

function copy_ip(index){

	if (get_by_id("ip_list" + index).selectedIndex > 0){
		get_by_id("ip" + index).value = get_by_id("ip_list" + index).options[get_by_id("ip_list" + index).selectedIndex].value;
	}else{
		alert(LangMap.msg['SELECT_COMPUTER_ERROR']);
	}
}

function copy_ip_t(){

	if (get_by_id("ip_list").selectedIndex > 0){
		get_by_id("ip").value = get_by_id("ip_list").options[get_by_id("ip_list").selectedIndex].value;
	}else{
		alert(LangMap.msg['SELECT_COMPUTER_ERROR']);
	}
}

function get_random_char(){
	var number_list = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz";
	var number = Math.round(Math.random()*62);

	return(number_list.substring(number, number + 1));
}

function generate_psk(key){
	var i = key.length;

	if (key.length < 8){
		for (; i < 8; i++){
			key += get_random_char();
		}
	}

	return key;
}

function create_wep_key128(passpharse, pharse_len){
    var pseed2 = "";
   	var md5_str = "";
   	var count;


    for(var i = 0; i < 64; i++){
        count = i % pharse_len;
        pseed2 += passpharse.substring(count, count+1);
    }

    md5_str = calcMD5(pseed2);

    return md5_str.substring(0, 26).toUpperCase();
}
function check_ascii_key_fun(data){

	if (!(data >= 'A' && data <= 'Z') && !(data >= '0' && data <= '9') && !(data >= 'a' && data <= 'z')){
		return false;
	}
	return true;
}

function check_name_key_fun(data){
	if (!(data >= 'A' && data <= 'Z') && !(data >= '0' && data <= '9') && !(data >= 'a' && data <= 'z') && !(data == "-") && !(data == "_")){
		return false;
	}
	return true;
}

//Tin add for check input name
/*2012 07 27*/
function check_name(data){
	
	for (var i = 0; i < data.length; i++)
	{	
		var temp_str = data.charAt(i);
		
		if (!(temp_str >= 'A' && temp_str <= 'Z') && !(temp_str >= '0' && temp_str <= '9') && !(temp_str >= 'a' && temp_str <= 'z') && temp_str != '-' && temp_str != '_')
			return false;
    }
	return true;
}

/* GraceYang
Disable All Form Elements
 */
function DisableEnableForm(xForm,xHow){
  objElems = xForm.elements;
  for(i=0;i<objElems.length;i++){
    objElems[i].disabled = xHow;
  }
}

function _isNumeric(str) {
	    var i;
	    for(i = 0; i<str.length; i++) {
	        var c = str.substring(i, i+1);
	        if("0" <= c && c <= "9") {
	            continue;
	        }
	        return false;
	    }
	    return true;
	}

function check_name_word_fun(obj,word){
	for(var k=0;k<obj.length;k++){
		if (!check_name_key_fun(obj.substring(k, k+1))){
			alert(word+" invalid. the legal characters are 0~9, A~Z, or a~z,-,_.");
			return false;
		}
	}
	return true;
}

function Find_word(strOrg,strFind){
	var index = 0;
	index = strOrg.indexOf(strFind,index);
	if (index > -1){
		return true;
	}
	return false;
}

function a_to_hex(inValue) {
	var outValue = "";
	if (inValue) {
		for (i = 0; i < inValue.length; i++) {
			if(inValue.charCodeAt(i).toString(16) < 10)
				outValue += 0;
			if(inValue.charCodeAt(i).toString(16) > 'a' && inValue.charCodeAt(i).toString(16) <= 'f')
				if(inValue.charCodeAt(i).toString(16).length == 1)
					outValue += 0;
			outValue += inValue.charCodeAt(i).toString(16);
		}
	}
	return outValue;
}

function hex_to_a(inValue){
	outValue = "";
	var k = '';
	for (i = 0; i < inValue.length; i++) {
		l = i % 2;
		if (l == 0)
			k += "%";
		k += inValue.substr(i, 1);
	}
	outValue = unescape(k);
	return outValue;
}

function change_word(inValue,strFind,strAdd){
	var outValue = "";
	for(var i=0;i<inValue.length;i++){
		if(inValue.substr(i,1) == strFind)
			outValue = outValue + strAdd;
		outValue += inValue.substr(i,1);
	}
	return outValue;
}

function ReplaceAll(strOrg,strFind,strReplace){
	var index = 0;
	while(strOrg.indexOf(strFind,index) != -1){
			strOrg = strOrg.replace(strFind,strReplace);
			index = strOrg.indexOf(strFind,index);
	}
	return strOrg
}

function addstr(input_msg)
{
	var last_msg = "";
	var str_location;
	var temp_str_1 = "";
	var temp_str_2 = "";
	var str_num = 0;
	temp_str_1 = addstr.arguments[0];
	while(1)
	{
		min = Math.min(temp_str_1.indexOf("%s"), temp_str_1.indexOf("%m"));
		max = Math.max(temp_str_1.indexOf("%s"), temp_str_1.indexOf("%m"));
		str_location = (min!=-1?min:max);
		if(str_location >= 0)
		{
			str_num++;
			temp_str_2 = temp_str_1.substring(0,str_location);
			last_msg += temp_str_2 + addstr.arguments[str_num];
			temp_str_1 = temp_str_1.substring(str_location+2,temp_str_1.length);
			continue;
		}
		if(str_location < 0)
		{
			last_msg += temp_str_1;
			break;
		}
	}
	return last_msg;
}

function addstr1(input_msg)
{
	var last_msg = "";
	var str_location;
	var temp_str_1 = "";
	var temp_str_2 = "";
	var str_num = 0;
	temp_str_1 = addstr1.arguments[0];
	while(1)
	{
		str_location = temp_str_1.indexOf("%");
		if(str_location >= 0)
		{
			str_num++;
			temp_str_2 = temp_str_1.substring(0,str_location);
			last_msg += temp_str_2 + addstr1.arguments[str_num];
			temp_str_1 = temp_str_1.substring(str_location+2,temp_str_1.length);
			continue;
		}
		if(str_location < 0)
		{
			last_msg += temp_str_1;
			break;
		}
	}
	return last_msg;
}

function replace_msg(obj_S){
	obj_D = new Array();
	for (i=0;i<obj_S.length;i++){
		obj_D[i] = addstr(obj_S[i], replace_msg.arguments[1]);
		obj_D[i] = obj_D[i].replace("%1n", replace_msg.arguments[2]);
		obj_D[i] = obj_D[i].replace("%2n", replace_msg.arguments[3]);
	}
	return obj_D;
}
function ip_num(IP_array){
	var total1 = 0;
	if(IP_array.length > 1){
   		total1 += parseInt(IP_array[3],10);
	    total1 += parseInt(IP_array[2],10)*256;
	    total1 += parseInt(IP_array[1],10)*256*256;
	    total1 += parseInt(IP_array[0],10)*256*256*256;
	}
	return total1;
}

function check_LAN_ip(LAN_IP, CHK_IP, obj_name){
	if(ip_num(LAN_IP) == ip_num(CHK_IP)){
		alert(addstr(LangMap.which_lang['LW1'], obj_name));
		return false;
	}
	return true;
}

function isHex(str) {
    var i;
    for(i = 0; i<str.length; i++) {
        var c = str.substring(i, i+1);
        if(("0" <= c && c <= "9") || ("a" <= c && c <= "f") || ("A" <= c && c <= "F")) {
            continue;
        }
        return false;
    }
    return true;
}

function open_more(rule_num, num, is_hidden, obj){
	var open_word = "none";
	get_by_id("show_more_word").style.display = "";
	get_by_id("show_less_word").style.display = "none";
	if(is_hidden){
		get_by_id("show_more_word").style.display = "none";
		get_by_id("show_less_word").style.display = "";
		open_word = "";
	}
	var start_num = parseInt(rule_num-1,10);
	for(j=start_num;j>=num;j--){
		get_by_id(obj+j).style.display = open_word;
	}
}

/*Date Used, copy by Netgear*/
function getDaysInMonth(mon,year)
{
	var days;
	if (mon==1 || mon==3 || mon==5 || mon==7 || mon==8 || mon==10 || mon==12) days=31;
	else if (mon==4 || mon==6 || mon==9 || mon==11) days=30;
	else if (mon==2)
	{
		if (isLeapYear(year)) { days=29; }
		else { days=28; }
	}
	return (days);
}

function isLeapYear (Year)
{
	if (((Year % 4)==0) && ((Year % 100)!=0) || ((Year % 400)==0)) {
		return (true);
	} else { return (false); }
}

function key_word(newobj,obj){
	get_by_id(obj).value = newobj.value;
}

/*
 * reset_form()
 *	Resets a form with previously saved default values.
 *	Skips elements with attribute 'modified' set to 'ignore'.
 */
function reset_form(form)
{
	if (typeof form == "string") {
		form = document.forms[form];
	}
	if (!form) {
		return;
	}
	if (form.getAttribute('saved') != "true") {
	return;
	}

	for (var i = 0, flen = form.elements.length; i < flen; i++) {
		var obj = form.elements[i];
		if (obj.getAttribute('modified') == 'ignore') {
			continue;
		}
		var name = obj.tagName;
		var value;
		if (name == 'INPUT') {
			var type = obj.type;
			if ((type == 'text') || (type == 'textarea') || (type == 'password') || (type == 'hidden')) {
				obj.value = obj.getAttribute('default');
			} else if ((type == 'checkbox') || (type == 'radio')) {
				value = obj.getAttribute('default');
				switch (typeof(value)) {
				case 'boolean':
					obj.checked = value;
					break;
				case 'string':
					if (typeof value.toLowerCase == "function") {
						value = value.toLowerCase();
					}
					if (value == "1" || value == "true" || value == "on") {
						obj.checked = true;
					}
					if (value == "0" || value == "false" || value == "off") {
						obj.checked = false;
					}
					break;
				default:
					break;
				}
			}
		} else if (name == 'SELECT') {
			var opt = obj.options;
			for (var j = 0, olen = opt.length; j < olen; j++) {
				value = obj[j].getAttribute('default');
				switch (typeof(value)) {
				case 'boolean':
					obj[j].selected = value;
					break;
				case 'string':
					if (typeof value.toLowerCase == "function") {
						value = value.toLowerCase();
					}
					if (value == "1" || value == "true" || value == "on") {
						obj[j].selected = true;
					}
					if (value == "0" || value == "false" || value == "off") {
						obj[j].selected = false;
					}
					break;
				default:
					break;
				}
			}
		}
	}
}

/*
 * is_form_modified
 *	Check if a form's current values differ from saved values in custom attribute.
 *	Function skips elements with attribute: 'modified'= 'ignore'.
 */
function is_form_modified(form_id)
{
	var df = document.forms[form_id];
	if (!df) {
		return false;
	}
	if (df.getAttribute('modified') == "true") {
		return true;
	}
	if (df.getAttribute('saved') != "true") {
		return false;
	}
	for (var i = 0, k = df.elements.length; i < k; i++) {
		var obj = df.elements[i];
		if (obj.getAttribute('modified') == 'ignore') {
			continue;
		}
		var name = obj.tagName.toLowerCase();
		if (name == 'input') {
			var type = obj.type.toLowerCase();
			if (((type == 'text') || (type == 'textarea') || (type == 'password') || (type == 'hidden')) &&
					!are_values_equal(obj.getAttribute('default'), obj.value)) {
				return true;
			} else if (((type == 'checkbox') || (type == 'radio')) && !are_values_equal(obj.getAttribute('default'), obj.checked)) {
				return true;
			}
		} else if (name == 'select') {
			var opt = obj.options;
			for (var j = 0; j < opt.length; j++) {
				if (!are_values_equal(opt[j].getAttribute('default'), opt[j].selected)) {
					return true;
				}
			}
		}
	}
	return false;
}

/*
 * set_form_default_values
 *	Save a form's current values to a custom attribute.
 */
function set_form_default_values(form_id)
{
	var df = document.forms[form_id];
	if (!df) {
		return;
	}
	for (var i = 0, k = df.elements.length; i < k; i++) {
		var obj = df.elements[i];
		if (obj.getAttribute('modified') == 'ignore') {
			continue;
		}
		var name = obj.tagName.toLowerCase();
		if (name == 'input') {
			var type = obj.type.toLowerCase();
			if ((type == 'text') || (type == 'textarea') || (type == 'password') || (type == 'hidden')) {
				obj.setAttribute('default', obj.value);
				/* Workaround for FF error when calling focus() from an input text element. */
				if (type == 'text') {
					obj.setAttribute('autocomplete', 'off');
				}
			} else if ((type == 'checkbox') || (type == 'radio')) {
				obj.setAttribute('default', obj.checked);
			}
		} else if (name == 'select') {
			var opt = obj.options;
			for (var j = 0; j < opt.length; j++) {
				opt[j].setAttribute('default', opt[j].selected);
			}
		}
	}
	df.setAttribute('saved', "true");
}

/*
 * are_values_equal()
 *	Compare values of types boolean, string and number. The types may be different.
 *	Returns true if values are equal.
 */
function are_values_equal(val1, val2)
{
	/* Make sure we can handle these values. */
	switch (typeof(val1)) {
	case 'boolean':
	case 'string':
	case 'number':
		break;
	default:
		// alert("are_values_equal does not handle the type '" + typeof(val1) + "' of val1 '" + val1 + "'.");
		return false;
	}

	switch (typeof(val2)) {
	case 'boolean':
		switch (typeof(val1)) {
		case 'boolean':
			return (val1 == val2);
		case 'string':
			if (val2) {
				return (val1 == "1" || val1.toLowerCase() == "true" || val1.toLowerCase() == "on");
			} else {
				return (val1 == "0" || val1.toLowerCase() == "false" || val1.toLowerCase() == "off");
			}
			break;
		case 'number':
			return (val1 == val2 * 1);
		}
		break;
	case 'string':
		switch (typeof(val1)) {
		case 'boolean':
			if (val1) {
				return (val2 == "1" || val2.toLowerCase() == "true" || val2.toLowerCase() == "on");
			} else {
				return (val2 == "0" || val2.toLowerCase() == "false" || val2.toLowerCase() == "off");
			}
			break;
		case 'string':
			if (val2 == "1" || val2.toLowerCase() == "true" || val2.toLowerCase() == "on") {
				return (val1 == "1" || val1.toLowerCase() == "true" || val1.toLowerCase() == "on");
			}
			if (val2 == "0" || val2.toLowerCase() == "false" || val2.toLowerCase() == "off") {
				return (val1 == "0" || val1.toLowerCase() == "false" || val1.toLowerCase() == "off");
			}
			return (val2 == val1);
		case 'number':
			if (val2 == "1" || val2.toLowerCase() == "true" || val2.toLowerCase() == "on") {
				return (val1 == 1);
			}
			if (val2 == "0" || val2.toLowerCase() == "false" || val2.toLowerCase() == "off") {
				return (val1 === 0);
			}
			return (val2 == val1 + "");
		}
		break;
	case 'number':
		switch (typeof(val1)) {
		case 'boolean':
			return (val1 * 1 == val2);
		case 'string':
			if (val1 == "1" || val1.toLowerCase() == "true" || val1.toLowerCase() == "on") {
				return (val2 == 1);
			}
			if (val1 == "0" || val1.toLowerCase() == "false" || val1.toLowerCase() == "off") {
				return (val2 === 0);
			}
			return (val1 == val2 + "");
		case 'number':
			return (val2 == val1);
		}
		break;
	default:
		return false;
	}
}

function jump_if(){
	for (var i = 0; i < document.forms.length; i++) {
		if (is_form_modified(document.forms[i].id)) {
			if (!confirm(LangMap.which_lang['up_jt_1']+"\n"+LangMap.which_lang['up_jt_2']+"\n"+LangMap.which_lang['up_jt_3'])){
				return false;
			}
		}
	}
	return true;
}

/*
 * Cancel and reset changes to the page.
 */
 function page_cancel_1(form_name, redirect_url){
	if (!is_form_modified(form_name) && confirm (LangMap.which_lang['up_fm_dc_1'])) {
		setTimeout(function() {
			window.location.href=redirect_url;
		}, 0);
	}
}
function page_cancel(form_name, redirect_url){
	if (is_form_modified(form_name) && confirm (LangMap.which_lang['up_fm_dc_1'])) {
		setTimeout(function() {
			window.location.href=redirect_url;
		}, 0);
	}
}

function page_reboot(){
	jump_if();
	setTimeout(function() {
		window.location.href='reboot.asp'
	}, 0);
}

/*
 * trim_string
 *	Remove leading and trailing blank spaces from a string.
 */
function trim_string(str)
{
	var trim = str + "";
	trim = trim.replace(/^\s*/, "");
	return trim.replace(/\s*$/, "");
}

/*
 * is_mac_valid()
 *	Check if a MAC address is in a valid form.
 *	Allow 00:00:00:00:00:00 and FF:FF:FF:FF:FF:FF if optional argument is_full_range is true.
 */
function is_mac_valid(mac, is_full_range)
{
	var macstr = mac + "";
	var got = macstr.match(/^[0-9a-fA-F]{2}[:-]?[0-9a-fA-F]{2}[:-]?[0-9a-fA-F]{2}[:-]?[0-9a-fA-F]{2}[:-]?[0-9a-fA-F]{2}[:-]?[0-9a-fA-F]{2}$/);
	if (!got) {
		return false;
	}
	macstr = macstr.replace (/[:-]/g, '');
	if (!is_full_range && (macstr.match(/^0{12}$/) || macstr.match(/^[fF]{12}$/))) {
		return false;
	}

    return true;
}

/*
 * is_ascii()
 *      Returns true if value is made of printable ASCII characters (or blank).
 */
function is_ascii(value)
{
    value += "";
    return value.match(/^[\x20-\x7E]*$/) ? true : false;
}

function is_quotes(value)
{
	value += "";
	return value.match(/"/g, "") ? true : false;
}

/*
 * is_number()
 *	Returns true if a value represents a number, else return false.
 */
function is_number(value)
{
	value += "";
	return value.match(/^-?\d*\.?\d+$/) ? true : false;
}

function compare_value(value1, value2)
{
	if(value1 < value2)
		return 1;
	else if(value1 == value2)
		return 0;
	else
		return -1;
}

/*
 * is_ipv4_valid
 *	Check is an IP address dotted string is valid.
 */
function is_ipv4_valid(ipaddr)
{
	var ip = ipv4_to_bytearray(ipaddr);
	if (ip === 0) {
		return false;
	}
	return true;
}

/*
 * ipv4_to_bytearray
 *	Convert an IPv4 address dotted string to a byte array
 */
function ipv4_to_bytearray(ipaddr)
{
	var ip = ipaddr + "";
	var got = ip.match (/^\s*(\d{1,3})\s*[.]\s*(\d{1,3})\s*[.]\s*(\d{1,3})\s*[.]\s*(\d{1,3})\s*$/);
	if (!got) {
		return 0;
	}
	var a = [];
	var q = 0;
	for (var i = 1; i <= 4; i++) {
		q = parseInt(got[i],10);
		if (q < 0 || q > 255) {
			return 0;
		}
		a[i-1] = q;
	}
	return a;
}

function check_ipv4_symbol(strOrg,strFind){
	/* Search ipv4_address has "." symbol */
	/*if false return 0, otherwises return 1 */
	var index = 0;
	index = strOrg.indexOf(strFind,index);

	if(index == -1)
			return 0;
	else
			return 1;
}



function transValue(data)
{
	var value =0;
	data = data.toUpperCase();

	if(data == "0")
		value =0;
	else if(data =="1")
		value = 1;
	else if(data =="2")
		value = 2;
	else if(data =="3")
		value = 3;
	else if(data =="4")
		value = 4;
	else if(data =="5")
		value = 5;
	else if(data =="6")
		value = 6;
	else if(data =="7")
		value = 7;
	else if(data =="8")
		value = 8;
	else if(data =="9")
		value = 9;
	else if(data =="A")
		value = 10;
	else if(data =="B")
		value = 11;
	else if(data =="C")
		value = 12;
	else if(data =="D")
		value = 13;
	else if(data =="E")
		value = 14;
	else if(data =="F")
		value = 15;
	else
		value = 0;
	return value ;
}                                                                                                                                         ;




function check_symbol(strOrg,strFind){
	var index = 0;
	index = strOrg.indexOf(strFind,index);
	return index;
}

function find_colon(strOrg,strFind)
{
        var index=0;
        var colon=0;
        index = strOrg.indexOf(strFind,index);
        while(index != -1)
        {
                colon++;
                index++;
                index = strOrg.indexOf(strFind,index);
        }
        return colon;
}

function count_colon_pos(strOrg,strFind,count)
{
        var index =0;
        var i=0;

        for(i=0;i<count;i++){
                index = strOrg.indexOf(strFind,index);
                index++;
        }
        return index;
}

function count_last_colon_pos(strOrg,strFind)
{
				var index =0;
				var pos=0;

        while(1){
                index = strOrg.indexOf(strFind,index);
                if(index == -1)
                		break;
                pos = index;
                index++;
        }
        return pos;
}
function compare_suffix(start_suffix,end_suffix)
{
	var start_suffix_length = start_suffix.length;
	var end_suffix_length = end_suffix.length;

	var start_suffix_value =0;
	var end_suffix_value=0;

	//calculate the start_suffix
	if(start_suffix_length == 1){
		start_suffix_value = transValue(start_suffix.charAt(0)) * 1;
	}else if(start_suffix_length == 2){
		start_suffix_value = transValue(start_suffix.charAt(0)) * 16;
		start_suffix_value += transValue(start_suffix.charAt(1)) * 1;
	}else if(start_suffix_length == 3){
		start_suffix_value = transValue(start_suffix.charAt(0)) * 256;
		start_suffix_value += transValue(start_suffix.charAt(1)) * 16;
		start_suffix_value += transValue(start_suffix.charAt(2)) * 1;
	}else if(start_suffix_length == 4){
		start_suffix_value = transValue(start_suffix.charAt(0)) * 4096;
		start_suffix_value += transValue(start_suffix.charAt(1)) * 256;
		start_suffix_value += transValue(start_suffix.charAt(2)) * 16;
		start_suffix_value += transValue(start_suffix.charAt(3)) * 1;
	}

	//calculate the end_suffix
	if(end_suffix_length == 1){
		end_suffix_value = transValue(end_suffix.charAt(0)) * 1;
	}else if(end_suffix_length == 2){
		end_suffix_value = transValue(end_suffix.charAt(0)) * 16;
		end_suffix_value += transValue(end_suffix.charAt(1)) * 1;
	}else if(end_suffix_length == 3){
		end_suffix_value = transValue(end_suffix.charAt(0)) * 256;
		end_suffix_value += transValue(end_suffix.charAt(1)) * 16;
		end_suffix_value += transValue(end_suffix.charAt(2)) * 1;
	}else if(end_suffix_length == 4){
		end_suffix_value = transValue(end_suffix.charAt(0)) * 4096;
		end_suffix_value += transValue(end_suffix.charAt(1)) * 256;
		end_suffix_value += transValue(end_suffix.charAt(2)) * 16;
		end_suffix_value += transValue(end_suffix.charAt(3)) * 1;
	}

	if(start_suffix_value >= end_suffix_value){
		alert(LangMap.which_lang['MSG040']);
		return false;
	}
	return true;
}

/*
function show_words(word){	
	with(document){
		return write(word);
	}
}
function show_words(idx) {
	//if(typeof(eval(idx)) == 'undefined')
	//	return;
	try {
		var id = eval(idx);
		document.write(LangMap.which_lang[id]);
	} catch (e) {
		alert('[show_words]::invalid idx: ' + idx);
	}
}
*/

function show_words(idx, lang_tb) {
	if (lang_tb == null) {
		lang_tb = LangMap.which_lang;
	}
	
	try {
		document.write(lang_tb[idx]);
	} catch (e) {
		//alert('invalid idx: ' + idx);
	}
}
/*
function get_words(idx) {
	//if(typeof(eval(idx)) == 'undefined')
	//	return '';
	try {
		var id = eval(idx);
		return LangMap.which_lang[id];
	} catch (e) {
		alert('[get_words]::invalid idx: ' + idx);
	}
	return '';
}
*/
function get_words(idx, lang_tb) {
	if (lang_tb == null) {
		lang_tb = LangMap.which_lang;
	}
	
	try {
		return lang_tb[idx];
	} catch (e) {
		//alert('invalid idx: ' + idx);
	}
}

function open_more(rule_num, num, is_hidden, obj){
	var open_word = "none";
	get_by_id("show_more_word").style.display = "";
	get_by_id("show_less_word").style.display = "none";
	if(is_hidden){
		get_by_id("show_more_word").style.display = "none";
		get_by_id("show_less_word").style.display = "";
		open_word = "";
	}
	var start_num = parseInt(rule_num-1,10);
	for(j=start_num;j>=num;j--){
		get_by_id(obj+j).style.display = open_word;
	}
}

/* GraceYang
Disable All Form Elements
 */
function DisableEnableForm(xForm,xHow){
  objElems = xForm.elements;
  for(i=0;i<objElems.length;i++){
    objElems[i].disabled = xHow;
  }
}
//Set Language & Response URL - GraceYang 20090217
function set_lang(){
   		//var url = document.URL.split("/");
   		get_by_id("language").value = get_by_id("site").value;
   		//get_by_id("html_response_lang").value = url[3];
   		send_submit('lang_form');
   }
   
function get_lang(){
//		 set_selectIndex(get_by_id("language").value, get_by_id("site"));
   }

/*The first argument is the sentence, and the other arguments are used to 
  replace word in the sentence with %[s,S,m,u,v,d,04X,04x,lu,02d]	*/

function lingual_replace(){	
	if(arguments.length<=0) return;

	var word = arguments[0];
	if(arguments.length > 1) {
		for(i=1;i<arguments.length;i++)
			word = word.replace(/%([s,S,m,u,v,d]|04X|04x|02d|lu)/,arguments[i]);				
	}
	return word;
}
function lingual_html_response_message(oriStr){
    var rtnStr="";
    if(oriStr == null || oriStr.length<1)   return rtnStr;

    if(oriStr.match("Success, system rebooting."))
        rtnStr=_success + " ("+YM3+")";
    else if(oriStr.match("Fail, system rebooting."))
        rtnStr=fl_Failure + " ("+YM3+")";
    else if(oriStr.match("Fail, firmware size error."))
        rtnStr=GW_UPGRADE_FAILED;
    else if(oriStr.match("Success, uploading finished."))
        rtnStr=rs_intro_4;
    else if(oriStr.match("Fail, configuration file error."))
        rtnStr=rs_intro_2;
    else if(oriStr.match("The setting is saved."))
        rtnStr=sc_intro_sv;
    else if(oriStr.match("Invalid password, please try again."))
        rtnStr=li_alert_3;
	else if(oriStr.match("Not Estimated"))
		rtnStr=at_NEst;
	else if(oriStr.match("Network is unreachable"))
		rtnStr=fl_Failure;
	else if(oriStr.match("Unknown Host"))
		rtnStr=GW_LAN_DOMAIN_NAME_INVALID;
	else if(oriStr.match("Success"))
		rtnStr=_success;
	else if(oriStr.match("Fail"))
		rtnStr=fl_Failure;
    else
        rtnStr=oriStr;

    return rtnStr;
}
   
//Get schedule value - Tina Tsao 20090410
function get_schedule_value(idx){
	var tmp_schedule_index = get_by_id("schedule" + idx).selectedIndex;
	var schedule, schedule_a;
	if (tmp_schedule_index > 1){
		schedule = get_by_id("schedule_rule_" + (tmp_schedule_index-2)).value;
		schedule_a = get_by_id("schedule_rule_" + (tmp_schedule_index-2)).value.split("/");
		schedule = schedule_a[0];
	}else if (tmp_schedule_index == 0){
		schedule = "Always";
	}else if (tmp_schedule_index == 1){
		schedule= "Never";
	}
	return schedule;
}

function get_schedule_index(which_value){
	var idx;
	for (var j = 0; j < 32; j++){
		var temp_sch = get_by_id("schedule_rule_" + j).value;
		var temp = temp_sch.split("/");
		if(which_value == temp[0]){
	  		idx = j;
		}
	}
	return idx;
}

function check_DeviceName(tmp_hostName){
        var i;
        var error =false;
        var tmp_count=0;
        
        if (tmp_hostName.length <= 63){
                var tmp_stringlength = tmp_hostName.length - 1
    for(i = 0; i<tmp_hostName.length; i++) {            
      var c = tmp_hostName.substring(i,i+1);
      if (("0" <= c && c <= "9")){
                                tmp_count=tmp_count+1;
                        }
                        if((("0" <= c && c <= "9") || ("a" <= c && c <= "z") || ("A" <= c && c <= "Z")||(i!=0 && c=="-")&&(i!=tmp_stringlength && c=="-"))&&(tmp_count != tmp_hostName.length)) {                         
         continue;
      }
      else{
                        alert(LangMap.msg['DDNS_HOST_ERROR']);
        error= true;
        return error;
      }
    }       
  }
  else{
        alert(LangMap.msg['DDNS_HOST_ERROR']);
    error= true;
        return error;
  }
}  
function check_wake_address(my_obj, mask_obj, ip_obj){
	var count_zero = 0;
	var count_bcast = 0;	
	var ip = my_obj.addr;
	var mask;
	
	if (my_obj.addr.length == 4){
		// check the ip is not multicast IP (127.x.x.x && 224.x.x.x ~ 239.x.x.x)
		if((my_obj.addr[0] == "127") || ((my_obj.addr[0] >= 224) && (my_obj.addr[0] <= 239))){
			alert(my_obj.e_msg[MULTICASE_IP_ERROR]);
			return false;
		}
		
		// check the ip is "0.0.0.0" or not
		for(var i = 0; i < ip.length; i++){
			if (ip[i] == "0"){
				count_zero++;			
			}
		}

		if (!my_obj.allow_zero && count_zero == 4){	// if the ip is not allowed to be 0.0.0.0
			alert(my_obj.e_msg[ZERO_IP]);			// but we check the ip is 0.0.0.0
			return false;
		}else if (count_zero != 4){		// when IP is not 0.0.0.0, checking range. Otherwise no need to check		
			count_zero = 0;
				
			if (check_wake_address.arguments.length >= 2 && mask_obj != null){
				mask = mask_obj.addr;
			}else{
				mask = new Array(255,255,255,0);
			}
						
			
			if ((count_zero == 4 && !my_obj.is_network) || (count_bcast == 4)){
				alert(my_obj.e_msg[INVALID_IP]);			
				return false;
			}													
		}
	}else{	// if the length of ip is not correct, show invalid ip msg
		alert(my_obj.e_msg[INVALID_IP]);
		return false;
	}

	return true;
}  

function itoa(i)
{ 
   return String.fromCharCode(i);
}

function decode(psstrs, iLen) 
{
   var oLen = (iLen*3) / 4;
   var out = '';
   var ip = 0;
   var op = 0;

   var map1="ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
   var i=0;

   var map2 = '';
   for (i=0; i<128; i++)
      	map2 += itoa(-1);
   for (i=0; i<64; i++)
   {
   	map2 = map2.substring(0,map1.charCodeAt(i)) + itoa(i) + map2.substring(map1.charCodeAt(i)+1,128);
   }

   while (iLen > 0 && psstrs.charCodeAt(iLen-1) == '=') iLen--;

   while (ip < iLen) {
      var i0 = psstrs.charCodeAt(ip++);
      var i1 = psstrs.charCodeAt(ip++);
      var i2 = ip < iLen ? psstrs.charCodeAt(ip++) : 'A';
      var i3 = ip < iLen ? psstrs.charCodeAt(ip++) : 'A';
      var b0 = map2.charCodeAt(i0);
      var b1 = map2.charCodeAt(i1);
      var b2 = map2.charCodeAt(i2);
      var b3 = map2.charCodeAt(i3);
      var o0 = (b0<<2) | (b1>>>4);
      var o1 = ((b1 & 0xf)<<4) | (b2>>>2);
      var o2 = ((b2 &   3)<<6) |  b3;
      out += itoa(o0);
      if (op<oLen) out += itoa(o1);
      if (op<oLen) out += itoa(o2); 
   }
   return out; 
}

function change_week(w)
{
	var Week = new Array(get_words('_Sun'), get_words('_Mon'), get_words('_Tue'), get_words('_Wed'), get_words('_Thu'), get_words('_Fri'), get_words('_Sat'));
	return Week[w];
}
function change_mon(m)
{
	var Month = new Array(get_words('tt_Jan'), get_words('tt_Feb'), get_words('tt_Mar'), get_words('tt_Apr'), get_words('tt_May'), get_words('tt_Jun'), get_words('tt_Jul'), 
						get_words('tt_Aug'), get_words('tt_Sep'), get_words('tt_Oct'), get_words('tt_Nov'), get_words('tt_Dec'));
	return Month[m];
}
function len_checked(obj)
{
	var lens = obj.toString().length;
	if (lens == 1)
		obj = '0' + obj;
	return obj;
}

//for Trendnet style
function changeMenuClass(tbObj, act)
{
	var tds = tbObj.getElementsByTagName('td');
	
	for(var i = 0; i < tds.length; i++){
		var className = tds[i].className;
		
		if (act == 'in') {
			if (className.indexOf('menu_sel_') != -1)
				continue;
			className = className.replace('menu_', 'menu_sel_');
		} else {
			className = className.replace('menu_sel_', 'menu_');
		}
		
		tds[i].className = className;
	}
}

function MM_openBrWindow(theURL,winName,features) { //v2.0
  funcWinOpen(theURL,winName,features);
}

function jq_ajax_post(url, data, logo_fw){
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	true,
		url: 	url,
		data: 	data+"&"+time+"="+time,
				
		success: function(data) {
			if (logo_fw != 1)
				endClock = new Date().getTime();
		}
	};
	$.ajax(ajax_param);
}

var renewTime=80;
var totalWaitTime;
var redirectURL;
var endClock;
var percent=null;
function wait_page()
{	
	if (percent == null)
	{
		endClock = new Date().getTime()+(totalWaitTime*1000);
		//redirectURL = ".." + location.pathname;
		document.getElementById("mainform").style.display = "none";
		document.getElementById("waitform").innerHTML = "<table bolder=\"0\"><tr><td colspan=\"2\"><font color=white>"+get_words('_processing_plz_wait')+"</font></td></tr><tr><td width=\"95%\"><table align=\"left\" bgcolor=\"#FFFFFF\" cellpadding=\"0\" cellspacing=\"0\" bordercolor=\"#000000\" style=\"border-style: solid; border-width: 1px\"><tr><td width=545 align=\"left\"><table id=\"progress\" bgcolor=\"blue\" height=\"25\"><tr><td></td></tr></table></td></tr></table></td><td width=\"95%\"><font color=white><span id=\"progressValue\">&nbsp;</span></font></td></tr></table>";
	}
	percent = (totalWaitTime*1000-(endClock-new Date().getTime()))/(totalWaitTime*10);
	if (percent >= 100) {
		if(redirectURL == null || redirectURL == "")
			redirectURL = "/";
		location.href = redirectURL;
		return;
	}
	document.getElementById("progress").style.width = Math.round(percent) + "%";
	document.getElementById("progressValue").innerHTML = Math.round(percent) + "%";
	window.setTimeout("wait_page()", renewTime);
}

/*
** check words if contains space or not.
** @author:	Moa
** @date:	2013-03-18
** @param:	words - the words be checked.
** @return:	true - the contains space.
			false - the not contains space.
*/
function isContainSpace(words)
{
	for(var i=0;i<words.length;++i)
	{
		var word = words.substring(i,i+1);
		if(word==' ')
			return true;
	}
	return false;
}
/*
** send an jQuery ajax without callback handler
** @author:	Moa Chung
** @date:	2013-07-17
** @param:	param - an object like {url:'',arg:''}
** 			isAsync - asynchronized or not (optional, default=true)
** 
*/
function ajax_submit(param,isAsync)
{
	var time=new Date().getTime();
	var ajax_param = {
		type: 	"POST",
		async:	(isAsync==undefined?true:isAsync),
		url: 	param.url,
		data: 	param.arg+"&"+time+"="+time
	};

	$.ajax(ajax_param);
}