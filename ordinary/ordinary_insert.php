<?php

session_start();
require_once "../config/config.php";
require_once "../model/User.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  //入力チェック
  if($_SESSION){
    $message = $User->validate($_SESSION);
  }
}
catch(PDOException $e){
  echo 'エラー'.$e->getMessage();
}
session_destroy();
?>
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <link rel="icon" type="image/jpg" href="../image/image6.png">
    <title>演奏会宣伝サイト</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
  </head>
  <body>
    <?php
    require_once "../html/login_header.php";
    ?>

    <div class="login">
      <div class="wrap">
        <p class="p_head">一般新規登録</p>

        <form action="ordinary_confirm.php" method="post">
          <table>
            <th class="size14"><span>*</span>は必須項目です。</th>
            <p class="size14">ご登録いただいたメールアドレス宛に<br>通知が届くようになっています。</p>
            <tr>
              <th>氏名<span>*</span><br><span><?php if(isset($message['name'])) echo $message['name'];?></span></th>
              <td><input type="text" name="name" value="<?php if(isset($_SESSION['name'])) echo h($_SESSION['name']);?>"></td>
            </tr>
            <tr>
              <th>フリガナ<span>*</span><br><span><?php if(isset($message['kana'])) echo $message['kana'];?></span></th>
              <td><input type="text" name="kana" value="<?php if(isset($_SESSION['kana'])) echo h($_SESSION['kana']);?>"></td>
            </tr>
            <tr>
              <th>メールアドレス<span>*</span><br><span><?php if(isset($message['email'])) echo $message['email'];?></span></th>
              <td><input type="text" name="email" value="<?php if(isset($_SESSION['email'])) echo h($_SESSION['email']);?>"></td>
            </tr>
            <tr>
              <th>パスワード<span>*</span><br><span><?php if(isset($message['password'])) echo $message['password'];?></span></th>
              <td><input type="password" name="password" value="<?php if(isset($_SESSION['password'])) echo h($_SESSION['password']);?>"></td>
            </tr>
          </table>
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="登　録">
          </div>
        </form>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
