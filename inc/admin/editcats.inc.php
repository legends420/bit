<?php admin_valid();

  echo "<h1>Categories</h1>\n";
  
  if (!empty($_GET['cid']) && !isset($_GET['pid']) && !isset($_GET['task'])) {
  
    if (!empty($_POST)) {
	
	  $_POST['cat_index'] = (empty($_POST['cat_index'])) ? 1 : $_POST['cat_index'];
	  $_POST['cat_parent'] = (empty($_POST['cat_parent'])) ? 0 : $_POST['cat_parent'];
	  $bad_par = false;
	  
	  if ($_POST['cat_parent'] > 0) {
        $par_cat = get_cat(safe_sql_str($_POST['cat_parent']));
        if (!empty($par_cat) && ($par_cat != 'N/A')) {
          $par_cat = mysqli_fetch_assoc($par_cat);
		  if ($par_cat['Parent'] > 0) {
		    $bad_par = true;
		  }
		}
	  }
	  
	  if ($bad_par == true) {
	    echo "<p class='error_txt'>Parent category cannot be another sub-category!</p>";
	  } elseif (empty($_POST['cat_name'])) {
	    echo "<p class='error_txt'>You must supply a category name!</p>";
	  } elseif (!is_numeric($_POST['cat_index']) || !is_numeric($_POST['cat_parent'])) {
	    echo "<p class='error_txt'>Index and Parent fields must be numeric values!</p>";
	  } else {
	    if (update_cat(safe_sql_str($_GET['cid']), $_POST['cat_index'], $_POST['cat_parent'],
		safe_sql_str($_POST['cat_name']), safe_sql_str($_POST['cat_icon']))) {
		  echo "<p class='happy_txt'>Category successfully updated!</p>";
	    } else {
		  echo "<p class='error_txt'>Failed to update category!</p>";
	    }
	  }
    }
	
    $sel_cat = get_cat(safe_sql_str($_GET['cid']));
    if (!empty($sel_cat) && ($sel_cat != 'N/A')) {
      $sel_cat = mysqli_fetch_assoc($sel_cat);
	  
?>

<p><b>Edit category:</b></p>

<form class="form-inline" name='cat_form' method='post' action=''>
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <table cellpadding="5">
	<tr><td><label for="cat_index">Index:</label></td>
	<td><input name="cat_index" type="text" maxlength="9" value="<?php safe_echo($sel_cat['CatPos']); ?>" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_parent" style="margin-top:6px;">Parent:</label></td>
	<td><input name="cat_parent" type="text" maxlength="9" value="<?php safe_echo($sel_cat['Parent']); ?>" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_name" style="margin-top:6px;">Name:</label></td>
	<td><input name="cat_name" type="text" maxlength="255" value="<?php safe_echo($sel_cat['Name']); ?>" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_icon" style="margin-top:6px;">Icon:</label></td>
	<td><input name="cat_icon" type="text" maxlength="255" value="<?php safe_echo($sel_cat['Image']); ?>" style="width:200px;" /></td></tr>
  </table>
  <br />
  <a class='btn' href='admin.php?page=editcats'>Go Back</a> 
  <button type='submit' name='submit' class='btn'>Update</button>
</form>

<p><b>NOTE:</b> you can make any category a sub-category of another category by putting the ID (not the Index) of the parent category into the Parent field above (you cannot make a sub-category the parent of another sub-category). If you don't want this category to be a sub-category then set the Parent field to 0.</p>

<p>The category Index is the position in the list where the category will be displayed (starting at 1). If two categories have the same index number they will be sorted alphabetically. When you create a new sub-category the index value of the first item in that sub-category can start back at 1.</p>

<?php
    } else {
	  echo "<p class='error_txt'>Category not found.</p>";
    }
  } elseif (isset($_GET['new'])) {
  
    if (!empty($_POST)) {
	
	  $_POST['cat_index'] = (empty($_POST['cat_index'])) ? 1 : $_POST['cat_index'];
	  $_POST['cat_parent'] = (empty($_POST['cat_parent'])) ? 0 : $_POST['cat_parent'];
	  $bad_par = false;
		  
	  if ($_POST['cat_parent'] > 0) {
        $par_cat = get_cat(safe_sql_str($_POST['cat_parent']));
        if (!empty($par_cat) && ($par_cat != 'N/A')) {
          $par_cat = mysqli_fetch_assoc($par_cat);
		  if ($par_cat['Parent'] > 0) {
		    $bad_par = true;
		  }
		}
	  }
	  
	  if ($bad_par == true) {
	    echo "<p class='error_txt'>Parent category cannot be another sub-category!</p>";
	  } elseif (empty($_POST['cat_name'])) {
	    echo "<p class='error_txt'>You must supply a category name!</p>";
	  } elseif (!is_numeric($_POST['cat_index']) || !is_numeric($_POST['cat_parent'])) {
	    echo "<p class='error_txt'>Index and Parent fields must be numeric values!</p>";
	  } else {
	    if (insert_cat($_POST['cat_index'], $_POST['cat_parent'], 
		safe_sql_str($_POST['cat_name']), safe_sql_str($_POST['cat_icon']), 1)) {
		  echo "<p class='happy_txt'>Category successfully created!</p>";
	    } else {
		  echo "<p class='error_txt'>Failed to create category!</p>";
	    }
	    $categories = get_pcats();
	  }
    }
?>

<p><b>Create new category:</b></p>

<form class='form-inline' name='cat_form' method='post' action=''>
  <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>" />
  <table cellpadding="5">
	<tr><td><label for="cat_index">Index:</label></td>
	<td><input name="cat_index" type="text" maxlength="9" value="" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_parent" style="margin-top:6px;">Parent:</label></td>
	<td><input name="cat_parent" type="text" maxlength="9" value="" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_name" style="margin-top:6px;">Name:</label></td>
	<td><input name="cat_name" type="text" maxlength="255" value="" style="width:200px;" /></td></tr>
	<tr><td><label for="cat_icon" style="margin-top:6px;">Icon:</label></td>
	<td><input name="cat_icon" type="text" maxlength="255" value="" style="width:200px;" /></td></tr>
  </table>
  <br />
  <a class='btn' href='admin.php?page=editcats'>Go Back</a> 
  <button class='btn' type='submit' name='submit'>Submit</button>
</form>

<p><b>NOTE:</b> you can make this category a sub-category of another category by putting the ID (not the Index) of the parent category into the Parent field above (you cannot make a sub-category the parent of another sub-category). If you don't want to make this category a sub-category then input 0 into the Parent field.</p>

<p>The category Index is the position in the list where the category will be displayed (starting at 1). If two categories have the same index number they will be sorted alphabetically. When you create a new sub-category the index value of the first item in that sub-category can start back at 1.</p>

<?php
  } else {
  
    if (!empty($_GET['task'])) {
	  if ($_SESSION['csrf_token'] !== $_GET['toke']) {
	    echo "<p class='error_txt'>".LANG('INVALID_ACCESS')."</p>";
      } elseif ($_GET['task'] == 'toggle') {
	    if ($_GET['newstate'] == 1) {
	      if (edit_cat($_GET['cid'], 'Active', 1)) {
		    echo "<p class='happy_txt'>Category successfully enabled!</p>";
	      } else {
		    echo "<p class='error_txt'>Failed to enable category!</p>";
	      }
		} else {
	      if (edit_cat($_GET['cid'], 'Active', 0)) {
		    echo "<p class='happy_txt'>Category successfully disabled!</p>";
	      } else {
		    echo "<p class='error_txt'>Failed to disable category!</p>";
	      }
		}
	  } elseif ($_GET['task'] == 'delete') {
	    if (delete_cat($_GET['cid'])) {
		  echo "<p class='happy_txt'>Category successfully deleted!</p>";
	    } else {
		  echo "<p class='error_txt'>Failed to delete category!</p>";
	    }
	  }
	  $categories = get_pcats();
	}
	
	$sub_ext = '';
	$cat_thead = 'Children';
    if (isset($_GET['pid'])) {
      $sel_cat = get_cat(safe_sql_str($_GET['pid']));
      if (!empty($sel_cat) && ($sel_cat != 'N/A')) {
        $sel_cat = mysqli_fetch_assoc($sel_cat);
		$categories = get_scats($sel_cat['CatID']);
		$sub_ext = '&amp;pid='.$sel_cat['CatID'];
		$cat_thead = 'Parent';
	  } else {
	    echo "<p class='error_txt'>Invalid category ID!</p>";
	  }
	}
?>

<p><b>List of categories:</b></p>
<table class='table table-striped table-bordered table-hover table-condensed'>
<tr><th>Name</th><th>ID</th><th>Index</th><th><?php echo $cat_thead; ?></th><th>Icon</th><th>Actions</th></tr>

<?php
    if (!empty($categories) && ($categories != 'N/A')) {
      mysqli_data_seek($categories, 0);
      while ($category = mysqli_fetch_assoc($categories)) {
	  
	  	if (empty($sub_ext)) {
		  $child_count = count_sub_cats($category['CatID']);
		  $subc_show = ($child_count == 0) ? 0 : "<a href='admin.php?page".
		  "=editcats&amp;pid=".$category['CatID']."'>$child_count</a>";
		} else {
		  $subc_show = safe_str($_GET['pid']);
		}
		
	    $cat_img = (empty($category['Image'])) ? 'None' : 
		"<img width='20' height='20' src='".$category['Image']."' alt='' />";
		
		if ($category['Active'] == 1) {
		  $cat_tog = 'DISABLE';
		  $row_class = 'success';
		  $action = 0;
		} else {
		  $cat_tog = 'ENABLE';
		  $row_class = 'error';
		  $action = 1;
		}
		
	    echo "<tr class='$row_class'><td>".$category['Name']."</td><td>".$category['CatID']."</td><td>".
		$category['CatPos']."</td><td>$subc_show</td><td>$cat_img</td><td><a href='admin.php?page=".
		"editcats&amp;cid=".$category['CatID']."'>EDIT</a> | <a href='#' onclick=\"toggle_cat(".
		$category['CatID'].", $action, '$sub_ext');\">$cat_tog</a> | <a href='#' onclick=\"del_cat(".
		$category['CatID'].", '$sub_ext');\">REMOVE</a></td></tr>";
	  }
	}
?>

</table>

<p><?php if (!empty($sub_ext)) { echo "<a class='btn' href='admin.php?page=editcats'>Go Back</a>"; } ?> 
<a class='btn' href='admin.php?page=editcats&amp;new'>New Category</a></p>

<p><b>NOTE:</b> when you create a new product you will need to input the Category <u>ID</u>, not the Category Index.</p>

<script language="JavaScript">
var csrf_token = '<?php echo $_SESSION['csrf_token']; ?>';

function del_cat(cat_id, sub_ext) {
	if (confirm('Are you sure you want to delete this category?')) {
	  var se = sub_ext.replace('&amp;', '&');
	  redirect('admin.php?page=editcats&cid='+cat_id+'&task=delete&toke='+csrf_token+se);
	}
}

function toggle_cat(cat_id, new_state, sub_ext) {
	if (new_state == 1) { var action = 'enable'; } else { var action = 'disable'; }
	if (confirm('Are you sure you want to '+action+' this category?')) {
		var se = sub_ext.replace('&amp;', '&');	
		redirect('admin.php?page=editcats&cid='+cat_id+'&task=toggle&newstate='+new_state+'&toke='+csrf_token+se);
	}
}
</script>

<?php } ?>