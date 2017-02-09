<?php
require_once(dirname(__FILE__).'/../inc/config.inc.php');
require_once(dirname(__FILE__).'/../lib/common.lib.php');

$conn = connect_to_db();				  
$unc_trans = list_unconfirmed_trans();
$now = mysqli_now();

if (!empty($unc_trans) && ($unc_trans !== 'N/A')) {
  while ($tran = mysqli_fetch_assoc($unc_trans)) {
	$diff = get_time_difference($tran['Created'], $now);
	if ($diff['hours'] > $tran_clean_time) {
	  $quant = safe_sql_str($tran['Quantity']);
      if (delete_transaction($tran['TranID'])) {
        if ($tran['Status'] == 'Unconfirmed') {
          $item = get_file($tran['ItemID']);
          if (!empty($item) && ($item !== 'N/A')) {
            $item = mysqli_fetch_assoc($item);
	        if (($item['FileMethod'] !== 'download') && ($item['FileMethod'] !== 'keys')) {
              edit_file($tran['ItemID'], "FileStock = FileStock+$quant");
			}
	      }
        }
      }  
    }
  }
}
?>