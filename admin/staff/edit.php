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
		if (isset($_GET['id'])) {
			$post_id = $_GET['id'];
			$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
			$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
			if (mysqli_num_rows($checkdb_staff) == 0) {
				header("Location: ".$cfg_baseurl."admin/personel.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_name = $_POST['name'];
					$post_fbid = $_POST['fbid'];
					$post_level = $_POST['level'];
					$post_pict = $_POST['pict'];
					if (empty($post_name) || empty($post_fbid)) {
						$msg_type = "error";
						$msg_content = "<b>Hata:</b> Lütfen tüm alanları doldurun.";
					} else if ($post_level != "Yönetici" AND $post_level != "Bayi" AND $post_level != "Geliştiriciler") {
						$msg_type = "error";
						$msg_content = "<b>Hata:</b> Geçersiz giriş.";
					} else {
						$update_staff = mysqli_query($db, "UPDATE staff SET name = '$post_name', contact = '$post_fbid', level = '$post_level', pict = '$post_pict' WHERE id = '$post_id'");
						if ($update_staff == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Başarılı:</b> Personel başarıyla güncellendi.<br /><b>Adı:</b> $post_name<br /><b>İletişim:</b> $post_fbid<br /><b>Seviye:</b> $post_level";
						} else {
							$msg_type = "error";
							$msg_content = "<b>Hata:</b> Sistem hatası.";
						}
					}
				}
				$checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
				$datadb_staff = mysqli_fetch_assoc($checkdb_staff);
				include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-edit"></i> Personel Düzenle</h3>
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
												<label class="col-md-2 control-label">Adı</label>
												<div class="col-md-10">
													<input type="text" name="name" class="form-control" placeholder="Adı" value="<?php echo $datadb_staff['name']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Profil Fotoğrafı URL'si</label>
												<div class="col-md-10">
													<input type="text" name="pict" class="form-control" placeholder="Profil Fotoğrafı URL'si" value="<?php echo $datadb_staff['pict']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">İletişim</label>
												<div class="col-md-10">
													<textarea name="fbid" class="form-control" placeholder="İletişim"><?php echo $datadb_staff['contact']; ?></textarea>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Seviye</label>
												<div class="col-md-10">
													<select class="form-control" name="level">
														<option value="<?php echo $datadb_staff['level']; ?>"><?php echo $datadb_staff['level']; ?> (Seçili)</option>
														<option value="Bayi">Bayi</option>
														<option value="Yönetici">Yönetici</option>
														<option value="Geliştiriciler">Geliştiriciler</option>
													</select>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/personel.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
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
			header("Location: ".$cfg_baseurl."admin/personel.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>
