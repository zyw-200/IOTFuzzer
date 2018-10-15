<?
if(query("/wlan/inf:1/webredirect/auth/enable") == 1)
{
	$m_context_title	="Authentication Success";
}
else
{
	$m_context_title	="Notice";
}
$m_redirect_success	="Authentication Success";
$m_pls_cls_win ="Please close this window and open a new one to access Internet";
$m_pls_wait = "Web will be redirected. Please wait ... ";
$m_thank_u ="Thank you";
$m_ok ="OK";

?>
