<?php
require_once("mainconfig.php");
$key = ""; 
$postdata = "api_key=$key&action=services";
 
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$chresult = curl_exec($ch);
curl_close($ch);
$json_result = json_decode($chresult, true);
$indeks=0;
$i = 1;
while($indeks < count($json_result['data'])){
   
$category=$json_result['data'][$indeks]['category'];
$id =$json_result['data'][$indeks]['id'];
$service = $json_result['data'][$indeks]['name'];
$min_order =$json_result['data'][$indeks]['min'];
$max_order = $json_result['data'][$indeks]['max'];
$price = $json_result['data'][$indeks]['price'];
$note = $json_result['data'][$indeks]['note'];
$indeks++;
$i++;
$k = 1000;
$rate = $price;
$rate_asli = $rate + $k; 
 $check_services = mysqli_query($db, "SELECT * FROM services WHERE pid = '$id' AND provider='CARTEL'");
            $data_services = mysqli_fetch_assoc($check_orders);
        if(mysqli_num_rows($check_services) > 0) {
            echo "Hizmet veritabanında zaten mevcut => $service | $id \n <br />";
        } else {
           
$insert=mysqli_query($db, "INSERT INTO services (sid,category,service,note, min, max, price, status, pid, provider) VALUES ('$id','$category','$service','$note','$min_order','$max_order','$rate_asli','Active','$id','CARTEL')");
if($insert == TRUE){
echo"Başaarıyla Eklendi -> Kategori : $category || SID : $id || Service :$service || Min :$min_order || Max : $max_order ||Price : $rate_asli || Note : $note <br />";
}else{
    echo "Veri girişi başarısız.";
   
}
}
}
?>
