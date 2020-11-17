<?php

namespace app\controller;

use app\BaseController;
use app\helper\ToolHelper;

class Tool extends BaseController {

    public function dirSelector() {
        return view('', [
            'list' => ToolHelper::getFlatList(),
        ]);
    }
}