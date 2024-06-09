<?php
session_start();
require("../../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Askıda") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Geliştiriciler") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_username = trim($_POST['username']);
			$post_password = $_POST['password'];
			$post_balance = $_POST['balance'];
			$post_level = $_POST['level'];

			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (empty($post_username) || empty($post_password)) {
				$msg_type = "error";
				$msg_content = "<b>Hata:</b> Lütfen tüm alanları doldurun.";
			} else if ($post_level != "Üye" AND $post_level != "Bayi" AND $post_level != "Yönetici" AND $post_level != "Acenta") {
				$msg_type = "error";
				$msg_content = "<b>Hata:</b> Geçersiz giriş.";
			} else if (mysqli_num_rows($checkdb_user) > 0) {
				$msg_type = "error";
				$msg_content = "<b>Hata:</b> $post_username kullanıcı adı veritabanında zaten kayıtlı.";
			} else {
				$post_api = random(20);
				$insert_user = mysqli_query($db, "INSERT INTO users (username, password, balance, level, registered, status, api_key, uplink) VALUES ('$post_username', '$post_password', '$post_balance', '$post_level', '$date', 'Aktif', '$post_api', '$sess_username')");
				if ($insert_user == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Başarılı:</b> Kullanıcı başarıyla eklendi.<br /><b>Kullanıcı Adı:</b> $post_username<br /><b>Şifre:</b> $post_password<br /><b>Seviye:</b> $post_level<br /><b>Bakiye:</b> ₺ ".number_format($post_balance, 0, ',', '.');
				} else {
					$msg_type = "error";
					$msg_content = "<b>Hata:</b> Sistem hatası.";
				}
			}
		}

	include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-plus"></i> Kullanıcı Ekle</h3>
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
											<div class="form-group">
												<label class="col-md-2 control-label">Seviye</label>
												<div class="col-md-10">
													<select class="form-control" name="level">
														<option value="Üye">Üye</option>
														<option value="Acenta">Acenta</option>
														<option value="Bayi">Bayi</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Kullanıcı Adı</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Şifre</label>
												<div class="col-md-10">
													<input type="text" name="password" class="form-control" placeholder="Şifre">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Bakiye</label>
												<div class="col-md-10">
													<input type="number" name="balance" class="form-control" placeholder="Bakiye">
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/kullanicilar.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Sıfırla</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="add">Ekle</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- sıra sonu -->
<?php
	include("../../lib/footer.php");
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>
