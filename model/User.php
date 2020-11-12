<?php
require_once "DB.php";

class User extends DB{

  //一般ログイン処理
  public function login($mail){
    $sql = "SELECT*FROM users WHERE email = :email AND delete_flg = :delete_flg";
    $stmt = $this->connect->prepare($sql);
    $params = array(':email' => $mail,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //一般ユーザ一部参照
  public function findById($id){
    $sql = 'SELECT * FROM users WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //一般ユーザ登録処理
  public function addOrdinary($arr){
    $sql = 'INSERT INTO users(name,kana,email,password,role,created_at,delete_flg) VALUES(:name,:kana,:email,:password,:role,:created_at,:delete_flg)';
    $stmt = $this->connect-> prepare($sql);
    $pass = password_hash($arr['password'],PASSWORD_DEFAULT);
    $params = array(
      ':name'=>$arr['name'],
      ':kana'=>$arr['kana'],
      ':email'=>$arr['email'],
      ':password'=>$pass,
      ':role'=>0,
      ':created_at'=>date('Y-m-d H:i:s'),
      ':delete_flg'=>'FALSE'
    );
    $stmt->execute($params);
  }

  //演奏会お気に入り参照処理
  public function findFavoliteAll($id){
    $sql = 'SELECT f.id AS favolite_id,c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,c.place,';
    $sql .= 'TIME_FORMAT(c.start,"%H:%m") AS start,c.admission,c.image,p.group_name,p.id AS promote_id ';
    $sql .= 'FROM favolite_concerts f ';
    $sql .= 'JOIN concerts c ON f.concert_id = c.id ';
    $sql .= 'JOIN users u ON f.user_id = u.id ';
    $sql .= 'JOIN promots p ON c.promote_id = p.id ';
    $sql .= 'WHERE u.id = :id AND p.delete_flg = :delete_flg';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //団体お気に入り参照処理
  public function findFavolitePromote($id){
    $sql = 'SELECT f.id,p.name, p.group_name,p.group_detail,COUNT(c.promote_id) AS count_id,c.promote_id ';
    $sql .= 'FROM favolite_promots f JOIN promots p ON f.promote_id = p.id ';
    $sql .= 'JOIN users u ON f.user_id = u.id JOIN concerts c ON p.id = c.promote_id ';
    $sql .= 'WHERE u.id = :id AND p.delete_flg = :delete_flg GROUP BY f.id,p.name, p.group_name,p.group_detail';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //演奏会お気に入り削除処理
  public function deleteFavoliteConcert($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM favolite_concerts WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    }
  }

  //団体お気に入り削除処理
  public function deleteFavolitePromote($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM favolite_promots WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    }
  }

  //演奏会よかった登録処理
  public function addGoodConcert($arr){
    $sql = 'INSERT INTO good_concerts(concert_id,user_id,created_at) VALUES (:concert_id,:user_id,:created_at)';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':concert_id'=>$arr['concert_id'],
      ':user_id'=>$arr['user_id'],
      ':created_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //演奏会よかった登録の有無
  public function goodConcerts($id,$user_id){
    $sql = 'SELECT * FROM good_concerts WHERE concert_id = :concert_id AND user_id = :user_id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':concert_id' => $id,':user_id' => $user_id);
    $stmt->execute($params);
    $result = $stmt->rowCount();
    return $result;
  }

  //よかった登録削除処理
  public function goodDelete($id = null,$user_id){
    if(isset($id)) {
      $sql = "DELETE FROM good_concerts WHERE concert_id = :id AND user_id = :user_id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id' => $id,':user_id' => $user_id);
      $stmt->execute($params);
    }
  }

  //一般登録情報更新処理
  public function edit($arr){
    $sql = 'UPDATE users SET name = :name,kana = :kana,email = :email,password = :password,updated_at = :updated_at WHERE id = :id';
    $stmt = $this->connect-> prepare($sql);
    $pass = password_hash(h($arr['password']),PASSWORD_DEFAULT);
    $params = array(
      ':id'=>h($arr['id']),
      ':name'=>h($arr['name']),
      ':kana'=>h($arr['kana']),
      ':email'=>h($arr['email']),
      ':password'=>$pass,
      ':updated_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //一般ユーザ登録削除処理
  public function delete($id = null){
    if(isset($id)) {
      $sql = "UPDATE users SET delete_flg = :delete_flg WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=> $id,':delete_flg'=>'TRUE');
      $stmt->execute($params);
    }
  }

  //パスワードリセットメール送信
  public function forget($mail){
    $sql = 'SELECT * FROM users WHERE email = :email';
    $stmt = $this->connect->prepare($sql);
    $params = array(':email' => $mail);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //URLにつけたidの列を参照
  public function riset($id){
    $sql = 'SELECT * FROM users WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //URLに送ったランダムの文字列を登録
  public function token($token,$id){
    $sql = 'UPDATE users SET token = :token WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':token' => $token,':id' => $id);
    $stmt->execute($params);
  }

  //パスワードの更新
  public function passriset($password,$id){
    $sql = 'UPDATE users SET password = :password WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $pass = password_hash($password,PASSWORD_DEFAULT);
    $params = array(':password' => $pass,':id' => $id);
    $stmt->execute($params);
  }

  //入力チェック
  public function validate($arr){
    $message = array();
    //ユーザ名
    if(empty($arr['name'])){
      $message['name'] = '氏名を入力してください。';
    }
    else{
      if(mb_strlen($arr['name']) > 10){
        $message['name'] = '氏名を10文字以内で入力してください。';
      }
    }
    //ユーザフリガナ
    if(empty($arr['kana'])){
      $message['kana'] = 'フリガナを入力してください。';
    }
    else{
      if(mb_strlen($arr['kana']) > 10){
        $message['kana'] = 'フリガナを10文字以内で入力してください。';
      }
    }
    //メールアドレス
    if(empty($arr['email'])){
      $message['email'] = 'メールアドレスを入力してください。';
    }
    else{
      if(!filter_var($arr['email'],FILTER_VALIDATE_EMAIL)){
        $message['email'] = 'メールアドレスが正しくありません。';
      }
    }
    //パスワード
    if(empty($arr['password'])){
      $message['password'] = 'パスワードを入力してください。';
    }
    else{
      if(!preg_match("/^[a-zA-Z0-9]+$/", $arr['password'])){
          $message['password'] = 'パスワードは英字大文字、半角英数字で入力してください。';
      }
    }
    return $message;
  }
}
