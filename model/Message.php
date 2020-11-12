<?php
require_once "DB.php";

class Message extends DB{

  //参照処理
  public function findAll($id){
    $sql = 'SELECT name,messages,DATE_FORMAT(created_at,"%Y年%m月%d日") AS created_at FROM message WHERE promote_id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //登録処理
  public function add($arr){
    $sql = 'INSERT INTO message(user_id,name,promote_id,messages,created_at) VALUES (:user_id,:name,:promote_id,:messages,:created_at)';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'=>h($arr['name']),
      ':user_id'=>$arr['user_id'],
      ':promote_id'=>h($arr['promote_id']),
      ':messages'=>h($arr['messages']),
      ':created_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //入力チェック
  public function validate($arr){
    $messages = array();
    //ユーザ名
    if(empty($arr['name'])){
      $messages['name'] = 'ニックネームを入力してください。';
    }
    else{
      if(mb_strlen($arr['name']) > 10){
        $messages['name'] = 'ニックネームを10文字以内で入力してください。';
      }
    }
    //メッセージ
    if(empty($arr['messages'])){
      $messages['messages'] = 'メッセージを入力してください。';
    }
    elseif(mb_strlen($arr['messages']) > 100){
      $messages['messages'] = 'メッセージは100文字以内で入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['messages'])){
        $messages['messages'] = 'メッセージはひらがな又は漢字で入力してください。';
      }
    }
    return $messages;
  }
}
