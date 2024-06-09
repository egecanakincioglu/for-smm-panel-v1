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
}

include("../lib/header.php");
?>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="panel panel-default">
                                    <div class="panel-heading">
                                        <h3 class="panel-title"><i class="fa fa-tag"></i> Sosyal Medya Fiyat Listesi</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="table-responsive">
                                            <table class="table table-striped table-bordered table-hover m-0">
                                                <thead>
                                                    <tr>
                                                        <th>ID</th>
                                                        <th>Hizmet</th>
                                                        <th>Fiyat/1000</th>
                                                        <th>Min</th>
                                                        <th>Max</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                <?php
                                                $check_service = mysqli_query($db, "SELECT * FROM services WHERE status = 'Active'");
                                                while ($data_service = mysqli_fetch_assoc($check_service)) {
                                                ?>
                                                    <tr>
                                                        <th scope="row"><?php echo $data_service['sid']; ?></th>
                                                        <td><?php echo $data_service['service']; ?></td>
                                                        <td>Rp <?php echo number_format($data_service['price'],0,',','.'); ?></td>
                                                        <td><?php echo number_format($data_service['min'],0,',','.'); ?></td>
                                                        <td><?php echo number_format($data_service['max'],0,',','.'); ?></td>
                                                    </tr>
                                                <?php
                                                }
                                                ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
<?php
include("../lib/footer.php");
?>
