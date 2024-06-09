<?php
session_start();
require("../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] == "Member") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_username = $_POST['username'];
			$post_balance = $_POST['balance'];

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_balance)) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Lütfen tüm girişleri doldurun.";
			} else if (mysqli_num_rows($checkdb_user) == 0) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> User $post_username bulunamadı.";
			} else if ($post_balance < $cfg_min_transfer) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Minimum transfer Rp'dir $cfg_min_transfer.";
			} else if ($data_user['balance'] < $post_balance) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Bakiyeniz bu tutarı transfer etmek için yeterli değil.";
			} else {
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$post_balance WHERE username = '$sess_username'");
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance+$post_balance WHERE username = '$post_username'");
				$insert_tf = mysqli_query($db, "INSERT INTO transfer_balance (sender, receiver, quantity, date) VALUES ('$sess_username', '$post_username', '$post_balance', '$date')");
				if ($insert_tf == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Başarılı:</b> Bakiye aktarımı başarılı.<br /><b>Gönderen:</b> $sess_username<br /><b>Alıcı:</b> $post_username<br /><b>Transfer miktarı:</b> Rp ".number_format($post_balance,0,',','.')." Denge";
				} else {
					$msg_type = "error";
					$msg_content = "<b>Başarısız:</b> Sistem hatası.";
				}
			}
		}

	include("../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-plus"></i> Bakiye transferi</h3>
									</div>
									<div class="panel-body">
										<?php 
										if ($msg_type == "success") {
										?>
										<div class="alert alert-success">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										} else if ($msg_type == "error") {
										?>
										<div class="alert alert-danger">
											<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="alert alert-info">
												- Bakiyeniz, bakiye transfer tutarına göre düşülecektir.<br />
												- Minimum transfer Rp'si <?php echo number_format($cfg_min_transfer,0,',','.'); ?>.
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Alıcı Kullanıcı Adı</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Transfer miktarı</label>
												<div class="col-md-10">
													<input type="number" name="balance" class="form-control" placeholder="Transfer miktarı">
												</div>
											</div>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Tekrar</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="add">Aktar</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
	include("../lib/footer.php");
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>