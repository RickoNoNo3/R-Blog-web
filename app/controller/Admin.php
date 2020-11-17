<?php

namespace app\controller;

use app\BaseController;
use app\helper\AdminHelper;
use app\model\Dir;
use think\facade\Request;
use app\model\Article;
use think\response\View;
use think\Response;
use app\helper\BlogCoreHelper;

class Admin extends BaseController {
    public function index() {
        if (!AdminHelper::checkPswd()) {
            return response('<script>location.href="/";</script>', 200);
        }
        return view('', [
            'option'      => 'edit',
            'queryString' => '?type=1&id=0',
            'title'       => '编辑',
            'isMobile'    => Request::isMobile(),
        ]);
    }

    /**
     * edit?type|id
     *
     * @param int $type
     * @param int $id
     * @return Response|View
     */
    public function edit($type = 1, $id = 0) {
        if (!AdminHelper::checkPswd()) {
            return response('<script>location.href="/";</script>', 200);
        }
        switch ($type) {
            case 0:
                // 约定id=-1时是新建, 其他时候是编辑
                //
                // 注意文章的md不直接给(因为用html或js直接填充多行字符串, 格式错误风险很大)
                // 而是让文章编辑页面自行ajax获取
                if ($id != -1) {
                    // 找到父目录id, 文章的父目录id是linkLoc的倒数第[一]项
                    $article = new Article($id);
                    $linkCnt = count($article->linkLoc);
                    $parentId = $article->linkLoc[$linkCnt - 1]['id'];
                } else {
                    // 如果是新建, 要额外传一个parentId参数, 因为无法通过现有信息获取
                    $parentId = Request::param('parentId', 0);
                }
                return view('admin/edit/article_edit', [
                    'isMobile' => Request::isMobile(),
                    'id'       => $id,
                    'parentId' => $parentId,
                ]);
            case 1:
                // 找到父目录id, 目录的父目录id是linkLoc的倒数第[二]项
                $dir = new Dir($id);
                $linkCnt = count($dir->linkLoc);
                $parentId = 0;
                if ($linkCnt > 1) {
                    $parentId = $dir->linkLoc[$linkCnt - 2]['id'];
                }
                return view('admin/edit/dir_edit', [
                    'isMobile' => Request::isMobile(),
                    'dir'      => $dir,
                    'parentId' => $parentId,
                ]);
            default:
                return response('', 404);
        }
    }
}