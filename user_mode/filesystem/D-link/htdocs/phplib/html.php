<?
function HTML_gen_301_header($host, $uri)
{
	echo "HTTP/1.1 301 Moved Permanently\r\n";
	echo "Location: http://";
	if ($host == "")	echo $_SERVER["HTTP_HOST"].$uri;
	else				echo $host.$uri;
	echo "\r\n\r\n";
}
?>
