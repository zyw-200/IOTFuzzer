<?include "/htdocs/phplib/inet.php";?>
<?include "/htdocs/phplib/inf.php";?>
<script type="text/javascript">
var tmpSMS;
var sms;
var must_wait=0;

function Page() {}
Page.prototype =
{
	services: "SMS",
        OnLoad: function() {},
        OnUnload: function() {},
	OnSubmitCallback: function (code, result) { return false; },
	InitValue: function(xml)
        {
		var get_Treturn = '<?if($_GET["Treturn"]=="") echo "0"; else echo $_GET["Treturn"];?>';
		if(get_Treturn!="1" || must_wait == 1)
		{	
			self.location.href = "./bsc_sms_inbox_rlt.php";
		}

		/* turn off sms led. */
		Service("SMS.LED.OFF");
		
		PXML.doc = xml;
                sms = PXML.FindModule("SMS");
                if (sms===""){ alert("InitValue ERROR!"); return false; }
		
		sms += "/runtime/callmgr/voice_service/mobile/sms/sm";
		tmpSMS = sms;

		var str = "<table class=\"general\" id=\"Tsms\"><tr>";
		str += "<th width=\"20px\"><input id=\"checkall\" type=\"checkbox\" onclick=\"SelectAll()\"/></th>";
		str += '<th width="100px">' + "<?echo i18n("From");?>" + "</th>";
		str += '<th width="151px">' + "<?echo i18n("Timestamp");?>" + "</th>";
		str += '<th width="162px">' + "<?echo i18n("Text");?>" + "</th>";
		str += "</tr>";

		var defaulttmp = 0;
	
		for (var i=1; i<=<?=$SMS_MAX_COUNT?>; i+=1)
                {
			var smsdelete = XG(sms + ":" + i + "/delete");
			var smsfrom = XG(sms + ":" + i + "/from");
			var smsdate = XG(sms + ":" + i + "/date");

			var smsmultiple = XG(sms + ":" + i + "/multiple_type");
                        var multiple_total = 0;

                        var smscontent = "";
                        if(smsmultiple == 1)
                        {
                                multiple_total = XG(sms + ":" + i + "/multiple_total");
                                for(var j=1; j<=multiple_total; j+=1)
                                {
                                        smscontent += RUnicode(XG(sms + ":" + i + "/multiple:"+ j +"/content"));
                                }
                        }
                        else
                        {
                                smscontent = RUnicode(XG(sms + ":" + i + "/content"));
                        }

			
			if(smsfrom != "" && smsdate != "" && smscontent != "")
			{
				str += "<tr onclick='TDchange("+ i +")'>";
                        	str += "<td width=\"20px\"><input id='cbx"+ i +"' type=\"checkbox\" /></td>";
                        	str += "<td width=\"115px\">" + smsfrom + "</td>";
				str += "<td width=\"136px\">" + smsdate + "</td>";
				str += "<td width=\"162px\">" + smscontent.substring(0,20)+"..." + "</td>";
				str += "</tr>";

				defaulttmp += 1;
				if(defaulttmp == 1)
				{
					OBJ("sms_number").value = smsfrom;
					OBJ("sms_content").value = smscontent;
				}
			}
		}
		str += "</table>";

		OBJ("sms_inbox").innerHTML = str;

		new TableSorter("Tsms",2);		

                return true;
        },
        PreSubmit: function() 
	{
		must_wait="1"; 
		for (var i=1; i<=<?=$SMS_MAX_COUNT?>; i+=1)
                {
			if(OBJ("cbx"+i) != null)
			{
				if(OBJ("cbx"+i).checked)
				{
					XS(sms + ":" + i + "/delete","1");
				}
				else
				{
					XS(sms + ":" + i + "/delete","0");
				}
			}
		}	

		return PXML.doc;
	},
        IsDirty: null,
        Synchronize: function() {}
        // The above are MUST HAVE methods ...
        ///////////////////////////////////////////////////////////////////////
}

function Service(svc)
{
	var ajaxObj = GetAjaxObj("SERVICE");
        ajaxObj.createRequest();
        ajaxObj.onCallback = function (){}
        ajaxObj.setHeader("Content-Type", "application/x-www-form-urlencoded");
        ajaxObj.sendRequest("service.cgi", "EVENT="+svc);
}

function RUnicode(s)
{
   var rs="";
   var m = "";
   for(i=0 ; i < s.length ; i=i+4)
   {
      m = s.substring(i,i+4);
      if(m=="0022")
      {
	rs += "\"";
      }
      else
      {
      	rs += String.fromCharCode(parseInt(m,16));
      }
   }
   return rs;
}

function TDchange(inx)
{
	var smsmultiple = XG(tmpSMS + ":" + inx + "/multiple_type");
        var multiple_total = 0;

        var smscontent = "";
        if(smsmultiple == 1)
        {
		multiple_total = XG(tmpSMS + ":" + inx + "/multiple_total");
                for(var j=1; j<=multiple_total; j+=1)
                {
			smscontent += RUnicode(XG(tmpSMS + ":" + inx + "/multiple:"+ j +"/content"));
                }
        }
	else
	{
		smscontent = RUnicode(XG(tmpSMS + ":" + inx + "/content"));
	}
	
	OBJ("sms_content").value = smscontent;
	OBJ("sms_number").value = XG(tmpSMS + ":" + inx + "/from");
}

function SelectAll()
{
	for (var i=0;i < OBJ("mainform").elements.length;i++)
	{
		var e = OBJ("mainform").elements[i];
		if (e.id != 'checkall' && e.type == 'checkbox' && e.disabled==false)
			e.checked = OBJ("mainform").checkall.checked;
	}
}

function TableSorter(table)
{
	this.Table = this.$(table);
	if(this.Table.rows.length <= 1)
	{
		return;
	}
	this.Init(arguments);
}

TableSorter.prototype.NormalCss = "NormalCss";
TableSorter.prototype.SortAscCss = "SortAscCss";
TableSorter.prototype.SortDescCss = "SortDescCss";

TableSorter.prototype.Init = function(args)
{
	this.ViewState = [];
	for(var x = 0; x < this.Table.rows[0].cells.length; x++)
	{
		this.ViewState[x] = false;
	}

	if(args.length > 1)
	{
		for(var x = 1; x < args.length; x++)
		{
			if(args[x] > this.Table.rows[0].cells.length)
			{
				continue;
			}
			else
			{
				this.Table.rows[0].cells[args[x]].onclick = this.GetFunction(this,"Sort",args[x]);
				this.Table.rows[0].cells[args[x]].style.cursor = "pointer";
			}
		}
	}
	else
	{
		for(var x = 0; x < this.Table.rows[0].cells.length; x++)
		{
			this.Table.rows[0].cells[x].onclick = this.GetFunction(this,"Sort",x);
			this.Table.rows[0].cells[x].style.cursor = "pointer";
		}
	}
}	

TableSorter.prototype.$ = function(element)
{
	return document.getElementById(element);
}

TableSorter.prototype.GetFunction = function(variable,method,param)
{
	return function()
	{
		variable[method](param);
	}
}

TableSorter.prototype.Sort = function(col)
{
	var SortAsNumber = true;
	for(var x = 0; x < this.Table.rows[0].cells.length; x++)
	{
		this.Table.rows[0].cells[x].className = this.NormalCss;
	}
	
	var Sorter = [];
	for(var x = 1; x < this.Table.rows.length; x++)
	{
		Sorter[x-1] = [this.Table.rows[x].cells[col].innerHTML, x];
		SortAsNumber = SortAsNumber && this.IsNumeric(Sorter[x-1][0]);
	}
	
	if(SortAsNumber)
	{
		for(var x = 0; x < Sorter.length; x++)
		{
			for(var y = x + 1; y < Sorter.length; y++)
			{
				if(parseFloat(Sorter[y][0]) < parseFloat(Sorter[x][0]))
				{
					var tmp = Sorter[x];
					Sorter[x] = Sorter[y];
					Sorter[y] = tmp;
				}
			}
		}
	}
	else
	{
		Sorter.sort();
	}

	if(this.ViewState[col])
	{
		Sorter.reverse();
		this.ViewState[col] = false;
		this.Table.rows[0].cells[col].className = this.SortDescCss;
	}
	else
	{
		this.ViewState[col] = true;
		this.Table.rows[0].cells[col].className = this.SortAscCss;
	}
	
	var Rank = [];
	for(var x = 0; x < Sorter.length; x++)
	{
		Rank[x] = this.GetRowHtml(this.Table.rows[Sorter[x][1]]);
	}
	for(var x = 1; x < this.Table.rows.length; x++)
	{
		for(var y = 0; y < this.Table.rows[x].cells.length; y++)
		{
			this.Table.rows[x].cells[y].innerHTML = Rank[x-1][y];
		}
	}

	this.OnSorted(this.Table.rows[0].cells[col], this.ViewState[col]);
}

TableSorter.prototype.GetRowHtml = function(row)
{
	var result = [];
	for(var x = 0; x < row.cells.length; x++)
	{
		result[x] = row.cells[x].innerHTML;
	}
	return result;
}

TableSorter.prototype.IsNumeric = function(num)
{
	return /^\d+(\.\d+)?$/.test(num);
}

TableSorter.prototype.OnSorted = function(cell, IsAsc)
{
	return;
}

function ReplyMessage()
{
	self.location="bsc_sms_send.php?receiver=" + OBJ("sms_number").value;
}

</script>
