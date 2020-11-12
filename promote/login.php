<?php
session_start();
require_once "../config/config.php";
require_once "../model/Promote_user.php";

try{
  $Promote = new Promote_user($host,$dbname,$user,$pass);
  $Promote->connectDb();

  //ログイン処理
  if($_POST){
    $pw = $_POST['password'];
    $result = $Promote->login($_POST['email']);
    if(!empty($result)){
      if(password_verify($pw,$result['password'])){
        $_SESSION['log'] = $result;
        if($_SESSION['log']['role'] ==='1'){
          header('Location:mypage.php');
          exit;
        }
      }
      else{
        $message = 'ログインできません';
      }
    }
    else{
      $message = 'ログインできません';
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
    <title>演奏会宣伝サイト 主催者ログイン</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
  </head>
  <body>
    <?php
    require_once "../html/login_header.php";
    ?>

    <div class="login">
      <div class="wrap">
        <p>吹奏楽演奏会宣伝サイト</p>
        <p>サウンド</p>
        <p>　</p>
        <p class="p_head">主催者ログイン</p>
        <span><p><?php if(isset($message))echo $message?></p></span>

        <form action="" method="post">
          <table>
            <tr>
              <th>メールアドレス</th>
              <td><input type="text" name="email"></td>
            </tr>
            <tr>
              <th>パスワード</th>
              <td><input type="password" name="password"></td>
            </tr>
          </table>
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="ログイン">
          </div>
        </form>

        <p class="sinki"><a href="promote_insert.php">新規登録はこちら</a></p>

        <p class="sinki"><br><a href="forget.php">パスワードを忘れた方はこちら</a></p>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
