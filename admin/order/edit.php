<?php
session_start();
require("../../mainconfig.php");
$msg_tipi = "hiçbir şey";

if (isset($_SESSION['user'])) {
	$sess_kullaniciadi = $_SESSION['user']['kullaniciadi'];
	$kullanici_kontrol = mysqli_query($db, "SELECT * FROM kullanicilar WHERE kullaniciadi = '$sess_kullaniciadi'");
	$kullanici_veri = mysqli_fetch_assoc($kullanici_kontrol);
	if (mysqli_num_rows($kullanici_kontrol) == 0) {
		header("Yer: ".$cfg_baseurl."logout.php");
	} else if ($kullanici_veri['durum'] == "Askıya alındı") {
		header("Yer: ".$cfg_baseurl."logout.php");
	} else if ($kullanici_veri['seviye'] != "Geliştiriciler") {
		header("Yer: ".$cfg_baseurl);
	} else {
		if (isset($_GET['oid'])) {
			$gonder_oi = $_GET['oid'];
			$siparis_kontrol = mysqli_query($db, "SELECT * FROM siparisler WHERE oi = '$gonder_oi'");
			$siparis_veri = mysqli_fetch_assoc($siparis_kontrol);
			if (mysqli_num_rows($siparis_kontrol) == 0) {
				header("Yer: ".$cfg_baseurl."admin/siparisler.php");
			} else if ($siparis_veri['durum'] == "Başarılı" || $siparis_veri['durum'] == "Hata" || $siparis_veri['durum'] == "Kısmi") {
				header("Yer: ".$cfg_baseurl."admin/siparisler.php");
			} else {
				if (isset($_POST['düzenle'])) {
					$post_durum = $_POST['durum'];
					$post_başlangıç = $_POST['başlangıç_sayısı'];
					$post_kalan = $_POST['kalan'];
					if ($post_durum != "Beklemede" AND $post_durum != "İşleniyor" AND $post_durum != "Hata" AND $post_durum != "Kısmi" AND $post_durum != "Başarılı") {
						$msg_tipi = "hata";
						$msg_icerik = "<b>Başarısız:</b> Giriş geçersiz.";
					} else {
						$siparis_güncelle = mysqli_query($db, "UPDATE siparisler SET başlangıç_sayısı = '$post_başlangıç', kalan = '$post_kalan', durum = '$post_durum' WHERE oi = '$gonder_oi'");
						if ($siparis_güncelle == TRUE) {
							$msg_tipi = "başarılı";
							$msg_icerik = "<b>Başarılı:</b> Sipariş başarıyla güncellendi.<br /><b>Sipariş ID:</b> $gonder_oi<br /><b>Durum:</b> $post_durum<br /><b>Başlangıç Sayısı:</b> ".number_format($post_başlangıç,0,',','.')."<br /><b>Kalan:</b> ".number_format($post_kalan,0,',','.');
						} else {
							$msg_tipi = "hata";
							$msg_icerik = "<b>Başarısız:</b> Sistem hatası.";
						}
					}
				}
				$siparis_kontrol = mysqli_query($db, "SELECT * FROM siparisler WHERE oi = '$gonder_oi'");
				$siparis_veri = mysqli_fetch_assoc($siparis_kontrol);
				include("../../lib/üstbilgi.php");
?>
						<div class="sıra">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-edit"></i> Siparişi Düzenle</h3>
									</div>
									<div class="panel-body">
										<?php 
										if ($msg_tipi == "başarılı") {
										?>
										<div class="uyarı alert-success">
											<a href="#" class="kapat" data-dismiss="alert" aria-label="kapat">×</a>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_icerik; ?>
										</div>
										<?php
										} else if ($msg_tipi == "hata") {
										?>
										<div class="uyarı alert-danger">
											<a href="#" class="kapat" data-dismiss="alert" aria-label="kapat">×</a>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_icerik; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Sipariş ID</label>
												<div class="col-md-10">
													<input type="text" class="form-control" placeholder="Sipariş ID" value="<?php echo $siparis_veri['oi']; ?>" readonly>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Başlangıç Sayısı</label>
												<div class="col-md-10">
													<input type="number" name="başlangıç_sayısı" class="form-control" placeholder="Başlangıç Sayısı" value="<?php echo $siparis_veri['başlangıç_sayısı']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Kalan</label>
												<div class="col-md-10">
													<input type="number" name="kalan" class="form-control" placeholder="Kalan" value="<?php echo $siparis_veri['kalan']; ?>">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Durum</label>
												<div class="col-md-10">
													<select class="form-control" name="durum">
														<option value="<?php echo $siparis_veri['durum']; ?>"><?php echo $siparis_veri['durum']; ?> (Seçildi)</option>
														<option value="Beklemede">Beklemede</option>
														<option value="İşleniyor">İşleniyor</option>
														<option value="Hata">Hata</option>
														<option value="Kısmi">Kısmi</option>
														<option value="Başarılı">Başarılı</option>
													</select>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/siparisler.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
											<div class="sağa çek">
												<button type="sıfırla" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Sıfırla</button>
												<button type="sun" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="düzenle">Düzenle</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- sıra sonu -->
<?php
				include("../../lib/altbilgi.php");
			}
		} else {
			header("Yer: ".$cfg_baseurl."admin/siparisler.php");
		}
	}
} else {
	header("Yer: ".$cfg_baseurl);
}
?>

