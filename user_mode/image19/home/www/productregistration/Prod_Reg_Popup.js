//xhtml popup
function ProductRegistration(){
       var c=msgbox("<div class='msgboxdesc'><table id='bodyContainer'><tr><td colspan='3'><div><table></tr></td><table class='tableStyle'><tr><td class='subSectionTabTopLeft spacer80Percent font12BoldBlue' >Product Registration</td><td class='subSectionTabTopRight spacer20Percent'></td></tr><tr><td class='subSectionTabTopShadow' colSpan='3'></td></tr></table></div></td></tr>"+
                    "<tr><td class='subSectionBodyDot'>&nbsp;&nbsp;&nbsp;&nbsp;</td><td><p>We are delighted to have you as a customer. Registration confirms your email alerts will work, lowers "+ 
                        "technical support resolution time and ensures your shipping address accuracy. We'd also like <br>"+
                        "to incorporate your feedback into future product development.<br><br>NETGEAR will never sell or rent your email address and you may opt out of communications at any time.<br><br>"+
					"Please register now. At a later time, you can also register by<br>choosing Support > Registration from the menu toolbar.</div>"+
					"<div class='msgboxdesc' >"+
					"<table style='float:right;margin-bottom:10px;padding-right:15px'><tr><td><input style='width:80px' class='msgboxbuttons' type='button' value='TURN OFF' onclick=javascript:checkSession('turnoff')></td>"+
					"<td><input style='width:120px' class='msgboxbuttons' type='button' value='REMIND ME LATER' onclick=javascript:checkSession('remindlater')></td>"+
					"<td ><input style='width:100px' class='msgboxbuttons' type='button' value='REGISTER NOW' onclick=javascript:Register()></td></table></p></td><td class='subSectionBodyDotRight'>&nbsp;&nbsp;&nbsp;</td></tr><tr><td colspan='3' class='subSectionBottom'>&nbsp;</td></tr></table></div>")
}

function msgbox(message,buttons,resfunc){
if(resfunc==undefined)resfunc=function(){return false;};

var disablediv=document.createElement("iframe");
disablediv.setAttribute("frameBorder","0");
disablediv.className="disablediv";
disablediv.setAttribute("class","disablediv");
disablediv.setAttribute("id","invisiblediv");
document.body.appendChild(disablediv);

msg=document.createElement("div");
msg.className="dvmsgbox";
msg.setAttribute("class","dvmsgbox");
msg.setAttribute("id","contentdiv");

msg.disablediv=disablediv;
msg.resfunc=resfunc;

var strHTML="<p class='msgboxdesc'>"+message+"</p>";
msg.innerHTML=strHTML;

document.body.appendChild(msg);
return msg;
}
function checkSession(func)
{
        var oOptions = {
                         method: "get",
                        asynchronous: false,
                        parameters: { checkActiveSession: 'check', id: Math.floor(Math.random()*1000001) },
                                                onSuccess: function (oXHR, oJson) {
                                var response = oXHR.responseText;
                                if(response == 'expired') {
                                        window.top.location.href = "index.php";
                                }
                                else if(response =='active')
                                {
                                  if(func=='remindlater')
                                  RegisterLater()
                                  else if(func=='turnoff')
                                  TurnOffReminder()
                                }
                              }
                       };
                var req = new Ajax.Request('/checkSession.php', oOptions);
}


//Register
function Register()
{
    window.open("productregistration/Product_Reg_form.php");
    var getid=document.getElementById('contentdiv')
    document.body.removeChild(getid);
    var getid=document.getElementById('invisiblediv')    
    document.body.removeChild(getid);
    var element = document.getElementById('roundcorners');
    element.parentNode.removeChild(element);    


}
//Register Later
function RegisterLater()
{
       new Ajax.Request('/productregistration/RegistrationStatus.php?status=remindlater',
	   {
	    method:'get',
	    parameters: { id: Math.floor(Math.random()*10005) },    
	    onSuccess: function(transport){
	    var response = transport.responseText || "no response text";
	    responseStat=response;
	   },
	onFailure: function(){ alert('Something went wrong...') }
	});
       var getid=document.getElementById('contentdiv')
       document.body.removeChild(getid);
       var getid=document.getElementById('invisiblediv')    
       document.body.removeChild(getid);
	var element = document.getElementById('roundcorners');
       element.parentNode.removeChild(element);    

}
//Turnoff Register
function TurnOffReminder()
{
	  new Ajax.Request('/productregistration/RegistrationStatus.php?status=turnoff',
	  {
	  method:'get',
	  parameters: { id: Math.floor(Math.random()*10005) },    
	  onSuccess: function(transport){
	  var response = transport.responseText || "no response text";
	  responseStat=response;
		},
	onFailure: function(){ alert('Something went wrong...') }
	});
       var getid=document.getElementById('contentdiv')
       document.body.removeChild(getid);
       var getid=document.getElementById('invisiblediv')    
       document.body.removeChild(getid);
	var element = document.getElementById('roundcorners');
       element.parentNode.removeChild(element);    


}
//Product Registered
function SerialRegistered()
{
	  new Ajax.Request('/productregistration/RegistrationStatus.php?status=registered',
	  {
	  method:'get',
	  parameters: { id: Math.floor(Math.random()*10005) },    
	  onSuccess: function(transport){
	  var response = transport.responseText || "no response text";
	  responseStat=response;
		},
	onFailure: function(){ alert('Something went wrong...') }
	});

}

function registerForm()
{
	window.open("/productregistration/Product_Reg_form.php");
}