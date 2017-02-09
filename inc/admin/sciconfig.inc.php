<?php admin_valid(); ?>

  <p><b>SCI Configuration</b></p>

  <div class="row-fluid">
    <div class="span6">
	  <label class="setlab" title="The name of your business.">Business name:</label>
      <input type="text" name="seller" value="<?php echo $seller; ?>" />
	  <label class="setlab" title="Number of decimal places in sum total values.">Payment precision:</label>
      <input type="text" name="p_precision" value="<?php echo $p_precision; ?>" />
	  <label class="setlab" title="Allow a bit of wiggle room for inexact payments.">Payment variance:</label>
      <input type="text" name="p_variance" value="<?php echo $p_variance; ?>" />
	  <label class="setlab" title="The thousands separator used when displaying price values.">Thousands separator:</label>
      <input type="text" name="t_separator" value="<?php echo $t_separator; ?>" />
	  <label class="setlab" title="The decimal separator used when displaying price values.">Decimal separator:</label>
      <input type="text" name="d_separator" value="<?php echo $d_separator; ?>" />
	  <label class="setlab" title="Weight multiplier for calculating shipping costs (fiat value).">Weight multiplier:</label>
      <input type="text" name="weight_mult" value="<?php echo $weight_mult; ?>" />
	  <label class="setlab" title="Maximum number of vouchers allowed in cart.">Voucher limit:</label>
      <input type="text" name="voucher_limit" value="<?php echo $voucher_limit; ?>" />
	</div>
	<div class="span6">
	  <label class="setlab" title="The currency symbol used for fiat price values.">Fiat symbol:</label>
      <input type="text" name="curr_symbol" value="<?php echo $curr_symbol; ?>" />
	  <label class="setlab" title="The currency code of the fiat currency you wish to use (eg USD, AUD, GBP).">Fiat code:</label>
	  <select name="curr_code">
	  <?php
	  foreach ($market_data as $key => $value) {
	    if ($key === 'timestamp') { continue; }
	    $sel = ($key == $curr_code) ? ' selected="selected"' : '';
	    echo "<option value='$key'$sel>$key</option>\n";
	  }
	  ?>
	  </select>
	  <label class="setlab" title="Display BTC values using different units (eg mBTC = millibit).">Bitcoin units:</label>
	  <select name="dec_shift">
	  <?php
	  foreach ($unit_symbols as $key => $value) {
	    $sel = ($key == $dec_shift) ? ' selected="selected"' : '';
	    echo "<option value='$key'$sel>".$value."BTC</option>\n";
	  }
	  ?>
	  </select>
	  <label class="setlab" title="The time period used for weighted exchange rates.">Weighted average:</label>
      <select name="price_type">
	  <?php
	  $ptypes = array('24h' => '24 hours', '7d' => '7 days', '30d' => '30 days');
	  foreach ($ptypes as $key => $value) {
		$selected = ($key == $price_type) ? 'selected="selected"' : '';
	    echo "<option value='$key' $selected>$value</option>";
	  }
	  ?>
	  </select>
	  <label class="setlab" title="Enable this to have an email sent to the admin whenever an order is placed.">Send admin email:</label>
      <select name="send_email">
		<option value="true" <?php if ($send_email) { echo 'selected="selected"'; } ?>>true</option>
		<option value="false" <?php if (!$send_email) { echo 'selected="selected"'; } ?>>false</option>
      </select>
	  <label class="setlab" title="Enable this to log orders to the RSS feed and display the feed on the home page.">Enable RSS feed:</label>
      <select name="rss_feed">
		<option value="true" <?php if ($rss_feed) { echo 'selected="selected"'; } ?>>true</option>
		<option value="false" <?php if (!$rss_feed) { echo 'selected="selected"'; } ?>>false</option>
      </select>
	  <label class="setlab" title="A random string used for security purposes (at least 10 characters long).">Security string:</label>
      <input type="password" name="sec_str" value="******" />
	</div>
  </div>