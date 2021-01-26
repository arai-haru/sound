<?php

session_start();
require_once "../config/config.php";
require_once "../model/Promote_user.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

try{
  $Promote = new Promote_user($host,$dbname,$user,$pass);
  $Promote->connectDb();

  //入力チェック
  if($_SESSION){
    $message = $Promote->validate($_SESSION);
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
        <p class="p_head">主催者新規登録</p>

        <form action="promote_confirm.php" method="post">
          <table>
            <th class="size14"><span>*</span>は必須項目です。</th>
            <p class="size14">登録いただいたメールアドレスあてに<br>管理者からメッセージがある可能性が<br>ありますのでご了承ください</p>
            <tr>
              <th>代表者氏名<span>*</span><br><span><?php if(isset($message['name'])) echo $message['name'];?></span></th>
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
            <tr>
              <th>団体名<span>*</span><br><span><?php if(isset($message['group_name'])) echo $message['group_name'];?></span></th>
              <td><input type="text" name="group_name" value="<?php if(isset($_SESSION['group_name'])) echo h($_SESSION['group_name']);?>"></td>
            </tr>
            <tr>
              <th>団体種類<span>*</span></th>
              <td>
                <select name="group_class">
                  <option value="中学校" <?php if(isset($_SESSION['group_class'])){if($_SESSION['group_class'] === "中学校"){ echo 'selected';}}?>>中学校</option>
                  <option value="高校" <?php if(isset($_SESSION['group_class'])){if($_SESSION['group_class'] === "高校"){ echo 'selected';}}?>>高校</option>
                  <option value="大学" <?php if(isset($_SESSION['group_class'])){if($_SESSION['group_class'] === "大学"){ echo 'selected';}}?>>大学</option>
                  <option value="社会人" <?php if(isset($_SESSION['group_class'])){if($_SESSION['group_class'] === "社会人"){ echo 'selected';}}?>>社会人</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>団体説明<span>*</span><br><span><?php if(isset($message['group_detail'])) echo $message['group_detail'];?></span></th>
              <td><textarea type="text" name="group_detail"><?php if(isset($_SESSION['group_detail'])) echo h($_SESSION['group_detail']);?></textarea></td>
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
