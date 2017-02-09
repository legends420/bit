<?php
// call required includes
require_once(dirname(__FILE__).'/../sci/config.php');
require_once(dirname(__FILE__).'/../ticker/config.php');
require_once(dirname(__FILE__).'/../lib/common.lib.php');

echo "<p>Requesting data from $market_api_url ...</p>\n";

// use cURL to request json string from API
$json_result = bitsci::curl_simple_post($market_api_url, false);

if (!empty($json_result)) {

  // decode json string
  $json_array = json_decode($json_result, true);
  
  // check json array
  if (!empty($json_array) && isset($json_array[$curr_code])) {

    // open local file for writing
    $fp = fopen(dirname(__FILE__)."/../ticker/$json_file", "w");
  
    // write to our opened file.
    if (fwrite($fp, $json_result) === FALSE) { 
      echo "<p>FAILURE! Could not write data to local file!</p>\n";
    } else {
      echo "<p>SUCCESS! $json_file has been updated!</p>\n";
    }
  
    // release file handle
    fclose($fp);
  
  } else {
    die("<p>FAILURE! API returned invalid data!</p>");
  }
} else {
  die("<p>FAILURE! API is not responding!</p>");
}
?>