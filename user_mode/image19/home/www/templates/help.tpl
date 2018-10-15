<input type="hidden" id="helpURL" value="{$templateName|replace:'.tpl':''}">
{if $errorString eq 'Wireless Radio is turned off!' AND $navigation.2 neq "Statistics" AND $navigation.2 neq "Logs"}
    {literal}
    <script type="text/javascript">
    <!--
    ['refresh','edit','save','details'].each(function(buttonId) {
        if (window.top.frames['action'].$(buttonId) != undefined) {
            window.top.frames['action'].$(buttonId).disabled = true;
            window.top.frames['action'].$(buttonId).src = window.top.frames['action'].$(buttonId).src.replace('_on.gif','_off.gif');
        }
    });
    -->
    </script>
    {/literal}
{/if}