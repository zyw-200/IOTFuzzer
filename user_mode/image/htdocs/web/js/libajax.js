function XmlDocument(xml)
{
	this.XDoc = xml;
	this.AnchorNode = null;
}
XmlDocument.prototype =
{
	Serialize : function ()
	{
		var xmlString;
		if (window.ActiveXObject) xmlString = this.XDoc.xml;
		else xmlString = (new XMLSerializer()).serializeToString(this.XDoc);
		return xmlString;
	},
	dbgdump : function ()
	{
		var ow = window.open();
		ow.document.open("content-type: text/xml");
		ow.document.write(this.Serialize());
	},
	Find : function (path, create)
	{
		var currnode = this.XDoc;
		var token = path.split("/");
		var i, j;
		var tagname, seq;

		/* User anchor as current node if it exist.
		 * Use the root as current if absolute path. */
		if (this.AnchorNode && token[0]!="") currnode = this.AnchorNode;
		else currnode = this.XDoc;

		/* Walk through the tokens */
		for (i=0; i<token.length; i+=1)
		{
			/* skip the empty token */
			if (token[i] == "") continue;
			/* parse the tag name & seq# */
			if (token[i].indexOf(":")<0)
			{
				tagname = token[i];
				seq = 1;
			}
			else
			{
				var tags = token[i].split(":");
				tagname = tags[0];
				seq = tags[1];
			}

			/* find the matching tag. */
			var tagseq = 0;
			var found = 0;
			var tagnode;
			for (j=0; j<currnode.childNodes.length; j+=1)
			{
				if (currnode.childNodes[j].nodeName == tagname)
				{
					tagseq+=1;
					if (seq == tagseq)
					{
						currnode = currnode.childNodes[j];
						found+=1;
						break;
					}
				}
			}
			if (found) continue;
			if (!create) return null;

			/* create the node */
			for (j=0; j < (seq - tagseq); j+=1)
			{
				tagnode = this.XDoc.createElement(tagname);
				currnode.appendChild(tagnode);
			}
			currnode = tagnode;
		}
		return currnode;
	},
	Anchor : function (path)
	{
		var old = this.AnchorNode;
		if (path && path!=="") this.AnchorNode = this.Find(path, false);
		return old;
	},
	AnchorPop: function (old)
	{
		this.AnchorNode = old;
	},
	GetDOMNodeValue : function (node)
	{
		if (node.hasChildNodes()) return node.firstChild.nodeValue;
		return "";
	},
	SetDOMNodeValue : function (node, value)
	{
		if (node.hasChildNodes()) node.firstChild.nodeValue = value;
		else
		{
			var valnode = this.XDoc.createTextNode(value);
			node.appendChild(valnode);
		}
	},
	Del : function (path)
	{
		var node = this.Find(path);
		if (node == null) return false;
		var pnode = node.parentNode;
		pnode.removeChild(node);
		return true;
	},
	Get : function (path)
	{
		var node;

		if (path.indexOf("#") < 0)
		{
			/* return the value of the node */
			node = this.Find(path, false);
			if (node) return this.GetDOMNodeValue(node);
			return "";
		}

		/* If the path is end with '#', count the number of node. */
		var count = 0;
		var tokens = path.split("#");
		/* Find the target */
		node = this.Find(tokens[0]);
		if (node)
		{
			var nodeName = node.nodeName;
			node = node.parentNode;
			for (var i=0; i<node.childNodes.length; i+=1)
				if (node.childNodes[i].nodeName == nodeName)
					count+=1;
		}
		return count;
	},
	Set : function (path, value)
	{
		var node = this.Find(path, true);
		if (node == null)
		{
			alert("BUG: this should not happen !!");
			return null;
		}
		this.SetDOMNodeValue(node, value);
		return node;
	},
	Add : function (path, value)
	{
		var node = this.Find(path, false);
		if (node == null)
		{
			node = this.Find(path, true);
			this.SetDOMNodeValue(node, value);
			return node;
		}
		var pnode = node.parentNode;
		var newnode = this.XDoc.createElement(node.nodeName);
		this.SetDOMNodeValue(newnode, value);
		pnode.appendChild(newnode);
		return newnode;
	},
	GetPathByTarget : function (root, node, target, value, create)
	{
		var i, j;
		var pnode, nnode, tnode;
		var found = false;
		var seq = 0;

		/* Get the parent node first. */
		pnode = this.Find(root, create);
		if (pnode == null) return null;
		/* Walk through the 'node' */
		for (i=0; i<pnode.childNodes.length && !found; i+=1)
		{
			if (pnode.childNodes[i].nodeName == node)
			{
				seq+=1;
				nnode = pnode.childNodes[i];
				for (j=0; j<nnode.childNodes.length; j+=1)
				{
					if (nnode.childNodes[j].nodeName == target)
					{
						tnode = nnode.childNodes[j];
						if (this.GetDOMNodeValue(tnode) == value) found+=1;
						break;
					}
				}
				if (found)
				{
					return root+"/"+node+":"+seq;
				}
			}
		}
		if (create)
		{
			seq+=1;
			var newpath = root+"/"+node+":"+seq+"/"+target;
			this.Set(newpath, value);
			return root+"/"+node+":"+seq;
		}
		return null;
	}
}

function HTTPClient(){}
HTTPClient.prototype =
{
	debug: false,
	__httpRequest : null,
	requestMethod : "POST",
	requestAsyn : true,
	returnXml : true,
	__header : null,
	onSend : null,
	onCallback : null,
	onError : function (msg)
	{
		if (!msg) throw (msg);
	},
	__callback : function()
	{
		if(!this.__httpRequest)
		{
			this.onError("Error : Request return error("+ this.__httpRequest.status +").");
		}
		else
		{
			if (this.__httpRequest.readyState == 2)
			{
				if (this.onSend) this.onSend();
			}
			else if (this.__httpRequest.readyState == 4)
			{
				if (this.__httpRequest.status == 200)
				{
					if (this.onCallback)
					{
						if (this.returnXml)
						{
							var xdoc = new XmlDocument(this.__httpRequest.responseXML);
							if (xdoc != null)
							{
								if (this.debug) xdoc.dbgdump();
								this.onCallback(xdoc);
							}
							else this.onError("Error : unable to create XmlDocument().");
						}
						else this.onCallback(this.__httpRequest.responseText);
					}
				}
				else
				{
					this.onError("Error : Request return error("+ this.__httpRequest.status +").");
				}
			}
		}
	},
	createRequest : function()
	{
		try
		{
			// For Mazilla or Safari or IE7
			this.__httpRequest = new XMLHttpRequest();
		}
		catch (e)
		{
			var __XMLHTTPS = new Array( "MSXML2.XMLHTTP.5.0",
										"MSXML2.XMLHTTP.4.0",
										"MSXML2.XMLHTTP.3.0",
										"MSXML2.XMLHTTP",
										"Microsoft.XMLHTTP" );
			var __Success = false;
			for (var i = 0; i < __XMLHTTPS.length && __Success == false; i+=1)
			{
				try
				{
					this.__httpRequest = new ActiveXObject(__XMLHTTPS[i]);
					__Success = true;
				}
				catch (e) { }
				if (!__Success)
				{
					this.onError("Browser do not support Ajax.");
				}
			}
		}
	},
	sendRequest : function(requestUrl, payload)
	{
		if (!this.__httpRequest) this.createRequest();
		var self = this;
		this.__httpRequest.onreadystatechange = function() {self.__callback();}
		if (!requestUrl)
		{
			this.onError("Error : Invalid request URL.");
			return;
		}
		this.__httpRequest.open(this.requestMethod, requestUrl, this.requestAsyn);
		if (this.__header)
		{
			for (var i = 0; i < this.__header.length; i+=1)
			{
				if (this.__header[i].value != "")
					this.__httpRequest.setRequestHeader(this.__header[i].name, this.__header[i].value);
			}
		}
		if (this.requestMethod == "GET" || this.requestMethod == "get")
			this.__httpRequest.send(null);
		else
		{
			if (!payload)
			{
				this.onError("Error : Invalid payload for POST.");
				return;
			}
			this.__httpRequest.send(payload);
		}
	},
	getResponseHeader : function(header)
	{
		if (!header)
		{
			this.onError("Error : You must assign a header name to get.");
			return "";
		}
		if (!this.__httpRequest)
		{
			this.onError("Error : The HTTP request object is not exist.");
			return "";
		}
		return this.__httpRequest.getResponseHeader(header);
	},
	getAllResponseHeaders : function()
	{
		if (this.__httpRequest) return this.__httpRequest.getAllResponseHeaders();
		else this.onError( "Error : The HTTP request object is not exist." );
	},
	setHeader : function(header, value)
	{
		if (header && value)
		{
			if (!this.__header) this.__header = new Array();
			var tmpHeader = new Object();
			tmpHeader.name = header;
			tmpHeader.value = value;
			this.__header[ this.__header.length ] = tmpHeader;
		}
	},
	clearHeader : function (header)
	{
		if (!this.__header) return;
		if (!header) return;
		for (var i = 0; i < this.__header.length; i+=1)
		{
			if (this.__header[i].name == header)
			{
				this.__header.value = "";
				return;
			}
		}
	},
	clearAllHeaders : function()
	{
		if (!this.__header) return;
		this.__header = null;
	},
	release : function()
	{
		this.__httpRequest = null;
		this.requestMethod = "POST";
		this.requestAsyn = true;
		this.returnXml = true;
		this.onCallback = null;
		this.onSend = null;
	}
};

var AJAX_OBJ = new Array();

function GetAjaxObj(name)
{
	var i=0;
	var ajax_num = AJAX_OBJ.length;
	if (ajax_num > 0)
	{
		for (i=0; i<ajax_num; i+=1)
		{
			if (AJAX_OBJ[i][0] == name)
			{
				return AJAX_OBJ[i][1];
			}
		}
	}
	AJAX_OBJ[ajax_num] = new Array();
	AJAX_OBJ[ajax_num][0] = name;
	AJAX_OBJ[ajax_num][1] = new HTTPClient();

	return AJAX_OBJ[ajax_num][1];
}

function OnunloadAJAX()
{
	var i;
	for (i=0; i<AJAX_OBJ.length; i+=1)
	{
		AJAX_OBJ[i][0]="";
		AJAX_OBJ[i][1].release();
		delete AJAX_OBJ[i][1];
		delete AJAX_OBJ[i];
	}
}
