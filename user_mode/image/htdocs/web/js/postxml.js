function Authenticate(group, timeout)
{
	this.AuthorizedGroup = group;
	this.timeout = timeout;
}
Authenticate.prototype =
{
	AuthorizedGroup: -1,
	TimeoutCallback: null,
	captcha: null,
	timerid: null,
	timeout: 600,

	CancelTimeout: function()
	{
		if (this.timerid) { clearTimeout(this.timerid); this.timerid=null; }
	},
	UpdateTimeout: function()
	{
		var self = this;
		this.CancelTimeout();
		if (this.AuthorizedGroup >= 0)
		{
			this.timerid =
				setTimeout(
					function(){
						self.CancelTimeout();
						if(self.TimeoutCallback) self.Logout(self.TimeoutCallback());
					},
					this.timeout * 1000
					);
		}
	},
	Captcha: function(callback)
	{
		/* Get Captcha image */
		var self = this;
		var AJAX = GetAjaxObj("captcha");
		//AJAX.debug = true;
		AJAX.release();
		AJAX.createRequest();
		AJAX.onCallback = function(xml){if (callback) callback(xml); AJAX.release();}
		AJAX.setHeader("Content-Type", "application/x-www-form-urlencoded");
		AJAX.sendRequest("captcha.cgi", "DUMMY=YES");
		this.UpdateTimeout();
	},
	Login: function(callback, user, passwd, captcha)
	{
		if (this.AuthorizedGroup >= 0)
		{
			if (callback) callback(null);
			return;
		}

		var self = this;
		var payload = "REPORT_METHOD=xml&ACTION=login_plaintext"+
			"&USER="+escape(user)+"&PASSWD="+escape(passwd)+"&CAPTCHA="+escape(captcha);
		var AJAX = GetAjaxObj("login");
		//AJAX.debug = true;
		AJAX.release();
		AJAX.createRequest();
		AJAX.onCallback = function(xml)
			{
				if (xml.Get("/report/RESULT")=="SUCCESS")
				{
					self.AuthorizedGroup = xml.Get("/report/AUTHORIZED_GROUP");
					self.UpdateTimeout();
				}
				AJAX.release();
				if (callback) callback(xml);
			}
		AJAX.setHeader("Content-Type", "application/x-www-form-urlencoded");
		AJAX.sendRequest("session.cgi", payload);
	},
	Logout: function(callback)
	{
		if (this.AuthorizedGroup < 0)
		{
			if (callback) callback(null);
			return;
		}
		var self = this;
		var payload = "REPORT_METHOD=xml&ACTION=logout";
		var AJAX = GetAjaxObj("login");
		AJAX.release();
		AJAX.createRequest();
		AJAX.onCallback = function(xml){if (callback) callback(xml); self.AuthorizedGroup=-1; AJAX.release();}
		AJAX.setHeader("Content-Type", "application/x-www-form-urlencoded");
		AJAX.sendRequest("session.cgi", payload);
	}
};

function PostXML(cmd) { if (cmd) this.pwpost = cmd; }
PostXML.prototype =
{
	//////////////////////////////////////////////////////
	pwpost: "SETCFG,SAVE,ACTIVATE",	// The post argument of Pigwidgeon.
	doc: null,
	callback:  null,
	hedwig_callback: function(xml)
	{
		var self = this;
		switch (xml.Get("/hedwig/result"))
		{
		case "OK":	COMM_CallPigwidgeon(this.pwpost, function(xml){self.pigwidgeon_callback(xml);}); break;
		case "BUSY":if (this.callback) this.callback("BUSY", null); break;
		default:	if (this.callback) this.callback("HEDWIG", xml); break;
		}
	},
	pigwidgeon_callback: function(xml)
	{
		switch (xml.Get("/pigwidgeon/result"))
		{
		case "OK":	if (this.callback) this.callback("OK", null); break;
		default:	if (this.callback) this.callback("PIGWIDGEON", xml); break;
		}
	},
	// Private part above ...
	//////////////////////////////////////////////////////
	UpdatePostXML: function(xml) { this.doc = xml; },
	GetCFG: function(service, result_callback)
	{
		var self = this;
		this.callback = result_callback;
		COMM_GetCFG(false, service,
			function(xml)
			{
				self.doc = xml;
				if (self.callback)
				{
					self.callback();
					self.callback = null;
				}
			});
	},
	FindModule: function(name)
	{
		if (this.doc === null) return null;
		return this.doc.GetPathByTarget("/postxml", "module", "service", name, false);
	},
	ActiveModule: function(name)
	{
		var b = this.FindModule(name);
		if (b === null) return false;

		this.doc.Del(b+"/FATLADY");
		this.doc.Del(b+"/SETCFG");
		this.doc.Del(b+"/ACTIVATE");
		return true;
	},
	DelayActiveModule: function(name, delay)
	{
		var b = this.FindModule(name);
		if (b === null) return false;

		/* Keep the original setting of FATLADY & SETCFG. */
		//this.doc.Del(b+"/FATLADY");
		//this.doc.Del(b+"/SETCFG");
		this.doc.Set(b+"/ACTIVATE", "delay");
		this.doc.Set(b+"/ACTIVATE_DELAY", delay);
		return true;
	},
	EventActiveModule: function(name, ev, delay)
	{
		var b = this.FindModule(name);
		if (b === null) return false;
		this.doc.Set(b+"/ACTIVATE", "event");
		this.doc.Set(b+"/ACTIVATE_EVENT", ev);
		this.doc.Set(b+"/ACTIVATE_DELAY", delay ? delay:"");
		return true;
	},
	IgnoreModule: function(name)
	{
		var b = this.FindModule(name);
		if (b === null) return false;

		this.doc.Set(b+"/FATLADY", "ignore");
		this.doc.Set(b+"/SETCFG", "ignore");
		this.doc.Set(b+"/ACTIVATE", "ignore");
		return true;
	},
	CheckModule: function(name, fatlady, setcfg, activate)
	{
		var b = this.FindModule(name);
		if (b === null) return false;

		if (fatlady)  this.doc.Set(b+"/FATLADY", fatlady);   else this.doc.Del(b+"/FATLADY");
		if (setcfg)   this.doc.Set(b+"/SETCFG", setcfg);     else this.doc.Del(b+"/SETCFG");
		if (activate) this.doc.Set(b+"/ACTIVATE", activate); else this.doc.Del(b+"/ACTIVATE");
		return true;
	},
	Post: function(result_callback)
	{
		var self = this;
		this.callback = result_callback;
		COMM_CallHedwig(this.doc, function(xml){self.hedwig_callback(xml);});
	}
};
