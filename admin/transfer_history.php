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
    } else if ($data_user['level'] != "Developers") {
        header("Location: ".$cfg_baseurl);
    } else {

        include("../lib/header.php");
        $check_wtransfer = mysqli_query($db, "SELECT SUM(quantity) AS total FROM transfer_balance");
        $data_wtransfer = mysqli_fetch_assoc($check_wtransfer);
?>

        <section class="panel panel-default">
            <div class="row m-l-none m-r-none bg-light lter">
                <div class="col-sm-12 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                        <i class="fa fa-circle fa-stack-2x text-info"></i>
                        <i class="fa fa-usd fa-stack-1x text-white"></i>
                    </span>
                    <a class="clear" href="#">
                        <span class="h3 block m-t-xs">
                            <strong>₺ <?php echo number_format($data_wtransfer['total'],0,',','.'); ?></strong>
                        </span>
                        <small class="text-muted text-uc">Toplam Tüm Bakiye Transferleri</small>
                    </a>
                </div>
        </section>    

        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-money"></i> Bakiye Transfer Geçmişi</h3>
                    </div>
                    <div class="panel-body">
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Tarih</th>
                                        <th>Gönderen</th>
                                        <th>Alıcı</th>
                                        <th>Miktar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // sayfalama yapılandırmasını başlat
                                $query_list = "SELECT * FROM transfer_balance ORDER BY id DESC"; // düzenle
                                $records_per_page = 30; // düzenle

                                $starting_position = 0;
                                if(isset($_GET["page_no"])) {
                                    $starting_position = ($_GET["page_no"]-1) * $records_per_page;
                                }
                                $new_query = $query_list." LIMIT $starting_position, $records_per_page";
                                $new_query = mysqli_query($db, $new_query);
                                // sayfalama yapılandırmasını bitir
                                while ($data_show = mysqli_fetch_assoc($new_query)) {
                                ?>
                                    <tr>
                                        <td><?php echo $data_show['date']; ?></td>
                                        <td><?php echo $data_show['sender']; ?></td>
                                        <td><?php echo $data_show['receiver']; ?></td>
                                        <td>₺ <?php echo number_format($data_show['quantity'],0,',','.'); ?></td>
                                    </tr>
                                <?php
                                }
                                ?>
                                </tbody>
                            </table>
                            <ul class="pagination">
                            <?php
                            // sayfalama bağlantısını başlat
                            $self = $_SERVER['PHP_SELF'];
                            $query_list = mysqli_query($db, $query_list);
                            $total_no_of_records = mysqli_num_rows($query_list);
                            echo "<li class='disabled'><a href='#'>Toplam: ".$total_no_of_records."</a></li>";
                            if($total_no_of_records > 0) {
                                $total_no_of_pages = ceil($total_no_of_records/$records_per_page);
                                $current_page = 1;
                                if(isset($_GET["page_no"])) {
                                    $current_page = $_GET["page_no"];
                                }
                                if($current_page != 1) {
                                    $previous = $current_page-1;
                                    echo "<li><a href='".$self."?page_no=1'>← İlk</a></li>";
                                    echo "<li><a href='".$self."?page_no=".$previous."'><i class='fa fa-angle-left'></i> Önceki</a></li>";
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
                                    echo "<li><a href='".$self."?page_no=".$next."'>Sonraki <i class='fa fa-angle-right'></i></a></li>";
                                    echo "<li><a href='".$self."?page_no=".$total_no_of_pages."'>Son →</a></li>";
                                }
                            }
                            // sayfalama bağlantısını bitir
                            ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- satırın sonu -->
<?php
        include("../lib/footer.php");
    }
} else {
    header("Location: ".$cfg_baseurl);
}
?>
