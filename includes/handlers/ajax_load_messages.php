<?php
include("../../config/config.php");
include("../classes/User.php");
include("../classes/Message.php");
include("../classes/Notification.php");

$limit = 7; // 読み込むメッセージ数

$message = new Message($con, $_REQUEST['userLoggedIn']);
echo $message->getConvosDropDown($_REQUEST, $limit);
?>