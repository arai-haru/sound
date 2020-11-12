<?php
require_once "../config/config.php";
require_once "../model/User.php";

try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  if(isset($_GET)){
    $result = $User->riset($_GET['id']);
    if($result['token'] < time()){
      header('Location:login.php');
      exit;
    }

    if(!isset($_GET['token'])){
      header('Location:login.php');
      exit;
    }
    else{
      if($_GET['token'] != $result['token']){
        header('Location:login.php');
        exit;
      }
    }
  }
  if($_POST){
    $message = $User->validate($_POST);
    if(empty($message['password'])){
      $User->passriset($_POST['password'],$_GET['id']);
      header('Location:login.php');
      exit;
    }
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
    <title>演奏会宣伝サイト パスワードを忘れた方</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
  </head>
  <body>
    <?php
    require_once "../html/login_header.php";
    ?>

    <div class="login">
      <div class="wrap">
        <p class="p_head">パスワード再設定</p>
        <p>こちらに新しいパスワードを入力してください</p>
        <span><p><?php if(isset($message['password'])) echo $message['password'];?></p></span>

        <form action="" method="post">
          <table>
            <tr>
              <th>新しいパスワード</th>
              <td><input type="password" name="password"></td>
            </tr>
          </table>
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="再設定">
          </div>
        </form>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
