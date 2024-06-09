<?php
session_start();
require("mainconfig.php");
$msg_type = "nothing";

if (isset($_SESSION['user'])) {
	$sess_username = $_SESSION['user']['username'];
	$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
	$data_user = mysqli_fetch_assoc($check_user);
	if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
	}

	$check_order = mysqli_query($db, "SELECT SUM(price) AS total FROM orders WHERE user = '$sess_username'");
	$data_order = mysqli_fetch_assoc($check_order);
	
	$count_users = mysqli_num_rows(mysqli_query($db, "SELECT * FROM users"));
	$count_orders = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orders"));
} else {
	if (isset($_POST['login'])) {
		$post_username = mysqli_real_escape_string($db, trim($_POST['username']));
		$post_password = mysqli_real_escape_string($db, trim($_POST['password']));
		if (empty($post_username) || empty($post_password)) {
			$msg_type = "error";
			$msg_content = "<b>Başarısız:</b> Lütfen tüm girişleri doldurun.";
		} else {
			$check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$post_username'");
			if (mysqli_num_rows($check_user) == 0) {
				$msg_type = "error";
				$msg_content = "<b>Başarısız:</b> Yanlış kullanıcı adı ya da parola.";
			} else {
				$data_user = mysqli_fetch_assoc($check_user);
				if ($post_password <> $data_user['password']) {
					$msg_type = "error";
					$msg_content = "<b>Başarısız:</b> Yanlış kullanıcı adı ya da parola.";
				} else if ($data_user['status'] == "Suspended") {
					$msg_type = "error";
					$msg_content = "<b>Başarısız:</b> Hesap devre dışı.";
				} else {
					$_SESSION['user'] = $data_user;
					header("Location: ".$cfg_baseurl."index.php");
				}
			}
		}
	}
}

include("lib/header.php");
if (isset($_SESSION['user'])) {
?>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
   <script> 
swal(
  'Hoş Geldin!',
  'Hoş Geldin <?php echo $cfg_logo_txt; ?> Keyifli Alışverişler :)',
  'info'
)
</script> 
                        <div class="row">
                            <div class="col-sm-6 col-lg-4">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-info"><i class="ion-social-usd"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="text-dark"> Rp : <?php echo number_format($data_user['balance'],0,',','.') ?></span>
                                        Toplam Bakiye:
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                    <span class="sr-only">60% Tamamlandı</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-lg-4">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-purple"><i class="ion-ios7-cart"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="text-dark">Rp : <?php echo number_format($data_order['total'],0,',','.'); ?></span>
                                        Toplam Sosyal Düzenler:
                                    </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-purple" role="progressbar" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100" style="width: 90%">
                                                    <span class="sr-only">90% Tamamlandı</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
							<div class="col-sm-6 col-lg-4">
                                <div class="mini-stat clearfix bx-shadow bg-white">
                                    <span class="mini-stat-icon bg-success"><i class="ion-eye"></i></span>
                                    <div class="mini-stat-info text-right text-dark">
                                        <span class="text-dark"><?php echo $count_users; ?></span>
                                        Toplam Aktif Kullanıcı Sayısı:                                 </div>
                                    <div class="tiles-progress">
                                        <div class="m-t-20">
                                            <div class="progress progress-sm m-0">
                                                <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: 60%">
                                                    <span class="sr-only">60% Tamamlandı</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div> <!-- End row-->

          
							
									
		
						
							<div class="row">
								<div class="col-md-12">
									<div class="panel panel-default">
										<div class="panel-heading">
											<h3 class="panel-title"><i class="fa fa-info-circle"></i> Informasi</h3>
										</div>
										<div class="panel-body">
											<div class="table-responsive">
												<table class="table table-striped table-bordered table-hover m-0">
													<thead>
							
													<tr>
														<th>#</th>
                                                        <th>Haber Yazın</th>
														<th>Tarih</th>
														<th>Haber Başlığı</th>
														<th>Oluşturan By</th>
													</tr>
												</thead>
												<tbody>
													<?php
// start paging config
if (isset($_GET['search'])) {
	$search = $_GET['search'];
	$query_list = "SELECT * FROM news WHERE username LIKE '%$search%' ORDER BY id DESC"; // edit
} else {
	$query_list = "SELECT * FROM news ORDER BY id DESC"; // edit
}
$records_per_page = 5; // edit

$starting_position = 0;
if(isset($_GET["page_no"])) {
	$starting_position = ($_GET["page_no"]-1) * $records_per_page;
}
$new_query = $query_list." LIMIT $starting_position, $records_per_page";
$new_query = mysqli_query($db, $new_query);
// end paging config
												while ($data_news = mysqli_fetch_assoc($new_query)) {
                                                    $check_news = mysqli_query($db, "SELECT * FROM news ORDER BY id DESC LIMIT 5");
                                                    $no = 1;
                                                     if($data_news['section'] == "Informasi") {
                                                        $label = "primary";
                                                    } else if($data_news['section'] == "Güncellemeler") {
                                                        $label = "info";
                                                    } else if($data_news['section'] == "Önemli") {
                                                        $label = "danger";
                                                    } else if($data_news['section'] == "Event") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Sunucu Güncellemeleri") {
                                                        $label = "warning";
                                                    } else if($data_news['section'] == "Instagram Sunucusu") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Kredi Sunucusu") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Youtube Sunucusu") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Facebook Sunucusu") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Bakiye yatırma") {
                                                        $label = "warning";
                                                    } else if($data_news['section'] == "İletişim") {
                                                        $label = "danger";
                                                    } else if($data_news['section'] == "Sosyal Medya Sunucusu") {
                                                        $label = "info";
                                                    } else if($data_news['section'] == "Kredi Sunucusu") {
                                                        $label = "info";
                                                    } else if($data_news['section'] == "Hesap Kaydı") {
                                                        $label = "info";
                                                    } else if($data_news['section'] == "Fiyat Düşüşü") {
                                                        $label = "success";
                                                    } else if($data_news['section'] == "Bakım Kredisi") {
                                                        $label = "danger";
                                                    } else if($data_news['section'] == "Sosyal Medya Bakımı") {
                                                        $label = "danger";
                                                    } else if($data_news['section'] == "Bakım Sunucusu") {
                                                        $label = "danger";
                                                    } else if($data_news['section'] == "Sipariş Kontrolü") {
                                                        $label = "primary";
                                                    }
                                                    ?>
													<tr>
                                                        <th scope="row"><?php echo $no; ?></th>
                                                        <td align="center"><label class="label label-<?php echo $label; ?>"><?php echo $data_news['section']; ?></label></td>
                                                        <td><?php echo $data_news['date']; ?></td>
                                                        <td><?php echo $data_news['content']; ?></td>
                                                        <td><?php echo $data_news['created_by']; ?></td>
                                                    </tr>
                                                    <?php
												}
												?>
												</tbody>
											</table>
											<ul class="pagination">
											<?php
// start paging link
$self = $_SERVER['PHP_SELF'];
$query_list = mysqli_query($db, $query_list);
$total_no_of_records = mysqli_num_rows($query_list);
echo "<li class='disabled'><a href='#'>Total: ".$total_no_of_records."</a></li>";
if($total_no_of_records > 0) {
	$total_no_of_pages = ceil($total_no_of_records/$records_per_page);
	$current_page = 1;
	if(isset($_GET["page_no"])) {
		$current_page = $_GET["page_no"];
	}
	if($current_page != 1) {
		$previous = $current_page-1;
		echo "<li><a href='".$self."?page_no=1'>← First</a></li>";
		echo "<li><a href='".$self."?page_no=".$previous."'><i class='fa fa-angle-left'></i> Previous</a></li>";
	}
	for($i=1; $i<=$total_no_of_pages; $i++) {
		if($i==$current_page) {
			echo "<li class='active'><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		} else {
			echo "<li><a href='".$self."?page_no=".$i."'>".$i."</a></li>";
		}
	}
	if($current_page!=$total_no_of_pages) {
		$next = $current_page+1;
		echo "<li><a href='".$self."?page_no=".$next."'>Next <i class='fa fa-angle-right'></i></a></li>";
		echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Last →</a></li>";
	}
}
// end paging link
											?>
											</ul>
										</div>
									</div>
								</div>
							</div>
						<!-- end row -->
<?php
} else {
?>
						<div class="row">
							<div class="col-md-7">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-user"></i> Giriş</h3>
									</div>
									<div class="panel-body">
										<?php 
										if ($msg_type == "error") {
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
												<label class="col-md-2 control-label">Kullanıcı Adı</label>
												<div class="col-md-10">
													<input type="text" name="username" class="form-control" placeholder="Kullanıcı Adı">
												</div>
											</div>
											<div class="form-group">
												<label class="col-md-2 control-label">Şifre</label>
												<div class="col-md-10">
													<input type="password" name="password" class="form-control" placeholder="Şifre">
												</div>
											</div>
											<div class="pull-right">
                                  <button type="reset" class="btn btn-warning"><i class="fa fa-refresh"></i> Ulangi </button>
                                  <button type="submit" name="login" class="btn btn-success"><i class="fa fa-sign-in"></i> Giriş </button>

		                            </form>
                              </div>
                            </div>
                        </div>
                      </div>
                <div class="col-md-5">
								<div class="panel panel-default">
									<div class="panel-heading">
										<h3 class="panel-title"><i class="fa fa-info-circle"></i> Bizi neden tercih etmelisiniz?</h3>
									</div>
									<div class="panel-body">
                                        <b><?php echo $cfg_webname; ?> </b> Facebook, Instagram, Twitter, YouTube ve diğer ihtiyaçlara yönelik sosyal medya hizmetlerine yönelik hizmet veren bir web sitesidir.<hr>                
                                    <ul>                
                    <li>API Entegrasyonu.</li>
                    <li>Anında ve Otomatik işleme.</li>
                    <li>En ucuz fiyat.</li>
                    <li>Servisi tamamlayın.</li>
                    <li>24 Saat Destek</li>
                    <li>Banka Ödemeleri</li> 
                    </div>
					</div>
                        <br>
                    <div class="col-md-12">
                 
                                    <script type="text/javascript">                                                                                  (function () { var c = document.createElement('link'); c.type = 'text/css'; c.rel = 'stylesheet'; c.href = 'https://images.dmca.com/badges/dmca.css?ID=fe8f2dcd-15df-49a5-a88b-0fed88eefa34'; var h = document.getElementsByTagName("head")[0]; h.appendChild(c); })();</script>
                                    <div id="DMCA-badge">
                                        <div class="dm-1 dm-1-b" style="left: 0;"><a href="https://www.dmca.com/" title="DMCA">CARTEL</a></div>
                                        <div class="dm-2 dm-2-b"><a href="http://www.dmca.com/Protection/Status.aspx?ID=fe8f2dcd-15df-49a5-a88b-0fed88eefa34" title="DMCA">PROTECTED</a></div>
                                    </div>
                                   
								
							</div>
	
						<!-- end row -->
						<!-- end row -->
						
    </div>
                        </div>
                    </div>
                </div>
            </div>
						
<?php
}
include("lib/footer.php");
?>