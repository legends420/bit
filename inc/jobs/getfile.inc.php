<?php
require_once(dirname(__FILE__).'/../config.inc.php');
require_once(dirname(__FILE__).'/../../sci/gateways/setup.php');
require_once(dirname(__FILE__).'/../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../session.inc.php');
require_once(dirname(__FILE__)."/../langs/$locale.inc.php");

$hide_crash = true;
$conn = connect_to_db();

if (admin_valid(true,false) && !empty($_GET['gate'])) {
  $gate = preg_replace("/[^a-z]/i", '', $_GET['gate']);
  require_once("../../sci/gateways/$gate/config.html");
  exit;
}

if (admin_valid(true,false) && !empty($_GET['file'])) {
  $file = preg_replace("/[^a-z0-9\-]/i", '', $_GET['file']);
  die(file_get_contents("../../sci/backup/$file"));
}
?>