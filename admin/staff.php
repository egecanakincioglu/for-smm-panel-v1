<?php
session_start();
require("../mainconfig.php");
$msg_type = "nothing";

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
        if (isset($_POST['delete'])) {
            $post_id = $_POST['id'];
            $post_name = $_POST['name'];
            $checkdb_staff = mysqli_query($db, "SELECT * FROM staff WHERE id = '$post_id'");
            if (mysqli_num_rows($checkdb_staff) == 0) {
                $msg_type = "error";
                $msg_content = "<b>Hata:</b> Personel bulunamadı.";
            } else {
                $delete_staff = mysqli_query($db, "DELETE FROM staff WHERE id = '$post_id'");
                if ($delete_staff == TRUE) {
                    $msg_type = "success";
                    $msg_content = "<b>Başarılı:</b> Personel <b>$post_name</b> silindi.";
                }
            }
        }

        include("../lib/header.php");
?>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title"><i class="fa fa-users"></i> Personel Listesi</h3>
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
                        <div class="col-md-6">
                            <a href="<?php echo $cfg_baseurl; ?>admin/staff/add.php" class="btn btn-info m-b-20"><i class="fa fa-plus"></i> Ekle</a>
                        </div>
                        <div class="col-md-6">
                        </div>
                        <div class="clearfix"></div>
                        <br />
                        <div class="col-md-12 table-responsive">
                            <table class="table table-striped table-bordered table-hover m-0">
                                <thead>
                                    <tr>
                                        <th>Ad</th>
                                        <th>İletişim</th>
                                        <th>Seviye</th>
                                        <th>İşlem</th>
                                    </tr>
                                </thead>
                                <tbody>
                                <?php
                                // sayfalama yapılandırmasını başlat
                                $query_list = "SELECT * FROM staff ORDER BY level ASC"; // düzenle
                                $records_per_page = 10; // düzenle

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
                                        <td><?php echo $data_show['name']; ?></td>
                                        <td><?php echo nl2br($data_show['contact']); ?></td>
                                        <td><?php echo $data_show['level']; ?></td>
                                        <td align="center">
                                        <a href="<?php echo $cfg_baseurl; ?>admin/staff/edit.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-warning"><i class="fa fa-edit"></i></a>
                                        <a href="<?php echo $cfg_baseurl; ?>admin/staff/delete.php?id=<?php echo $data_show['id']; ?>" class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></a>
                                        </td>
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
