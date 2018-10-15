function select_ipv6_wan_page(ipv6_sel_wan){ 	
	location.href = ipv6_sel_wan+".asp";
}


// ipv6 address
function ipv6_addr_obj(addr, e_msg, allow_zero, is_network){
	this.addr = addr;
	this.e_msg = e_msg;
	this.allow_zero = allow_zero;		
	this.is_network = is_network;	
}	

function check_ipv6_symbol(strOrg,strFind){
	/*For fitting old check_ipv6_address function use*/		
	/*if false return 2, if have double-colon return 1, completely IPv6 address return 0*/
	
	var symbol_count=0; // colon acount
	var _index = 0;
	var current_index =-1;
	var dc_flag=0; //numbers of back-to-back colons
	
	// Rewrite this function by setting strFind = ":", but not removed strFind	argument
	strFind = ":";		

	for(_index=0;_index<strOrg.length;_index++)
	{
	  _index = strOrg.indexOf(strFind,_index);		
	  //strOrg.indexof() method works rotationlly, so I need to set a break
	 if(_index<=-1)			
		break;
	 else{
	     if(_index == -1){
		return -1;
	     }else{
		symbol_count++;
		if(current_index != -1 && (_index-current_index)==1){
			dc_flag++;
			if(dc_flag>1){
				alert(get_words('MSG041'));
				return 2;
			}
		}	
	//alert("symbol_count="+symbol_count+"\n index ="+ _index+"current ="+ current_index+"\n dc_flag:"+dc_flag);
		current_index = _index;			
	    }		
	 }		
	}
	if(symbol_count<2 || symbol_count>7){ // colon acount is illegal.	  
		alert(get_words('MSG042'));
		return 2;	
	}	
	if(symbol_count>=2 && symbol_count<7 && dc_flag==0){	//simplified IPv6 address should have double-colon
		alert(get_words('MSG042'));
		return 2;	
	}	
	if(symbol_count==7 && dc_flag>0){ //complete IPv6 address should not have any double-colon	
		alert(get_words('MSG042'));
		return 2;
	}	
	return dc_flag;
}   
   
function check_ipv6_address(my_obj,strFind){
	
	var ip = my_obj.addr;
	var count_zero=0;  
	var ip_temp;
	var sum = 0;
	var ipv6_field_number = 0;
		 
	
	if(strFind == "::"){ 		
		if (my_obj.addr.length == 2){ //檢查是否有2個區塊
			
			//check the ip is not multicast IP (FFxx:0:0:0:0:0:0:2 or ffxx:0:0:0:0:0:0:2) 不可有ff or FF 開頭
			if(ip[0].charAt(0) =="f" || ip[0].charAt(0) =="F"){
				if(ip[0].charAt(1) =="f" || ip[0].charAt(1) =="F"){
					alert(my_obj.e_msg[IPv6_MULTICASE_IP_ERROR]);
					return false;
				}		
			}	 		
			 						 
			for(var i = 0; i < 2; i++){	//利用'::' 分成前半段 和 後半段
			
				ip_temp = ip[i].split(":");
																
				for(var index =0; index < ip_temp.length; index++){
	
					if((!my_obj.allow_zero && ip_temp[index].length == 0) || ip_temp[index].length > 4){
						alert(my_obj.e_msg[IPv6_INVALID_IP]);
						return false;
					}
					
					for(var pos =0; pos < ip_temp[index].length ;pos++){
						if(!check_hex(ip_temp[index].charAt(pos))){
							alert(my_obj.e_msg[IPv6_FIRST_IP_ERROR+ipv6_field_number]);
							return false;
						}
						  
						// check the ip is "0:0:0:0:0:0:0:0" or not
						sum += transValue(ip_temp[index].charAt(pos))*(pos+1);	 //calculate the ipv6 weight value
					}
					ipv6_field_number++;		
				}										
			}
			
			if(!my_obj.allow_zero && sum == 0){   // 0 represents ipv6 has zero address
				alert(my_obj.e_msg[IPv6_ZERO_IP]);			
				return false; 	
			}
						
		}else{	// if the length of ip is not correct, show invalid ip msg			
			alert(my_obj.e_msg[IPv6_INVALID_IP]);
			return false;
		}
	}  
	else{		
		if (my_obj.addr.length == 8){ //檢查是否有8個區塊
			
			//check the ip is not multicast IP (FFxx:0:0:0:0:0:0:2 or ffxx:0:0:0:0:0:0:2) 不可有ff or FF 開頭
			if(ip[0].charAt(0) =="f" || ip[0].charAt(0) =="F"){
				if(ip[0].charAt(1) =="f" || ip[0].charAt(1) =="F"){
					alert(my_obj.e_msg[IPv6_MULTICASE_IP_ERROR]);
					return false;
				}		
			}	 		
			    
			// check the ip is "0:0:0:0:0:0:0:0" or not 
			for(var i = 0; i < ip.length; i++){
				if (ip[i] == "0"){
					count_zero++;
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0")){
					count_zero++;	
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0") && (ip[i].charAt(2) =="0")){
					count_zero++;	
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0") && (ip[i].charAt(2) =="0") && (ip[i].charAt(3) =="0")){
					count_zero++;	
				}
				
			}  
						  
			if (!my_obj.allow_zero && count_zero == 8){	// if the ip is not allowed to be 0:0:0:0:0:0:0:0
				alert(my_obj.e_msg[IPv6_ZERO_IP]);			
				return false; 
			}else{
				
				count_zero=0;
				for(var i = 0; i < ip.length; i++){

						
					if(ip[i].length > 4 || ip[i].length == 0){
						alert(my_obj.e_msg[IPv6_INVALID_IP]);
						return false;
					}		
							
					for(var index =0; index < ip[i].length ;index++){
						if(!check_hex(ip[i].charAt(index))){
							alert(my_obj.e_msg[IPv6_FIRST_IP_ERROR+i]);
							return false;
						}	 	
					}
				}
				 		
			}
			
			
			
		}else{	// if the length of ip is not correct, show invalid ip msg
			alert(my_obj.e_msg[IPv6_INVALID_IP]);
			return false;
		}	
	}

	return true;
} 

function check_ipv6_route_address(my_obj,strFind){
	
	var ip = my_obj.addr;
	var count_zero=0;  
	var ip_temp;
	var sum = 0;
	var ipv6_field_number = 0;
		 
	
	if(strFind == "::"){ 		
		if (my_obj.addr.length == 2){ //檢查是否有2個區塊
			
			//check the ip is not multicast IP (FFxx:0:0:0:0:0:0:2 or ffxx:0:0:0:0:0:0:2) 不可有ff or FF 開頭
			if(ip[0].charAt(0) =="f" || ip[0].charAt(0) =="F"){
				if(ip[0].charAt(1) =="f" || ip[0].charAt(1) =="F"){
					alert(my_obj.e_msg[IPv6_MULTICASE_IP_ERROR]);
					return false;
				}		
			}	 		
			 						 
			for(var i = 0; i < 2; i++){	//利用'::' 分成前半段 和 後半段
			
				ip_temp = ip[i].split(":");
																
				if(ip[i]!=""){
				for(var index =0; index < ip_temp.length; index++){
	
					if((!my_obj.allow_zero && ip_temp[index].length == 0) || ip_temp[index].length > 4){
						alert(my_obj.e_msg[IPv6_INVALID_IP]);
						return false;
					}
					
					for(var pos =0; pos < ip_temp[index].length ;pos++){
						if(!check_hex(ip_temp[index].charAt(pos))){
							alert(my_obj.e_msg[IPv6_FIRST_IP_ERROR+ipv6_field_number]);
							return false;
						}
						  
						// check the ip is "0:0:0:0:0:0:0:0" or not
						sum += transValue(ip_temp[index].charAt(pos))*(pos+1);	 //calculate the ipv6 weight value
					}
					ipv6_field_number++;		
				}
				}
										
			}
			
			if(!my_obj.allow_zero && sum == 0){   // 0 represents ipv6 has zero address
				alert(my_obj.e_msg[IPv6_ZERO_IP]);			
				return false; 	
			}
						
		}else{	// if the length of ip is not correct, show invalid ip msg			
			alert(my_obj.e_msg[IPv6_INVALID_IP]);
			return false;
		}
	}  
	else{		
		if (my_obj.addr.length == 8){ //檢查是否有8個區塊
			
			//check the ip is not multicast IP (FFxx:0:0:0:0:0:0:2 or ffxx:0:0:0:0:0:0:2) 不可有ff or FF 開頭
			if(ip[0].charAt(0) =="f" || ip[0].charAt(0) =="F"){
				if(ip[0].charAt(1) =="f" || ip[0].charAt(1) =="F"){
					alert(my_obj.e_msg[IPv6_MULTICASE_IP_ERROR]);
					return false;
				}		
			}	 		
			    
			// check the ip is "0:0:0:0:0:0:0:0" or not 
			for(var i = 0; i < ip.length; i++){
				if (ip[i] == "0"){
					count_zero++;
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0")){
					count_zero++;	
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0") && (ip[i].charAt(2) =="0")){
					count_zero++;	
				}else if((ip[i].charAt(0) =="0") && (ip[i].charAt(1) =="0") && (ip[i].charAt(2) =="0") && (ip[i].charAt(3) =="0")){
					count_zero++;	
				}
				
			}  
						  
			if (!my_obj.allow_zero && count_zero == 8){	// if the ip is not allowed to be 0:0:0:0:0:0:0:0
				alert(my_obj.e_msg[IPv6_ZERO_IP]);			
				return false; 
			}else{
				
				count_zero=0;
				for(var i = 0; i < ip.length; i++){

						
					if(ip[i].length > 4 || ip[i].length == 0){
						alert(my_obj.e_msg[IPv6_INVALID_IP]);
						return false;
					}		
							
					for(var index =0; index < ip[i].length ;index++){
						if(!check_hex(ip[i].charAt(index))){
							alert(my_obj.e_msg[IPv6_FIRST_IP_ERROR+i]);
							return false;
						}	 	
					}
				}
				 		
			}
			
			
			
		}else{	// if the length of ip is not correct, show invalid ip msg
			alert(my_obj.e_msg[IPv6_INVALID_IP]);
			return false;
		}	
	}

	return true;
} 

function get_stateful_ipv6(ipv6_addr)
{
	var ipv6_addr_prefix=""; 
	var ipv6_addr_suffix="";
	var index=0;
	var string_len=0;
	var colon=0;
	var total_colon=0;
	var fields=0;
	var zero_ipv6_addr="";
	var i=0;

	string_len = ipv6_addr.length;
	index = check_symbol(ipv6_addr,"::"); 
	if(index != -1){
		ipv6_addr_prefix = ipv6_addr.substring(0,index);
		ipv6_addr_suffix = ipv6_addr.substring(index+2,string_len);	
                //count the colon 
                colon = find_colon(ipv6_addr_prefix,":");
                total_colon = colon;
                colon = find_colon(ipv6_addr_suffix,":");
                total_colon += colon;
                fields = total_colon+2;
                //insert "0" to zero_ipv6_addr
                for(i=0;i<(8-fields);i++){
                        zero_ipv6_addr += ":0"; 		                               
	        }
	        ipv6_addr = ipv6_addr_prefix+ zero_ipv6_addr +":"+ ipv6_addr_suffix;
        }		
	return  ipv6_addr;
}

function get_stateful_prefix(ipv6_addr,length){
        var index=0;
        var ipv6_addr_prefix="";
        
        if(length == 64)
                index = count_colon_pos(ipv6_addr,":",4);
         if(length == 112)
                index = count_colon_pos(ipv6_addr,":",7);
               
        ipv6_addr_prefix = ipv6_addr.substring(0,index-1);
        return ipv6_addr_prefix;                  
}
         
    
function get_stateful_suffix(ipv6_addr){
        var index=0;
        var ipv6_addr_suffix="";
        var string_len=0;
 				 				       
        string_len = ipv6_addr.length;        
        index = count_last_colon_pos(ipv6_addr,":");
       	ipv6_addr_suffix = ipv6_addr.substring(index+1,string_len);	
        return ipv6_addr_suffix;                  
}        
 
function check_ipv6_address_suffix(strOrg,tag){
				
	if( strOrg.length > 0 && strOrg.length < 5){
		for(var index =0; index < strOrg.length ;index++){
			if(!check_hex(strOrg.charAt(index))){
				//alert("The suffix of "+tag +" must be hexadecimal.");
				alert(get_words('MSG036_1')+" "+tag +" "+get_words('MSG038_1'));
				return false;
			}	 	
		}
	}else{
		//alert("The suffex of "+tag +" is an invalid address.");
		alert(get_words('MSG036_1')+" "+tag +" "+get_words('MSG039_1'));
		return false;	
	}
	
	return true;			
}

function check_lan_ipv6_subnet(strOrg,tag){
				
	if( strOrg.length > 0 && strOrg.length < 5){
		for(var index =0; index < strOrg.length ;index++){
			if(!check_hex(strOrg.charAt(index))){
				alert(get_words('MSG037_1')+" "+tag +" "+get_words('MSG038_1'));
				//alert("The subnet of "+tag +" must be hexadecimal.");
				return false;
			}	 	
		}
	}else{
		alert(get_words('MSG037_1')+" "+tag +" "+get_words('MSG039_1'));
		//alert("The subnet of "+tag +" is an invalid address.");
		return false;	
	}
	
	return true;			
}

/** 20120229 silvia add to filter ipv6 addr 0:0:0~ */
function filter_ipv6_addr(data)
{
	var v6_ip = data;
	var v6_ip_s = data.split(':');
	var tmp_ip ='';
	var tmp_ip_1 ='';
	var tmp_ip_2 ='';
	var isabridge = data.split('::');

	if (isabridge.length == 2)
		return v6_ip;

	for (var i = 0; i <8; i++)
	{
		if (v6_ip_s[i] == 0)
		{
			tmp_ip += (i==0)? '0': ':0';
		}else{
			if (tmp_ip_1 == '')
			{
				tmp_ip_1 = tmp_ip;
				tmp_ip = '';
			}else{
				tmp_ip_2 = tmp_ip;
				tmp_ip = '';
			}
		}
		if ((tmp_ip_1 != '') && (v6_ip_s[7] == 0))
			tmp_ip_2 = tmp_ip + ':';
	}
	tmp_ip = (tmp_ip == '')?((tmp_ip_1.length >= tmp_ip_2.length)?tmp_ip_1:tmp_ip_2):tmp_ip;

	if (tmp_ip ==':0')
		return v6_ip;

	if ((data.indexOf(tmp_ip) != -1) && (tmp_ip != ''))
		v6_ip = (data.replace(tmp_ip, ":") + ':');
		
	if ((v6_ip.indexOf('::') != -1) && (v6_ip.length == (v6_ip.lastIndexOf(':')+1))
		&& (v6_ip.length != (v6_ip.lastIndexOf('::')+2)))
		v6_ip = v6_ip.substring(0,v6_ip.length-1);

	return v6_ip;
}