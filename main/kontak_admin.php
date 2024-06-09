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
}

include("../lib/header.php");
?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-info">
			<i class="fa fa-info-circle"></i> Bakiye yüklemek için bizimle iletişime geçebilirsiniz.
		</div>
	</div>
	<?php
	$check_staff = mysqli_query($db, "SELECT * FROM staff ORDER BY level ASC");
	while ($data_staff = mysqli_fetch_assoc($check_staff)) {
	?>
	<div class="col-md-4">
		<div class="white-box text-center bg-info" style="padding: 20px 0;">
			<img src="<?php echo $data_staff['pict']; ?>" class="img-thumbnail" style="width: 100px; border-radius: 50px;"><br />
			</br>
			<h3 class="text-white text-uppercase"><i class="ti-user"></i><?php echo $data_staff['name']; ?></h3>
			<p class="text-white text-uppercase"><?php echo $data_staff['level']; ?></p>
			<p class="text-white"><?php echo $data_staff['contact']; ?></p>
		</div>
	</div>
	<?php
	}
	?>
</div>
<!-- end row -->
<?php
include("../lib/footer.php");
?>
