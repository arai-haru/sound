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


try{
  $User = new User($host,$dbname,$user,$pass);
  $User->connectDb();

  //参照処理
  $result_u['User'] = $User->findById($_SESSION['log']['id']);

  if(isset($_GET['del'])){
    if($result_u['User']['role'] === '0'){
      $User->deleteFavoliteConcert($_GET['del']);
    }
  }

  if($result_u['User']['role'] === '0'){
    $result = $User->findFavoliteAll($result_u['User']['id']);
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

        $('.good').on('click',function(){
          var $_t = $( this ).parent();
          var favolit = $_t.find('.concert_id');
          var concert = favolit.val();
          var user = $(".user_id").val();
            // alert(concert+'/'+user);
          $.ajax({
            url:'ajax.php',
            type:'POST',
            data:{
              'concert_id':concert,
              'user_id':user,
            }
          })
          .done(function(data){

          })
          .fail(function(msg) {
            alert("通信失敗");
          });
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
        <h2><?= $result_u['User']['name']?>さんマイページ</h2>
        <!-- <a href="../musical_instrument/top.php">楽器紹介</a> -->

        <p>お気に入り演奏会一覧</p>
        <p id="alert"><br><span>＊</span>演奏会情報の下にある[よかった]ボタンは演奏会に行かれた後に押してください（強制ではありません）</p>

        <?php if(empty($result)):?>
          <p>　</p>
          <p>お気に入りは登録されていません</p>
        <?php endif;?>

        <?php foreach ($result as $row):?>
          <?php
          //よかったを押したかの有無
          $Good = $User->goodConcerts($row['id'],$_SESSION['log']['id']);
          ?>
          <img class="pin1" src="../image/image10.png" alt="ピン">
          <div id="sukima"></div>
          <div class="list">
            <div id="good">
              <?php if(empty($row['image'])):?>
                <img src="../image/image1.png" alt="宣伝画像">
              <?php else:?>
                <div class="image">
                  <img src="../html/image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                  <div class="popup">
                    <img class="img_popup" src="../html/image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                    <button class="close">閉じる</button>
                  </div>
                </div>
              <?php endif;?>
              <?php if($Good != '1'):?>
                <input type="hidden" class="concert_id" name="concert_id" value="<?= $row['id'];?>">
                <input type="hidden" class="user_id" name="user_id" value="<?= $_SESSION['log']['id'];?>">
                <div class="good">
                  <div class="off_heat">
                    <img src="../image/icon.png" alt="アイコン">
                  </div>
                  <p><a href="message_insert.php?promote_id=<?= $row['promote_id'];?>">よかった</a></p>
                </div>
              <?php else:?>
                <div class="off_heat" style="margin: 5px">
                  <img src="../image/good_on.png" alt="アイコン">
                </div>
              <?php endif;?>
            </div>
            <div id="concert">
              <p class="color"><?= $row['name'];?></p>
              <p><?= $row['group_name'];?></p>
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

            <div id="botan">
              <div class="delet">
                <p><a href="?del=<?= $row['favolite_id'];?>" onClick="if(!confirm('お気に入りから削除しますがよろしいですか？')) return false;">削除</a></p>
              </div>
              <div class="concert_detail">
                <p><a href="../html/concert_detail.php?id=<?= $row['id'];?>">詳細</a></p>
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
