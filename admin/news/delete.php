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
				include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-trash"></i> Haberi Sil</h3>
									</div>
									<div class="panel-body">
										<form class="form-horizontal" role="form" method="POST" action="<?php echo $cfg_baseurl; ?>admin/news.php">
											<input type="hidden" name="id" value="<?php echo $datadb_news['id']; ?>">
											<div class="form-group">
												<label class="col-md-2 control-label">İçerik</label>
												<div class="col-md-10">
													<textarea class="form-control" placeholder="İçerik" readonly><?php echo $datadb_news['content']; ?></textarea>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/news.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<button type="submit" class="sağa çek btn btn-success btn-bordered waves-effect w-md waves-light" name="delete">Sil</button>
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
			header("Yer: ".$cfg_baseurl."admin/users.php");
		}
	}
} else {
	header("Yer: ".$cfg_baseurl);
}
?>
