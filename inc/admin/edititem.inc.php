<?php admin_valid();

if (!empty($_GET['fid'])) {
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $meth = safe_sql_str($_POST['method']);
    $type = safe_sql_str($_POST['type']);
    $name = safe_sql_str($_POST['name']);
    $desc = safe_sql_str($_POST['desc']);
	$code =  safe_sql_str($_POST['code']);
    $price = safe_decimal($_POST['price']);
    $stock = safe_decimal($_POST['stock']);
    $cat = (int) $_POST['cat'];
	$curr = $_POST['currency'];
	
	if ((empty($type) && $type != '0') || (empty($stock) && $stock != '0') || (empty($cat) && $cat != '0') || 
	empty($name) || empty($price) || empty($curr) || empty($desc) || (empty($code) && $code != '0')) {
	  $msg = "<p class='error_txt'>None of the fields can be empty!</p>";
	} else {
	  $price = ($curr == 'BTC') ? -$price : $price;
	  $new_id = edit_file(safe_sql_str($_GET['fid']), "FileMethod='$meth', FileType='$type', FileStock=$stock, FileName='$name', FileDesc='$desc', FileCat=$cat, FilePrice=$price, FileCode='$code'");
      if ($new_id > 0) {
        $msg = "<p class='happy_txt'><b>Item was successfully updated!</b></p>";
      } else {
        $msg = "<p class='error_txt'>There was an unexpected error!</p>\n";  
      }
	}
  }
} else {
  $msg = "<p class='error_txt'>File ID was not specified!</p>\n";
  $continue = 'no';
}

require_once(dirname(__FILE__).'/tinymce.inc.php');
?>

<p style='text-align:center;'><b>Update Item</b></p>

<center>
  <?php
  if (!empty($msg)) { echo $msg; } 
    if (empty($continue)) {
	  if (isset($_POST['method'])) {
	    $file['FileMethod'] = $_POST['method'];
	  }
	  if ($file['FileMethod'] == 'download') {
	    $type_str = 'Type (EXT)';
	    $stock_str = 'Size (MB)';
		$meth_cur = 'Instant Download';
		$meth_val = 'download';
	  } elseif ($file['FileMethod'] == 'keys') {
	    $type_str = 'File (ID)';
	    $stock_str = 'Life (days)';
		$meth_cur = 'File Key';
		$meth_val = 'keys';
	  } elseif ($file['FileMethod'] == 'ship') {
	    $type_str = 'Weight';
	    $stock_str = 'Stock';
		$meth_cur = 'Physical Item';
		$meth_val = 'ship';
	  } elseif ($file['FileMethod'] == 'email') {
	    $type_str = 'Type';
	    $stock_str = 'Stock';
		$meth_cur = 'Manual Email';
		$meth_alt = 'Code List';
		$malt_val = 'codes';
	  } else {
	    $type_str = 'Type';
	    $stock_str = 'Stock';
		$meth_cur = 'Code List';
		$meth_alt = 'Manual Email';
		$malt_val = 'email';
	  }
  ?>
  <form class="form-inline" action="" method="post" name="newdown_form" target="_self">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
    <table cellpadding="5" cellspacing="0" border="0" style="width:400px;"><tr>
      <tr><td align="left">Method:</td><td align="right">
	    <select name="method" id="method" <?php if (!isset($meth_alt)) { echo "disabled='disabled'"; } ?>>
		  <option value="<?php echo $file['FileMethod']; ?>" selected='selected'><?php echo $meth_cur; ?></option>
		  <?php if (isset($meth_alt)) { ?>
		  <option value="<?php echo $malt_val; ?>"><?php echo $meth_alt; ?></option>
        </select>
		<?php } else { ?>
		</select>
		<input name="method" type="hidden" value="<?php echo $meth_val; ?>" />
		<?php } ?>
	  </td></tr>
      <tr><td align="left">Name:</td><td align="right">
	  <input name="name" type="text" maxlength="50" value="<?php if (!empty($_POST['name'])) { 
	  echo $_POST['name']; } else { echo $file['FileName']; } ?>" /></td></tr>
      <tr><td align="left">Price:</td><td align="right">
	    <input name="price" type="text" maxlength="10" style="width:132px;" value="<?php 
		if (!empty($_POST['price'])) { echo $_POST['price']; } else { echo abs($file['FilePrice']); } ?>" />
		<select name="currency" id="currency" style="width:70px;">
		  <?php 
		  if (!empty($_POST['currency'])) {
		    if ($_POST['currency'] == 'BTC') {
			  $opt2_sel = "selected='selected'";
			  $opt1_sel = '';
			} else {
			  $opt1_sel = "selected='selected'";
			  $opt2_sel = '';
			}
		  } else {
		    if ($file['FilePrice'] > 0) {
			  $opt1_sel = "selected='selected'";
			  $opt2_sel = '';
			} else {
			  $opt2_sel = "selected='selected'";
			  $opt1_sel = '';
			}
		  }
		  ?>
		  <option value="<?php safe_echo($curr_code); ?>" <?php 
		  echo $opt1_sel; ?>><?php safe_echo($curr_code); ?></option>
		  <option value="BTC" <?php echo $opt2_sel; ?>>BTC</option>
        </select>
	  </td></tr>
      <tr><td align="left"><?php echo $type_str; ?>:</td><td align="right">
	    <input name="type" type="text" maxlength="50" value="<?php 
		if (!empty($_POST['type'])) { echo $_POST['type']; } else { echo $file['FileType']; } ?>" />
	  </td></tr>
      <tr><td align="left"><?php echo $stock_str; ?>:</td><td align="right">
	    <input name="stock" type="text"  maxlength="10" value="<?php 
		if (!empty($_POST['stock'])) { echo $_POST['stock']; } else { echo $file['FileStock']; } ?>" />
	  </td></tr>
      <tr><td align="left">Category:</td><td align="right">
	    <input name="cat" type="text" maxlength="5" value="<?php 
		if (!empty($_POST['cat'])) { echo $_POST['cat']; } else { echo $file['FileCat']; } ?>" />
	  </td></tr>
      <tr><td align="left">Code:</td><td align="right">
	    <input name="code" type="text" maxlength="50" value="<?php 
		if (!empty($_POST['code'])) { echo $_POST['code']; } else { echo $file['FileCode']; } ?>" />
	  </td></tr>
	  <tr><td colspan="2">
	    <div style="width:100%;margin-bottom:5px;">
	      <div class='float_right'>
	        <button class='btn btn-mini' type='button' onClick='toggle_editor();'>Toggle Graphical Editor</button>
	      </div>
		  <div class='float_left'>
		    Description:
		  </div>
		  <br clear="all" />
		</div>
		<div style="width:400px;">
	      <textarea name="desc" id="page_data" maxlength="5000" style="width:390px;height:250px;"><?php if (!empty($_POST['desc'])) { echo $_POST['desc']; } else { echo $file['FileDesc']; } ?></textarea>
		</div>
	  </td></tr>
	  <tr><td colspan="2">
		<a class="btn" href="admin.php?page=items&action=edit&fid=<?php echo $_GET['fid']; ?>">Go Back</a> 
        <button type="submit" class="btn">Submit</button>
	  </td></tr>
	</table>
  </form>
</center>

<?php } ?>
