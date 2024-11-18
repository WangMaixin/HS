<?php
/** .-------------------------------------------------------------------
 * |  Software: [HNDK HeadcountSystem]
 * |-------------------------------------------------------------------
 * |    Author: 王迈新
 * | Copyright (c) 2024, www.minitegi.xyz. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace Boot\Controller;

use Boot\Controller\Controller;

class DbController extends Controller {
    public static function connect($sql) {

        return self::query($sql);
    }


    /**
     * query database
     * @var SQL
     */
    private static function query($SQL) {
        $query_result = self::link()->query($SQL);
        $json_data = json_encode($query_result);

        return $json_data;
    }

    /**
     * connect MySQL to Sofnet Heyuan Server
     * @method Connect Database
     */
    private static function link() {
        $host = Controller::getenv('SERVER_IP');
        $databaseuser = Controller::getenv('MYSQL_USER');
        $password = Controller::getenv('MYSQL_PASSWORD');
        $databasename = Controller::getenv('MYSQL_DBNAME');
        // 连接数据库
        $db = mysqli_connect($host,$databaseuser,$password,$databasename);
        // 判断数据库连接是否成功
        if( $db->connect_errno <> 0){
            die('数据库系统正在维护中');
        }
        // 定义与数据库传输的编码
        $db->query("SET NAMES UTF8");

        return $db;
    }
}
?>