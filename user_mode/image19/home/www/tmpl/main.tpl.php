<?php /* Smarty version 2.6.18, created on 2012-05-25 03:22:41
         compiled from main.tpl */ ?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"

   "http://www.w3.org/TR/html4/frameset.dtd">

<HTML>

<HEAD>

<TITLE>Netgear</TITLE>
<meta http-equiv="X-UA-Compatible" content="IE=8"/>
<script type="text/javascript">

<!--

<?php echo '

    function setCols(colValue) {

        if (document.getElementById(\'mainFrameset\'))

            document.getElementById(\'mainFrameset\').cols = colValue;

    }

'; ?>


-->

</script>

</HEAD>

		<FRAMESET rows="123px,*,66px" style="margin: 0px; padding: 0px;" framespacing="0" border="0">

			<FRAME name="header" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="<?php if ($this->_tpl_vars['sessionEnabled'] == ''): ?>login_<?php endif; ?>header.php">

			<FRAMESET cols="<?php if ($this->_tpl_vars['sessionEnabled'] == true): ?>166px<?php else: ?>18px<?php endif; ?>,*,11px" id="mainFrameset" style="margin: 0px; padding: 0px;" framespacing="0" border="0">

				<FRAME name="thirdmenu" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="thirdMenu.html">

				<FRAME name="master" scrolling="auto" NORESIZE frameborder="0" framespacing="0" marginheight="0" border="0" src="">

				<FRAME name="dummyright" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="background.html">

			</FRAMESET>

			<FRAME name="action" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="<?php if ($this->_tpl_vars['sessionEnabled'] == ''): ?>login_<?php endif; ?>button.html">

		</FRAMESET>

	<NOFRAMES>

		<p>Please use a browser that supports Frames!!!</p>

	</NOFRAMES>

</HTML>