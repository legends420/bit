<?php admin_valid();

if ($feat_prods == false) {
  echo "<p class='error_tst'>Featured products are not enabled. The selected products will not be displayed until you set \$feat_prods to true. \$feat_prods can be changed in the main config file.</p>";
}

if (($_SERVER['REQUEST_METHOD'] == 'POST') && isset($_POST['feat_ids'])) {
  $feat_ids = explode(',', $_POST['feat_ids']);
  $feat_items = array();
  $feat_new = '';

  foreach ($feat_ids as $key => $value) {
    if (is_numeric($value)) {
      $feat_new .= round($value).',';
    }
  }
  
  $feat_new = str_replace(',,', ',', $feat_new);
  $feat_new = trim($feat_new, ',');
  
  file_put_contents("inc/feat_ids.inc", $feat_new);
}

$feat_str = file_get_contents("inc/feat_ids.inc");
?>

<p><b>Featured products</b></p>

<form class="form-inline" name="feat_form" method="post" action="">
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <label>Product ID's (comma seperated):</label><br />
  <div class="input-append">
    <input type="text" name="feat_ids" value="<?php echo $feat_str ?>" maxlength="50" />
    <input class="btn" type="submit" value="Update" />
  </div>
</form>

<p>Selected products:</p>

<?php

if (!empty($feat_str)) {
  $feat_ids = explode(',', $feat_str);
  $feat_items = array();
  $feat_test = 0;
  $feat_count = 0;

  foreach ($feat_ids as $key => $value) {
    $feat_test = get_file(safe_sql_str($value));
    if (!empty($feat_test) && ($feat_test != 'N/A')) {
      $feat_items[$feat_count] = $feat_test;
	  $feat_count++;  
    }
  }
}

if (!empty($feat_items) && ($feat_items != 'N/A')) {

  echo '<ul>';  
  for ($findex=0;$findex<$feat_count;$findex++) {
  
    $row = mysqli_fetch_assoc($feat_items[$findex]);
	
    if (!empty($row)) {
			
      if (strlen($row['FileName']) > 18) {
        $item_name = safe_str($row['FileName']);
        $short_name = str_replace(' ', '&nbsp;', safe_str(substr($row['FileName'], 0, 18).'...'));
      } else {
        $item_name = safe_str($row['FileName']);
        $short_name = $item_name;
      }
	
      $item_url = "admin.php?page=items&amp;action=edit&amp;fid=".$row['FileID'];
			
      echo "<li><a href='$item_url' title='$item_name'>".$short_name."</a></li>";
    }
  }
  echo '</ul>';
} else {
  echo "<p>No featured products selected.</p>";
}
?>

<p><a class="btn" href="admin.php?page=items">Go Back</a></p>