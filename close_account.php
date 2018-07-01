<?php
include("includes/header.php");

if(isset($_POST['cancel'])){
    header("Location: settings.php");
}

if(isset($_POST['close_account'])){
    $close_query = mysqli_query($con, "UPDATE users SET user_closed='yes' WHERE username='$userLoggedIn'");
    session_destroy();
    header("Location: register.php");
}

?>
<div class="main_column column">
    <h4>アカウントの利用解除</h4>

    アカウントの利用を解除してよろしいですか。<br><br>
    利用を解除するとあなたのプロフィールとその他のアクティビティが他のユーザーから非表示になります。<br><br>
    サインインを行うことで、いつでもアカウントの利用を再開できます。<br><br>

    <form action="close_account.php" method="POST">
        <input type="submit" name="close_account" id="close_account" value="利用停止" class="danger  settings_submit">
        <input type="submit" name="cancel" id="update_details" value="キャンセル" class="info settings_submit">
    </form>
</div>