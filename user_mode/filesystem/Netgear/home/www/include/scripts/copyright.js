function checkSession()
{
        var oOptions = {
                         method: "get",
                        asynchronous: false,
                        parameters: { checkActiveSession: 'check', id: Math.floor(Math.random()*1000001) },
                                                onSuccess: function (oXHR, oJson) {
                                var response = oXHR.responseText;
                                if(response == 'expired') {
                                        window.opener.location.href = window.opener.location.href;
										window.opener.focus();
										window.close();

                                }
                              }
                       };
                var req = new Ajax.Request('/checkSession.php', oOptions);
}
function getCopyright(copyright)
{
var copyright="Copyright &copy; 1996-2015 Netgear &reg;";
document.write(copyright);
}