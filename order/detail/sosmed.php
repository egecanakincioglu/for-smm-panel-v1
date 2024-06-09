<?php
session_start();
require("../../mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."user/logout.php");
	} else {
		if (isset($_GET['oid'])) {
			$post_oid = $_GET['oid'];
			$checkdb_order = mysqli_query($db, "SELECT * FROM orders WHERE oid = '$post_oid'");
			$datadb_order = mysqli_fetch_assoc($checkdb_order);
			if (mysqli_num_rows($checkdb_order) == 0) {
				header("Location: ".$cfg_baseurl."order/history/sosmed");
			} else {
				include("../../lib/header.php");
?>
										
			<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
									</div>
									<div class="panel-body">
                                <h4 class="card-title text-primary"><i class="fa fa-history"></i> Sosyal Medya Sipariş Detayı <?php echo $datadb_order['oid']; ?></h4>
                	<div class="box-body table-responsive no-padding">
				        <table class="table table-striped">
                                                <tr>
													<td><b>Sipariş ID</b></td>
													<td><code><?php echo $datadb_order['oid']; ?></code></td>
												</tr>
												<tr>
													<td><b>Alıcı</b></td>
													<td><?php echo $datadb_order['user']; ?></td>
												</tr>
												<tr>
													<td><b>Hizmet</b></td>
													<td><?php echo $datadb_order['service']; ?></td>
												</tr>
												<tr>
													<td><b>Veri</b></td>
													<td><?php echo $datadb_order['link']; ?></td>
												</tr>
												<tr>
													<td><b>Satın Alınan Miktar</b></td>
													<td><?php echo number_format($datadb_order['quantity'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Başlangıç Miktarı</b></td>
													<td><?php echo number_format($datadb_order['start_count'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Kalan</b></td>
													<td><?php echo number_format($datadb_order['remains'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Fiyat</b></td>
													<td>Rp <?php echo number_format($datadb_order['price'],0,',','.'); ?></td>
												</tr>
												<tr>
													<td><b>Durum</b></td>
													<td><?php echo $datadb_order['status']; ?></td>
												</tr>
												<tr>
													<td><b>İade</b></td>
													<td><label class="label label-<?php if($datadb_order['refund'] == 0) { echo "danger"; } else { echo "success"; } ?>"><?php if($datadb_order['refund'] == 0) { ?>Hayır<?php } else { ?> Evet<?php } ?></label></td>
												</tr>
												<tr>
													<td><b>Tarih</b></td>
													<td><?php echo $datadb_order['date']; ?></td>
						 						</tr>
						                    </table>
					                    </div>
                                    </div>
                                <div class="box-footer">
                        </div>
                    </div>
                </div>
            </div>
<?php
				include("../../lib/footer.php");
			}
		} else {
			header("Location: ".$cfg_baseurl."order/history/sosmed.php");
		}
	}
} else {
	header("Location: ".$cfg_baseurl);
}
?>
