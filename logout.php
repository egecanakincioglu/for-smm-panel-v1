<?php
session_start();
require("mainconfig.php");

session_destroy();
header("Location: ".$cfg_baseurl."index.php");