<?php
session_start();
require_once "../config/config.php";
require_once "../model/Message.php";

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
  $Message = new Message($host,$dbname,$user,$pass);
  $Message->connectDb();

  if($_POST){
    $messages = $Message->validate($_POST);

    if(empty($messages['name']) && empty($messages['messages'])){
      $Message->add($_POST);
      header('Location:mypage.php');
      exit;
    }
  }

}
catch(PDOException $e){
  echo 'エラー'.$e->getMessage();
}
// session_destroy();
?>

<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
    <link rel="icon" type="image/jpg" href="../image/image6.png">
    <title>演奏会宣伝サイト</title>
    <link rel="stylesheet" type="text/css" href="../css/base.css">
    <link rel="stylesheet" type="text/css" href="../css/message.css">
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
        <p class="p_head">メッセージ</p>
        <p>　</p>
        <p class="size12">演奏会に足をお運び頂きありがとうございます。</p>
        <p class="size12">よろしければ応援メッセージやよかったを付けて頂いた理由など</p>
        <p class="size12">ご入力いただければ幸いです。</p>
        <p class="size12">なおご入力頂いたメッセージは団体詳細に表示されます。</p>
        <p class="size12">ご入力されない場合は戻るを押してください。</p>

        <span>
          <p><?php if(isset($messages['name'])) echo $messages['name'];?></p>
          <p><?php if(isset($messages['messages'])) echo $messages['messages'];?></p>
        </span>

        <form action="" method="post">
          <table>
            <tr>
              <th><p>ニックネーム：</p></th>
              <td><input type="text" name="name" value="<?php if(isset($_POST['name'])) echo $_POST['name']?>"></td>
            </tr>
            <tr>
              <th><p>メッセージ：</p></th>
              <td><textarea type="text" name="messages"><?php if(isset($_POST['messages'])) echo $_POST['messages']?></textarea></td>
            </tr>
          </table>
          <div class="botan">
            <input class="submit" type="submit" name="submit" value="送信">
          </div>
          <input type="hidden" name="user_id" value="<?= $_SESSION['log']['id']?>">
          <input type="hidden" name="promote_id" value="<?= $_GET['promote_id']?>">
        </form>
        <p class="size12"><a href="mypage.php">戻る</a></p>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
