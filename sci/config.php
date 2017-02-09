<?php
// business name
$seller = 'My Business';

// location of bitcoin sci folder from install path
$bitsci_url = 'sci/';

// weight multiplier for shipping costs (fiat value)
$weight_mult = 4;

// set maximum number of allowed vouchers/coupons
$voucher_limit = 2;

// payment precision (decimal places in sum totals)
$p_precision = 5;

// payment variance (allow a bit of wiggle room)
$p_variance = '0.00001';

// thousands separator for price values
$t_separator = ',';

// decimal separator for price values
$d_separator = '.';

// shift decimal on btc prices (0,1,2,3,6)
$dec_shift = 3;

// fiat currency symbol
$curr_symbol = '$';

// fiat currency code (USD, AUD, etc)
$curr_code = 'USD';

// exchange price weight time (30d, 7d, 24h)
$price_type = '24h';

// receive an email upon confirmation?
$send_email = true;

// enable rss feed to announce sales?
$rss_feed = true;

// security string used for encryption (16 chars)
$sec_str = 'CHANGETHISSTRING';

// public RSA key used to encrypt private keys
$pub_rsa_key = '';

/* IGNORE ANYTHING UNDER THIS LINE */
require_once(dirname(__FILE__).'/gateways/setup.php');
require_once(dirname(__FILE__).'/../inc/config.inc.php');

$ipn_log_file = 'ipn-control.log';
$site_url = $base_url;

define('CONF_NUM', $confirm_num);
define('SEC_STR', $sec_str);
define('SEP_STR', $t_separator);
define('DEC_STR', $d_separator);
define('USE_TESTNET', $use_testnet);
?>