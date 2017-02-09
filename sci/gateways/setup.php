<?php
require_once(dirname(__FILE__).'/default/config.php');
require_once(dirname(__FILE__).'/coinbase/config.php');
require_once(dirname(__FILE__).'/gocoin/config.php');

$gateways = array(
	'coinbase' => array($enable_coinbase, 'Coinbase', 'BTC'),
	'gocoin' => array($enable_gocoin, 'GoCoin', 'BTC, LTC, DOGE')
);
?>