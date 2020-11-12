<?php
// $referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : null;
//
// if($referer != "http://".$_SERVER["HTTP_HOST"]."/php_code/ordinary/login.php" || "http://".$_SERVER["HTTP_HOST"]."/php_code/ordinary/forget.php"){
//   header('Location:../ordinary/login.php');
//   exit;
// }

require_once "../config/config.php";
require_once "../model/User.php";


try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  if($_POST){
    $message = $User->validate($_POST);
    if(empty($message['email'])){
      $result = $User->forget($_POST['email']);

      if(!empty($result)){
        $random = bin2hex(random_bytes(32));

        mb_language("ja");
        mb_internal_encoding("UTF-8");

        $limit = time() + 1800;//有効期限30分
        $random_l = $limit."_".$random;
        $mail = $result['email'];
        $msg = '以下のアドレスからパスワードのリセットを行ってください。' . PHP_EOL;
        $msg .=  'アドレスの有効時間は３０分間です。' . PHP_EOL . PHP_EOL;
        $msg .=  '３０分を過ぎたらURLを押してもログイン画面になります。' . PHP_EOL . PHP_EOL;
        $msg .=  'お心当たりのない場合は無視してください。' . PHP_EOL;
        $msg .= 'http://wifiのipアドレス:8000/php_code/ordinary/riset.php?token=' . $random_l.'&id='.$result['id'];
        $sender = '管理人';
        $headers = "From: ".$sender."<gメールアドレス>";
        if(mb_send_mail($mail, 'パスワードのリセット', $msg,$headers)){
          // echo '送信されました';
          $User->token($random_l,$result['id']);
        }
        else{
          echo '送信されませんでした';
        }
      }
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
        <?php if($_POST && empty($message['email'])):?>
          <p class="p_head">メールが送信されました。</p>
        <?php else:?>
          <p class="p_head">パスワードを忘れた方へ</p>
          <p class="size14">登録しているメールアドレスを入力してください</p>
          <p class="size14">認証ボタンを押すと登録メールアドレスにメールが送られますので<br>そちらからパスワードの変更を行ってください</p>
          <span><p><?php if(isset($message['email'])) echo $message['email'];?></p></span>

          <form action="" method="post">
            <table>
              <tr>
                <th>メールアドレス</th>
                <td><input type="text" name="email"></td>
              </tr>
            </table>
            <div class="botan">
              <input class="submit" type="submit" name="submit" value="認証">
            </div>
          </form>
        <?php endif;?>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
