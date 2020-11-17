<?php

namespace app\controller\api;

use app\BaseController;
use app\helper\AdminHelper;

class Admin extends BaseController {
    public function login($pswd = 'nopswd') {
        if (!isset($pswd)) {
            return response('', 400);
        }
        AdminHelper::setPswd($pswd);
        return json(['text' => 'ok']);
    }

    // TODO: dir页编辑, article页编辑, 关于页/关于页编辑
}