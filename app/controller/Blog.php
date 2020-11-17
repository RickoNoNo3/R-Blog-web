<?php

namespace app\controller;

use app\BaseController;
use app\helper\GlobalHelper;
use app\model\Article;
use app\model\Dir;

class Blog extends BaseController {
    /**
     * 目录页面
     *
     * @param string $id
     * @return string
     */
    public function dir($id = '0') {
        $dir = new Dir($id);
        return view('', [
            'info' => GlobalHelper::getInfoForBlog($dir),
            'dir'  => $dir,
        ]);
    }

    /**
     * 文章页面
     *
     * @param string $id
     * @return string
     */
    public function article($id = '0') {
        $article = new Article($id);
        return view('', [
            'info'    => GlobalHelper::getInfoForBlog($article),
            'article' => $article,
        ]);
    }

}