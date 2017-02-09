<?php admin_valid();

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
   
    $method = $_POST['method'];
    $name = $_POST['name'];
    $price = $_POST['price'];
	$curr = $_POST['currency'];
    $type = $_POST['itype'];
    $stock = $_POST['stock'];
    $cat = $_POST['cat'];
    $desc = $_POST['desc'];
	$code = bin2hex(crypt_random_string(16));
	
	if (empty($method) || (empty($type) && $type != '0') || (empty($stock) && $stock != '0') ||
	(empty($cat) && $cat != '0') || empty($name) || empty($desc) || empty($price) || empty($curr)) {
	  $msg = "<p class='error_txt'>None of the fields can be empty!</p>";
	} else {
	  $price = ($curr == 'BTC') ? -$price : $price;
	  $new_id = create_file($type, $stock, $name, $desc, $cat, $code, $price, $method);
      if ($new_id > 0) {
        $msg = "<p class='happy_txt'>New item successfully created! ".
        "(<a href='admin.php?page=items&action=edit&fid=$new_id'>edit item</a>)</p>";	
		if ($method === 'download') {
          $msg .= "<div class='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button><p><b>UPLOADING:</b> You can upload and attach a file to this product by using the &quot;edit item&quot; link displayed above and then selecting &quot;EDIT FILE&quot;. Or if the size of the file exceeds the upload limit dictated by your web host you can manually upload a file for this product via FTP by renaming your file to: <b>$code</b> (no file extension) and then upload the file into the <i>uploads</i> folder. If you don't rename the file correctly or upload it into the wrong folder, the download will not work when the file is purchased.</p></div>";	
		} elseif ($method === 'codes') {
		  $stock_check = 0;
		  $codes = explode("\n", $_POST['codes']); 
		  foreach ($codes as $key => $value) {
			if (!empty($value)) {
		      insert_code(trim($value), $new_id, 0, 0);
			  $stock_check++;
		    }
		  }
		  if ($stock_check <> $stock) {
		    edit_file($new_id, "FileStock = $stock_check");
		  }
		}
      } else {
        $msg = "<p class='error_txt'>There was an unexpected error!</p>\n";  
      }
	}
  }
?>

<script language="JavaScript">
function update_form(sel_val) {
  
  switch(sel_val) {
  case 'email':
    $('#code_tr').hide();
    $('#item_name').html('Item Name:');
    $('#item_fprice').html('Item Price:');
    $('#item_type').html('Item Type:');
    $('#item_size').html('Item Stock:');
	$('#stock').removeAttr('readonly');
   break;
  case 'codes':
	$('#code_tr').show();
    $('#item_name').html('Code Name:');
    $('#item_fprice').html('Code Price:');
    $('#item_type').html('Code Type:');
	$('#item_size').html('Code Stock:');
	$('#stock').attr('readonly', 'readonly');
    if ($('#codes').val().trim() == '') {
	  $('#stock').val('0');
	} else {
      $('#stock').val(($('#codes').val()).lineCount().toString());
	}
   break;
  case 'keys':
	$('#code_tr').hide();
    $('#item_name').html('Key Name:');
    $('#item_fprice').html('Key Price:');
    $('#item_type').html('Key File (ID):');
    $('#item_size').html('Key Life (days):');
	$('#stock').removeAttr('readonly');
   break;
   case 'ship':
	$('#code_tr').hide();
    $('#item_name').html('Item Name:');
    $('#item_fprice').html('Item Price:');
    $('#item_type').html('Item Weight:');
    $('#item_size').html('Item Stock:');
	$('#stock').removeAttr('readonly');
   break;
  default:
	$('#code_tr').hide();
    $('#item_name').html('File Name:');
    $('#item_fprice').html('File Price:');
    $('#item_type').html('File Type (EXT):');
    $('#item_size').html('File Size (MB):');
	$('#stock').removeAttr('readonly');
  }
}

$(document).ready(function(){
  $('#code_tr').hide();
  
  $('#codes').keyup(function(){
    if ($('#codes').val().trim() == '') {
	  $('#stock').val('0');
	} else {
      $('#stock').val(($('#codes').val()).lineCount().toString());
	}
  });
  
  $('#method').on('change', function() {
    update_form($(this).val());
  });
});
</script>
<?php require_once(dirname(__FILE__).'/tinymce.inc.php'); ?>

  <p><b>Add New Item</b></p>

  <?php if (!empty($msg)) { echo $msg; } ?>
  <form class="form-inline" action="" method="post" name="newdown_form" target="_self">
    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
    <table cellpadding="5" cellspacing="0" border="0" width="400">
      <tr><td width="40%">Sell Method:</td><td align="right">
	    <select name="method" id="method">
		  <option value="download" selected="selected">File Download (unlimited stock)</option>
		  <option value="keys">File Key (unlimited stock)</option>
		  <option value="email">Manual Email (limited stock)</option>
		  <option value="codes">Code List (limited stock)</option>
		  <option value="ship">Physical Item (limited stock)</option>
		</select>
	  </td></tr>
      <tr><td width="20%" id="item_name">File Name:</td><td align="right">
	    <input name="name" type="text" maxlength="50" value="<?php if (!empty($_POST['name'])) { echo $_POST['name']; } ?>" />
	  </td></tr>
      <tr><td id="item_fprice">
	    File Price:</td><td align="right">
		<input name="price" type="text" maxlength="10" style="width:132px;" value="<?php if (!empty($_POST['price'])) { echo $_POST['price']; } ?>" />
		<select name="currency" id="currency" style="width:70px;">
		  <option value="<?php safe_echo($curr_code); ?>" selected="selected"><?php safe_echo($curr_code); ?></option>
		  <option value="BTC">BTC</option>
        </select>
	  </td></tr>
      <tr><td id="item_type">File Type (EXT):</td><td align="right">
	    <input name="itype" id="itype" type="text" maxlength="50" value="<?php if (!empty($_POST['itype'])) { echo $_POST['itype']; } ?>" />
	  </td></tr>
      <tr id="size_tr"><td id="item_size">File Size (MB):</td><td align="right">
	    <input name="stock" id="stock" type="text"  maxlength="10" value="<?php if (!empty($_POST['stock'])) { echo $_POST['stock']; } ?>" />
	  </td></tr>
      <tr><td id="item_cat">Category (ID):</td><td align="right">
	    <input name="cat" type="text" maxlength="5" value="<?php if (!empty($_POST['cat'])) { echo $_POST['cat']; } ?>" />
	  </td></tr>
	  <tr><td colspan="2">
	    <div style="width:100%;margin-bottom:5px;">
	      <div class='float_right'>
	        <button class='btn btn-mini' type='button' onClick='toggle_editor();'>Toggle Graphical Editor</button>
	      </div>
		  <div class='float_left'>
		    Description (supports HTML):
		  </div>
		  <br clear="all" />
		</div>
		<div style="width:400px;">
	      <textarea name="desc" id="page_data" maxlength="5000" style="width:390px;height:250px;"><?php if (!empty($_POST['desc'])) { echo $_POST['desc']; } ?></textarea>
		</div>
      </td></tr>
	  <tr id="code_tr"><td colspan="2">
	    Code/key List (one per line): <br />
		<textarea id="codes" name="codes" maxlength="999999" style="width:390px;height:250px;"><?php if (!empty($_POST['codes'])) { echo $_POST['codes']; } ?></textarea>
	  </td></tr>
	  <tr><td>
	    <a class="btn" href="admin.php?page=items">Go Back</a> 
	    <button class="btn" name="submit_btn" type="submit">Submit</button>
	  </td></tr>
	</table> 
	
    <h5>Sell Method Information:</h5>
	
	<p><u>File Download</u>: this will allow you to sell a file which can be downloaded by buyers immediately after their payment is confirmed. This type of product will have unlimited stock because it can be downloaded an infinite number of times. The download link supplied to the buyer upon purchase will expire in 2 days to prevent link sharing.</p>
	
	<p><u>File Key</u>: this will allow you to sell product keys which are generated on demand (unlimited) and allow buyers to instantly download the corresponding file from the 'Client Files' page. These keys can also be set to expire (input 0 for no expiry). To use this method you must first create a File Download product and then deactivate it. Then create a File Key product and put the Item ID in the 'Key File (ID)' field.</p>
	
	<p><u>Manual Email</u>: this will allow you to sell basically any digital product by manually emailing the item to the buyer after the payment has been confirmed. This type of product will have a limited stock because you may have a limited number of items. This method is slow but probably the safest way to sell digital products.</p>
	
	<p><u>Code List</u>: this will allow you to sell codes which are selected from a user-defined list of codes. When the buyers payment has been confirmed they will receive a code from that list (via automatic email) and then that code is taken out of stock. This type of product will have limited stock because each code in the list is only sold once.</p>
	
	<p><u>Physical Item</u>: this will allow you to sell physical items which you will manually ship to customers. The shipping cost will be calculated based on the item weight. The weight multiplier value can be modified in the SCI settings. This type of product will have limited stock because physical items can only be sold once.</p>

  </form>