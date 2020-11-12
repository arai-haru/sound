<?php
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;

if($referer != "http://".$_SERVER["HTTP_HOST"]."/php_code/ordinary/ordinary_insert.php"){
  header('Location:../ordinary/login.php');
  exit;
}

session_start();
require_once "../config/config.php";
require_once "../model/User.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

$name = h($_POST['name']);
$kana = h($_POST['kana']);
$email = h($_POST['email']);
$password = h($_POST['password']);

if(isset($name)){
  $_SESSION['name'] = $name;
}
if(isset($kana)){
  $_SESSION['kana'] = $kana;
}
if(isset($email)){
  $_SESSION['email'] = $email;
}
if(isset($password)){
  $_SESSION['password'] = $password;
}

try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  //ログイン処理
  if($_POST){
    $message = $User->validate($_POST);
    if(empty($message['name']) && empty($message['kana']) && empty($message['email']) && empty($message['password'])){
    }
    else{
      header('Location:ordinary_insert.php');
      exit;
    }
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
        <p class="p_head">新規登録内容確認</p>
        <p class="p_head">この内容でよろしいですか？</p>
        <form action="conplete.php" method="post">
          <table>
            <tr>
              <th>氏名：</th>
              <td><?= h($name)?></td>
              <input type="hidden" name="name" value="<?= h($name)?>">
            </tr>
            <tr>
              <th>フリガナ：</th>
              <td><?= h($kana)?></td>
              <input type="hidden" name="kana" value="<?= h($kana)?>">
            </tr>
            <tr>
              <th>メールアドレス：</th>
              <td><?= h($email)?></td>
              <input type="hidden" name="email" value="<?= h($email)?>">
            </tr>
            <tr>
              <th>パスワード：</th>
              <td>＊＊＊＊</td>
              <input type="hidden" name="password" value="<?= h($password)?>">
            </tr>
          </table>
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="登録する">
          </div>
        </form>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
