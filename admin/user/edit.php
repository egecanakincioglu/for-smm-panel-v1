<?php
session_start();
require("../../mainconfig.php");
$msg_type = "hiçbir şey";

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
		if (isset($_GET['username'])) {
			$post_username = $_GET['username'];
			$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			$datadb_user = mysqli_fetch_assoc($checkdb_user);
			if (mysqli_num_rows($checkdb_user) == 0) {
				header("Location: ".$cfg_baseurl."admin/kullanicilar.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_status = $_POST['status'];
					$post_password = $_POST['password'];
					$post_balance = $_POST['balance'];
					$post_level = $_POST['level'];
					if (empty($post_password)) {
						$msg_type = "hata";
						$msg_content = "<b>Başarısız:</b> Lütfen tüm alanları doldurun.";
					} else if ($post_level != "Üye" AND $post_level != "Bayi" AND $post_level != "Yönetici" AND $post_level != "Acente") {
						$msg_type = "hata";
						$msg_content = "<b>Başarısız:</b> Geçersiz giriş.";
					} else if ($post_status != "Aktif" AND $post_status != "Askıda") {
						$msg_type = "hata";
						$msg_content = "<b>Başarısız:</b> Geçersiz giriş.";
					} else {
						$update_user = mysqli_query($db, "UPDATE users SET password = '$post_password', balance = '$post_balance', level = '$post_level', status = '$post_status' WHERE username = '$post_username'");
						if ($update_user == TRUE) {
							$msg_type = "başarılı";
							$msg_content = "<b>Başarılı:</b> Kullanıcı başarıyla güncellendi.<br /><b>Kullanıcı Adı:</b> $post_username<br /><b>Şifre:</b> $post_password<br /><b>Seviye:</b> $post_level<br /><b>Durum:</b> $post_status<br /><b>Bakiye:</b> Rp ".number_format($post_balance,0,',','.');
						} else {
							$msg_type = "hata";
							$msg_content = "<b>Başarısız:</b> Sistem hatası.";
						}
					}
				}
				$checkdb_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
				$datadb_user = mysqli_fetch_assoc($checkdb_user);
				include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-edit"></i> Kullanıcıyı Düzenle</h3>
									</div>
									<div class="panel-body">
										<?php 
										if ($msg_type == "başarılı") {
										?>
										<div class="alert alert-icon alert-success alert-dismissible fade in" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Kapat">
												<span aria-hidden="true">&times;</span>
											</button>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										} else if ($msg_type == "hata") {
										?>
										<div class="alert alert-icon alert-danger alert-dismissible fade in" role="alert">
											<button type="button" class="close" data-dismiss="alert" aria-label="Kapat">
												<span aria-hidden="true">&times;</span>
											</button>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_content; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 control-label">Kullanıcı Adı</label>
												<div class="col-md-10">
													<input type="text" class="form-control" placeholder="Kullanıcı Adı" value="<?php echo $datadb_user['username']; ?>" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Şifre</label>
												<div class="col-md-10">
													<input type="text" name="password" class="form-control" placeholder="Şifre" value="<?php echo $datadb_user['password']; ?>">
												</div>
												</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Seviye</label>
												<div class="col-md-10">
													<select class="form-control" name="level">
														<option value="<?php echo $datadb_user['level']; ?>"><?php echo $datadb_user['level']; ?> (Seçili)</option>
														<option value="Üye">Üye</option>
														<option value="Acente">Acente</option>
														<option value="Bayi">Bayi</option>
														<option value="Yönetici">Yönetici</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Durum</label>
												<div class="col-md-10">
													<select class="form-control" name="status">
														<option value="<?php echo $datadb_user['status']; ?>"><?php echo $datadb_user['status']; ?> (Seçili)</option>
														<option value="Aktif">Aktif</option>
														<option value="Askıda">Askıda</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Bakiye</label>
												<div class="col-md-10">
													<input type="number" name="balance" class="form-control" placeholder="Bakiye" value="<?php echo $datadb_user['balance']; ?>">
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/kullanicilar.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Sıfırla</button>
												<button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="edit">Düzenle</button>
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
			header("Location: ".$cfg_baseurl."admin/kullanicilar.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>
