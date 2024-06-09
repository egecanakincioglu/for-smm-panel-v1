<?php
session_start();
require("../../mainconfig.php");
$msg_türü = "hiçbirşey";

if (isset($_SESSION['kullanıcı'])) {
	$sess_kullanıcı_adı = $_SESSION['kullanıcı']['kullanıcı_adı'];
	$kullanıcı_kontrolü = mysqli_query($db, "SELECT * FROM kullanıcılar WHERE kullanıcı_adı = '$sess_kullanıcı_adı'");
	$veri_kullanıcı = mysqli_fetch_assoc($kullanıcı_kontrolü);
	if (mysqli_num_rows($kullanıcı_kontrolü) == 0) {
		header("Yer: ".$cfg_baseurl."oturum_kapat.php");
	} else if ($veri_kullanıcı['durum'] == "Askıya Alınmış") {
		header("Yer: ".$cfg_baseurl."oturum_kapat.php");
	} else if ($veri_kullanıcı['seviye'] != "Geliştiriciler") {
		header("Yer: ".$cfg_baseurl);
	} else {
		if (isset($_POST['ekle'])) {
			$post_sid = $_POST['sid'];
			$post_kat = $_POST['kategori'];
			$post_hizmet = $_POST['hizmet'];
			$post_not = $_POST['not'];
			$post_min = $_POST['min'];
			$post_maks = $_POST['maks'];
			$post_fiyat = $_POST['fiyat'];
			$post_pid = $_POST['pid'];
			$post_sağlayıcı = $_POST['sağlayıcı'];

			$hizmet_kontrolü = mysqli_query($db, "SELECT * FROM hizmetler WHERE sid = '$post_sid'");
			$veri_hizmet = mysqli_fetch_assoc($hizmet_kontrolü);
			if (empty($post_sid) || empty($post_hizmet) || empty($post_not) || empty($post_min) || empty($post_maks) || empty($post_fiyat) || empty($post_pid) || empty($post_sağlayıcı)) {
				$msg_türü = "hata";
				$msg_içeriği = "<b>Başarısız:</b> Lütfen tüm girdileri doldurun.";
			} else if (mysqli_num_rows($hizmet_kontrolü) > 0) {
				$msg_türü = "hata";
				$msg_içeriği = "<b>Başarısız:</b> Hizmet ID $post_sid zaten veritabanında kayıtlı.";
			} else {
				$hizmet_ekle = mysqli_query($db, "INSERT INTO hizmetler (sid, kategori, hizmet, not, min, maks, fiyat, durum, pid, sağlayıcı) VALUES ('$post_sid', '$post_kat', '$post_hizmet', '$post_not', '$post_min', '$post_maks', '$post_fiyat', 'Aktif', '$post_pid', '$post_sağlayıcı')");
				if ($hizmet_ekle == TRUE) {
					$msg_türü = "başarı";
					$msg_içeriği = "<b>Başarılı:</b> Hizmet başarıyla eklendi.<br /><b>Hizmet ID:</b> $post_sid<br /><b>Hizmet Adı:</b> $post_hizmet<br /><b>Kategori:</b> $post_kat<br /><b>Not:</b> $post_not<br /><b>Min:</b> ".number_format($post_min,0,',','.')."<br /><b>Maks:</b> ".number_format($post_maks,0,',','.')."<br /><b>Fiyat/1000:</b> ₺ ".number_format($post_fiyat,0,',','.')."<br /><b>Sağlayıcı ID:</b> $post_pid<br /><b>Sağlayıcı Kodu:</b> $post_sağlayıcı";
				} else {
					$msg_türü = "hata";
					$msg_içeriği = "<b>Başarısız:</b> Sistem hatası.";
				}
			}
		}

	include("../../lib/başlık.php");
?>
						<div class="satır">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-plus"></i> Hizmet Ekle</h3>
									</div>
									<div class="panel-body">
										<?php 
										if ($msg_türü == "başarı") {
										?>
										<div class="bildirim bildirim-başarılı">
											<a href="#" class="kapat" data-dismiss="bildirim" aria-label="kapat">×</a>
											<i class="fa fa-check-circle"></i>
											<?php echo $msg_içeriği; ?>
										</div>
										<?php
										} else if ($msg_türü == "hata") {
										?>
										<div class="bildirim bildirim-hata">
											<a href="#" class="kapat" data-dismiss="bildirim" aria-label="kapat">×</a>
											<i class="fa fa-times-circle"></i>
											<?php echo $msg_içeriği; ?>
										</div>
										<?php
										}
										?>
										<form class="form-horizontal" role="form" method="POST">
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Kategori</label>
												<div class="col-md-10">
													<select class="form-control" name="kategori">
														<?php
														$kategori_kontrolü = mysqli_query($db, "SELECT * FROM hizmet_kategorileri ORDER BY ad ASC");
														while ($kategori_veri = mysqli_fetch_assoc($kategori_kontrolü)) {
														?>
														<option value="<?php echo $kategori_veri['kod']; ?>"><?php echo $kategori_veri['ad']; ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Hizmet ID</label>
												<div class="col-md-10">
													<input type="number" name="sid" class="form-control" placeholder="Hizmet ID">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Hizmet Adı</label>
												<div class="col-md-10">
													<input type="text" name="hizmet" class="form-control" placeholder="Hizmet Adı">
												</div>
												</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Not</label>
												<div class="col-md-10">
													<input type="text" name="not" class="form-control" placeholder="Örn: Kullanıcı adı, Bağlantı ekle">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Min Sipariş</label>
												<div class="col-md-10">
													<input type="number" name="min" class="form-control" placeholder="Min Sipariş">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Maks Sipariş</label>
												<div class="col-md-10">
													<input type="number" name="maks" class="form-control" placeholder="Maks Sipariş">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Fiyat/1000</label>
												<div class="col-md-10">
													<input type="number" name="fiyat" class="form-control" placeholder="Örn: 30000">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Sağlayıcı ID</label>
												<div class="col-md-10">
													<input type="number" name="pid" class="form-control" placeholder="Sağlayıcı ID">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 kontrol-etiketi">Sağlayıcı Kodu</label>
												<div class="col-md-10">
													<select class="form-control" name="sağlayıcı">
														<?php
														$sağlayıcı_kontrolü = mysqli_query($db, "SELECT * FROM sağlayıcı");
														while ($veri_sağlayıcı = mysqli_fetch_assoc($sağlayıcı_kontrolü)) {
														?>
														<option value="<?php echo $veri_sağlayıcı['kod']; ?>"><?php echo $veri_sağlayıcı['kod']; ?></option>
														<?php
														}
														?>
													</select>
												</div>
											</div>
											<a href="<?php echo $cfg_baseurl; ?>admin/hizmetler.php" class="btn btn-info btn-bordered dalgalar-etkisi w-md dalgalar-ışık">Listeye Geri Dön</a>
											<div class="sağa-yasla">
												<button type="reset" class="btn btn-danger btn-bordered dalgalar-etkisi w-md dalgalar-ışık">Sıfırla</button>
												<button type="submit" class="btn btn-success btn-bordered dalgalar-etkisi w-md dalgalar-ışık" name="ekle">Ekle</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
						<!-- satır sonu -->
<?php
	include("../../lib/altbilgi.php");
	}
} else {
	header("Yer: ".$cfg_baseurl);
}
?>
