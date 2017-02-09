<?php admin_valid(); ?>

<div class="alert no_display" id="error_box">
  <span id='error_msg'></span>
</div>

<h1>System</h1>

<?php
$ipn_log = "./sci/$ipn_log_file";

if (isset($_GET['action'])) {

  if ($_GET['action'] == 'logs') {
  
    if (!empty($_GET['task'])) {
	  if ($_SESSION['csrf_token'] === $_GET['toke']) {
	    if ($_GET['task'] === 'clear') {
	      if (file_put_contents($ipn_log, '') !== false) {
	        echo "<p class='happy_txt'>Log file successfully cleared!</p>";
	      } else {
	        echo "<p class='error_txt'>Failed to clear log file!</p>";
	      }
		}
	  } else {
	    echo "<p class='error_txt'>".LANG('INVALID_ACCESS')."</p>";
	  }
	}
?>

<script language="JavaScript">
var csrf_token = '<?php echo $_SESSION['csrf_token']; ?>';

function clear_log() {
	if (confirm('This action will permanently clear the IPN log file. Continue?')) {
	  redirect('admin.php?page=system&action=logs&task=clear&toke='+csrf_token);
	}
}
</script>

<p><b>IPN Order Log:</b></p>

<textarea style="width:500px;height:350px;"><?php 
echo file_get_contents($ipn_log); ?></textarea>

<br clear="both" />
<a class="btn" href="admin.php?page=system">Go Back</a> 
<a class="btn" href="#" onclick="clear_log();">Clear Log</a>

<?php
  } elseif ($_GET['action'] == 'backups') {
  
    $backups = list_binaries('sci/backup/');
	$options = '';
	
	foreach ($backups as $key => $value) {
	  if ($value !== 'index.html') {
	    $options .= "<option value='$value'>$value</option>\n";
	  }
	}
?>

<p><b>Select a Backup:</b></p>

<select id="backup_list" class="bot_gap" style="width:360px;">
  <?php echo $options; ?>
</select>

<br /><textarea id="etxt" style="width:350px;height:150px;">Loading ...</textarea>
<br /><a class="btn" href="admin.php?page=system">Go Back</a> 
<input type="button" class="btn" onclick="click_btn();" value="Decrypt" />

<script language="JavaScript">
function do_decrypt(pk, ciphertext) {

	var rsa = new RSAKey();
	var pub_dat = '<?php echo $pub_rsa_key; ?>';
	var pri_dat = pk.split(':');

	var n = pub_dat;
	var d = pri_dat[0];
	var p = pri_dat[1];
	var q = pri_dat[2];
	var dp = pri_dat[3];
	var dq = pri_dat[4];
	var c = pri_dat[5];

	rsa.setPrivateEx(n, '10001', d, p, q, dp, dq, c);

	var res = rsa.decrypt(ciphertext);

	if (res == null) {
		return "*** Invalid Ciphertext ***";
	} else {
		return res;
	}
}

function click_btn() {
	var priv_key = prompt('Private RSA Key:', '');
	$('#etxt').val(do_decrypt(priv_key, $('#etxt').val()));
}

function handle_error(response) {
    $('#error_box').show();
    $('#error_msg').html(response);
}

function handle_success(response) {
	$('#error_box').hide();
	$('#etxt').val(response);
}

function load_backup(file) {
	ajax_get('./inc/jobs/getfile.inc.php', 
	'file='+file, handle_success, handle_error);
}

$('#backup_list').change(function() {
	load_backup(this.value);
});

$(document).ready(function() {
	load_backup($('#backup_list').val());
});
</script>

<?php
  } elseif ($_GET['action'] == 'info') {
  
    echo '<p><b>System information:</b></p>';
	echo '<p><b>Application Version:</b> ' . $bs_version . '<br />';
	echo '<b>Current PHP version:</b> ' . phpversion() . '<br />';
	echo '<b>Current MySQL version:</b> ' . $conn->server_info . '<br />';
	echo '<b>Max upload file size:</b> ' . ini_get('post_max_size') . 'B <br />';
	echo '<b>Total disk space:</b> ' . get_disk_space('total') . 'GB <br />';
	echo '<b>Free disk space:</b> ' . get_disk_space('free') . 'GB <br />';
	echo '<b>IP address:</b> ' . get_remote_ip() . '</p>';
	echo '<p><a class="btn" href="admin.php?page=system">Go Back</a></p>';

  } else {
    echo LANG('INVALID_ACTION');
  }
} else {
?>

<p><b>Select an option:</b></p>

<p>
  <a href="admin.php?page=system&action=logs" title="View Logs">ORDER LOGS</a><br />
  <a href="admin.php?page=system&action=backups" title="Key Backups">KEY BACKUPS</a><br />
  <a href="admin.php?page=system&action=info" title="System Information">SYSTEM INFO</a><br />
  <a href="admin.php?page=home" title="Main Menu">BACK</a>
</p>

<?php } ?>