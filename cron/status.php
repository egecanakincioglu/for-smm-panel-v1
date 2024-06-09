<?php
require("../mainconfig.php");
 
$check_order = mysqli_query($db, "SELECT * FROM orders WHERE status IN ('','Pending','Processing') AND provider = 'JOZPEDIA'");
 
if (mysqli_num_rows($check_order) == 0) {
  die("Order Pending not found.");
} else {
  while($data_order = mysqli_fetch_assoc($check_order)) {
    $o_oid = $data_order['oid'];
    $o_poid = $data_order['poid'];
    $o_provider = $data_order['provider'];
  if ($o_provider == "MANUAL") {
    echo "Order manual<br />";
  } else {
   
    $check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$o_provider'");
    $data_provider = mysqli_fetch_assoc($check_provider);
   
    $p_apikey = $data_provider['api_key'];
    $p_link = $data_provider['link'];
   
    if ($o_provider != "MANUAL") {
      $api_postdata = "api_key=$p_apikey&action=status&id=$o_poid";
    } else {
      die("System error!");
    }
   
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, '');
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $chresult = curl_exec($ch);
    echo $chresult;
    curl_close($ch);
    $json_result = json_decode($chresult);
 
      if ($o_provider == "C") {
          $u_status = $json_result->data->status;
          $u_start = $json_result->data->start_count;
          $u_remains = $json_result->data->remains;
         if ($u_status == "Pending") {
            $un_status = "Pending";
         } else if ($u_status == "Processing") {
            $un_status = "Processing";
         } else if ($u_status == "Partial") {
            $un_status = "Partial";
         } else if ($u_status == "Error") {
            $un_status = "Error";
         } else if ($u_status == "Success") {
            $un_status = "Success";
         } else {
             $un_status = "Pending";
         }
        }
    $update_order = mysqli_query($db, "UPDATE orders SET status = '$un_status', remains = '$u_remains', start_count = '$u_start' WHERE poid = '$o_poid'");
    if ($update_order == TRUE) {
      echo "ID Web: $o_oid<br />ID Pusat: $o_poid<br /> Status: $un_status<br /> Remains: $u_remains<br /><br />";
    } else {
      echo "Error database.";
    }
  }
  }
}
