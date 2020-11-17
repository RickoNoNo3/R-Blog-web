<?php

namespace app\model;

use app\helper\BlogCoreHelper;
use think\Exception;
use think\facade\View;

/**
 * Dir和Article的统一基类.
 *
 * 构造时直接拿到目录链(数组形式和html形式)以及type和id, 还有title, createdT, modifiedT.
 *
 * @package app\model
 */
abstract class Entity {
    public int $type, $id;
    public string $title = '', $createdT, $modifiedT;
    public array $linkLoc = [], $readRes = [];

    public function __construct(int $type, int $id) {
        $this->type = $type;
        $this->id = $id;
        $tmpLinkLoc = BlogCoreHelper::withJson(['link', $type, $id]);
        if ($tmpLinkLoc !== false) {
            $this->linkLoc = $tmpLinkLoc['link'];
        }
        $tmpReadRes = BlogCoreHelper::withJson(['read', $type, $id]);
        if ($tmpReadRes !== false) {
            $this->readRes = $tmpReadRes;
            $this->title = $this->readRes['title'];
            $this->createdT = $this->readRes['createdT'];
            $this->modifiedT = $this->readRes['modifiedT'];
        } else {
            throw new Exception('entity binding error');
        }
    }

    /**
     * 将linkLoc转换成超链接的形式
     *
     * @return string
     */
    public function getLinkLoc(): string {
        $tmpLinkLoc = $this->linkLoc;
        foreach ($tmpLinkLoc as $i => $v) {
            $tmpLinkLoc[$i] = View::display('<a href="{$href}">{$text}</a>', [
                'href' => '/blog/dir/' . $v['id'],
                'text' => $v['title'],
            ]);
        }
        return implode(" > ", $tmpLinkLoc);
    }
}