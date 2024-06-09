<?php
session_start();
require("mainconfig.php");

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

include("lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-random"></i> API Dokümantasyonu</h3>
									</div>
									<div class="panel-body">
										<table class="table table-bordered">
											<tbody>
												<tr>
													<td>HTTP Methodu</td>
													<td>POST</td>
												</tr>
												<tr>
													<td>API URL</td>
													<td><?php echo $cfg_baseurl; ?>api.php</td>
												</tr>
												<tr>
													<td>Talep formatı </td>
													<td>JSON</td>
												</tr>
												<tr>
													<td>Örnek PHP Kodu</td>
													<td><a href="<?php echo $cfg_baseurl; ?>api_example.php" target="blank">Örnek</a></td>
												</tr>
											</tbody>
										</table>
									</div>
									<div class="panel-footer">
										<h3 class="panel-title">Method <font color="red">add</font> (Place order)</h3>
									</div>
									<div class="panel-body">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Parametreler</th>
													<th>Açıklamalar</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>anahtar</td>
													<td>API Anahtarınız</td>
												</tr>
												<tr>
													<td>eylem</td>
													<td>ekle</td>
												</tr>
												<tr>
													<td>servisler</td>
													<td>Servis ID <a href="<?php echo $cfg_baseurl; ?>price_list.php">Fiyat listesine bakın</a></td>
												</tr>
												<tr>
													<td>link</td>
													<td>sayfaya bağlantı</td>
												</tr>
												<tr>
													<td>miktar</td>
													<td>gereken miktar</td>
												</tr>
											</tbody>
										</table>
<b>Örnek Talep</b><br />
<pre>
Sipariş başarılı ise

{
  "order_id":"12345"
}

Sipariş başarısız ise

{
  "error":"Hatalı istek"
}
</pre>
									</div>
									<div class="panel-footer">
										<h3 class="panel-title">Metod <font color="red">durum</font> (Sipariş durumunu kontrol et)</h3>
									</div>
									<div class="panel-body">
										<table class="table table-bordered">
											<thead>
												<tr>
													<th>Parametreler</th>
													<th>Açıklamalar</th>
												</tr>
											</thead>
											<tbody>
												<tr>
													<td>anahtar</td>
													<td>API Anahtarınızy</td>
												</tr>
												<tr>
													<td>eylem</td>
													<td>durum</td>
												</tr>
												<tr>
													<td>siparis_id</td>
													<td>Sipariş ID</td>
												</tr>
											</tbody>
										</table>
<b>Örnek Talep</b><br />
<pre>
DURUM BAŞARISININ KONTROL EDİLMESİ İSE

{
  "charge":"10000",
  "start_count":"123",
  "status":"Başrılı",
  "remains":"0"
}

DURUM KONTROLÜ BAŞARISIZ OLURSA

{
  "error":"Hatalı istek"
}
</pre>
									</div>
									<div class="panel-footer text-right">
										<span>Kodlara Göre API Sistemi</span>
									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
<?php
include("lib/footer.php");
?>
