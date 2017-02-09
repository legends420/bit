<?php admin_valid(); ?>

<h1>Admin Control Panel</h1>

<p>Here you can review your orders, manage your products, etc.<br />
Use the navigation menu to the  left to get started.</p>

<hr />

<h4>Summary of last 30 days:</h4>
<table class="table table-striped table-bordered table-condensed">
  <tr>
    <th>No. confirmed transactions:</th>
    <th>No. unconfirmed transactions</th>
    <th width="30%">Total income from all sales:</th>
    <th width="30%">Average income of each sale:</th>
  </tr>
  <tr>
    <td><?php echo order_num(30); ?></td>
    <td><?php echo order_num(30, false); ?></td>
    <?php
    $ti = total_income(30);
    $ai = average_income(30);
    $ti_c = round($ti * $exch_rate, 2);
    $ai_c = round($ai * $exch_rate, 2);
    ?>
    <td><?php echo bitsci::btc_num_format($ti, 8, $dec_shift).' '.$dec_unit.'BTC'; ?> 
	<small>(<?php echo $ti_c.' '.$curr_code; ?>)</small></td>
    <td><?php echo bitsci::btc_num_format($ai, 8, $dec_shift).' '.$dec_unit.'BTC'; ?> 
	<small>(<?php echo $ai_c.' '.$curr_code; ?>)</small></td>
  </tr>
</table>

<h4>Product summary:</h4>
<table class="table table-striped table-bordered table-condensed">
  <tr>
    <th>Number of active products:</th>
    <th>Number of inactive products:</th>
    <th width="30%">Best selling product:</th>
    <th width="30%">Highest ranked product:</th>
  </tr>
  <tr>
    <td><?php echo count_active_files(); ?></td>
	<td><?php echo count_inactive_files(); ?></td>
    <td>
	  <?php
	  $best_item = best_file();
	  if (!empty($best_item) && $best_item != 'N/A') {
		echo "<a href='admin.php?page=items&amp;action=edit&amp;fid=".
		$best_item['FileID']."'>".$best_item['FileName']."</a>";
	  } else {
	    echo 'none';
	  }
	  ?>
	</td>
	<td>
	  <?php 
	  $top_item = top_file();
	  if (!empty($top_item) && $top_item != 'N/A') {
		echo "<a href='admin.php?page=items&amp;action=edit&amp;fid=".
		$top_item['FileID']."'>".$top_item['FileName']."</a>";
	  } else {
	    echo 'none';
	  }
	  ?>
	</td>
  </tr>
</table>