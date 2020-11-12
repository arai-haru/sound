<?php
require_once "../config/config.php";
require_once "../model/Concert.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  try{
    $Concert = new Concert($host,$dbname,$user,$pass);
    $Concert->connectDb();


    if(!empty($result)){
    }
    else{
      //お気に入り登録処理
      if($_POST){
        $Concert->addFavoliteConcert($_POST);

      }
    }
  }
  catch(PDOException $e){
    echo 'エラー'.$e->getMessage();
  }
}
