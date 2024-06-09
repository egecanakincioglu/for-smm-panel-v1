<?php
session_start();
require("../../mainconfig.php");

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."user/logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."user/logout.php");
	}

	include("../../lib/header.php");
?>

			<div class="row">
							<div class="col-md-12">
								<div class="panel panel-default">
									<div class="panel-heading">
                                <h4 class="panel-title"><i class="fa fa-history"></i> Pulsa / Oyun Sipariş Geçmişi</h4>
                                    <div class="panel-body">
										<div class="alert alert-success">
											<i class="fa fa-globe"></i> : Web sitesi üzerinden sipariş verilmiştir.<br />
											<i class="fa fa-random"></i> : API üzerinden sipariş verilmiştir.<br />
							                <i class="fa fa-check"></i> : İade yapılmıştır.<br />
							                <i class="fa fa-times"></i> : İade yapılmamıştır.<hr>
							                Sipariş detaylarını görmek için <b>Sipariş ID</b>'sine tıklayın.
										</div>
										<div class="table-responsive m-t-40">
                                    <table class="table table-bordered">
                                        <thead>
													<tr>
														<th></th>
														<th>Sipariş ID</th>
														<th>Hizmet</th>
														<th>Veri</th>
														<th>Adet</th>
														<th>Fiyat</th>
														<th>Durum</th>
													</tr>
												</thead>
												<tbody>
												<?php
// sayfalama yapılandırmasını başlat
$query_order = "SELECT * FROM orders WHERE user = '$sess_username' ORDER BY id DESC"; // düzenle
$records_per_page = 30; // düzenle

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_order." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// sayfalama yapılandırmasını bitir
												while ($data_order = mysqli_fetch_assoc($new_query)) {
													if($data_order['status'] == "Pending") {
														$label = "warning";
													} else if($data_order['status'] == "Processing") {
														$label = "info";
													} else if($data_order['status'] == "Error") {
														$label = "danger";
													} else if($data_order['status'] == "Partial") {
														$label = "danger";
													} else if($data_order['status'] == "Success") {
														$label = "success";
													}
												?>
													<tr class="odd gradeX">
														<td align="center"><label class="label label-<?php if($data_order['refund'] == 0) { echo "default"; } else { echo "default"; } ?>"><?php if($data_order['refund'] == 0) { ?><i class="mdi mdi-close"></i><?php } else { ?><i class="mdi mdi-check"></i><?php } ?></label> <?php if($data_order['place_from'] == "API") { ?><label class="label label-default"><i class="mdi mdi-fire"></i></label><?php } else { ?><label class="label label-default"><i class="mdi mdi-firefox"></i></label><?php } ?></td>	
														<td><a href="<?php echo $cfg_baseurl; ?>order/detail/sosmed.php?oid=<?php echo $data_order['oid']; ?>" class="text-bold text-aqua" title="Detay"><i class="fa fa-shopping-cart"></i> <?php echo $data_order['oid']; ?></a></td>
														<td><?php echo $data_order['service']; ?></td>
														<td><?php echo $data_order['link']; ?></td>
														<td><?php echo number_format($data_order['quantity'],0,',','.'); ?></td>
														<td>Rp <?php echo number_format($data_order['price'],0,',','.'); ?></td>
														<td align="center"><label class="label label-<?php echo $label; ?>"><?php echo $data_order['status']; ?></label></td>														

													</tr>
												<?php
												}
												?>
												</tbody>
											</table>
										</div>
										</br>
										<ul class="pagination">
											<?php
// sayfa bağlantılarını başlat
$self = $_SERVER['PHP_SELF'];
$query_order = mysqli_query($db, $query_order);
$total_no_of_records = mysqli_num_rows($query_order);
echo "<li class='disabled page-item'><a class='page-link' href='#'>Toplam: ".$total_no_of_records."</a></li>";
if($total_no_of_records > 0) {
	$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page_no"])) {
		$current_page = $_GET["page_no"];
	}
	if($current_page != 1) {
		$previous = $current_page-1;
		echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=1'>← İlk</a></li>";
		echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$previous."'><i class='fa fa-angle-left'></i> Önceki</a></li>";
	}
	for($i=1; $i<=$total_no_of_pages; $i++) {
		if($i==$current_page) {
			echo "<li class='active page-item'><a class='page-link' href='".$self."?page_no=".$i."'>".$i."</a></li>";
		} else {
			echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$i."'>".$i."</a></li>";
		}
	}
	if($current_page!=$total_no_of_pages) {
		$next = $current_page+1;
		echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$next."'>Sonraki <i class='fa fa-angle-right'></i></a></li>";
		echo "<li class='page-item'><a class='page-link' href='".$self."?page_no=".$total_no_of_pages."'>Son →</a></li>";
	}
}
// sayfa bağlantılarını bitir
											?>
										</ul>
									</div>
								</div>
							</div>
						</div>
						<!-- row'u bitir -->
<?php
	include("../../lib/footer.php");
} else {
	header("Location: ".$cfg_baseurl);
}
?>
