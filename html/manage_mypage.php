<?php
session_start();
require_once "../config/config.php";
require_once "../model/Promote_user.php";

//ログアウト処理
if(isset($_GET['logout'])){
  //セッション破棄
  $_SESSION = array();
  session_destroy();
}

//ログイン画面を経由しているか
if(!isset($_SESSION['log'])){
  header('Location:../ordinary/login.php');
  exit;
}

//一般ユーザの場合、ログインしたユーザ情報を設定する
if($_SESSION['log']['role'] === '2'){
  $result['User'] = $_SESSION['log'];
}

try{
  $Promote = new Promote_user($host,$dbname,$user,$pass);
  $Promote->connectDb();

  //削除処理
  if(isset($_GET['del'])){
    $Promote->delet($_GET['del']);
    $Promote->findManage();
  }

  //一覧表示処理
  if($_SESSION['log']['role'] === '2'){
    $result = $Promote->findManage();
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
        <h2>管理者マイページ</h2>

        <p>登録団体一覧</p>

        <?php foreach ($result as $row):?>
          <img class="pin1" src="../image/image10-3.png" alt="ピン">
          <div id="sukima"></div>
          <div class="list">
            <div id="group">
              <p><?= $row['group_name']?></p>
              <p class="pink">登録公演数<br><?= $row['count_concert']?></p>
            </div>
            <div id="detail">
              <div class="flex">
                <h3>代表者</h3>
                <p><?= $row['name']?></p>
                <h3>メールアドレス</h3>
                <p><?= $row['email']?></p>
              </div>
              <h3>団体詳細</h3>
              <p class="space"><?= $row['group_detail']?></p>
            </div>
            <div id="botan" class="delet">
              <p><a href="?del=<?= $row['id'];?>" onClick="if(!confirm('削除しますがよろしいですか？')) return false;">削除</a></p>
            </div>
          </div>
        <?php endforeach;?>
      </div>
    </div>

    <?php
    require_once "footer.php";
    ?>
  </body>
</html>
