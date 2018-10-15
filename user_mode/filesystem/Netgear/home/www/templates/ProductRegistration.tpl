	<tr>
		<td>
			<table class="tableStyle">
			<tr>
					<td width="100%" colspan="2" align="center">
						<div align="center" style="margin:auto;">
							
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="3">
						<table class='tableStyle'>
						<tr>
							<td colspan="3"><script>tbhdr('Registration','ProductRegistration')</script></td>
						</tr>
						</table>
					</td>
				</tr>
				<tr >
					<td class="subSectionBodyDot">&nbsp;&nbsp;&nbsp;</td>
					<td  class = "helppageattributes" style='padding:10px'>
					<div id='Registration_popup' style='display:none'>
					<p style='font-size:12px;line-height: 1.6em;'>We are delighted to have you as a customer.<br>Registration confirms your email alerts will work,<br> lowers technical support resolution time and ensures your<br>shipping address accuracy. We'd also like to incorporate<br>your feedback into future product development.</p>
					<br>
					<p style='font-size:12px;line-height: 1.6em;'>NETGEAR will never sell or rent your email address and <br>you may opt out of communications at any time.</p>
					</div>
					{php}
					if(@fsockopen("www.netgear.com",80)) 
						{
					{/php}
					<script type='text/javascript'>
					<!--
					{literal}
						new Ajax.Request('/productregistration/Prod_Reg_SerialCheck.php',
						  {
						  method:'get',
						  parameters: { id: Math.floor(Math.random()*10005) },    
						  onSuccess: function(RegisterStat){
						  var response = RegisterStat.responseText;
						  if(response=='notregistered')
								{
								  document.getElementById('Registration_popup').style.display='block';						  
									var buttons = new buttonObject();
									buttons.getStaticButtons(['register']);
									top.action.$('register').style.display = 'block';
								}
								else if(response=='registered')
								{
									document.getElementById('Registration_popup').style.display='block';
									document.getElementById('Registration_popup').innerHTML="<p style='font-size:12px;'>The product has being registered, no further action required.</p>";
									var buttons = new buttonObject();
									buttons.getStaticButtons(['back']);
									function doBack()
									{
									doSubmit('cancel');
									}

								}							

							}
						});
					{/literal}
					-->
					</script>
					{php}
						} 
					else
					{
					{/php}
					<script type='text/javascript'>
					<!--
					{literal}
					$('br_head').innerHTML = "Error: Registration server is not accessible;<br>please check your internet connectivity of the product";
					$('errorMessageBlock').show();
				    top.action.$('register').style.display = 'none';
					{/literal}
					-->
					
					</script>
					{php}
					}
					{/php}
					</td>
					<td class="subSectionBodyDotRight">&nbsp;&nbsp;&nbsp;</td>
				</tr>
					<td class="subSectionBodyDot" >&nbsp;</td>
					<td >
					</td>
					<td class="subSectionBodyDotRight">&nbsp;</td>
				<tr>
					<td colspan="3" class="subSectionBottom">&nbsp;</td>
				</tr>
			</table>
		</td>
	</tr>
