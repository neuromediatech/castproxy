<?php
// PHP Proxy File for CasterStats(r)
// (c) Copyright 2020 NeuroMedia Software SPRL

// Instructions

// 1. Fill the variables below & uncomment
// 2. Configure mod rewrite

// For SHOUTcast 1
// RewriteCond %{QUERY_STRING} (^|&)mode=viewxml($|&)
// RewriteRule ^admin\.cgi$ /proxy.php?&%{QUERY_STRING}

// (optional) Uncomment and fill the following values if you want to enable authentication on the proxy
// To later access the proxy, include the following value at the end of your url :
// ?username=admin&password=yourpass (replace "admin" and "yourpass" with the values you entered below)

//$proxy_username = 'admin';
//$proxy_password = 'yourpass';

if (!empty($proxy_username) && !empty($proxy_password)) {
	if (!isset($_GET["username"]) || !isset($_GET["password"]) ||
	$_GET["username"] != $proxy_username || $_GET["password"] != $proxy_password) {
		header('HTTP/1.0 401 Unauthorized');
		exit;
	}
}

header("Content-Type: text/xml");

$username = 'admin';
$password = 'yourpass';
$host = 'www.example.org';
$port = '8000';
$mount = 'mountname'; // mount name for IceCast2, sid for SHOUTcast2, empty for SHOUTcast1

// Uncomment the line related to your streaming server

// URL for IceCast
$url = 'http://' . $host . ':' . $port . '/admin/listclients?mount=/' . $mount;

// URL for SHOUTcast1
//$url = 'http://' . $host . ':' . $port . '/admin.cgi?mode=viewxml';

// URL for SHOUTcast2
//$url = 'http://' . $host . ':' . $port . '/index.html=' . $mount;

$context = stream_context_create(array(
    "http" => array(
        	"header"  => "Authorization: Basic " . base64_encode("$username:$password"),
              	"user_agent" => "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0"
    		)
));
$data = file_get_contents($url, false, $context);
echo $data;
?>