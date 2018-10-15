<tr>
	<td class="centered">
		<input type=checkbox id="<? echo 'enable_'.$ROUTING_INDEX;?>">
		<input type="hidden" id="<?echo "uid_".$ROUTING_INDEX;?>" value="<?=$ROUTING_INDEX?>"/>	
	</td>
	
	<td class="centered" >
		<select id="<?echo "inf_".$ROUTING_INDEX;?>" style="width: 150px;">
			
<?		
			include "/htdocs/phplib/xnode.php";
			
			/*
				D-link spec. 
				If PPTP, should show 
				 WAN (ip)
				 WAN Physical (ip)
				When in PPTP mode, we use the value of 'lowerlayer' decide which ifname is physical. 
			*/
			$wan_lowerlayer = "";
			
			$i=1;
			while ($i>0 && $i<4)
			{
				$ifname = "WAN-".$i;
				$ifpath = XNODE_getpathbytarget("runtime", "inf", "uid", $ifname, 0);
				$ifpath2 = XNODE_getpathbytarget("", "inf", "uid", $ifname, 0);
				
				if ($ifpath == "") { $i++; continue; }
				
				$inet_addrtype = query($ifpath."/inet/addrtype");
				
				$lowerlayer = query($ifpath2."/lowerlayer");
				if($lowerlayer != "") { $wan_lowerlayer = $lowerlayer; }
				
				$str = "";
				if($wan_lowerlayer == $ifname) { $str = "Physical"; }
				
				if($inet_addrtype == "ipv4")
				{
					$ip = query($ifpath."/inet/ipv4/ipaddr");
					$show_ifname = "WAN ".$str." (".$ip.")";
				}
				else if($inet_addrtype == "ppp4")
				{
					$ip = query($ifpath."/inet/ppp4/local");
					$show_ifname = "WAN ".$str. "(".$ip.")";
				}
				else
				{
					$i++; 
					continue;
				}
					echo '<option value="'.$ifname.'">'.$show_ifname.'</option>';
					$i++;
			}
			
			/*
			//$i=1;
			$i=3;
			while ($i>0 && $i<4)
			{
				$ifname = "LAN-".$i;
				$ifpath = XNODE_getpathbytarget("runtime", "inf", "uid", $ifname, 0);
				//if ($ifpath == "") { $i++; continue; }
				if ($ifpath == "") { $i--; continue; }
				$inet_uid = query($ifpath."/inet/uid");
				$inet_path = XNODE_getpathbytarget("inet", "entry", "uid", $inet_uid, 0);
				$inet_addrtype = query($inet_path."/addrtype");
				
				//$show_ifname = $ifname;
		        if($inet_addrtype == "ipv4")
		        {
					//$show_ifname = $ifname."(".query($inet_path.'/ipv4/ipaddr').")";
					$show_ifname = $ifname;
		        } else
		        {
		        	//$i++;
		        	$i--;
		        	continue;	
		        }
		        	
				echo '<option value="'.$ifname.'">'.$show_ifname.'</option>';
				//$i++;
				$i--;
			}
			*/
?>
		</select>
	</td>
	<td class="centered"><input type=text id="<? echo 'dstip_'.$ROUTING_INDEX;?>" size=16 maxlength=15></td>
	<td class="centered"><input type=text id="<? echo 'netmask_'.$ROUTING_INDEX;?>" size=16 maxlength=15></td>
	<td class="centered"><input type=text id="<? echo 'gateway_'.$ROUTING_INDEX;?>" size=16 maxlength=15></td>
</tr>
