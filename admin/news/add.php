<?php
session_start();
require("../../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Yer: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Askıya Alındı") {
		header("Yer: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Geliştiriciler") {
		header("Yer: ".$cfg_baseurl);
	} else {
		if (isset($_POST['add'])) {
			$post_content = $_POST['content'];
			$post_section = $_POST['section'];

			if (empty($post_content) || empty($post_section)) {
				$msg_type = "error";
				$msg_content = "<b>Hata:</b> Lütfen tüm alanları doldurun.";
			} else {
				$insert_news = mysqli_query($db, "INSERT INTO news (date, section, content, created_by) VALUES ('$date','$post_section', '$post_content', '$sess_username')");
				if ($insert_news == TRUE) {
					$msg_type = "success";
					$msg_content = "<b>Başarılı:</b> Bilgi başarıyla eklendi.<br /><b>Tür:</b> $post_section<br /><b>İçerik:</b> $post_content<br /><b>Tarih:</b> $date";
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
										<h3 class="panel-title"><i class="fa fa-plus"></i> Haber Ekle</h3>
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
												<label class="col-md-2 control-label">Tür</label>
												<div class="col-md-10">
													<select class="form-control" name="section">
														<option value="Etkinlik">Etkinlik</option>
														<option value="Bilgi">Bilgi</option>
														<option value="Güncelleme">Güncelleme</option>
														<option value="Önemli">Önemli</option>
														<option value="Sunucu Güncellemesi">Sunucu Güncellemesi</option>
														<option value="Instagram Sunucu">Instagram Sunucu</option>
														<option value="Pul Sunucu">Pul Sunucu</option>
														<option value="Youtube Sunucu">Youtube Sunucu</option>
														<option value="Facebook Sunucu">Facebook Sunucu</option>
														<option value="Bakiye Yatırma">Bakiye Yatırma</option>
														<option value="Yardım İletişim">Yardım İletişim</option>
														<option value="Sosyal Medya Sunucu">Sosyal Medya Sunucu</option>
														<option value="Pul Sunucu">Pul Sunucu</option>
														<option value="Hesap Kaydı">Hesap Kaydı</option>
														<option value="Fiyat Düşürme">Fiyat Düşürme</option>
														<option value="Pul Bakımı">Pul Bakımı</option>
														<option value="Sosyal Medya Bakımı">Sosyal Medya Bakımı</option>
														<option value="Sunucu Bakımı">Sunucu Bakımı</option>
														<option value="Sipariş Kontrolü">Sipariş Kontrolü</option>
													</select>
												</div>
											</div>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 control-label">İçerik</label>
												<div class="col-md-10">
													<textarea name="content" class="form-control" placeholder="İçerik"></textarea>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/news.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<div class="pull-right">
												<button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Temizle</button>
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
	header("Yer: ".$cfg_baseurl);
}
?>
