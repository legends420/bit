<?php
require_once(dirname(__FILE__).'/config.php');

// get json data from local copy (result is assoc array)
$ticker_dir = dirname(__FILE__).'/'.$json_file;
if (file_exists($ticker_dir)) {
  $market_data = json_decode(file_get_contents($ticker_dir), true);
} else {
  die("Could not locate $ticker_dir!");
}
?>