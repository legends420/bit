<?php
require_once('./../lib/coinbase.lib.php');

if (isset($gateway) && $gateway === 'coinbase') {

  // generate gateway return URLs
  $cancel_url = $site_url.'?page=return&result=cancel&delay=2&code='.$order_code;
  $success_url = $site_url.'?page=return&result=success&delay=2&code='.$order_code;

  // insert new order into database
  if (is_numeric($total_btc)) {
    if (!isset($_GET['ocode'])) {
      $order_id = save_order($account_id, $total_btc, $shipping, $cart_str, 
	  $address, $note, $order_code, "empty:coinbase.com wallet");
	}
  } else {
    die(LANG('PROB_CALC_TOTAL').' '.LANG('TRY_REFRESHING'));
  }
  
  // generate Coinbase gateway link
  try {
    // trim total to 4 decimal places since coinbase does that anyway
	// and not doing so may cause mispayment errors to occur. See:
	// community.coinbase.com/t/payment-page-display-inaccurate-btc-amount/5577
    $t_total = trim_decimal($total_btc, 4);
    $coinbase = Coinbase::withApiKey($coinbase_api_key, $coinbase_api_secret);
	$coinbase->setDebugMode($debug_coinbase);
    $button = $coinbase->createButton(LANG('ORDER')." #$order_id", "$t_total", "BTC", $order_code,
	array(
      "type" => "buy_now",
      "success_url" => $success_url,
      "cancel_url" => $cancel_url,
      "auto_redirect" => true
    ));
	if ($debug_coinbase) {
	  $gateway_url = 'https://sandbox.coinbase.com/checkouts/'.$button->button->code;
	} else {
	  $gateway_url = 'https://coinbase.com/checkouts/'.$button->button->code;
	}
	$coinbase_online = true;
  } catch (Exception $e) {
	if ($debug_coinbase) { die($e->getMessage()); }
	$coinbase_online = false;
  }
  
  // check the order was inserted into database
  if (isset($order_id) && is_numeric($order_id)) {
	
	// save order data to array
	$order_data = array(
	$total_btc, $buyer, $note, $order_id, 
    $exch_rate, $gateway, time());
	
	if (!isset($_GET['ocode'])) {
	  // encrypt order data
	  $t_data = bitsci::save_pay_query($order_data);
	
	  // save encrypted data to file
	  if (file_put_contents('t_data/'.$order_code, $t_data) !== false) {
	    chmod('t_data/'.$order_code, 0600);
	  } else {
        die(LANG('ERROR_CREATE_TRAN').' '.LANG('TRY_AGAIN_BACK'));
	  }
	}

    // go to coinbase gateway if online
	if ($coinbase_online) {
      redirect($gateway_url);
	} else {
      die('Coinbase '.LANG('GATEWAY_OFFLINE').' '.LANG('TRY_AGAIN_BACK'));
	}
  } else {
    die(LANG('ERROR_CREATE_TRAN').' '.LANG('TRY_AGAIN_BACK'));
  }
} else {
  die(LANG('ERROR_CREATE_TRAN').' '.LANG('TRY_AGAIN_BACK'));
}
?>