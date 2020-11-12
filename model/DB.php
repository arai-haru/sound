<?php
class DB{
  //プロパティ
  private $host;
  private $dbname;
  private $user;
  private $pass;
  protected $connect;

  //コンストラクタ
  public function __construct($host,$dbname,$user,$pass){
    $this->host = $host;
    $this->dbname = $dbname;
    $this->user = $user;
    $this->pass = $pass;
  }

  //メソッド
  public function connectDb(){
    $this->connect = new PDO('mysql:dbname='.$this->dbname.';host='.$this->host.';charset=utf8',$this->user,$this->pass);
    if(!$this->connect){
      echo '接続エラー';
      die();
    }
  }
}
