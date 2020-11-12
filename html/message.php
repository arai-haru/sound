<?php
session_start();
require_once "../config/config.php";
require_once "../model/Message.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

//ログアウト処理
if(isset($_GET['logout'])){
  if($_SESSION['log']['role'] == '0' || $_SESSION['log']['role'] == '2'){
    //セッション破棄
    $_SESSION = array();
    session_destroy();
    header('Location:../ordinary/login.php');
    exit;
  }
  else{
    $_SESSION = array();
    session_destroy();
    header('Location:../promote/login.php');
    exit;
  }
}

//ログイン画面を経由しているか
if(!isset($_SESSION['log'])){
  header('Location:../ordinary/login.php');
  exit;
}

try{
  $Message = new Message($host,$dbname,$user,$pass);
  $Message->connectDb();

  if(isset($_GET['promote_id'])){
    //参照処理
    $result = $Message->findAll($_GET['promote_id']);
  }
  else{
    $result = $Message->findAll($_SESSION['log']['id']);
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
    if($_SESSION['log']['role'] === '2'){
      require_once "header.php";//role権限でheaderの表示を変える
    }
    elseif($_SESSION['log']['role'] === '0'){
      require_once "../ordinary/header.php";//role権限でheaderの表示を変える
    }
    elseif($_SESSION['log']['role'] === '1'){
      require_once "../promote/header.php";
    }
    ?>

    <div class="login">
      <div class="wrap">
        <p class="p_head">団体に向けてのメッセージ</p>
        <p class="size12">こちらは演奏会によかったを付けた方のメッセージが表示されます。</p>
        <p>　</p>

        <?php if(empty($result)):?>
          <p>メッセージはまだありません</p>
        <?php endif;?>

        <?php foreach ($result as $row):?>
          <img class="pin1" src="../image/image10.png" alt="ピン">
          <div id="sukima"></div>
          <div class="list">
            <h3><?= $row['messages']?></h3>
            <div class="flex">
              <p><?= $row['name']?>さん</p>
              <p class="size12"><?= $row['created_at']?></p>
            </div>
          </div>
        <?php endforeach;?>
      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
