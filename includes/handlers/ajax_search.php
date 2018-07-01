<?php
include("../../config/config.php");
include("../classes/User.php");

$query        = $_POST['query'];
$userLoggedIn = $_POST['userLoggedIn'];

// スペースで分割して配列へ
$names = explode(" ", $query);

// アンダースコアが含まれている場合、ユーザー名を検索
if(strpos($query, '_') !== false){
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE username '$query%' AND user_closed='no' LIMIT 8");
// 2つのワードが含まれていた場合、それぞれ名と姓として検索
}else if(count($names) == 2){
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' AND last_name LIKE '$names[1]%') AND user_closed='no' LIMIT 8");
// 1つのワードの場合、姓または名を検索
}else{
    $usersReturnedQuery = mysqli_query($con, "SELECT * FROM users WHERE (first_name LIKE '$names[0]%' OR last_name LIKE '$names[0]%') AND user_closed='no' LIMIT 8");
}

if($query != ""){
    while($row = mysqli_fetch_array($usersReturnedQuery)){
        $user = new User($con, $userLoggedIn);

        if($row['username'] != $userLoggedIn){
            $mutual_friends = "共通の友達：" . $user->getMutualFriends($row['username']) . " 人";
        }else{
            $mutual_friends = "";
        }

        echo "<div class='resultDisplay'>
                <a href='" . $row['username'] . "' style='color: #16a085'>
                    <div class='liveSearchProfilePic'>
                        <img src='" .$row['profile_pic'] . "'>
                    </div>

                    <div class='liveSearchText'>
                        " . $row['first_name'] . " " . $row['last_name'] . "
                        <p>" . $row['username'] . "</p>
                        <p id='grey'>" . $mutual_friends ."</p>
                    </div>
                </a>
            </div>";
    }
}
?>