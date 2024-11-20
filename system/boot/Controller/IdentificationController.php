<?php
/** .-------------------------------------------------------------------
 * |  Software: [HNDK HeadcountSystem]
 * |-------------------------------------------------------------------
 * | Author: 王迈新
 * | Copyright (c) 2024, www.minitegi.xyz. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace Boot\Controller;

use Boot\Controller\Controller;
use Boot\Controller\DbController;

/**
 * 说明：此对象针对用户端一次访问绑定寝室后无需每次访问都重新认证进行开发
 */
class IdentificationController extends Controller {

    /**
     * API: index.html=>identification->run()->submit();
     */
    public static function login() {

        // 获取数据
        $UserIC = $_POST['UserIC'];
        $dormitoryNumber = $_POST['dormitoryNumber'];
        $buildingNumber = $_POST['buildingNumber'];
        $dormitoriesNumber = $_POST['dormitoriesNumber'];
        $grade = $_POST['grade'];
        $classNumber = $_POST['classNumber'];
        $AdminIC = $_POST['AdminIC'];

        // 后续版本后台也需要进行数据过滤

        $db_result = self::insertDB($UserIC, $dormitoryNumber, $buildingNumber, $dormitoriesNumber, $grade, $classNumber, $AdminIC);

        $session_result = self::generateSESSION($db_result['UUID']);
        echo json_encode($db_result);
        // echo $UserIC;

        // var_dump(date("Y-m-d H:i:s", self::getSystemTime()));
    }

    /**
     * generate Universally Unique Identifier
     * @return UUID
     */
    private static function generateUUID() {
        $UUID = sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );

        return $UUID;
    }

    /**
     * generate SESSION
     * @method SESSION
     */
    private static function generateSESSION($UUID) {
        session_start();
        $result = $_SESSION['UUID'] = $UUID;

        return $result; 
    }

    /**
     * Time
     * @return time
     */
    private static function getSystemTime() {
        // 后续将进行时间精准代码升级
        return time();
    }

    /**
     * insert Unique identification data
     * @method Insert
     * Sheet: HS(Sofnet Heyuan):Identification
     */
    private static function insertDB($UserIC,$DormitoryCode,$BuildingNumber,$NumberOfDormitories,$Grade,$ClassCode,$GuildIC) {
        $UUID = self::generateUUID();

        $SQL = "
        INSERT INTO Identification (SessionCode, Time, UserIC, DormitoryCode, BuildingNumber, NumberOfDormitories, Grade, ClassCode, AdminIC, LastRecordTime)
        VALUES
        ('". $UUID ."', '". self::getSystemTime() ."', '". $UserIC ."', '". $DormitoryCode ."', '". $BuildingNumber ."', '". $NumberOfDormitories ."', '". $Grade ."', '". $ClassCode ."', '". $GuildIC ."', '". self::getSystemTime() ."')
        ";
        $result = DbController::connect($SQL);

        return array(
            'code' => $result,
            'UUID' => $UUID
        );
    }
}
?>