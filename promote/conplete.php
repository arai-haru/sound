<?php
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

if($referer != "http://".$_SERVER["HTTP_HOST"]."/php_code/promote/promote_confirm.php"){
  header('Location:../promote/login.php');
  exit;
}

require_once "../config/config.php";
require_once "../model/Promote_user.php";

try{
  $User = new Promote_user($host,$dbname,$user,$pass);
  $User->connectDb();

  //主催者登録処理
  if($_POST){
    $User->addPromote($_POST);
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
