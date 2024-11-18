<?php
/** .-------------------------------------------------------------------
 * |  Software: [HNDK HeadcountSystem]
 * |-------------------------------------------------------------------
 * |    Author: 王迈新
 * | Copyright (c) 2024, www.minitegi.xyz. All Rights Reserved.
 * '-------------------------------------------------------------------*/

namespace Boot\Controller;

use Boot\Controller\Controller;
use Boot\Controller\DbController;

class IdentificationController extends Controller {
    public static function set() {

        var_dump(self::insertDB(self::getID()));
    }

    /**
     * get Unique identifier
     * @return ID
     */
    private static function getID() {
        $Id = md5(uniqid());

        return $Id;
    }

    private static function insertDB($id) {
        $SQL = "INSERT INTO Identification (Session) VALUES ('$id')";
        $result = DbController::connect($SQL);

        return $result;
    }
}
?>