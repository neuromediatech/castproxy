<?php
// PHP Proxy File for CasterStats(r)
// (c) Copyright 2022 NeuroMedia Software SPRL

// Instructions

// 1. Fill the variables below & uncomment
// 2. Serve this file on your PHP-compatible web server of choice

$server_type = 'IceCast'; // Your streaming server type. Should be one of: IceCast, SHOUTcast1, SHOUTcast2.
$username = 'admin'; // The username used to access the admin interface of your streaming server.
$password = 'yourpass'; // The password used to access the admin interface of your streaming server.
$host = 'www.example.org'; // The hostname of your streaming server.
$port = '8000'; // The port used by your streaming server.
// The variable '$mount' is optional and is only used as a fallback value in case the proxy is called without entering a mount value explicitly.
$mount = 'mountname'; // Mount name for IceCast2, sid for SHOUTcast2, empty for SHOUTcast1.

// (optional) Uncomment and fill the following values if you want to enable authentication on the proxy
// To later access the proxy, include the following value at the end of your url :
// ?username=admin&password=yourpass (replace "admin" and "yourpass" with the values you entered below)

// $proxy_username = 'admin';
// $proxy_password = 'yourpass';

// For SHOUTcast 1, uncomment the two lines below
// RewriteCond %{QUERY_STRING} (^|&)mode=viewxml($|&)
// RewriteRule ^admin\.cgi$ /proxy.php?&%{QUERY_STRING}

// ==================== Everything below is raw code and should not be modified ====================

if (!empty($proxy_username) && !empty($proxy_password)) {
	if (
		!isset($_GET["username"]) || !isset($_GET["password"]) ||
		$_GET["username"] != $proxy_username || $_GET["password"] != $proxy_password
	) {
		header('HTTP/1.0 401 Unauthorized');
		exit;
	}
}

if (isset($_GET["mount"])) {
	$mount = $_GET["mount"];
}

$url = 'http://' . $host . ':' . $port;
switch ($server_type) {
	case 'IceCast':
		$url = $url . '/admin/listclients?mount=/' . $mount;
		break;
	case 'SHOUTcast1':
		$url = $url . '/admin.cgi?mode=viewxml';
		break;
	case 'SHOUTcast2':
		$url = $url . '/index.html=' . $mount;
		break;
	default:
		header("HTTP/1.1 500 Internal Server Error");
		echo $server_type, " is not a supported server type. Please use one of 'IceCast', 'SHOUTcast1' or 'SHOUTcast2'";
		exit;
		break;
}

header("Content-Type: text/xml");
$context = stream_context_create(array(
	"http" => array(
		"header"  => "Authorization: Basic " . base64_encode("$username:$password"),
		"user_agent" => "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:47.0) Gecko/20100101 Firefox/47.0"
	)
));
$data = file_get_contents($url, false, $context);
echo $data;
