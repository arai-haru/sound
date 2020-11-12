<?php
session_start();
require_once "../config/config.php";
require_once "../model/Concert.php";


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
  $Concert = new Concert($host,$dbname,$user,$pass);
  $Concert->connectDb();

  if($_GET['id']){
    //選択した演奏会表示
    $result['User'] = $Concert->findConcertsDetail($_GET['id']);

    //団体登録演奏会数
    $result_p = $Concert->findPromoteConcerts($result['User']['promote_id']);

    //公演がお気に入りに登録されているかの判断
    $result_f['User'] = $Concert->favolitConcerts($_GET['id'],$_SESSION['log']['id']);

    //団体がお気に入りに登録されているかの判断
    $result_fp['User'] = $Concert->FavolitePromoteId($result['User']['promote_id'],$_SESSION['log']['id']);
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
    <link rel="stylesheet" type="text/css" href="../css/detail.css">
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

      $('.favolit_f').on('click',function(){
        var $_t = $( this ).parent();
        var favolit = $_t.find('.concert_id');
        var concert = favolit.val();
        var user = $(".user_id").val();
          // alert(concert+'/'+user);
        $(this).addClass('icon_on');
        $(this).css('background-color','#F3FFD8');
        $(this).html('<img src="../image/icon-on.png" alt="アイコン">');
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

      $('.favolit_p').on('click',function(){
        var $_t = $( this ).parent();
        var favolit = $_t.find('.promote_id');
        var promote = favolit.val();
        var user = $(".user_id").val();
          // alert(concert+'/'+user);
        $(this).addClass('icon_on');
        $(this).css('background-color','#F3FFD8');
        $(this).html('<img src="../image/icon-on.png" alt="アイコン">');
        $.ajax({
          url:'ajax_promote.php',
          type:'POST',
          data:{
            'promote_id':promote,
            'user_id':user,
          }
        })
        .done(function(data){

        })
        .fail(function(msg) {
          alert("通信失敗");
        });
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
        <img id="pin1" src="../image/image10.png" alt="ピン">
        <img id="pin2" src="../image/image10-2.png" alt="ピン">
        <p class="size18">演奏会詳細</p>

        <div id="board">
          <?php foreach ($result as $row):?>
            <?php if($_SESSION['log']['role'] === '0'):?>
              <?php if($result_f['User'] == '1'):?>
                <div class="icon_on">
                  <img src="../image/icon-on.png" alt="アイコン">
                </div>
              <?php else:?>
                <div id="botan">
                  <input type="hidden" class="concert_id" name="concert_id" value="<?= $row['id'];?>">
                  <input type="hidden" class="user_id" name="user_id" value="<?= $_SESSION['log']['id'];?>">
                  <div class="favolit_f">
                    <div class="off">
                      <img src="../image/icon-off.png" alt="アイコン">
                    </div>
                    <p>お気に入り登録</p>
                  </div>
                </div>
              <?php endif;?>
            <?php endif;?>

            <div id="date">
              <div class="flex">
                <?php if(empty($row['image'])):?>
                  <img src="../image/image1.png" alt="宣伝画像">
                <?php else:?>
                  <img src="image.php?image_name=<?= $row['image'];?>" alt="宣伝画像">
                <?php endif;?>
                <div id="title">
                  <p id="size18_left"><?= $row['name'];?></p>
                  <div id="day" class="flex">
                    <h3>公演日</h3>
                    <p><?= $row['dated'];?></p>
                    <div id="time" class="flex">
                      <h3>開場時間</h3>
                      <p><?= $row['entrance'];?>〜</p>
                      <h3>開演時間</h3>
                      <p><?= $row['start'];?>〜</p>
                    </div>
                  </div>
                  <h3>公演場所</h3>
                  <p><?= $row['place'];?></p>
                  <div id="place" class="flex">
                    <h3>入場料</h3>
                    <p><?= $row['admission'];?>円</p>
                    <h3>演奏人数</h3>
                    <p><?= $row['number_p'];?>人</p>
                  </div>
                </div>
              </div>

              <div class="flex" id="program">
                <div class="border" id="wid50">
                  <h3>主なプログラム</h3>
                  <p><?= $row['program'];?></p>
                </div>
                <div class="border" id="wid50">
                  <h3>宣伝コメント</h3>
                  <p><?= $row['comment'];?></p>
                </div>
              </div>
              <div class="border" id="ticket">
                <h3>チケット販売方法</h3>
                <p><?= $row['ticket'];?></p>
              </div>
            </div>

            <div id="list_de">
              <?php if($_SESSION['log']['role'] === '0'):?>
                <?php if($result_fp['User'] == '1'):?>
                  <div class="icon_on" id="botan">
                    <img src="../image/icon-on.png" alt="アイコン">
                  </div>
                <?php else:?>
                  <div id="botan">
                    <input type="hidden" class="promote_id" name="promote_id" value="<?= $row['promote_id'];?>">
                    <input type="hidden" class="user_id" name="user_id" value="<?= $_SESSION['log']['id'];?>">
                    <div class="favolit_p">
                      <div class="off">
                        <img src="../image/icon-off.png" alt="アイコン">
                      </div>
                      <p>お気に入り登録</p>
                    </div>
                  </div>
                <?php endif;?>
              <?php endif;?>
              <div id="promote">
                <div id="group">
                  <p class="size18"><a href="message.php?promote_id=<?= $row['promote_id'];?>"><?= $result['User']['group_name']?></a></p>
                  <p class="pink">演奏会登録数<br><?= $result_p?></p>
                </div>
                <div class="break">
                  <h3>団体詳細</h3>
                  <p><?= $result['User']['group_detail']?></p>
                </div>
              </div>
            </div>
          <?php endforeach;?>
        </div>
      </div>
    </div>

    <?php
    require_once "footer.php";
    ?>
  </body>
</html>
