<?php
require_once(dirname(__FILE__).'/config.php');
require_once(dirname(__FILE__).'/../../config.php');
require_once(dirname(__FILE__).'/../../../lib/common.lib.php');
require_once(dirname(__FILE__).'/../../../lib/coinbase.lib.php');

// set up a few directory strings
$dat_file = dirname(__FILE__).'/../../t_data/';
$ipn_file = dirname(__FILE__).'/../../ipn-control.php';

// get data being passed to us
$post_data = json_decode(file_get_contents('php://input'), true);

// verify authenticity of callback
if (empty($_GET['s']) || $_GET['s'] !== $coinbase_call_secret) {
  header('HTTP/1.1 400 Bad request', true, 400);
  exit;
}

// get transaction details from Coinbase
try {
  $coinbase = Coinbase::withApiKey($coinbase_api_key, $coinbase_api_secret);
  $tran_id = $post_data['order']['id'];
  $order = $coinbase->getOrder($tran_id);
  $pay_status = $order->status;
  $total_btc = $order->total_btc->cents / 1e8;
  $cust_code = $order->custom;
  $currency = 'BTC';
  if ($pay_status === 'mispaid') {
    $amount_paid = $order->mispaid_btc->cents / 1e8;
	if (valid_balance($amount_paid, $total_btc, $p_variance)) {
	  $pay_status = 'completed';
	}
  } else {
    $amount_paid = $total_btc;
  }
}
catch (Exception $e) {
  header('HTTP/1.1 500 Internal Error', true, 500);
  exit;
}

// get order data from file
$t_code = preg_replace("/[^a-z0-9]/i", '', $cust_code);
$t_data = get_key_data($dat_file, $t_code);

// make sure the order was found
if ($t_data !== false) {

  $t_data = bitsci::read_pay_query($t_data);
  list($total, $buyer, $note, $order_id, 
  $exch_rate, $gateway, $order_time) = $t_data;

  switch($pay_status) {
	case 'mispaid':
	  $status = 'Underpaid';
	  break;
	case 'completed':
      require($ipn_file);
	  if ($error !== false) {
	    $status = 'Callback Error';
	  }
	  break;
	case 'expired':
	  $status = 'Expired/Invalid';
	  break;
	default: 
	  $status = 'Unknown';
  }

} else {
  header('HTTP/1.1 500 Internal Error', true, 500);
  exit;
}

if (isset($status)) {      
  // connect to database
  $hide_crash = true;
  $conn = connect_to_db();

  // update payment status in db
  set_order_status($order_id, $status);
}

// return error code if unable to confirm order
if (isset($error) && $error !== false) {
  header('HTTP/1.1 500 Internal Error', true, 500);
} else {
  header('HTTP/1.1 200 OK', true, 200);
}
?>
