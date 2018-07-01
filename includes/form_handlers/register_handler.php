<?php

// 
$fname       = "";  // First name
$lname       = "";  // Last name
$em          = "";  // Email
$em2         = "";  // Email2
$password    = "";  // Password
$password2   = "";  // Password2
$date        = "";  // Sign up date
$error_array = array();  // Holds error messages

// 登録処理
if(isset($_POST['register_button'])){

    // First name
    $fname = strip_tags($_POST['reg_fname']);   // HTMLおよびPHPタグを取り除く
    $fname = str_replace(' ', '', $fname);      // 空白を取り除く
    $fname = ucfirst(strtolower($fname));       // strtolower: アルファベット部分をすべて小文字にする -> ucfirst: アルファベットの最初の文字を大文字にする
    $_SESSION['reg_fname'] = $fname;            // セッションに追加

    // Last name
    $lname = strip_tags($_POST['reg_lname']);
    $lname = str_replace(' ', '', $lname);
    $lname = ucfirst(strtolower($lname));
    $_SESSION['reg_lname'] = $lname;

    // Email
    $em = strip_tags($_POST['reg_email']);
    $em = str_replace(' ', '', $em);
    $em = strtolower($em);
    $_SESSION['reg_email'] = $em;

    // Email 2
    $em2 = strip_tags($_POST['reg_email2']);
    $em2 = str_replace(' ', '', $em2);
    $em2 = strtolower($em2);
    $_SESSION['reg_email2'] = $em2;

    // Password
    $password = strip_tags($_POST['reg_password']);
    $password2 = strip_tags($_POST['reg_password2']);

    $date = date("Y-m-d"); //現在の日付

    // Emailが同一か確認
    if($em == $em2){
        // EmailアドレスがRFC822に沿った形式であるか確認
        if(filter_var($em, FILTER_VALIDATE_EMAIL)){
            $em = filter_var($em, FILTER_VALIDATE_EMAIL);
            
            // 登録済みアドレスとの重複確認
            $e_check = mysqli_query($con, "SELECT email FROM users WHERE email='$em'");

            $num_rows = mysqli_num_rows($e_check);

            if($num_rows > 0){
                array_push($error_array, "Email already in use<br>");
            }

        }else{
            array_push($error_array, "Invalid email format<br>");
        }
    }else{
        array_push($error_array, "Emails don't match<br>");
    }

    // First name 文字数チェック
    if(strlen($fname) > 25 || strlen($fname) < 2){
        array_push($error_array, "Your first name must be between 2 and 25 characters<br>");
    }

    // Last name 文字数チェック
    if(strlen($lname) > 25 || strlen($lname) < 2){
        array_push($error_array, "Your last name must be between 2 and 25 characters<br>");
    }

    // Password 同一チェック
    if($password != $password2){
        array_push($error_array, "Your passwords do not match<br>");
    }else{
        // 入力規則チェック
        if(preg_match('/[^A-Za-z0-9]/', $password)){
            array_push($error_array, "Your password can only contain english chatacters or numbers<br>");
        }
    }

    // パスワード 文字数チェック
    if(strlen($password > 30 || strlen($password) < 5)){
        array_push($error_array, "Your password must be between 5 and 30 characters<br>");
    }

    if(empty($error_array)){
        $password = md5($password); // パスワード暗号化

        $username = strtolower($fname . "_" . $lname);
        $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='$username'");

        // ユーザー名が同一であった場合の処理
        $i = 0;
        while(mysqli_num_rows($check_username_query) != 0){
            $i++;
            $username = $username . "_" . $i;
            $check_username_query = mysqli_query($con, "SELECT username FROM users WHERE username='username'");
        }

        // プロフィール画像
        $rand = rand(1, 2);
        if($rand == 1){
            $profile_pic = "assets/images/profile_pics/defaults/head_deep_blue.png";
        }else if($rand == 2){
            $profile_pic = "assets/images/profile_pics/defaults/head_emerald.png";
        }

        $query = mysqli_query($con, "INSERT INTO users VALUES ('', '$fname', '$lname', '$username', '$em', '$password', '$date', '$profile_pic', '0', '0', 'no', ',')");

        array_push($error_array, "<span style='color: #14C800;'>You're all set! Go ahead and login!</span><br>");

        // セッションリセット
        $_SESSION['reg_fname'] = "";
        $_SESSION['reg_lname'] = "";
        $_SESSION['reg_email'] = "";
        $_SESSION['reg_email2'] = "";
    }

}

?>