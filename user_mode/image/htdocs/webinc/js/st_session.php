<? include "/htdocs/phplib/xnode.php"; ?>
<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "RUNTIME.INF.LAN-1",
	OnLoad: function() {},
	OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var infp = PXML.FindModule("RUNTIME.INF.LAN-1");
		infp += "/runtime/inf/inet/ipv4";
		this.ipaddr	= XG(infp+"/ipaddr");
		this.mask	= XG(infp+"/mask");
		this.OnClickRefresh();
		return true;
	},
	PreSubmit: function() { return null; },
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	ipaddr: null,
	mask: null,
	ctp: "/conntrack/message",
	OnClickRefresh: function()
	{
		BODY.CleanTable("sess_list"); 
		var ajaxObj = GetAjaxObj("SERVICE");
		AUTH.UpdateTimeout();
		ajaxObj.createRequest();
		ajaxObj.onCallback = function (xml)
		{
			ajaxObj.release();
			switch (xml.Get("/conntrack/result"))
			{
			case "OK":
				PXML.doc = xml;
				PAGE.RefreshTable();
				break;
			case "FAILED":
			default:
				BODY.ShowAlert("Internal Error!!");
			}
		}
		ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
		ajaxObj.sendRequest("conntrack.cgi", "NETWORK="+COMM_IPv4NETWORK(this.ipaddr, this.mask)+"&MASK="+this.mask);
	},
	RefreshTable: function()
	{
		var cnt = XG(this.ctp+"/count");
		var tcp_session = parseInt(XG(this.ctp+"/tcp_count"), 10);
		var udp_session = parseInt(XG(this.ctp+"/udp_count"), 10);

		BODY.CleanTable("sess_list");
		for (var i=1; i<=cnt; i++)
		{
			var ipaddr	= XG(this.ctp+"/entry:"+i+"/ipaddr");
			var tcp_cnt	= XG(this.ctp+"/entry:"+i+"/tcp");
			var udp_cnt	= XG(this.ctp+"/entry:"+i+"/udp");
			var data	= [ipaddr, tcp_cnt, udp_cnt];
			var type	= ["text", "text", "text"];
			BODY.InjectTable("sess_list", "DUMMY_"+i, data, type);
		}
		OBJ("tcp_sess").innerHTML = tcp_session;
		OBJ("udp_sess").innerHTML = udp_session;
		OBJ("all_sess").innerHTML = tcp_session + udp_session;
	}
}
</script>
