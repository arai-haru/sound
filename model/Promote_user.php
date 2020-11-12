<?php
require_once "DB.php";

class Promote_user extends DB{

  //主催者ログイン処理
  public function login($mail){
    $sql = "SELECT*FROM promots WHERE email = :email AND delete_flg = :delete_flg";
    $stmt = $this->connect->prepare($sql);
    $params = array(':email' => $mail,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //管理者マイページ登録団体参照処理
  public function findManage(){
    $sql = 'SELECT p.id,p.name,p.email,p.group_name,p.group_detail,COUNT(c.promote_id) AS count_concert ';
    $sql .= 'FROM promots p JOIN concerts c ON p.id = c.promote_id WHERE p.delete_flg = :delete_flg ';
    $sql .= 'GROUP BY  p.id,p.name,p.email,p.group_name,p.group_detail';
    $stmt = $this->connect->prepare($sql);
    $params = array(':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //主催者ユーザ一部参照処理
  public function findById($id){
    $sql = 'SELECT * FROM promots WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //主催者登録処理
  public function addPromote($arr){
    $sql = 'INSERT INTO promots(name,kana,email,password,role,group_name,group_class,group_detail,created_at,delete_flg) VALUES(:name,:kana,:email,:password,:role,:group_name,:group_class,:group_detail,:created_at,:delete_flg)';
    $stmt = $this->connect-> prepare($sql);
    $pass = password_hash($arr['password'],PASSWORD_DEFAULT);
    $params = array(
      ':name'=>$arr['name'],
      ':kana'=>$arr['kana'],
      ':email'=>$arr['email'],
      ':password'=>$pass,
      ':role'=>1,
      ':group_name'=>$arr['group_name'],
      ':group_class'=>$arr['group_class'],
      ':group_detail'=>$arr['group_detail'],
      ':created_at'=>date('Y-m-d H:i:s'),
      ':delete_flg'=>'FALSE'
    );
    $stmt->execute($params);
  }

  //主催者ユーザ登録更新処理
  public function edit($arr){
    $sql = 'UPDATE promots SET name = :name,kana = :kana,email = :email,password = :password,group_name = :group_name,group_class = :group_class,group_detail = :group_detail,updated_at = :updated_at WHERE id = :id';
    $stmt = $this->connect-> prepare($sql);
    $pass = password_hash(h($arr['password']),PASSWORD_DEFAULT);
    $params = array(
      ':id' => h($arr['id']),
      ':name'=>h($arr['name']),
      ':kana'=>h($arr['kana']),
      ':email'=>h($arr['email']),
      ':password'=>$pass,
      ':group_name'=>h($arr['group_name']),
      ':group_class'=>h($arr['group_class']),
      ':group_detail'=>h($arr['group_detail']),
      ':updated_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //主催者ユーザ登録削除処理
  public function delete($id = null){
    if(isset($id)) {
      $sql = "UPDATE promots SET delete_flg = :delete_flg WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id'=> $id,':delete_flg'=> 'TRUE');
      $stmt->execute($params);
    }
  }

  //パスワードリセットメール送信
  public function forget($mail){
    $sql = 'SELECT * FROM promots WHERE email = :email';
    $stmt = $this->connect->prepare($sql);
    $params = array(':email' => $mail);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //URLにつけたidの列を参照
  public function riset($id){
    $sql = 'SELECT * FROM promots WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //URLに送ったランダムの文字列を登録
  public function token($token,$id){
    $sql = 'UPDATE promots SET token = :token WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':token' => $token,':id' => $id);
    $stmt->execute($params);
  }

  //パスワードの更新
  public function passriset($password,$id){
    $sql = 'UPDATE promots SET password = :password WHERE id = :id';
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
    //ユーザ団体名
    if(empty($arr['group_name'])){
      $message['group_name'] = '団体名を入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['group_name'])){
        $message['group_name'] = '団体名はひらがな又は漢字で入力してください。';
      }
    }
    //ユーザ団体詳細
    if(empty($arr['group_detail'])){
      $message['group_detail'] = '団体説明を入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['group_detail'])){
        $message['group_detail'] = '団体説明はひらがな又は漢字で入力してください。';
      }
    }
    return $message;
  }

}
