<?php
date_default_timezone_set('Europe/Istanbul');

$cfg_webname = "ForSMM";
$cfg_baseurl = "http://localhost/";
$cfg_desc = "Türkiye'nin 1 Numaralı SMM Paneli";

$cfg_logo_txt = "ForSMM";
$cfg_registerurl = "#";
$cfg_about = "Facebook, Instagram, Twitter, YouTube ve diğer ihtiyaçlara yönelik sosyal medya ihtiyaçlarına hizmet veren bir web sitesidir.";


$cfg_min_transfer = 1000;
$cfg_member_price = 10000; 
$cfg_member_bonus = 5000; 
$cfg_agen_price = 15000; 
$cfg_agen_bonus = 10000; 
$cfg_reseller_price = 35000; 
$cfg_reseller_bonus = 25000; 
$cfg_admin_price = 50000; 
$cfg_admin_bonus = 50000; 


$db_server = "localhost";
$db_user = "#";
$db_password = "#";
$db_name = "#";

$date = date("Y-m-d");
$time = date("H:i:s");

require("lib/database.php");
require("lib/function.php");