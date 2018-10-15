<style>
/* The CSS is only for this page.
 * Notice:
 *	If the items are few, we put them here,
 *	If the items are a lot, please put them into the file, htdocs/web/css/$TEMP_MYNAME.css.
 */
</style>

<script type="text/javascript">
function Page() {}
Page.prototype =
{
	services: "URLCTRL",
	OnLoad: function()
	{
		if (!this.rgmode)
		{
			BODY.DisableCfgElements(true);
		}
	},
	OnUnload: function() {},
	OnSubmitCallback: function(code, result) { return false; },
	InitValue: function(xml)
	{
		PXML.doc = xml;
		var p = PXML.FindModule("URLCTRL");
		if (p === "") alert("ERROR!");
		p += "/acl/urlctrl";

		TEMP_RulesCount(p, "rmd");
		var count = XG(p+"/count");
		for (var i=1; i<=<?=$URL_MAX_COUNT?>; i+=1)
		{
			var b = p+"/entry:"+i;
			OBJ("uid_"+i).value = XG(b+"/uid");
			OBJ("en_"+i).checked = XG(b+"/enable")==="1";
			OBJ("url_"+i).value = XG(b+"/url");
			<?
			if($FEATURE_NOSCH!="1")
			{
				echo 'if (XG(b+"/schedule")!=="")     OBJ("sch_"+i).value = XG(b+"/schedule");\n
';
				echo 'else				      OBJ("sch_"+i).value = "-1";\n';
			}
			?>
		}
		var policy = XG(p+"/policy");
		if(policy !== "")	OBJ("url_mode").value = policy;
		else 			OBJ("url_mode").value = "DISABLE";

		return true;
	},
	PreSubmit: function()
	{
		var p = PXML.FindModule("URLCTRL");
		p += "/acl/urlctrl";
		var old_count = XG(p+"/count");
		var cur_count = 0;
		/* delete the old entries
		 * Notice: Must delte the entries from tail to head */
		while(old_count > 0)
		{
			XD(p+"/entry:"+old_count);
			old_count -= 1;
		}
		/* update the entries */
		for (var i=1; i<=<?=$URL_MAX_COUNT?>; i+=1)
		{
			/* if the description field is empty, it means to remove this entry,
			 * so skip this entry. */
			if (OBJ("url_"+i).value!=="")
			{
				cur_count+=1;
				var b = p+"/entry:"+cur_count;
				XS(b+"/uid",		"URLF-"+i);
				XS(b+"/enable",		OBJ("en_"+i).checked ? "1" : "0");
				XS(b+"/description",	"No_Description");  //It should not be blank due to Fatlady check.
				XS(b+"/url",		OBJ("url_"+i).value);
<?
				if($FEATURE_NOSCH!="1")
				echo 'XS(b+"/schedule",	 (OBJ("sch_"+i).value==="-1") ? "" : OBJ("sch_"+i).value);\n'
;
				?>
			}
		}
		/* we only handle 'count' here, the 'seqno' and 'uid' will handle by setcfg.
		 * so DO NOT modified/generate 'seqno' and 'uid' here. */
		XS(p+"/count", cur_count);
		
		XS(p+"/policy",	OBJ("url_mode").value);

		return PXML.doc;
	},
	IsDirty: null,
	Synchronize: function() {},
	// The above are MUST HAVE methods ...
	///////////////////////////////////////////////////////////////////////
	rgmode: <?if (query("/runtime/device/layout")=="bridge") echo "false"; else echo "true";?>,
	OnClickClearListURL: function()
	{
		for (var i = 1; i <= <?=$URL_MAX_COUNT?>; i++)
		{
			OBJ("url_"+i).value = '';
		}
	}
}
</script>
