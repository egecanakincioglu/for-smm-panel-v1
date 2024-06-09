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
	} else if ($data_user['status'] == "Askıya Alınmış") {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['level'] != "Geliştiriciler") {
		header("Location: ".$cfg_baseurl);
	} else {
		if (isset($_GET['sid'])) {
			$post_sid = $_GET['sid'];
			$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
			$datadb_service = mysqli_fetch_assoc($checkdb_service);
			if (mysqli_num_rows($checkdb_service) == 0) {
				header("Location: ".$cfg_baseurl."admin/hizmetler.php");
			} else {
				if (isset($_POST['edit'])) {
					$post_cat = $_POST['kategori'];
					$post_service = $_POST['hizmet'];
					$post_note = $_POST['not'];
					$post_min = $_POST['min'];
					$post_max = $_POST['max'];
					$post_price = $_POST['fiyat'];
					$post_pid = $_POST['pid'];
					$post_provider = $_POST['saglayici'];
					$post_status = $_POST['durum'];
					if (empty($post_service) || empty($post_note) || empty($post_min) || empty($post_max) || empty($post_price) || empty($post_pid) || empty($post_provider)) {
						$msg_type = "error";
						$msg_content = "<b>Hata:</b> Lütfen tüm alanları doldurun.";
					} else if ($post_status != "Aktif" AND $post_status != "Aktif Değil") {
						$msg_type = "error";
						$msg_content = "<b>Hata:</b> Geçersiz giriş.";
					} else {
						$update_service = mysqli_query($db, "UPDATE services SET category = '$post_cat', service = '$post_service', note = '$post_note', min = '$post_min', max = '$post_max', price = '$post_price', status = '$post_status', pid = '$post_pid', provider = '$post_provider' WHERE sid = '$post_sid'");
						if ($update_service == TRUE) {
							$msg_type = "success";
							$msg_content = "<b>Başarılı:</b> Hizmet başarıyla güncellendi.<br /><b>Hizmet ID:</b> $post_sid<br /><b>Hizmet Adı:</b> $post_service<br /><b>Kategori:</b> $post_cat<br /><b>Not:</b> $post_note<br /><b>Min:</b> ".number_format($post_min,0,',','.')."<br /><b>Max:</b> ".number_format($post_max,0,',','.')."<br /><b>Fiyat/1000:</b> Rp ".number_format($post_price,0,',','.')."<br /><b>Sağlayıcı ID:</b> $post_pid<br /><b>Sağlayıcı Kodu:</b> $post_provider<br /><b>Durum:</b> $post_status";
						} else {
							$msg_type = "error";
							$msg_content = "<b>Hata:</b> Sistem hatası.";
						}
					}
				}
				$checkdb_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_sid'");
				$datadb_service = mysqli_fetch_assoc($checkdb_service);
				include("../../lib/header.php");
?>
						<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-edit"></i> Hizmeti Güncelle</h3>
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
												<label class="col-md-2 control-label">Hizmet ID</label>
<div class="col-md-10">
    <input type="text" class="form-control" placeholder="Hizmet ID" value="<?php echo $datadb_service['sid']; ?>" readonly>
</div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Hizmet Adı</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="service" placeholder="Hizmet Adı" value="<?php echo $datadb_service['service']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Kategori</label>
    <div class="col-md-10">
        <select class="form-control" name="category">
            <option value="<?php echo $datadb_service['category']; ?>"><?php echo $datadb_service['category']; ?> (Seçili)</option>
            <?php
            $check_cat = mysqli_query($db, "SELECT * FROM service_cat ORDER BY name ASC");
            while ($data_cat = mysqli_fetch_assoc($check_cat)) {
            ?>
            <option value="<?php echo $data_cat['code']; ?>"><?php echo $data_cat['name']; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Not</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="note" placeholder="Not" value="<?php echo $datadb_service['note']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Min Sipariş</label>
    <div class="col-md-10">
        <input type="number" class="form-control" name="min" placeholder="Min Sipariş" value="<?php echo $datadb_service['min']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Max Sipariş</label>
    <div class="col-md-10">
        <input type="number" class="form-control" name="max" placeholder="Max Sipariş" value="<?php echo $datadb_service['max']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Fiyat/1000</label>
    <div class="col-md-10">
        <input type="number" class="form-control" name="price" placeholder="Fiyat/1000" value="<?php echo $datadb_service['price']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Sağlayıcı ID</label>
    <div class="col-md-10">
        <input type="text" class="form-control" name="pid" placeholder="Sağlayıcı ID" value="<?php echo $datadb_service['pid']; ?>">
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Sağlayıcı Kodu</label>
    <div class="col-md-10">
        <select class="form-control" name="provider">
            <option value="<?php echo $datadb_service['provider']; ?>"><?php echo $datadb_service['provider']; ?> (Seçili)</option>
            <?php
            $check_prov = mysqli_query($db, "SELECT * FROM provider");
            while ($data_prov = mysqli_fetch_assoc($check_prov)) {
            ?>
            <option value="<?php echo $data_prov['code']; ?>"><?php echo $data_prov['code']; ?></option>
            <?php
            }
            ?>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-md-2 control-label">Durum</label>
    <div class="col-md-10">
        <select class="form-control" name="status">
            <option value="<?php echo $datadb_service['status']; ?>"><?php echo $datadb_service['status']; ?> (Seçili)</option>
            <option value="Aktif">Aktif</option>
            <option value="Aktif Değil">Aktif Değil</option>
        </select>
    </div>
</div>
<a href="<?php echo $cfg_baseurl; ?>admin/services.php" class="btn btn-info btn-bordered waves-effect w-md waves-light">Listeye Geri Dön</a>
<div class="pull-right">
    <button type="reset" class="btn btn-danger btn-bordered waves-effect w-md waves-light">Sıfırla</button>
    <button type="submit" class="btn btn-success btn-bordered waves-effect w-md waves-light" name="edit">Değişiklikleri Kaydet</button>
</div>
</form>
</div>
</div>
</div>
</div>
<!-- end row -->
<?php
include("../../lib/footer.php");
}
}
} else {
header("Location: ".$cfg_baseurl);
}
?>
