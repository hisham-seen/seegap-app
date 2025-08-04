<?php
echo "URL Rewriting is working correctly!";
echo "<br>Request URI: " . $_SERVER['REQUEST_URI'];
echo "<br>Query String: " . $_SERVER['QUERY_STRING'];
echo "<br>GET parameters: " . print_r($_GET, true);
?>
