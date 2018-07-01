<?php
include("includes/header.php");

if(isset($_GET['q'])){
    $query = $_GET['q'];
}else{
    $query = "";
}

if(isset($_GET['type'])){
    $type = $_GET['type'];
}else{
    $type = "name";
}
?>

<div class="main_column column" id="main_column">
    <?php
    if($query == ""){
        echo "検索ボックスに何か入力してください。";
    }else{
       
        // アンダースコアが含まれている場合、ユーザー名を検索
        if($type == "username"){
            $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username LIKE '$query%' AND user_closed='no' LIMIT 8");
        }else{
            // スペースで分割して配列へ
            $names = explode(" ", $query);
            // 3つのワードが含まれていた場合、2つ目をミドルネームと仮定し、1つ目を名と3つ目を姓として検索
            if(count($names) == 3){
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[2]%') AND user_closed='no'");
            // 2つのワードが含まれていた場合、それぞれ名と姓として検索
            }else if(count($names) == 3){
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no'");
            // 1つのワードの場合、姓または名を検索
            }else{
                $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no'");
            }

            // 
            if(mysqli_num_rows($usersReturnedQuery) == 0){
                echo "「" . $type . "」に一致する " . $type . " は見つかりませんでした。"; 
            }else{
                echo mysqli_num_rows($usersReturnedQuery) . " 件の検索結果: <br><br>";
            }

            echo "<p id='grey'>Try searching for:</p>";
            echo "<a href='search.php?q=" . $query . "&type=name'>Names</a>, <a href='search.php?q=" . $query . "&type=username'>Username</a><br><br><hr class='search_hr'>";

            while($row = mysqli_fetch_array($usersReturnedQuery)){
                $user_obj = new User($con, $user['username']);

                $button = "";
                $mutual_friends = ""; 

                if($user['username'] != $row['username']){

                    // 友達ステータスに応じてボタン生成
                    if($user_obj->isFriend($row['username'])){
                        $button = "<input type='submit' name='" . $row['username'] . "' class='danger' value='友達から削除'>";
                    }else if($user_obj->didReceiveRequest($row['username'])){
                        $button = "<input type='submit' name='" . $row['username'] . "' class='warning' value='リクエストを承認'>";
                    }else if($user_obj->didSendRequest($row['username'])){
                        $button = "<input  type='submit' name='' class='default' value='リクエスト済み'>";
                    }else{
                        $button = "<input type='submit' name='" . $row['username'] . "' class='success' value='友達になる'>";
                    }

                    $mutual_friends = "共通の友達：" . $user_obj->getMutualFriends($row['username']) . " 人";

                    // ボタンフォーム
                    if(isset($_POST[$row['username']])){
                        if($user_obj->isFriend($row['username'])){
                            $user_obj->removeFriend($row['username']);
                            header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        }else if($user_obj->didReceiveRequest($row['username'])){
                            header("Location: requests.php");
                        }else if($user_obj->didSendRequest($row['username'])){

                        }else{
                            $user_obj->SendRequest($row['username']);
                            header("Location: http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]");
                        }
                    }
                }

                echo "<div class='search_result'>
                        <div class='searchPageFriendButtons'>
                            <form action='' method='POST'>
                                " . $button . "
                                <br>
                            </form>
                        </div>

                        <div class='result_profile_pic'>
                            <a href='" . $row['username'] . "'><img src='" . $row['profile_pic'] . "' style='height: 100px;'></a>
                        </div>
                        <a href='" . $row['username'] . "'>" . $row['first_name'] . " " . $row['last_name'] . "
                            <p id='grey'>" . $row['username'] . "</p>
                        </a>
                        <br>
                        " .$mutual_friends . "
                        <br>
                      </div>
                      <hr class='search_hr'>";
            } // End while
        }
    }
    ?>
</div>