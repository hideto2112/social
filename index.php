<?php
include("includes/header.php");

if(isset($_POST['post'])){

    $uploadOk     = 1;
    $imageName    = $_FILES['fileToUpload']['name'];
    $errorMessage = "";

    if($imageName != ""){
        $targetDir     = "assets/images/posts/";
        $imageName     = $targetDir . uniqid() . basename($imageName);
        $imageFileType = pathinfo($imageName, PATHINFO_EXTENSION);

        if($_FILES['fileToUpload']['size'] > 10000000){
            $errorMessage = "サイズが大きすぎます。";
            $uploadOk     = 0;
        }

        if(strtolower($imageFileType) != "jpeg" && strtolower($imageFileType) != "png" && strtolower($imageFileType) != "jpg"){
            $errorMessage = "拡張子がjpeg, jpg, pngのファイルのみ対応しています。";
            $uploadOk     = 0;
        }

        if($uploadOk){
            if(move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $imageName)){
                // アップロード成功
            }else{
                // アップロード失敗
                $uploadOk = 0;
            }
        }
    }

    if($uploadOk){
        $post = new Post($con, $userLoggedIn);
        $post->submitPost($_POST['post_text'], 'none', $imageName);
    }else{
        echo "<div style='text-align: center;' class='alert alert-danger'>
                $errorMessage
              </div>";
    }
}
?>
        <div class="user_details column">
            <a href="<?php echo $userLoggedIn; ?>"><img src="<?php echo $user['profile_pic']; ?>"></a>
            
            <div class="user_details_left_right">
                <a href="<?php echo $userLoggedIn; ?>">
                <?php
                    echo $user['first_name'] . " " . $user['last_name'] . "<br>";
                ?>
                </a>
                <?php
                    echo "投稿数: " . $user['num_posts'] . "<br>";
                    echo "いいね: " . $user['num_likes']
                ?>
            </div>
        </div>

        <!-- メインコンテンツ -->
        <div class="main_column column">
            <form class="post_form" action="index.php" method="POST" enctype="multipart/form-data">
                <input type="file" name="fileToUpload" id="fileToUpload">
                <textarea name="post_text" id="post_text" placeholder="今なにしてる？"></textarea>
                <input type="submit" name="post" id="post_button" value="投稿">
                <hr>
            </form>

            <div class="posts_area"></div>
            <img id="loading" src="assets/images/icons/loading.gif">

        </div>

        <!-- トレンド表示 -->
        <div class="user_details column">

            <h4>トレンドワード</h4>
            <div class="trends">
                <?php
                $query = mysqli_query($con, "SELECT * FROM trends ORDER BY hits LIMIT 9");

                foreach($query as $row){
                    $word     = $row['title'];
                    $word_dot = strlen($word) >= 14 ? "..." : "";

                    $trimmed_word = str_split($word, 14);
                    $trimmed_word = $trimmed_word[0];

                    echo "<div style'padding: 1px'>";
                    echo    $trimmed_word . $word_dot;
                    echo "<br></div>";
                }
                ?>
            </div>
        </div>

        <script>
            $(document).ready(function(){

                $('.dropdown_data_window').scroll(function(){
                    let inner_height = $('.dropdown_data_window').innerHeight();
                    let scroll_top   = $('.dropdown_data_window').scrollTop();
                    let page         = $('.dropdown_data_window').find('.nextPageDropDownData').val();
                    let noMoreData   = $('.dropdown_data_window').find('.noMoreDropDownData').val();

                    if((scroll_top + inner_height >= $('.dropdown_data_window')[0].scrollHeight) && noMoreData == 'false'){
                        
                        let pageName;
                        let type = $("#dropdown_data_type").val();

                        if(type == 'notification'){
                            pageName = "ajax_load_notification.php";
                        
                        }else if(type == 'message'){
                            pageName = "ajax_load_messages.php";
                        }

                        // 
                        let ajaxRex = $.ajax({
                            url:   "includes/handlers/" + pageName,
                            type:  "POST",
                            data:  "page=" + page + "&userLoggedIn=" + userLoggedIn,
                            cache: false,

                            success: function(response){
                                $('.dropdown_data_window').find('.nextPageDropDownData').remove();
                                $('.dropdown_data_window').find('.noMoreDropDownData').remove();

                                $('.dropdown_data_window').append(response);
                            }
                        });
                    }
                    return false;
                });
            });
        </script>

    </div>
</body>
</html>