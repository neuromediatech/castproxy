<?php
// PHP Proxy File for CasterStats(r)
// (c) Copyright 2017 NeuroMedia Software SPRL

// Instructions

// 1. Fill the variables below & uncomment
// 2. Configure mod rewrite

// For SHOUTcast 1
// RewriteCond %{QUERY_STRING} (^|&)mode=viewxml($|&)
// RewriteRule ^admin\.cgi$ /proxy.php?&%{QUERY_STRING}

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