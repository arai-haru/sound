<?php
require_once "DB.php";

class Concert extends DB{

  //演奏会一覧参照処理
  public function findConcertsAll(){
    $sql = 'SELECT c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,';
    $sql .= 'c.place,TIME_FORMAT(c.start,"%H:%m") AS start,c.admission,c.image,';
    $sql .= 'p.group_name,COUNT(fc.concert_id) AS fav_concert_id FROM concerts c ';
    $sql .= 'JOIN promots p ON c.promote_id = p.id LEFT JOIN favolite_concerts fc ON fc.concert_id = c.id ';
    $sql .= 'WHERE p.delete_flg = :delete_flg AND dated BETWEEN now()+1 AND DATE_ADD(NOW(), INTERVAL 2 MONTH) ';
    $sql .= 'GROUP BY c.id,c.name,c.dated,c.place,c.start,c.admission,c.image,p.group_name ';
    $sql .= 'ORDER BY c.dated';
    $stmt = $this->connect->prepare($sql);
    $params = array(':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //主催者登録演奏会一覧表示処理
  public function findByConcert($id){
    $sql = 'SELECT c.id,c.name,DATE_FORMAT(c.dated,"%m月%d日") AS dated,c.place,';
    $sql .= 'TIME_FORMAT(c.start,"%H:%m") AS start,c.admission,c.image,';
    $sql .= 'COUNT(fc.concert_id) AS fav_concert_id,';
    $sql .= '(SELECT COUNT(g.concert_id) FROM good_concerts g WHERE g.concert_id = c.id) AS good_concert_id ';
    $sql .= 'FROM concerts c LEFT JOIN favolite_concerts fc ON fc.concert_id = c.id ';
    $sql .= 'WHERE promote_id = :promote_id ';
    $sql .= 'GROUP BY c.id,c.name,c.dated,c.place,c.start,c.admission,c.image ';
    $sql .= 'ORDER BY c.dated DESC';
    $stmt = $this->connect->prepare($sql);
    $params = array(':promote_id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //主催者ユーザ一部参照処理
  public function findProById($id){
    $sql = 'SELECT * FROM promots WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //更新の表示処理
  public function findConcertsDetails($id){
    $sql = 'SELECT * FROM concerts WHERE id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //団体演奏会登録数
  public function findPromoteConcerts($id){
    $sql = 'SELECT * FROM concerts WHERE promote_id = :promote_id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':promote_id' => $id);
    $stmt->execute($params);
    $result = $stmt->rowCount();
    return $result;
  }

  //演奏会詳細表示処理
  public function findConcertsDetail($id){
    $sql = 'SELECT c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,';
    $sql .= 'c.place,c.todouhuken_id,TIME_FORMAT(c.entrance,"%H:%m") AS entrance,';
    $sql .= 'TIME_FORMAT(c.start,"%H:%m") AS start,c.number_p,c.admission,c.ticket,c.program,';
    $sql .= 'c.comment,c.image,p.id AS promote_id,p.group_name,p.group_detail ';
    $sql .= 'FROM concerts c JOIN promots p ON c.promote_id = p.id WHERE c.id = :id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':id' => $id);
    $stmt->execute($params);
    $result = $stmt->fetch();
    return $result;
  }

  //演奏会登録処理
  public function add($arr,$image){
    $sql = 'INSERT INTO concerts(name,dated,place,todouhuken_id,entrance,start,number_p,admission,ticket,program,comment,image,created_at,promote_id)';
    $sql .= ' VALUES (:name,:dated,:place,:todouhuken_id,:entrance,:start,:number_p,:admission,:ticket,:program,:comment,:image,:created_at,:promote_id)';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':name'=>h($arr['name']),
      ':dated'=>h($arr['dated']),
      ':place'=>h($arr['place']),
      ':todouhuken_id'=>h($arr['todouhuken_id']),
      ':entrance'=>h($arr['entrance']),
      ':start'=>h($arr['start']),
      ':number_p'=>h($arr['number_p']),
      ':admission'=>h($arr['admission']),
      ':ticket'=>h($arr['ticket']),
      ':program'=>h($arr['program']),
      ':comment'=>h($arr['comment']),
      ':program'=>h($arr['program']),
      ':image'=>$image,
      ':created_at'=>date('Y-m-d H:i:s'),
      ':promote_id'=>h($arr['promote_id'])
    );
    $stmt->execute($params);
  }

  //演奏会お気に入り登録数
  public function favolitConcerts($id,$user_id){
    $sql = 'SELECT * FROM favolite_concerts WHERE concert_id = :concert_id AND user_id = :user_id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':concert_id' => $id,':user_id' => $user_id);
    $stmt->execute($params);
    $result = $stmt->rowCount();
    return $result;
  }

  //演奏会お気に入り登録処理
  public function addFavoliteConcert($arr){
    $sql = 'INSERT INTO favolite_concerts(concert_id,user_id,created_at) VALUES (:concert_id,:user_id,:created_at)';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':concert_id'=>$arr['concert_id'],
      ':user_id'=>$arr['user_id'],
      ':created_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //団体お気に入り参照処理
  public function FavolitePromoteId($id,$user_id){
    $sql = 'SELECT * FROM favolite_promots WHERE promote_id = :promote_id AND user_id = :user_id';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':promote_id' => $id,
      ':user_id' => $user_id
    );
    $stmt->execute($params);
    $result = $stmt->rowCount();
    return $result;
  }

  //団体お気に入り合計参照処理
  public function FavolitePromoteAll($id){
    $sql = 'SELECT * FROM favolite_promots WHERE promote_id = :promote_id';
    $stmt = $this->connect->prepare($sql);
    $params = array(':promote_id' => $id,);
    $stmt->execute($params);
    $result = $stmt->rowCount();
    return $result;
  }

  //団体お気に入り登録処理
  public function addFavolitePromote($arr){
    $sql = 'INSERT INTO favolite_promots(promote_id,user_id,created_at) VALUES (:promote_id,:user_id,:created_at)';
    $stmt = $this->connect->prepare($sql);
    $params = array(
      ':promote_id'=>$arr['promote_id'],
      ':user_id'=>$arr['user_id'],
      ':created_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //演奏会更新処理
  public function edit($arr,$image){
    $sql = 'UPDATE concerts SET name = :name,dated = :dated,place = :place,';
    $sql .= 'todouhuken_id = :todouhuken_id,entrance = :entrance,start = :start,number_p = :number_p,';
    $sql .= 'admission = :admission,ticket = :ticket,program = :program,comment = :comment,image = :image,updated_at = :updated_at WHERE id = :id';
    $stmt = $this->connect-> prepare($sql);
    $params = array(
      ':id' => h($arr['id']),
      ':name'=>h($arr['name']),
      ':dated'=>h($arr['dated']),
      ':place'=>h($arr['place']),
      ':todouhuken_id'=>h($arr['todouhuken_id']),
      ':entrance'=>h($arr['entrance']),
      ':start'=>h($arr['start']),
      ':number_p'=>h($arr['number_p']),
      ':admission'=>h($arr['admission']),
      ':ticket'=>h($arr['ticket']),
      ':program'=>h($arr['program']),
      ':comment'=>h($arr['comment']),
      ':program'=>h($arr['program']),
      ':image'=>$image,
      ':updated_at'=>date('Y-m-d H:i:s')
    );
    $stmt->execute($params);
  }

  //登録演奏会削除処理
  public function delete($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM concerts WHERE id = :id";
      $stmt = $this->connect->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    }
  }

  //演奏会お気に入り削除処理
  public function deleteFavoliteConcert($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM favolite_concerts WHERE concert_id = :id";
      $stmt = $this->connect->prepare($sql);
      $stmt->bindParam(':id', $id);
      $stmt->execute();
    }
  }

  //よかった登録削除処理
  public function goodDelete($id = null){
    if(isset($id)) {
      $sql = "DELETE FROM good_concerts WHERE concert_id = :id";
      $stmt = $this->connect->prepare($sql);
      $params = array(':id' => $id);
      $stmt->execute($params);
    }
  }

  //演奏会検索処理
  public function search($post){
    $sql = 'SELECT c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,';
    $sql .= 'c.place,t.todouhuken_name,TIME_FORMAT(c.start,"%H:%m") AS start,c.image,';
    $sql .= 'c.admission,p.group_name,p.group_class,COUNT(fc.concert_id) AS fav_concert_id FROM concerts c ';
    $sql .= 'JOIN promots p ON c.promote_id = p.id JOIN todouhuken t ON t.id = c.todouhuken_id ';
    $sql .= 'LEFT JOIN favolite_concerts fc ON fc.concert_id = c.id ';
    $sql .= 'WHERE (c.name LIKE :post OR dated LIKE :post ';
    $sql .= 'OR c.place LIKE :post OR t.todouhuken_name LIKE :post ';
    $sql .= 'OR start LIKE :post OR p.group_name LIKE :post ';
    $sql .= 'OR p.group_class LIKE :post) AND p.delete_flg = :delete_flg AND dated >= now()+1 ';
    $sql .= 'GROUP BY c.id,c.name,c.dated,c.place,c.start,c.admission,c.image,p.group_name ';
    $sql .= 'ORDER BY c.dated';
    $stmt = $this->connect->prepare($sql);
    $params = array(':post' => $post,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }

  //演奏会検索公演済み処理
  public function searchEnd($post){
    $sql = 'SELECT c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,';
    $sql .= 'c.place,t.todouhuken_name,TIME_FORMAT(c.start,"%H:%m") AS start,c.image,';
    $sql .= 'c.admission,p.group_name,p.group_class,COUNT(fc.concert_id) AS fav_concert_id FROM concerts c ';
    $sql .= 'JOIN promots p ON c.promote_id = p.id JOIN todouhuken t ON t.id = c.todouhuken_id ';
    $sql .= 'LEFT JOIN favolite_concerts fc ON fc.concert_id = c.id ';
    $sql .= 'WHERE (c.name LIKE :post OR dated LIKE :post ';
    $sql .= 'OR c.place LIKE :post OR t.todouhuken_name LIKE :post ';
    $sql .= 'OR start LIKE :post OR p.group_name LIKE :post ';
    $sql .= 'OR p.group_class LIKE :post) AND p.delete_flg = :delete_flg AND dated <= now()+1 ';
    $sql .= 'GROUP BY c.id,c.name,c.dated,c.place,c.start,c.admission,c.image,p.group_name ';
    $sql .= 'ORDER BY c.dated';
    $stmt = $this->connect->prepare($sql);
    $params = array(':post' => $post,':delete_flg' => 'FALSE');
    $stmt->execute($params);
    $result = $stmt->fetchAll();
    return $result;
  }
  // SELECT c.id,c.name,DATE_FORMAT(c.dated,"%Y年%m月%d日") AS dated,
  // c.place,TIME_FORMAT(c.start,"%H:%m") AS start,c.admission,c.image,
  // p.group_name,COUNT(fc.concert_id) AS fav_concert_id FROM concerts c
  // JOIN promots p ON c.promote_id = p.id
  // LEFT JOIN favolite_concerts fc ON fc.concert_id = c.id
  // WHERE p.delete_flg = 'FALSE' AND dated >= now()+1
  // GROUP BY c.id,c.name,c.dated,c.place,c.start,c.admission,
  // c.image,p.group_name ORDER BY c.dated

  //入力チェック
  public function validate($arr){
    $message = array();
    //演奏会名
    if(empty($arr['name'])){
      $message['name'] = '演奏会名を入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['name'])){
        $message['name'] = '演奏会名はひらがな又は漢字、半角英字で入力してください。';
      }
    }
    //日付
    if(empty($arr['dated'])){
      $message['dated'] = '公演日を入力してください。';
    }
    //公演場所
    if(empty($arr['place'])){
      $message['place'] = '公演場所を入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['place'])){
        $message['place'] = '公演場所はひらがな又は漢字で入力してください。';
      }
    }
    //開場時間
    if(empty($arr['entrance'])){
      $message['entrance'] = '開場時間を入力してください。';
    }
    //開演時間
    if(empty($arr['start'])){
      $message['start'] = '開演時間を入力してください。';
    }
    //演奏人数
    if(empty($arr['number_p'])){
      $message['number_p'] = '演奏人数を入力してください。';
    }
    //入場料
    if(empty($arr['admission'])){
      $message['admission'] = '入場料を入力してください。';
    }
    //チケット販売方法
    if(empty($arr['ticket'])){
      $message['ticket'] = 'チケット販売方法を入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~%+$\"\'\*\^\,]+$/", $arr['ticket'])){
        $message['ticket'] = 'チケット販売方法はひらがな又は漢字で入力してください。';
      }
    }
    //プログラム
    if(empty($arr['program'])){
      $message['program'] = '主なプログラムを入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>:;=&~@%+$\"\'\*\^\,_-]+$/", $arr['program'])){
        $message['program'] = 'プログラムはひらがな又は漢字で入力してください。';
      }
    }
    //宣伝コメント
    if(empty($arr['comment'])){
      $message['comment'] = '宣伝コメントを入力してください。';
    }
    else{
      if(!preg_match("/^[^!#<>;=&~@%+$\"\'\*\^\,_]+$/", $arr['comment'])){
        $message['comment'] = 'コメントはひらがな又は漢字で入力してください。';
      }
    }
    return $message;
  }

}
