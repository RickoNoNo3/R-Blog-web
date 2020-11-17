<?php

namespace app\controller\api;

use app\BaseController;
use app\helper\ToolHelper;

class Tool extends BaseController {

    public function dirSelector($showArticle = 0) {
        if ($showArticle != 0) $showArticle = 1;
        return json(ToolHelper::getFlatList($showArticle === 1));
    }
}