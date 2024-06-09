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
	} else if ($data_user['status'] == "Suspended") {
		header("Yer: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Geliştiriciler") {
		header("Yer: ".$cfg_baseurl);
	} else {
		if (isset($_GET['id'])) {
			$post_id = $_GET['id'];
			$checkdb_news = mysqli_query($db, "SELECT * FROM news WHERE id = '$post_id'");
			$datadb_news = mysqli_fetch_assoc($checkdb_news);
			if (mysqli_num_rows($checkdb_news) == 0) {
				header("Yer: ".$cfg_baseurl."admin/news.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_content = $_POST['content'];
					if (empty($post_content)) {
						$msg_type = "error";
						$msg_content = "<b>Hata:</b> Lütfen tüm girişleri doldurun.";
					} else {
						$update_news = mysqli_query($db, "UPDATE news SET content = '$post_content' WHERE id = '$post_id'");
						if ($update_news == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Başarılı:</b> Haber başarıyla güncellendi.";
						} else {
							$msg_type = "error";
							$msg_content = "<b>Hata:</b> Sistem hatası.";
						}
					}
				}
				$checkdb_news = mysqli_query($db, "SELECT * FROM news WHERE id = '$post_id'");
				$datadb_news = mysqli_fetch_assoc($checkdb_news);
				include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-edit"></i> Haberi Düzenle</h3>
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
												<label class="col-md-2 control-label">İçerik</label>
												<div class="col-md-10">
													<textarea name="content" class="form-control" placeholder="İçerik"><?php echo $datadb_news['content']; ?></textarea>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/news.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<div class="sağa çek">
												<button type="sıfırla" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Sıfırla</button>
												<button type="sun" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="edit">Düzenle</button>
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
			header("Yer: ".$cfg_baseurl."admin/news.php");
		}
	}
} else {
	header("Yer: ".$cfg_baseurl);
}
?>
