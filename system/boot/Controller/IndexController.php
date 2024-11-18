<?php
/** .-------------------------------------------------------------------
 * |  Software: [HNDK HeadcountSystem]
 * |-------------------------------------------------------------------
 * |    Author: 王迈新
 * | Copyright (c) 2024, www.minitegi.xyz. All Rights Reserved.
 * '-------------------------------------------------------------------*/


namespace Boot\Controller;

use Boot\Controller\Controller;

class IndexController extends Controller {
    public function Index() {
        var_dump(Controller::getenv('A'));
    }


}
?>