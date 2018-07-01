<?php
ob_start();
session_start();

$timezone = date_default_timezone_set("Asia/Tokyo");

$servername = getenv('IP');
$username = getenv('C9_USER');
$password = "";
$database = "social";
$dbport = 3306;

// $con = mysqli_connect("localhost", "root", "", "social"); //DB接続
$con = mysqli_connect($servername, $username, $password, $database, $dbport); //DB接続

if(mysqli_connect_errno()){
    // 接続エラー表示
    // echo "Failed to connect: " . mysqli_connect_errno();
    echo "Failed to connect: " . mysqli_connect_error();
}else{
    // 文字コードセット
    $con->set_charset("utf8");
}
?>