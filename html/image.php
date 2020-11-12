<?php
require_once "../config/config.php";
require_once "../model/Concert.php";

if(isset($_GET["image_name"]) && $_GET["image_name"] !== ""){
      $image = $_GET["image_name"];
      $img_dir = '../Upload_image/'.$image;
    }

    header("Content-Type: multipart/form-data;");
    readfile ($img_dir);
