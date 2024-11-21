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

        // data filter
        self::checkContent('UserIC', $UserIC)['status'] ? true : self::filter('短位码重复！');
        self::dormitoryCheck($dormitoryNumber, $buildingNumber)['status'] ? true : self::filter('寝室已绑定！');
        // 后续开发添加班级和导员认证


        $db_result = self::insertDB($UserIC, $dormitoryNumber, $buildingNumber, $dormitoriesNumber, $grade, $classNumber, $AdminIC);
        $session_result = self::generateSESSION($db_result['UUID']);

        // 测试代码
        // sleep(3);

        echo json_encode($db_result);
    }

    /**
     * 高危方法->此方法会直接操作数据库
     * API: page/set/quit.html
     */
    public static function quit() {
        $UserIC = $_GET['IC'];

        $db_result = self::deleteDB($UserIC);
        $result = $db_result ? self::deleteSESSION($UserIC) : 'flase';

        echo json_encode($result);
    }

    /**
     * PUBLIC API
     * get SESSION
     * @return UUID Session
     */
    public static function getSESSION() {
        session_start();

        $session_name = $_GET['session_name'];

        if(isset($_SESSION[$session_name])) {
            $result = array(
                'code' => true,
                'UUID' => $_SESSION[$session_name]
            );
        }else {
            $result = array(
                'code' => false,
                'UUID' => 'null'
            );
        }
        echo json_encode($result);
    }

    /**
     * PUBLIC API
     * get data base UUID
     * @return UUID DB
     */
    private static function getDBUUID() {
        $UserIC = $_POST['UserIC'];

        $SQL = "
        SELECT * FROM Identification WHERE UserIC = ". $UserIC ."
        ";

        return $SQL;
    }


    /**
     * data filter
     * @method filter
     */
    private static function filter($text) {
        echo json_encode(array(
            'code' => false,
            'msg' => $text
        ));
        die();
    }

    /**
     * Dormitory plagiarism check
     * @method check
     */
    private static function dormitoryCheck($dormitoryNumber, $buildingNumber) {
        $SQL = "
        SELECT count(*) FROM Identification WHERE DormitoryCode = ". $dormitoryNumber ." AND BuildingNumber = ". $buildingNumber .";
        ";
        $query = DbController::implementQuery($SQL);
        
        $row = $query->fetch_array( MYSQLI_ASSOC )['count(*)'];
        
        $row_result = $row > 0 ? false : true;

        return array(
            'rows' => $row,
            'status' => $row_result
        );
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

    /**
     * delete data base
     * @method Delete DB
     */
    private static function deleteDB($UserIC) {
        
        $SQL = "
        DELETE FROM Identification WHERE UserIC = ". $UserIC .";
        ";
        $result = DbController::connect($SQL);

        return $result;
    }

    /**
     * delect SESSION
     * @method Delete
     */
    private static function deleteSESSION($UserIC) {
        session_start();
        unset($_SESSION['UUID']);
        
        $result = '';
        if(!isset($_SESSION['UUID'])) {
            $result = true;
        }else {
            $result = false;
        }

        return $result;
    }

    /**
     * check Content
     * @method check
     */
    private static function checkContent($Condition, $Data) {
        $result = DbController::checkPlagiarism('Identification', $Condition, $Data);

        return $result;
    }
}
?>