<?php
include("includes/header.php");

$message_obj = new Message($con, $userLoggedIn);

if(isset($_GET['profile_username'])){
    $username           = $_GET['profile_username'];
    $user_details_query = mysqli_query($con, "SELECT * FROM users WHERE username='$username'");
    $user_array         = mysqli_fetch_array($user_details_query);

    $num_friends        = substr_count($user_array['friend_array'], ",") - 1;
}

if(isset($_POST['remove_friend'])){
    $user = new User($con, $userLoggedIn);
    $user->removeFriend($username);
}

if(isset($_POST['add_friend'])){
    $user = new User($con, $userLoggedIn);
    $user->sendRequest($username);
}

if(isset($_POST['respond_request'])){
    header("Location: request.php");
}

if(isset($_POST['post_message'])){
    if(isset($_POST['message_body'])){
        $body = mysqli_real_escape_string($con, $_POST['message_body']);
        $date = date("Y-m-d H:i:s");
        $message_obj->sendMessage($username, $body, $date);
    }

    $link = '#profileTabs a[href="#messages_div"]';
    echo "<script>
            $(function(){
                $('" . $link . "').tab('show');
            });
          </script>";
}

?>
    <style type="text/css">
        .wrapper {
            margin-left: 0px;
            padding-left: 0px;
        }
    </style>

        <div class="profile_left">
            <img src="<?php  echo $user_array['profile_pic']; ?>">
            <div class="profile_info">
                <p><?php echo "投稿数： " . $user_array['num_posts']; ?></p>
                <p><?php echo "いいね： " . $user_array['num_likes']; ?></p>
                <p><?php echo "友達： " . $num_friends . " 人"; ?></p>
            </div>

            <form action="<?php echo $username; ?>" method="POST">
                <?php
                    $profile_user_obj = new User($con, $username);
                    if($profile_user_obj->isClosed()){
                        header("Location: user_closed.php");
                    }

                    $logged_in_user_obj = new User($con, $userLoggedIn);

                    if($userLoggedIn != $username) {

                        if($logged_in_user_obj->isFriend($username)){
                            echo '<input type="submit" name="remove_friend" class="danger" value="友達から削除"><br>';
                        }else if($logged_in_user_obj->didReceiveRequest($username)){
                            echo '<input type="submit" name="respond_request" class="warning" value="リクエストを承認"><br>';
                        }else if($logged_in_user_obj->didSendRequest($username)){
                            echo '<input type="submit" name="" class="default" value="リクエスト済み"><br>';
                        }else{
                            echo '<input type="submit" name="add_friend" class="success" value="友達になる"><br>';
                        }
                    }
                ?>
            </form>
            <input type="submit" class="deep_blue" data-toggle="modal" data-target="#post_form" value="Post Something">

            <?php
                if($userLoggedIn != $username){
                    echo '<div class="profile_info_bottom">';
                    echo    "共通の友達：" . $logged_in_user_obj->getMutualFriends($username) . " 人";
                    echo '</div>';
                }
            ?>

        </div>

        <div class="profile_main_column column">

            <!-- タブ見出し -->
            <ul class="nav nav-tabs" id="profileTabs" role="tablist">
                <!-- Newsfeed -->
                <li class="nav-item">
                    <a class="nav-link active" data-toggle="tab" href="#newsfeed_div" role="tab" aria-controls="newsfeed_div" aria-selected="true">Newsfeed</a>
                </li>

                <!-- Messages -->
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#messages_div" role="tab" aria-controls="messages_div" aria-selected="false">Messages</a>
                </li>
            </ul>

            <!-- タブ内容 -->
            <div class="tab-content" id="myTabContent">
                <!-- Newsfeed -->
                <div class="tab-pane fade show active" id="newsfeed_div" role="tabpanel" aria-labelledby="newsfeed_div">
                    <div class="posts_area"></div>
                    <img id="loading" src="assets/images/icons/loading.gif">
                </div>

                <!-- Messages -->
                <div class="tab-pane fade" id="messages_div" role="tabpanel" aria-labelledby="messages_div">
                    <?php
                        

                        echo "<h4>You and <a href='" . $username . "'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

                        echo "<div classe='loaded_messages' id='scroll_messages'>";
                        echo    $message_obj->getMessages($username);
                        echo "</div>";
                    ?>
                    <div class-"message_post">
                        <form action="" method="POST">
                                <textarea name='message_body' id='message_textarea' placeholder='メッセージを入力'></textarea>
                                <input type='submit' name='post_message' class='info' id ='message_submit' value='送信'>
                        </form>
                    </div>

                    <!-- <script>
                        let div = document.getElementById("scroll_messages");
                        div.scrollTop = div.scrollHeight;
                    </script> -->
                </div>
            </div>

            <!-- <ul class="nav nav-tabs" role="tablist" id="profileTabs">
                <li class="nav-item">
                    <a class="nav-link active" href="#newsfeed_div" aria-controls="newsfeed_div" role="tab">Newsfeed</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#about_div" aria-controls="about_div" role="tab">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#messages_div" aria-controls="messages_div" role="tab">Messages</a>
                </li>
            </ul>

            <div class="tab-content"> -->

                <!-- <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
                    <div class="posts_area"></div>
                    <img id="loading" src="assets/images/icons/loading.gif">
                </div>

                <div role="tabpanel" class="tab-pane fade" id="about_div">
                    
                </div>

                <div role="tabpanel" class="tab-pane fade" id="messages_div"> -->
                    <?php
                        // $message_obj = new Message($con, $userLoggedIn);

                        // echo "<h4>You and <a href='" . $username . "'>" . $profile_user_obj->getFirstAndLastName() . "</a></h4><hr><br>";

                        // echo "<div classe='loaded_messages' id='scroll_messages'>";
                        // echo    $message_obj->getMessages($username);
                        // echo "</div>";
                    ?>
                    <!-- <div class-"message_post">
                        <form action="" method="POST">
                                <textarea name='message_body' id='message_textarea' placeholder='メッセージを入力'></textarea>
                                <input type='submit' name='post_message' class='info' id ='message_submit' value='送信'>
                        </form>
                    </div> -->

                    <!-- <script>
                        let div = document.getElementById("scroll_messages");
                        div.scrollTop = div.scrollHeight;
                    </script> -->
                <!-- </div> -->

            </div>
            
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title" id="myModalLabel">Post something!</h4>
                </div>
                <div class="modal-body">
                    <p>This will appear on the user's profile page and also their newsfeed for your friends to see!</p>
                    <form class="profile_post" action="" method="POST">
                        <div class="form-group">
                            <textarea name="post_body" class="form-control"></textarea>
                            <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
                            <input type="hidden" name="user_to" value="<?php echo $username; ?>">
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">Post</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let userLoggedIn    = '<?php echo $userLoggedIn; ?>';
        let profileUsername = '<?php echo $username; ?>';

        $(document).ready(function(){
            $('#loading').show();

            // Ajax投稿読み込み（初回）
            $.ajax({
                url:   "includes/handlers/ajax_load_profile_posts.php",
                type:  "POST",
                data:  "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
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
                        url:   "includes/handlers/ajax_load_profile_posts.php",
                        type:  "POST",
                        data:  "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
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
</body>
</html>