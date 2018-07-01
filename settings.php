<?php
include("includes/header.php");
include("includes/form_handlers/settings_handler.php");
?>

<div class="main_column column">
    <h4>アカウント設定</h4>
    <?php
    echo "<img src='" . $user['profile_pic'] . "' id='small_profile_pic'>";
    ?>
    <br>
    <a href="upload.php">プロフィール写真を変更</a>
    <br>
    <br>
    <br>
    値を変更し、「変更を保存」をクリックしてください。

    <?php
    $user_data_query = mysqli_query($con, "SELECT first_name, last_name, email FROM users WHERE username='$userLoggedIn'");
    $row             = mysqli_fetch_array($user_data_query);

    $first_name = $row['first_name'];
    $last_name  = $row['last_name'];
    $email      = $row['email'];
    ?>

    <form action="settings.php" method="POST">
        First Name: <input type="text" name="first_name" value="<?php echo $first_name; ?>" class='settings_input'><br>
        Last Name: <input type="text" name="last_name" value="<?php echo $last_name; ?>" class='settings_input'><br>
        Email: <input type="text" name="email" value="<?php echo $email; ?>" class='settings_input'><br>

        <?php echo $message; ?>

        <input type="submit" name="update_details" id="update_details" value="変更を保存" class="info settings_submit"><br>
    </form>

    <h4>パスワードの変更</h4>
    <form action="settings.php" method="POST">
        現在のパスワード: <input type="password" name="old_password" class='settings_input'><br>
        新しいパスワード: <input type="password" name="new_password_1" class='settings_input'><br>
        新しいパスワードを再入力: <input type="password" name="new_password_2" class='settings_input'><br>

        <?php echo $password_message; ?>

        <input type="submit" name="update_password" id="update_password" value="パスワードの更新" class="info settings_submit"><br>
    </form>

    <h4>アカウントの利用解除</h4>
    <form action="settings.php" method="POST">
        <input type="submit" name="close_account" id="close_account" value="アカウントの利用解除" class="danger settings_submit">
    </form>
</div>