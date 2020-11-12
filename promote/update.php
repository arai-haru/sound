<?php
session_start();
require_once "../config/config.php";
require_once "../model/Promote_user.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

//ログアウト処理
if(isset($_GET['logout'])){
  //セッション破棄
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか
if(!isset($_SESSION['log'])){
  header('Location:login.php');
  exit;
}

try{
  $Promote = new Promote_user($host,$dbname,$user,$pass);
  $Promote->connectDb();

  //更新処理
  if($_POST){
    $message = $Promote->validate($_POST);
    print_r($_POST);
    if(empty($message['name']) && empty($message['kana']) && empty($message['email']) && empty($message['password']) && empty($message['group_name']) && empty($message['group_detail'])){
      $Promote->edit($_POST);
      header('Location:mypage.php');
      exit;
    }
  }
  //参照処理
  $result['User'] = $Promote->findById($_SESSION['log']['id']);

  //削除処理
  if(isset($_GET['del'])){
    if($result['User']['role'] === '1'){
      $Promote->delete($_GET['del']);
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
    <title>演奏会宣伝サイト</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/login.css">
    <script type="text/javascript" src="../js/jquery.js"></script>
    <script>
    $(function(){
      $('#h_menu').on('click',function(){
        if($("#h_menu").hasClass("on")){
          $("#h_menu").removeClass("on");
          $(".media_header").css("display","none");
        }
        else{
          $("#h_menu").addClass("on");
          $(".media_header").css("display","block");
        }
      });
    });
    </script>
  </head>
  <body>
    <?php
    require_once "header.php";
    ?>

    <div class="login">
      <div class="wrap">
        <p class="p_head">登録情報変更</p>
        <span>
          <p><?php if(isset($message['name'])) echo $message['name'];?></p>
          <p><?php if(isset($message['kana'])) echo $message['kana'];?></p>
          <p><?php if(isset($message['email'])) echo $message['email'];?></p>
          <p><?php if(isset($message['password'])) echo $message['password'];?></p>
          <p><?php if(isset($message['group_name'])) echo $message['group_name'];?></p>
          <p><?php if(isset($message['group_detail'])) echo $message['group_detail'];?></p>
        </span>
        <div id="delet">
          <a href="?del=<?= $result['User']['id']?>" onClick="if(!confirm('お使いのアカウントが削除されますがよろしいですか？')) return false;">アカウントを削除する</a>
        </div>
        <form action="" method="post">
          <table>
            <th id="size14"><span>*</span>は必須項目です。</th>
            <tr>
              <th>代表者氏名<span>*</span></th>
              <td><input type="text" name="name" value="<?= h($result['User']['name'])?>"></td>
            </tr>
            <tr>
              <th>フリガナ<span>*</span></th>
              <td><input type="text" name="kana" value="<?= h($result['User']['kana'])?>"></td>
            </tr>
            <tr>
              <th>メールアドレス<span>*</span></th>
              <td><input type="text" name="email" value="<?= h($result['User']['email'])?>"></td>
            </tr>
            <tr>
              <th>パスワード<span>*</span></th>
              <td><input type="password" name="password"></td>
            </tr>
            <tr>
              <th>団体名<span>*</span></th>
              <td><input type="text" name="group_name" value="<?= h($result['User']['group_name'])?>"></td>
            </tr>
            <tr>
              <th>団体種類<span>*</span></th>
              <td>
                <select name="group_class">
                  <option value="中学校" <?php if($result['User']['group_class'] === "中学校") echo "selected"?>>中学校</option>
                  <option value="高校" <?php if($result['User']['group_class'] === "高校") echo "selected"?>>高校</option>
                  <option value="大学" <?php if($result['User']['group_class'] === "大学") echo "selected"?>>大学</option>
                  <option value="社会人" <?php if($result['User']['group_class'] === "社会人") echo "selected"?>>社会人</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>団体説明<span>*</span></th>
              <td><textarea type="text" name="group_detail"><?= h($result['User']['group_detail'])?></textarea></td>
            </tr>
          </table>
          <input type="hidden" name="id" value="<?= $result['User']['id']?>">
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="変更する">
          </div>
        </form>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
