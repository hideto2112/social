<?php
require 'config/config.php';
include("includes/classes/User.php");
include("includes/classes/Post.php");
include("includes/classes/Message.php");
include("includes/classes/Notification.php");

if(isset($_SESSION['username'])){
    $userLoggedIn = $_SESSION['username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$userLoggedIn'");
    $user = mysqli_fetch_array($user_details_query);
}else{
    header("Location: register.php");
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <!-- Javascript -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="assets/js/popper.js"></script>
    <script src="assets/js/bootstrap.js"></script>
    <script src="assets/js/bootbox.min.js"></script>
    <script src="assets/js/social.js"></script>
    <script src="assets/js/jquery.jcrop.js"></script>
	<script src="assets/js/jcrop_bits.js"></script>

    <!-- CSS -->
    <!-- <link rel="stylesheet" type="text/css" href="assets/css/style.css"> -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.0.13/css/all.css" integrity="sha384-DNOHZ68U8hZfKXOrtjWvjxusGo9WQnrNx2sqG0tfsghAvtVlRW3tvkXWZh58N9jp" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="assets/css/bootstrap.css">
    <link rel="stylesheet" type="text/css" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/jquery.Jcrop.css" type="text/css"/>
</head>
<body>
    <div class="top_bar">
        <!-- ロゴ -->
        <div class="logo">
            <a href="index.php">ロゴ</a>
        </div>

        <!-- 検索 -->
        <div class="search">
            <form action="search.php" method="GET" name="search_form">
                <input type="text" onkeyup="getLiveSearchUsers(this.value, '<?php echo $userLoggedIn; ?>')" name="p" placeholder="検索" autocomplete="off" id="search_text_input">
                <div class="button_holder">
                    <img src="assets/images/icons/magnifying_glass.png">
                </div>
            </form>

            <div class="search_results"></div>

            <div class="search_results_footer_empty"></div>
        </div>

        <!-- ナビメニュー -->
        <nav>
            <?php
                // 未読メッセージ
                $messages     = new Message($con, $userLoggedIn);
                $num_messages = $messages->getUnreadNumber();

                // 未読通知
                $notifications     = new Notification($con, $userLoggedIn);
                $num_notifications = $notifications->getUnreadNumber();

                // 未読通知
                $user_obj     = new User($con, $userLoggedIn);
                $num_requests = $user_obj->getNumberOffFriendRequests();
            ?>

            <!-- ユーザー名 -->
            <a href="<?php echo $userLoggedIn; ?>">
                <?php echo $user['first_name']; ?>
            </a>

            <!-- ホーム -->
            <a href="index.php">
                <i class="fa fa-home fa-lg"></i>
            </a>

            <!-- メッセージ -->
            <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'message')">
                <i class="fa fa-envelope fa-lg"></i>
                <?php
                if($num_messages > 0){
                    echo '<span class="notification_badge" id="unreaded_message">' . $num_messages .'</span>';
                }
                ?>
            </a>

            <!-- 通知 -->
            <a href="javascript:void(0);" onclick="getDropdownData('<?php echo $userLoggedIn; ?>', 'notification')">
                <i class="fa fa-bell fa-lg"></i>
                <?php
                if($num_notifications > 0){
                    echo '<span class="notification_badge" id="unreaded_notification">' . $num_notifications .'</span>';
                }
                ?>
            </a>

            <!-- 友達リクエスト -->
            <a href="requests.php">
                <i class="fa fa-users fa-lg"></i>
                <?php
                if($num_requests > 0){
                    echo '<span class="notification_badge" id="unreaded_requests">' . $num_requests .'</span>';
                }
                ?>
            </a>

            <!-- 設定 -->
            <a href="settings.php">
                <i class="fa fa-cog fa-lg"></i>
            </a>

            <!-- サインアウト -->
            <a href="includes/handlers/logout.php">
                <i class="fa fa-sign-out-alt fa-lg"></i>
            </a>

        </nav>

        <div class="dropdown_data_window" style="height: 0px; boder: none;"></div>
        <input type="hidden" id="dropdown_data_type" value="">
    </div>

    <script>
        let userLoggedIn = '<?php echo $userLoggedIn; ?>';

        $(document).ready(function(){
            $('#loading').show();

            // Ajax投稿読み込み（初回）
            $.ajax({
                url:   "includes/handlers/ajax_load_posts.php",
                type:  "POST",
                data:  "page=1&userLoggedIn=" + userLoggedIn,
                cache: false,

                success: function(data){
                    $('#loading').hide();
                    $('.posts_area').html(data);
                }
            })

            $(window).scroll(function(){
                let height = $('.posts_area').height();
                let scroll_top = $(this).scrollTop();
                let page = $('.posts_area').find('.nextPage').val();
                let noMorePosts = $('.posts_area').find('.noMorePosts').val();

                // Chrome バーション61以降などdocument.body.scrollTopで取得不可の場合document.documentElement.scrollTopで対応
                if((document.body.scrollHeight == document.body.scrollTop + window.innerHeight || document.body.scrollHeight == document.documentElement.scrollTop + window.innerHeight) && noMorePosts == 'false'){
                    $('#loading').show();

                    // 
                    let ajaxRex = $.ajax({
                        url:   "includes/handlers/ajax_load_posts.php",
                        type:  "POST",
                        data:  "page=" + page + "&userLoggedIn=" + userLoggedIn,
                        cache: false,

                        success: function(response){
                            $('.posts_area').find('.nextPage').remove();
                            $('.posts_area').find('.noMorePosts').remove();

                            $('#loading').hide();
                            $('.posts_area').append(response);
                        }
                    });
                }
                return false;
            });
        });
    </script>
    
    <div class="wrapper">