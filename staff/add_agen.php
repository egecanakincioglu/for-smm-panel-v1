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
	} else if ($data_user['level'] == "Member" OR $data_user['level'] == "Agen") {
		header("Location: ".$cfg_baseurl);
	} else {
		$post_balance = $cfg_agen_bonus;
		$post_price = $cfg_agen_price;
		if (isset($_POST['add'])) {
			$post_username = trim($_POST['username']);
			$post_password = $_POST['password'];

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_password)) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Lütfen tüm girişleri doldurun.";
			} else if (mysqli_num_rows($checkdb_user) > 0) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Kullanıcı adı $post_username zaten veritabanına kayıtlı.";
			} else if ($data_user['balance'] < $post_price) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Bakiyeniz Temsilci olarak kaydolmak için yeterli değil.";
			} else {
				$post_api = random(20);
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$post_price WHERE username = '$sess_username'");
				$insert_user = mysqli_query($db, "INSERT INTO users (username, password, balance, level, registered, status, api_key, uplink) VALUES ('$post_username', '$post_password', '$post_balance', 'Agen', '$date', 'Active', '$post_api', '$sess_username')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Başarılı:</b> Temsilci eklendi.<br /><b>Kullanıcı adı:</b> $post_username<br /><b>Şifreler:</b> $post_password<br /><b>Seviyeler:</b> Agen<br /><b>Denge:</b> Rp ".number_format($post_balance,0,',','.');
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
										<h3 class="panel-title"><i class="fa fa-plus"></i> Temsilci Ekle</h3>
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
												- Bakiyeniz IDR tarafından kesildi <?php echo number_format($post_price,0,',','.'); ?> 1 temsilcinin kaydı için.<br />
												- Yeni temsilciler Rp bakiyesi alacak. <?php echo number_format($post_balance,0,',','.'); ?>.
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Kullanıcı adı</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Kullanıcı adı">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Şifreler</label>
												<div class="col-md-10">
													<input type="text" name="password" class="form-control" placeholder="Şifreler">
												</div>
											</div>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Tekrarlamak</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="add">Artı</button>
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