<?php

namespace app\helper;

class ToolHelper {

    /**
     * 获取一个平板化的(带深度标识的)全博客内容列表
     *
     * @param bool $showArticle 是否添加文章到列表里, 否就是仅目录
     * @return array
     */
    public static function getFlatList(bool $showArticle = false) {
        $list = [];
        self::getFlatList_inner($list, 0, 0, $showArticle);
        return $list;
    }

    /**
     * 先序遍历做平板化
     *
     * @param array $list        引用, 存储结果的列表
     * @param int   $dirId       当前栈顶dirId
     * @param int   $depth       当前栈顶高度(递归深度)
     * @param bool  $showArticle 是否添加文章和文件到列表里, 不添加就是仅目录
     */
    private static function getFlatList_inner(array &$list, int $dirId, int $depth, bool $showArticle) {
        $readRes = BlogCoreHelper::withJson(['read', 1, $dirId]);
        if (isset($readRes)) {
            // 添加自身
            $list[] = [
                'type'  => 1,
                'id'    => $dirId,
                'title' => $readRes['title'],
                'depth' => $depth,
            ];
            foreach ($readRes['list'] as $i => $vo) {
                // 递归进入本目录下的目录
                // 如果showArticle, 添加本目录下的文件
                if ($vo['type'] == 1) {
                    self::getFlatList_inner($list, $vo['id'], $depth + 1, $showArticle);
                } else if ($showArticle) {
                    $list[] = [
                        'type'  => $vo['type'],
                        'id'    => $vo['id'],
                        'title' => $vo['text'],
                        'depth' => $depth + 1,
                    ];
                }
            }
        }
    }

    // TODO 除了平板, 还应该有一种树形结构的直接数据
    /*public static function getTreeList(bool $showArticle = false) {

    }

    private static function getTreeList_inner(int $dirId = 0) {

    }*/
}