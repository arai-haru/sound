<?php
session_start();
require_once "../config/config.php";
require_once "../model/User.php";

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

//一般ユーザの場合、ログインしたユーザ情報を設定する
if($_SESSION['log']['role'] === '0'){
  $result['User'] = $_SESSION['log'];
}

try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  if(isset($_GET['del'])){
    //お気に入りから削除
    if($result['User']['role'] === '0'){
      $User->deleteFavolitePromote($_GET['del']);
    }
  }

  //お気に入りに登録した団体の参照
  if($result['User']['role'] === '0'){
    $result = $User->findFavolitePromote($result['User']['id']);
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
    <link rel="stylesheet" type="text/css" href="../css/mypage.css">
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
        <p>お気に入り団体一覧</p>

        <?php if(empty($result)):?>
          <p>　</p>
          <p>お気に入りは登録されていません</p>
        <?php endif;?>

        <?php foreach ($result as $row):?>
          <?php //if($row['promote_id'] == ''){ echo '演奏会が登録されました。';}?>
          <img class="pin1" src="../image/image10-3.png" alt="ピン">
          <div id="sukima"></div>
          <div class="list" id="p_favolite">
            <div id="group">
              <p><a href="../html/message.php?promote_id=<?= $row['promote_id'];?>"><?= $row['group_name'];?></a></p>
              <p class="pink">登録公演数</p>
              <p class="pink"><?= $row['count_id'];?></p>
            </div>
            <div id="detail">
              <div class="flex">
                <h3>代表者</h3>
                <p><?= $row['name'];?></p>
              </div>
              <h3>団体詳細</h3>
              <p class="space"><?= $row['group_detail'];?></p>
            </div>
            <div id="botan">
              <div class="delet">
                <p><a href="?del=<?= $row['id'];?>" onClick="if(!confirm('お気に入りから削除しますがよろしいですか？')) return false;">削除</a></p>
              </div>
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
