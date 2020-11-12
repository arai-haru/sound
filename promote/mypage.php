<?php
session_start();
require_once "../config/config.php";
require_once "../model/Concert.php";

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

//主催者ユーザの場合、ログインしたユーザ情報を設定する
if($_SESSION['log']['role'] === '1'){
  $result['User'] = $_SESSION['log'];
}

try{
  $Concert = new Concert($host,$dbname,$user,$pass);
  $Concert->connectDb();

  //参照処理
  $result_p['Promote'] = $Concert->findProById($result['User']['id']);
  $result_f = $Concert->FavolitePromoteAll($result['User']['id']);

  //削除処理
  if(isset($_GET['del'])){
    if($result['User']['role'] === '1'){
      $Concert->delete($_GET['del']);
      $Concert->deleteFavoliteConcert($_GET['del']);
      $Concert->goodDelete($_GET['del']);
    }
    $result = $Concert->findByConcert($result['User']['id']);
  }
  else{
    //ログインした主催者の登録した演奏会表示
    if($result['User']['role'] === '1'){
      $result = $Concert->findByConcert($result['User']['id']);
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

        $('.image').on('click',function(){
          $('.close').on('click',function(){
            $('.img_popup').css('width','');
            $('.img_popup').css('height','');
            $('.image').find('.popup').removeClass('show');
            $('.pin1').css('display','block');
          });
          if($(this).hasClass('on')){
            $(this).removeClass('on');
          }
          else{
            $(this).removeClass('on');
            var winW = $(window).width();
            var devW = 500;
            if(winW <= devW){
              $('.img_popup').css('width','70%');
            }
            else{
              $('.img_popup').css('width','40%');
            }
            $('.img_popup').css('height','80%');
            $('.pin1').css('display','none');
            $(this).find('.popup').addClass('show');
            $(this).addClass('on');
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

        <h2><?= $result_p['Promote']['name'];?>さんのマイページ</h2>
        <h2 class="pink">お気に入り数　<?= $result_f?></h2>
        <p></p>

        <p>登録演奏会一覧</p>

        <?php if(empty($result)):?>
          <p>　</p>
          <p>演奏会は登録されていません</p>
        <?php endif;?>

        <?php foreach ($result as $row):?>
          <img class="pin1" src="../image/image10.png" alt="ピン">
          <div id="sukima"></div>

          <div class="list">
            <div class="good_p">
              <?php if(empty($row['image'])):?>
                <img src="../image/image1.png" alt="宣伝画像">
              <?php else:?>
                <div class="image">
                  <img src="../html/image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                  <div class="popup">
                    <img class="img_popup" src="../html/image.php?image_name=<?= $row['image']?>" alt="宣伝画像">
                    <button class="close">閉じる</button>
                  </div>
                </div>
              <?php endif;?>
              <div class="flex">
                <div class="nice">
                  <img src="../image/icon.png" alt="good">
                  <p><?= $row['good_concert_id']?></p>
                </div>
                <img src="../image/icon-on.png" alt="fav">
                <p><?= $row['fav_concert_id']?></p>
              </div>
            </div>

            <div id="concert">
              <div class="concert_name">
                <p class="color"><?= $row['name'];?></p>
                <p><?= $_SESSION['log']['group_name'];?></p>
              </div>
              <div class="table">
                <div class="flex">
                  <h3>開催日</h3>
                  <p><?= $row['dated'];?></p>
                  <h3>開演時間</h3>
                  <p><?= $row['start'];?>〜</p>
                </div>
                <div class="flex" id="place">
                  <div class="flex">
                    <h3>開催場所</h3>
                    <p><?= $row['place'];?></p>
                  </div>
                  <div class="flex">
                    <h3>入場料</h3>
                    <p><?= $row['admission'];?>円</p>
                  </div>
                </div>
              </div>
            </div>

            <div id="botan_up">
              <div id="update">
                <p><a href="concert_insert.php?edit=<?= $row['id'];?>">変更</a></p>
              </div>
              <div id="botan">
                <div class="delet">
                  <p><a href="?del=<?= $row['id'];?>" onClick="if(!confirm('削除しますがよろしいですか？')) return false;">削除</a></p>
                </div>
                <div class="concert_detail">
                  <p><a href="../html/concert_detail.php?id=<?= $row['id'];?>">詳細</a></p>
                </div>
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
