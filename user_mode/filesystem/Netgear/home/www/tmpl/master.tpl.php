<?php /* Smarty version 2.6.18, created on 2012-01-24 09:28:16
         compiled from master.tpl */ ?>
<?php echo '<?xml'; ?>
 version="1.0"<?php echo '?>'; ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Netgear</title>
<script type="text/javascript" src="include/scripts/prototype.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/control-modal.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="config.php?json=true&code=6153137"></script>
<script type="text/javascript" src="productregistration/Prod_Reg_Popup.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<link rel="stylesheet" href="include/css/layout.css?code=<?php echo $this->_tpl_vars['random']; ?>
" type="text/css">
<link rel="stylesheet" href="include/css/style.css?code=<?php echo $this->_tpl_vars['random']; ?>
" type="text/css">
<link rel="stylesheet" href="include/css/default.css?code=<?php echo $this->_tpl_vars['random']; ?>
" type="text/css">
<link rel="stylesheet" href="include/css/fonts.css?code=<?php echo $this->_tpl_vars['random']; ?>
" type="text/css">
<link rel="stylesheet" href="productregistration/Prod_Reg_Popup.css?code=<?php echo $this->_tpl_vars['random']; ?>
">
</head>
<body style="background-color: #FFFFFF; margin: 0px; padding: 0px;">
<script language="javascript">
<!--
<?php echo '
var progressBar = new ModalWindow(false, {
			contents: function() { return "<img src=\'images/loading.gif\'>"; },
			overlayCloseOnClick: true,
			overlayClassName: \'ProgressBar_overlay\',
			containerClassName: \'ProgressBar_container\',
			opacity: 100,
			iframe: true
		});
progressBar.open();
function fnTrapKD(event){
    if (document.all){
        if (window.event.keyCode == 13){
            doSubmitAction(window.event);
        }
    }
    else if (document.getElementById){
        if (event.which == 13){
            doSubmitAction(event);
        }
    }
    else if(document.layers){
        if(event.which == 13){
            doSubmitAction(event);
        }
    }
}
function doSubmitAction(event)
{
    if (window.event && window.event.cancelBubble && window.event.returnValue){
        window.event.cancelBubble = true;
        window.event.returnValue = false;
    }
    if (event && event.stopPropagation && event.preventDefault){
        event.stopPropagation();
        event.preventDefault();
    }
    if (window.top.frames.header.loginPage == undefined) {
     //   window.top.frames[\'master\'].doSubmit(window.top.frames[\'action\'].$(\'applyButton\'),\'apply\',window.top.frames[\'action\']._isChanged);
    }
    else {
        window.top.frames[\'master\'].doLogin(event);
    }
}
document.body.onkeypress=function(event){
	fnTrapKD(event);
}
'; ?>

-->
</script>
<script type="text/javascript" src="include/scripts/browser-ext.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/effects.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/common.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/wirelessnew.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/TableSort.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/ipv6.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/livevalidation.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/prototype-ext.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
<script type="text/javascript" src="include/scripts/validation.js?code=<?php echo $this->_tpl_vars['random']; ?>
"></script>
	<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "data.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>
</body>
</html>