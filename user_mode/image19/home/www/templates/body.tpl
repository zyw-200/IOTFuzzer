<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Frameset//EN"
   "http://www.w3.org/TR/html4/frameset.dtd">
<HTML>
<HEAD>
<TITLE>Netgear</TITLE>
</HEAD>
      <frameset cols="10px,{if $thirdmenuDisplay}154px,{/if}*,10px" style="margin: 0px; padding: 0px;" framespacing="0" border="0">
      	<FRAME name="dummyleft" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="body.php?page=dummy">
	{if $thirdmenuDisplay}
      	<FRAME name="thirdmenu" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="body.php?page=thirdmenu">
      {/if}
      	<FRAME name="master" scrolling="auto" NORESIZE frameborder="0" marginheight="0" border="0" src="body.php?page=master">
      	<FRAME name="dummyright" scrolling="no" NORESIZE frameborder="0" marginheight="0" border="0" src="body.php?page=dummy">
      </frameset>
</HTML>