<?php

$dsn  = 'mysql:host=localhost;dbname=elfert_cms';
$user = 'root';
$pass = '';
$options = array(
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'
);

try{

    $conn = new PDO($dsn,$user,$pass,$options);

}catch (exception $e){

    echo '<div class="aler alert-danger role="alert" style="text-align: center;color: red;font-size:30px;"><b>هناك خطأ في الاتصال بقاعده البيانات</b></div>';

}

