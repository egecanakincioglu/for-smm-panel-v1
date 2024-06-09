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

	if (isset($_POST['order'])) {
		$post_service = $_POST['service'];
		$post_quantity = $_POST['quantity'];
		$post_link = trim($_POST['link']);
		$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_service' AND status = 'Active'");
		$data_service = mysqli_fetch_assoc($check_service);

        $check_orders = mysqli_query($db, "SELECT * FROM orders WHERE link = '$post_link' AND status IN ('Pending','Processing')");
        $data_orders = mysqli_fetch_assoc($check_orders);
		$rate = $data_service['price'] / 1000;
		$price = $rate*$post_quantity;
		$oid = random_number(3).random_number(4);
		$service = $data_service['service'];
		$provider = $data_service['provider'];
		$pid = $data_service['pid'];

		$check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$provider'");
		$data_provider = mysqli_fetch_assoc($check_provider);
		

		if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Lütfen girişi doldurun.";
		} else if (mysqli_num_rows($check_orders) == 1) {
		    $msg_type = "error";
		    $msg_content = "<b>Başarısız:</b> Aynı kullanıcı adı için siparişler var ve durumu Beklemede/İşleniyor.";
		} else if (mysqli_num_rows($check_service) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Servis bulunamadı.";
		} else if (mysqli_num_rows($check_provider) == 0) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Sunucu bakımı.";
		} else if ($post_quantity < $data_service['min']) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Asgari tutar: ".$data_service['min'].".";
		} else if ($post_quantity > $data_service['max']) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Maksimum miktar: ".$data_service['max'].".";
		} else if ($data_user['balance'] < $price) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Bakiyeniz bu satın alma işlemini gerçekleştirmek için yeterli değil.";
		} else {

			// api data
			$api_link = $data_provider['link'];
			$api_key = $data_provider['api_key'];
			// end api data

			if ($provider == "MANUAL") {
				$api_postdata = "";
			} else if ($provider == "JOZPEDIA") {
                $postdata = "api_key=$api_key&action=order&service=$pid&data=$post_link&quantity=$post_quantity";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $api_link);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                $chresult = curl_exec($ch);
                echo $chresult;
                curl_close($ch);
                    $json_result = json_decode($chresult);
                    
			} else {
				die("System Error!");
			}

			if ($provider != "MANUAL" AND $json_result->status == false) {
				$msg_type = "error";
				$msg_content = "Bu hizmette bir hata oluştu.";
			} else {
				if ($provider == "JOZPEDIA") {
					$poid = $json_result->data->id;
				} else if ($provider == "MANUAL") {
					$poid = $oid;
				 }		
				$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$price WHERE username = '$sess_username'");
				if ($update_user == TRUE) {
					$insert_order = mysqli_query($db, "INSERT INTO orders (oid, poid, user, service, link, quantity, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$sess_username', '$service', '$post_link', '$post_quantity', '$price', 'Pending', '$date', '$provider', 'WEB')");
					if ($insert_order == TRUE) {
						$msg_type = "success";
						$msg_content = "<b>Sipariş alındı.</b><br /><b>Hizmet:</b> $service<br /><b>Bağlantı:</b> $post_link<br /><b>Miktar:</b> ".number_format($post_quantity,0,',','.')."<br /><b>Maliyet:</b> Rp ".number_format($price,0,',','.');
					} else {
						$msg_type = "error";
						$msg_content = "<b>Başarısız:</b> Sistem hatası (2).";
					}
				} else {
					$msg_type = "error";
					$msg_content = "<b>Başarısız:</b> Sistem hatası (1).";
				}
			}
		}
	}
	
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
?>
				<div class="row">
                            <!-- INBOX -->
                            <div class="col-lg-7">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-shopping-cart"></i> Yeni Siparişler Sosyal Medya</h4>
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
												<label class="col-md-2 control-label">Kategori</label>
												<div class="col-md-10">
													<select class="form-control" id="category">
														<option value="0">Birini seç...</option>
														<?php
														$check_cat = mysqli_query($db, "SELECT * FROM service_cat ORDER BY name ASC");
														while ($data_cat = mysqli_fetch_assoc($check_cat)) {
														?>
														<option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
														<?php
														}
														?>
													</select>												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Hizmet</label>
												<div class="col-md-10">
													<select class="form-control" name="service" id="service">
														<option value="0">Kategori seç...</option>
													</select>
												</div>
											</div>
											<div id="note">
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Bağlantı hedefi</label>
												<div class="col-md-10">
													<input type="text" name="link" class="form-control" placeholder="Bağlantı">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Miktar</label>
												<div class="col-md-10">
													<input type="number" name="quantity" class="form-control" placeholder="Miktar" onkeyup="get_total(this.value).value;">
												</div>
											</div>
											
											<input type="hidden" id="rate" value="0">
											<div class="form-group">
												<label class="col-md-2 control-label">Total Harga</label>
												<div class="col-md-10">
													<input type="number" class="form-control" id="total" readonly>
												</div>
											</div>
                                            <hr>
											<button type="submit" class="pull-right btn btn-success" name="order"><i class="fa fa-shopping-cart"></i> Sipariş Vermek</button>
										</form>
									</div>
								</div>
							</div>
                            <!-- INBOX -->
                            <div class="col-lg-5">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h4 class="panel-title"><i class="fa fa-info-circle"></i> Sipariş Vermek</h4>
                                    </div>
                                    <div class="panel-body">
					Sipariş formunu doldurma prosedürü:
					<ul>
						<li>Kategorilerden birini seçin <b>Kategori</b>, daha sonra mevcut hizmetlerin bir listesi görüntülenecektir <b>Hizmet</b>, Lütfen hizmetlerden birini seçin.</li>
						<li>Verileri kullanıcı adı veya bağlantı biçiminde girin <b>Veri</b> hizmeti seçtikten sonra görüntülenen komut istemine göre.</li>
						<li>İstediğiniz tutarı girin <b>Miktar</b>, daha sonra bakiyeyle ödenecek toplam fiyat görüntülenecektir <b>Toplam fiyat</b>.</li>
						<li>Tüm girişler doğru şekilde doldurulmuşsa, tıklayın <b>Göndermek</b>. Siparişler, gönderimden sonra görüntülenen sonuçların başarılı olması durumunda işleme alınacaktır.</li>
						<li>Sipariş verirseniz <i>sıkışmak</i>/durumu beklemede durumundan değişmez, destek bildirimi yoluyla Admin ile iletişime geçebilirsiniz.</li>
					</ul>
					Girişi doldurma prosedürü <b>Veri</b> uygun:
					<ul>
						<li>Verileri istendiği gibi kullanıcı adı veya bağlantı biçiminde girin.</li>
						<li>Hedef hesabın durumunun olmadığından emin olun <i>özel</i>.</li>
						<li>Kullanıcı tarafından verilerin doldurulmasında hata olması durumunda geri ödeme yapılmaz.</li>
					</ul>

									</div>
								</div>
							</div>
						</div>
						<!-- end row -->
						<script type="text/javascript" src="https://code.jquery.com/jquery-1.10.2.js"></script>
	<script type="text/javascript">
$(document).ready(function() {
	$("#category").change(function() {
		var category = $("#category").val();
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_service.php',
			data: 'category=' + category,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#service").html(msg);
			}
		});
	});
	$("#service").change(function() {
		var service = $("#service").val();
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_note.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#note").html(msg);
			}
		});
		$.ajax({
			url: '<?php echo $cfg_baseurl; ?>inc/order_rate.php',
			data: 'service=' + service,
			type: 'POST',
			dataType: 'html',
			success: function(msg) {
				$("#rate").val(msg);
			}
		});
	});
});

function get_total(quantity) {
	var rate = $("#rate").val();
	var result = eval(quantity) * rate;
	$('#total').val(result);
}
	</script>
<?php
	include("../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>