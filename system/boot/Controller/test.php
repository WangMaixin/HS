<?php
$host = '47.121.199.238';
 $databaseuser = 'hs';
 $password = 'hshndk20241115';
 $databasename = 'hs';
 // 连接数据库
 $db = mysqli_connect($host,$databaseuser,$password,$databasename);
//  判断数据库连接是否成功
 if( $db->connect_errno <> 0){
     die('数据库系统正在维护中');
 }
 // 定义与数据库传输的编码
 $db->query("SET NAMES UTF8");

var_dump($db);
?>