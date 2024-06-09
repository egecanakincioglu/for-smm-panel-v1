<?php
session_start();
require("../mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}

	include("../lib/header.php");
	$msg_type = "nothing";

	if (isset($_POST['change_pswd'])) {
		$post_password = trim($_POST['password']);
		$post_npassword = trim($_POST['npassword']);
		$post_cnpassword = trim($_POST['cnpassword']);
		if (empty($post_password) || empty($post_npassword) || empty($post_cnpassword)) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Lütfen tüm alanları doldurun.";
		} else if ($post_password <> $data_user['password']) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Şifre yanlış.";
		} else if (strlen($post_npassword) < 5) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Yeni şifre çok kısa, en az 5 karakter olmalıdır.";
		} else if ($post_cnpassword <> $post_npassword) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Yeni şifre onayı eşleşmiyor.";
		} else {
			$update_user = mysqli_query($db, "UPDATE users SET password = '$post_npassword' WHERE username = '$sess_username'");
			if ($update_user == TRUE) {
				$msg_type = "success";
				$msg_content = "<b>Başarılı:</b> Şifre değiştirildi.";
			} else {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Sistem hatası.";
			}
		}
	} else if (isset($_POST['change_api'])) {
		$set_api_key = random(20);
		$update_user = mysqli_query($db, "UPDATE users SET api_key = '$set_api_key' WHERE username = '$sess_username'");
		if ($update_user == TRUE) {
			$msg_type = "success";
			$msg_content = "<b>Başarılı:</b> API Anahtarı değiştirildi.";
		} else {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Sistem hatası.";
		}
	}
	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
?>
						<div class="row">
							<div class="col-md-12">
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
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-gear"></i> Ayarlar</h3>
									</div>
									<div class="panel-body">
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-3 control-label">Şifre</label>
												<div class="col-md-9">
													<input type="password" name="password" class="form-control" placeholder="Şifre">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 control-label">Yeni Şifre</label>
												<div class="col-md-9">
													<input type="password" name="npassword" class="form-control" placeholder="Yeni Şifre">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-3 control-label">Yeni Şifre Onayı</label>
												<div class="col-md-9">
													<input type="password" name="cnpassword" class="form-control" placeholder="Yeni Şifre Onayı">
												</div>
											</div>
											<button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="change_pswd">Şifreyi Değiştir</button>
										</form>
									</div>
								</div>
							</div>
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-random"></i> API Anahtarı</h3>
									</div>
									<div class="panel-body">
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 control-label">API Anahtarı</label>
												<div class="col-md-10">
													<input type="text" class="form-control" value="<?php echo $data_user['api_key']; ?>" readonly>
												</div>
											</div>
											<button type="submit" class="pull-right btn btn-success btn-bordered waves-effect w-md waves-light" name="change_api">API Anahtarını Değiştir</button>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>
