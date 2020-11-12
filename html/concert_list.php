<?php
session_start();
require_once "../config/config.php";
require_once "../model/Concert.php";

function h($post){
  return htmlspecialchars($post,ENT_QUOTES,'UTF-8');
}

// if(isset($_SESSION['log'])){
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
// }

try{
  $Concert = new Concert($host,$dbname,$user,$pass);
  $Concert->connectDb();

  //削除処理
  if(isset($_GET['del'])){
    if($_SESSION['log']['role'] === '2'){
      $Concert->delet($_GET['del']);
      $Concert->findConcertsAll();
    }
  }

  //検索処理
  if($_POST){
    if($_POST['radio'] == '未公演'){
      $search = h($_POST['search']);
      $result = $Concert->search('%'.$search.'%');
    }
    else{
      $search = h($_POST['search']);
      $result = $Concert->searchEnd('%'.$search.'%');
    }
  }
  else{
    if(isset($_SESSION['log'])){
      //演奏会一覧表示
      $result = $Concert->findConcertsAll();
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

        $('.favolit').on('click',function(){
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
          $(this).css('width','0');
          $(this).css('background-color','#F3FFD8');
          $(this).addClass('on_heat');
          $(this).html('<img src="../image/icon-on.png" alt="アイコン">');
          $(this).addClass('on');
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
    if($_SESSION['log']['role'] === '2'){
      require_once "header.php";//role権限でheaderの表示を変える
    }
    elseif($_SESSION['log']['role'] === '0'){
      require_once "../ordinary/header.php";//role権限でheaderの表示を変える
    }

    ?>

    <div class="login">
      <div class="wrap">
        <p>演奏会一覧</p>

        <form id="search" action="" method="post">
          <input type="text" name="search" value="<?php if($_POST) echo $_POST['search']?>">
          <input type="submit" name="submit" value="検索する"><br>
          <input type="radio" name="radio" value="未公演"checked>今後公演予定
          <input type="radio" name="radio" value="公演済み"<?php if($_POST){if($_POST['radio'] == '公演済み'){ echo 'checked';}}?>>公演済み
        </form>

        <?php if(empty($result)):?>
          <p>検索条件に一致するものはありません</p>
        <?php endif;?>

        <?php foreach ($result as $row):?>
          <?php
          //お気に入りされているかの判定
          $result_fc = $Concert->favolitConcerts($row['id'],$_SESSION['log']['id']);
          ?>
          <img class="pin1" src="../image/image10.png" alt="ピン">
          <div id="sukima"></div>
          <div class="list">
            <div id="img">
              <?php if(empty($row['image'])):?>
                <img src="../image/image1.png" alt="宣伝画像">
              <?php else:?>
                <div class="image">
                  <img src="image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                  <div class="popup">
                    <img class="img_popup" src="image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                    <button class="close">閉じる</button>
                  </div>
                </div>
              <?php endif;?>
            </div>

            <div id="concert">
              <p class="color"><?= $row['name']?></p>
              <p><?= $row['group_name']?></p>
              <div class="table">
                <div class="flex" id="day">
                  <div class="flex">
                    <h3>開催日</h3>
                    <p><?= $row['dated']?></p>
                  </div>
                  <div class="flex">
                    <h3>開演時間</h3>
                    <p><?= $row['start']?>〜</p>
                  </div>
                </div>
                <div class="flex" id="place">
                  <div class="flex">
                    <h3>開催場所</h3>
                    <p><?= $row['place']?></p>
                  </div>
                  <div class="flex">
                    <h3>入場料</h3>
                    <p><?= $row['admission']?>円</p>
                  </div>
                </div>
              </div>
            </div>

            <div id="botan">
              <?php if($_SESSION['log']['role'] === '0'):?>
                <?php if($result_fc == '1'):?>
                  <div class="on_heat">
                    <img src="../image/icon-on.png" alt="アイコン">
                  </div>
                <?php else:?>
                  <input type="hidden" class="concert_id" name="concert_id" value="<?= $row['id'];?>">
                  <input type="hidden" class="user_id" name="user_id" value="<?= $_SESSION['log']['id'];?>">
                  <div class="favolit">
                    <div class="off_heat">
                      <img src="../image/icon-off.png" alt="アイコン">
                    </div>
                    <p>お気に入り</p>
                    <p><?= $row['fav_concert_id'];?></p>
                  </div>
                <?php endif;?>
              <?php endif;?>
              <?php if($_SESSION['log']['role'] === '2'):?>
                <div class="delet">
                  <p><a href="?del=<?= $row['id'];?>" onClick="if(!confirm('削除しますがよろしいですか？')) return false;">削除</a></p>
                </div>
              <?php endif;?>
              <div class="concert_detail">
                <p><a href="../html/concert_detail.php?id=<?= $row['id'];?>">詳細</a></p>
              </div>
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
