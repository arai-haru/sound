<?php
require_once "../config/config.php";
require_once "../model/User.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try{
    $User = new User($host,$dbname,$user,$pass);
    $User->connectDb();

    if(!empty($result)){
    }
    else{
      //お気に入り登録処理
      if($_POST){
        $User->addGoodConcert($_POST);
      }
    }
  }
  catch(PDOException $e){
    echo 'エラー'.$e->getMessage();
  }
}
