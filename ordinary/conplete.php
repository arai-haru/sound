<?php

require_once "../config/config.php";
require_once "../model/User.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  //一般登録
  if(empty($_POST['name'] || $_POST['email'] || $_POST['password'])){
    header('Location:login.php');
    exit;
  }else{
    $User->addOrdinary($_POST);
  }
}
catch(PDOException $e){
  echo 'エラー'.$e->getMessage();
}
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
        <p class="p_head">登録が完了しました。</p>
        <p class="p_head">ご登録いただきありがとうございます！</p>

        <div id="conplete">
          <p class="sinki"><a href="login.php">ログイン画面へ戻る</a></p>
        </div>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
