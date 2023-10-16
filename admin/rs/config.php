<?php

error_reporting(E_ALL);
ini_set('display_errors', true);
ini_set('display_startup_errors', true);

date_default_timezone_set('Australia/Sydney');
	
session_name('risk_safe');
session_start();

define("APP_TITLE", "RiskSafe - Risk Assessment");
define("ROOT_PATH", realpath("./"));
define("DIR_PATH", __DIR__);

//getrisksafe.com
// test@test.com
// 12345678
define("APP_BASE_URL", "http://localhost/risksafe/");
// define("APP_BASE_URL", "http://risksafe.co/");

function printPageURL($url){
	echo  APP_BASE_URL . $url;
}

