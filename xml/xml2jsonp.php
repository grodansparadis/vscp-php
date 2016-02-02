<!-- This file is part of the VSCP project http://www.vscp.org                  -->
<!-- Credits Cris West - http://cwestblog.com/2014/06/20/php-xml-to-json-proxy/ -->
<!-- Changed to use only local domain by                                        -->
<!-- Ake Hedman, Paradise of the Frog AB,  <akhe@paradiseofthefrog.com>         -->
<!-- Syntax: xml2json.php?url="local-xml-file"[&jsonp="callback-function-name"  -->
<html>
<head>
  <title>XML2JSON</title>
</head>
<body>
<?php 

  error_reporting(E_ALL);
  ini_set('display_errors', '1');
  echo "Hello2";

  // Restrict to work on local server 
  $url = $_GET['url'];
  echo $url;
/*
  <!-- Allow only local requests -->

  $path = parse_url($url, PHP_URL_PATH);
  echo @path;

  trim( $path );
  echo $path;

  if ( 0 == strpos( $path, "/" ) ) {
    $path = substr( $path, 1 );
  }
  echo $path;

  <!-- Allow only local requests -->

  // Output json
  $json = json_encode(simplexml_load_string(file_get_contents($path)));
  if( isset($_GET['jsonp']) ) {
    $json = "{$_GET['jsonp']}($json);";
  }
  echo $json;
*/
?> 
</body>
</html>
