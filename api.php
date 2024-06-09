<?php
require("mainconfig.php");
header("Content-Type: application/json");

if (isset($_POST['key']) AND isset($_POST['action'])) {
	$post_key = mysqli_real_escape_string($db, trim($_POST['key']));
	$post_action = $_POST['action'];
	if (empty($post_key) || empty($post_action)) {
		$array = array("error" => "Hatalı istek");
	} else {
		$check_user = mysqli_query($db, "SELECT * FROM users WHERE api_key = '$post_key'");
		$data_user = mysqli_fetch_assoc($check_user);
		if (mysqli_num_rows($check_user) == 1) {
			$username = $data_user['username'];
			if ($post_action == "add") {
				if (isset($_POST['service']) AND isset($_POST['link']) AND isset($_POST['quantity'])) {
					$post_service = $_POST['service'];
					$post_link = $_POST['link'];
					$post_quantity = $_POST['quantity'];
					if (empty($post_service) || empty($post_link) || empty($post_quantity)) {
						$array = array("error" => "Hatalı istek");
					} else {
						$check_service = mysqli_query($db, "SELECT * FROM services WHERE sid = '$post_service' AND status = 'Aktif'");
						$data_service = mysqli_fetch_assoc($check_service);
						if (mysqli_num_rows($check_service) == 0) {
							$array = array("error" => "Servis bulunamadı.");
						} else {
							$oid = random_number(7);
							$rate = $data_service['price'] / 1000;
							$price = $rate*$post_quantity;
							$service = $data_service['service'];
							$provider = $data_service['provider'];
							$pid = $data_service['pid'];
							if ($post_quantity < $data_service['min']) {
								$array = array("error" => "Miktar yanlış");
							} else if ($post_quantity > $data_service['max']) {
								$array = array("error" => "Miktar yanlış");
							} else if ($data_user['balance'] < $price) {
								$array = array("error" => "Düşük bütçe");
							} else {
								$check_provider = mysqli_query($db, "SELECT * FROM provider WHERE code = '$provider'");
								$data_provider = mysqli_fetch_assoc($check_provider);
								$provider_key = $data_provider['api_key'];
								$provider_link = $data_provider['link'];
	
								if ($provider == "MANUAL") {
									$api_postdata = "";
								} else if ($provider !== "MANUAL") {
									$api_postdata = "api_key=$provider_key&action=order&service=$pid&link=$post_link&quantity=$post_quantity";
								}
	
								if ($provider !== "MANUAL") {
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_URL, "$provider_link");
									curl_setopt($ch, CURLOPT_POST, 1);
									curl_setopt($ch, CURLOPT_POSTFIELDS, $api_postdata);
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									$chresult = curl_exec($ch);
									curl_close($ch);
									$json_result = json_decode($chresult, true);
								}
								
								if ($provider !== "MANUAL" AND $json_result['error'] == TRUE) {
									$array = array("error" => "Sunucu Bakımda");
								} else {
									if ($provider !== "MANUAL") {
										$poid = $json_result['order_id'];
									} else if ($provider == "MANUAL") {
										$poid = $oid;
									}
									$update_user = mysqli_query($db, "UPDATE users SET balance = balance-$price WHERE username = '$username'");
									if ($update_user == TRUE) {
										$insert_order = mysqli_query($db, "INSERT INTO orders (oid, poid, user, service, link, quantity, price, status, date, provider, place_from) VALUES ('$oid', '$poid', '$username', '$service', '$post_link', '$post_quantity', '$price', 'Pending', '$date', '$provider', 'API')");
										if ($insert_order == TRUE) {
											$array = array("order_id" => "$oid");
										} else {
											$array = array("error" => "Sistem Hatası");
										}
									} else {
										$array = array("error" => "Sistem Hatası");
									}
								}
							}
						}
					}
				} else {
					$array = array("error" => "Hatalı istek");
				}
			} else if ($post_action == "status") {
				if (isset($_POST['order_id'])) {
					$post_oid = $_POST['order_id'];
					$post_oid = $_POST['order_id'];
					$check_order = mysqli_query($db, "SELECT * FROM orders WHERE oid = '$post_oid' AND user = '$username'");
					$data_order = mysqli_fetch_array($check_order);
					if (mysqli_num_rows($check_order) == 0) {
						$array = array("error" => "Sipariş bulunamadı.");
					} else {
						$array = array("charge" => $data_order['price'], "start_count" => $data_order['start_count'], "status" => $data_order['status'], "remains" => $data_order['remains']);
					}
				} else {
					$array = array("error" => "Hatalı istek");
				}
			} else {
				$array = array("error" => "Yanlış aksiyon");
			}
		} else {
			$array = array("error" => "Geçersiz API anahtarı");
		}
	}
} else {
	$array = array("error" => "Hatalı istek");
}

$print = json_encode($array);
print_r($print);
