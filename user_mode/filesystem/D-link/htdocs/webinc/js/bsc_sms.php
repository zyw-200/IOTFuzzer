<?include "/htdocs/phplib/inet.php";?>
<?include "/htdocs/phplib/inf.php";?>
<script type="text/javascript">

function Page() {}
Page.prototype =
{
	services: "",
        OnLoad: function() {},
        OnUnload: function() {},
        OnSubmitCallback: function (code, result) { return false; },
        InitValue: function(xml)
        {
                return true;
        },
        PreSubmit: function() { return null; },
        IsDirty: null,
        Synchronize: function() {}
        // The above are MUST HAVE methods ...
        ///////////////////////////////////////////////////////////////////////
}
</script>
