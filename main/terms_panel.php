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
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-info"></i> Şartlar ve Koşullar</h3>
									</div>
									<div class="panel-body">
										<p><?php echo $cfg_webname; ?> tarafından sağlanan hizmetler aşağıdaki şartları kabul etmeniz koşuluyla sunulmaktadır.</p>
										<p><b>1. Genel</b>
										<br /><?php echo $cfg_webname; ?>'ye kaydolarak ve hizmetlerimizi kullanarak, tüm hizmet şartlarımızı otomatik olarak kabul etmiş olursunuz. Bu hizmet şartlarını önceden bildirimde bulunmaksızın değiştirme hakkını saklı tutarız. Sipariş vermeden önce tüm hizmet şartlarımızı okumanız beklenir.
										<br />Feragat: <?php echo $cfg_webname; ?>, işinizde yaşadığınız herhangi bir kayıptan sorumlu tutulamaz.
										<br />Sorumluluk: <?php echo $cfg_webname; ?>, Instagram, Twitter, Facebook, YouTube ve diğer platformlar tarafından hesabınızın askıya alınmasından veya gönderilerinizin silinmesinden sorumlu değildir.
										<br /><b>2. Hizmetler</b>
										<br /><?php echo $cfg_webname; ?> yalnızca sosyal medya promosyonu ve hesabınızın görünümünü artırmak amacıyla kullanılmalıdır.
										<br /><?php echo $cfg_webname; ?>, yeni takipçilerinizin sizinle etkileşime gireceğini garanti etmez, sadece satın aldığınız takipçileri almanızı garanti eder.
										<br /><?php echo $cfg_webname; ?>, siparişler sistemimize girdikten sonra iptal veya iade talebini kabul etmez. Sipariş tamamlanamazsa uygun bir iade sunarız.</p>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
include("../lib/footer.php");
?>
