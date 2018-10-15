<form name="dataForm" id="dataForm" enctype="multipart/form-data" action="index.php?page=master" method="post">
	<input type="hidden" id="loading" value="true">
	<input type="hidden" name="menu1" id="menu1" value="{$navigation.1}">
	<input type="hidden" name="menu2" id="menu2" value="{$navigation.2}">
	<input type="hidden" name="menu3" id="menu3" value="{$navigation.3}">
	<input type="hidden" name="menu4" id="menu4" value="{$navigation.4}">
	<input type="hidden" name="mode7" id="mode7" value="">
	<input type="hidden" name="mode8" id="mode8" value="">
	<input type="hidden" name="mode9" id="mode9" value="">
	<input type="hidden" name="Action" id="Action" value="">
	<input type="hidden" id="logout">
	{include file="help.tpl"}
	<div id="bodyblock" {if $templateName eq 'Login.tpl'}align="center"{/if} style="background-color: #FFFFFF;">
		<table {if $templateName eq "Login.tpl"}align="center"{else}id="bodyContainer"{/if}>
			<tr>
				<td class="font15Bold">{if $templateName neq 'Login.tpl'}{$navigation.0|replace:"&nbsp;":""}{/if}&nbsp;</td>
			</tr>
			<tr>
				<td class="spacerHeight9" style="text-align: center;" align="center">
					<table id="errorMessageBlock" align="center" style="margin: 4px auto 10px auto; {if $errorString eq ''}display: none;{elseif $templateName eq "Login.tpl"}margin-left: 10px; text-align: center;{/if}">
						<tr>
							<td style="padding: 5px; vertical-align: top;"><img src="images/alert.gif" style="border: 0px; padding: 0px; margin: 0px;"></td>
							<td style="padding: 5px 5px 5px 0px; vertical-align: middle;"><b id="br_head">{if $errorString eq ''}Please address the fields highlighted!{else}{$errorString}{/if}</b></td>
						</tr>
						<tr>
							<td style="padding: 0px; vertical-align: top;"></td>
							<td style="padding: 0px 5px 5px 0px; text-align: left;"></td>
						</tr>
					</table>
				</td>
			</tr>
			{*include file="sample_datablock.tpl"*}
			{php}
			//echo $this->_tpl_vars['templateName'];
			if (file_exists("templates/".$this->_tpl_vars['templateName'])) {
			{/php}{include file=$templateName}{php}
			}
			else {
			{/php}{include file="404.tpl"}{php}
			}
			{/php}
			<!--@@@USERACCOUNTSSTART@@@-->
			{if $config.USERACCOUNTS.status}
			{if $templateName neq 'Login.tpl'}
			{if (($smarty.session.previlige)=='rw' && $navigation.2 != 'Logs' && ($navigation.1 == 'Monitoring' || $navigation.1 == 'Maintenance' || $navigation.4 == 'User Accounts') )}
				<script>
				{literal}
				<!--
						var inputs=fetchAllInputFields();
						for (var a = 0; a < inputs.length; a++)
						{
						inputs[a].disabled = true;
						}
						-->
				{/literal}
				</script>
			{/if}
			{if (($smarty.session.previlige)=='ro' && $navigation.4 != 'Change Password')}
				<script>
				{literal}
				<!--
						var inputs=fetchAllInputFields();
						if(window.top.frames['action'].$('save'))
						{
						window.top.frames['action'].$('save').disabled=true;
						window.top.frames['action'].$('save').src="images/save_off.gif";
						}
						if(window.top.frames['action'].$('saveas'))
						{
						window.top.frames['action'].$('saveas').disabled=true;
						window.top.frames['action'].$('saveas').src="images/save_as_off.gif";
						window.top.frames['action'].$('clear').disabled=true;
						window.top.frames['action'].$('clear').src="images/clear_off.gif";
						}	
						for (var a = 0; a < inputs.length; a++)
						{
						inputs[a].disabled = true;
						}
						$('inlineTab1').observe('click', disableForm);
						$('inlineTab2').observe('click', disableForm);
						function disableForm()
						{
						Form.disable(document.dataForm);
						}
						-->
				{/literal}
				</script>
				{/if}
			{/if}
			{/if}
			<!--@@@USERACCOUNTSEND@@@-->
		</table>
	</div>
</form>
<!--@@@PRODUCTREGISTRATIONSTART@@@-->
{if !$config.LGE-WAP2080.status || !$config.LGE-WAP2080.status}
{php} 
if ($this->_tpl_vars['navigation']['4'] != "Product Registration"):  
/*Product Registration check*/
/*Unlink Turnoff option after 24 hours*/
$PopupTurnOff="/var/Prod_Reg_Rem_TurnOff";
$PopupRemindLater="/tmp/Popup_RemindLater";
if(file_exists($PopupRemindLater)) 
 {  
    if ((filemtime($PopupRemindLater)+86400) <= time()) 
    {
        @unlink($PopupRemindLater);    
    }
 } 
/*Check for Product Registrion Reminder Turnoff */
$PopupRemindLater="/tmp/Popup_RemindLater";
if(!file_exists("/var/SerialRegistered")) 
{
	if(!file_exists("/var/Prod_Reg_Rem_TurnOff")) 
	 {
	     if(!file_exists("/tmp/Popup_RemindLater")) 
	     {   
			/*Check for Session id*/
			$sd = explode(',',@file_get_contents('/tmp/sessionid'));
			 if ($sd[0] == session_id())
	        { 
				$fp = @fsockopen("www.netgear.com", 80, $errno, $errstr, 1);
				if($fp) 
	            {
					{/php}
					<script type='text/javascript'>
					{php} 
					echo "
					new Ajax.Request('/productregistration/Prod_Reg_SerialCheck.php',
						{
						  method:'get',
						  parameters: { id: Math.floor(Math.random()*10005) },    
						  onSuccess: function(RegisterStat){
						  var response = RegisterStat.responseText;
						  if(response=='notregistered')
						   ProductRegistration()
						  else if(response=='registered')
						   SerialRegistered()
						},
						onFailure: function(){ alert('Something went wrong...') }
						});
					 "; 
					 {/php}
					</script>
					{php}
	            } 
	            else
				{
	            /*echo "<script>alert('No Access to Remote Server')</script>";*/
				}
				@fclose($fp);
	        }
	    } 
	}
}  
endif; 
 {/php}
{/if}
<!--@@@PRODUCTREGISTRATIONEND@@@-->