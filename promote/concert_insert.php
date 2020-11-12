<?php
session_start();
require_once "../config/config.php";
require_once "../model/Concert.php";

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
//主催者ユーザの場合、ログインしたユーザ情報を設定する
if($_SESSION['log']['role'] === '1'){
  $result['User'] = $_SESSION['log'];
}
// session_destroy();

try{
  $Concert = new Concert($host,$dbname,$user,$pass);
  $Concert->connectDb();


  //更新処理
  if(isset($_GET['edit'])){
    if($_FILES){
      $storeDir = '../Upload_image/';
      $img_name = $_FILES['image']['name'];
      // $fileName = uniqid().$img_name;
      move_uploaded_file($_FILES['image']['tmp_name'],$storeDir.$img_name);
    }

    if($_POST){
      $message = $Concert->validate($_POST);
      if(empty($message['name']) && empty($message['dated']) && empty($message['place']) && empty($message['entrance']) && empty($message['start']) &&
      empty($message['number_p']) && empty($message['admission']) && empty($message['ticket']) && empty($message['program']) && empty($message['comment'])){
        $image = $_FILES['image']['name'];
        $Concert->edit($_POST,$image);
        header('Location:mypage.php');
        exit;
      }
    }
    $result['User'] = $Concert->findConcertsDetails($_GET['edit']);
  }
  else{
    //演奏会登録処理
    if($_POST){
      $message = $Concert->validate($_POST);
      if(empty($message['name']) && empty($message['dated']) && empty($message['place']) && empty($message['entrance']) && empty($message['start']) &&
      empty($message['number_p']) && empty($message['admission']) && empty($message['ticket']) && empty($message['program']) && empty($message['comment'])){
        $image = $_FILES['image']['name'];
        $Concert->add($_POST,$image);
        header('Location:mypage.php');
        exit;
      }
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
        <?php if(isset($_GET['edit'])):?>
          <p class="p_head">演奏会変更</p>
        <?php else:?>
          <p class="p_head">演奏会登録</p>
        <?php endif;?>

        <span>
          <p><?php if(isset($message['name'])) echo $message['name'];?></p>
          <p><?php if(isset($message['dated'])) echo $message['dated'];?></p>
          <p><?php if(isset($message['place'])) echo $message['place'];?></p>
          <p><?php if(isset($message['entrance'])) echo $message['entrance'];?></p>
          <p><?php if(isset($message['entrance'])) echo $message['entrance'];?></p>
          <p><?php if(isset($message['start'])) echo $message['start'];?></p>
          <p><?php if(isset($message['number'])) echo $message['number'];?></p>
          <p><?php if(isset($message['admission'])) echo $message['admission'];?></p>
          <p><?php if(isset($message['ticket'])) echo $message['ticket'];?></p>
          <p><?php if(isset($message['program'])) echo $message['program'];?></p>
          <p><?php if(isset($message['comment'])) echo $message['comment'];?></p>
          <?php if(!isset($_GET['edit'])):?>
          <p><?php if(isset($message)) echo '公演場所の都道府県を設定しなおしてください';?></p>
          <?php endif;?>
        </span>

        <form action="" method="post" enctype="multipart/form-data">
          <table>
            <th class="size14"><span>*</span>は必須項目です。</th>
            <tr>
              <th>公演名<span>*</span></th>
              <td><input type="text" name="name" value="<?php if(isset($_GET['edit'])){ echo $result['User']['name'];}else{if(isset($_POST['name'])){echo $_POST['name'];}}?>"></td>
            </tr>
            <tr>
              <th>公演日<span>*</span></th>
              <td><input type="date" name="dated" max="9999-12-31" value="<?php if(isset($_GET['edit'])){ echo $result['User']['dated'];}else{if(isset($_POST['dated'])){echo $_POST['dated'];}}?>"></td>
            </tr>
            <tr>
              <th>公演場所<span>*</span></th>
              <td><input type="text" name="place" value="<?php if(isset($_GET['edit'])){ echo $result['User']['place'];}else{if(isset($_POST['place'])){echo $_POST['place'];}}?>"></td>
            </tr>
            <tr>
              <th>公演場所の都道府県<span>*</span></th>
              <td>
                <select name="todouhuken_id">
                  <option value="1" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '1'){ echo 'selected';}}?>>北海道</option>
                  <option value="2" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '2'){ echo 'selected';}}?>>青森県</option>
                  <option value="3" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '3'){ echo 'selected';}}?>>岩手県</option>
                  <option value="4" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '4'){ echo 'selected';}}?>>宮城県</option>
                  <option value="5" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '5'){ echo 'selected';}}?>>秋田県</option>
                  <option value="6" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '6'){ echo 'selected';}}?>>山形県</option>
                  <option value="7" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '7'){ echo 'selected';}}?>>福島県</option>
                  <option value="8" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '8'){ echo 'selected';}}?>>茨城県</option>
                  <option value="9" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '9'){ echo 'selected';}}?>>栃木県</option>
                  <option value="10" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '10'){ echo 'selected';}}?>>群馬県</option>
                  <option value="11" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '11'){ echo 'selected';}}?>>埼玉県</option>
                  <option value="12" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '12'){ echo 'selected';}}?>>千葉県</option>
                  <option value="13" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '13'){ echo 'selected';}}?>>東京都</option>
                  <option value="14" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '14'){ echo 'selected';}}?>>神奈川県</option>
                  <option value="15" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '15'){ echo 'selected';}}?>>新潟県</option>
                  <option value="16" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '16'){ echo 'selected';}}?>>富山県</option>
                  <option value="17" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '17'){ echo 'selected';}}?>>石川県</option>
                  <option value="18" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '18'){ echo 'selected';}}?>>福井県</option>
                  <option value="19" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '19'){ echo 'selected';}}?>>山梨県</option>
                  <option value="20" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '20'){ echo 'selected';}}?>>長野県</option>
                  <option value="21" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '21'){ echo 'selected';}}?>>岐阜県</option>
                  <option value="22" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '22'){ echo 'selected';}}?>>静岡県</option>
                  <option value="23" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '23'){ echo 'selected';}}?>>愛知県</option>
                  <option value="24" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '24'){ echo 'selected';}}?>>三重県</option>
                  <option value="25" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '25'){ echo 'selected';}}?>>滋賀県</option>
                  <option value="26" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '26'){ echo 'selected';}}?>>京都府</option>
                  <option value="27" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '27'){ echo 'selected';}}?>>大阪府</option>
                  <option value="28" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '28'){ echo 'selected';}}?>>兵庫県</option>
                  <option value="29" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '29'){ echo 'selected';}}?>>奈良県</option>
                  <option value="30" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '30'){ echo 'selected';}}?>>和歌山県</option>
                  <option value="31" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '31'){ echo 'selected';}}?>>鳥取県</option>
                  <option value="32" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '32'){ echo 'selected';}}?>>島根県</option>
                  <option value="33" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '33'){ echo 'selected';}}?>>岡山県</option>
                  <option value="34" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '34'){ echo 'selected';}}?>>広島県</option>
                  <option value="35" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '35'){ echo 'selected';}}?>>山口県</option>
                  <option value="36" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '36'){ echo 'selected';}}?>>徳島県</option>
                  <option value="37" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '37'){ echo 'selected';}}?>>香川県</option>
                  <option value="38" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '38'){ echo 'selected';}}?>>愛媛県</option>
                  <option value="39" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '39'){ echo 'selected';}}?>>高知県</option>
                  <option value="40" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '40'){ echo 'selected';}}?>>福岡県</option>
                  <option value="41" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '41'){ echo 'selected';}}?>>佐賀県</option>
                  <option value="42" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '42'){ echo 'selected';}}?>>長崎県</option>
                  <option value="43" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '43'){ echo 'selected';}}?>>熊本県</option>
                  <option value="44" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '44'){ echo 'selected';}}?>>大分県</option>
                  <option value="45" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '45'){ echo 'selected';}}?>>宮崎県</option>
                  <option value="46" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '46'){ echo 'selected';}}?>>鹿児島県</option>
                  <option value="47" <?php if(isset($_GET['edit'])){ if($result['User']['todouhuken_id'] === '47'){ echo 'selected';}}?>>沖縄県</option>
                </select>
              </td>
            </tr>
            <tr>
              <th>開場時間<span>*</span></th>
              <td><input type="time" name="entrance" value="<?php if(isset($_GET['edit'])){ echo $result['User']['entrance'];}else{if(isset($_POST['entrance'])){echo $_POST['entrance'];}}?>"></td>
            </tr>
            <tr>
              <th>開演時間<span>*</span></th>
              <td><input type="time" name="start" value="<?php if(isset($_GET['edit'])){ echo $result['User']['start'];}else{if(isset($_POST['start'])){echo $_POST['start'];}}?>"></td>
            </tr>
            <tr>
              <th>演奏人数<span>*</span></th>
              <td><input type="number" name="number_p" min="0" value="<?php if(isset($_GET['edit'])){ echo $result['User']['number_p'];}else{if(isset($_POST['number_p'])){echo $_POST['number_p'];}}?>">人</td>
            </tr>
            <tr>
              <th>入場料<span>*</span></th>
              <td><input type="number" name="admission" min="0" value="<?php if(isset($_GET['edit'])){ echo $result['User']['admission'];}else{if(isset($_POST['admission'])){echo $_POST['admission'];}}?>">円</td>
            </tr>
            <tr>
              <th>チケット販売方法<span>*</span></th>
              <td><textarea type="text" name="ticket"><?php if(isset($_GET['edit'])){ echo $result['User']['ticket'];}else{if(isset($_POST['ticket'])){echo $_POST['ticket'];}}?></textarea></td>
            </tr>
            <tr>
              <th>主なプログラム<span>*</span></th>
              <td><textarea type="text" name="program"><?php if(isset($_GET['edit'])){ echo $result['User']['program'];}else{if(isset($_POST['program'])){echo $_POST['program'];}}?></textarea></td>
            </tr>
            <tr>
              <th>宣伝コメント<span>*</span></th>
              <td><textarea type="text" name="comment"><?php if(isset($_GET['edit'])){ echo $result['User']['comment'];}else{if(isset($_POST['comment'])){echo $_POST['comment'];}}?></textarea></td>
            </tr>
            <tr>
              <th>宣伝画像URL</th>
              <td><input type="file" name="image" accept="image/*" value="<?php if(isset($_GET['edit'])){ echo $result['User']['image'];}else{if(isset($_POST['image'])){echo $_POST['image'];}}?>"></td>
            </tr>
          </table>
          <input type="hidden" name="id" value="<?php if(isset($_GET['edit'])) echo $result['User']['id']?>">
          <input type="hidden" name="promote_id" value="<?php if($_SESSION['log']['role'] === '1') echo $_SESSION['log']['id']?>">
          <div class="botan">
            <?php if(isset($_GET['edit'])):?>
              <input class="submit" type="submit" name="submit" value="変更する">
            <?php else:?>
              <input class="submit" type="submit" name="submit" value="登　録">
            <?php endif;?>
          </div>
        </form>

      </div>
    </div>

    <?php
    require_once "../html/footer.php";
    ?>
  </body>
</html>
