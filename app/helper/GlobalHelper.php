<?php

namespace app\helper;

use app\model\Entity;
use think\facade\Request;
use Throwable;

class GlobalHelper {
    /**
     * @param Entity $entity
     * @return array
     */
    public static function getInfoForBlog(Entity $entity): array {
        return self::getInfo($entity->getLinkLoc(), $entity->title);
    }

    /**
     * 获取页面(主要是global页面)所需的各种信息
     *
     * @param string $loc
     * @param string $title
     * @return array
     */
    public static function getInfo(string $loc = '?', string $title = ''): array {
        $isMobile = Request::isMobile();
        // 根据UA判断是不是特殊浏览器
        // IE: trident, 老Edge: edge          这两个必用兼容模式
        // safari: safari && !chrome          这个暂时用兼容模式
        // firefox: firefox, chrome: chrome   这两个用普通模式
        $isSpecial = false;
        try {
            $ua = strtolower(Request::header('user-agent'));
            if (preg_match('/(edge|trident)/i', $ua)) {
                $isSpecial = true;
            } else if (preg_match('/(firefox|chrome)/i', $ua)) {
                $isSpecial = false;
            } else if (preg_match('/safari/i', $ua)) {
                $isSpecial = false;//true;
            }
        } catch (Throwable $e) {
            $isSpecial = true;
        }

        return [
            'title'     => self::getPageTitle($title),
            'isMobile'  => $isMobile,
            'isSpecial' => $isSpecial,
            'loc'       => $loc,
        ];
    }

    /**
     * PageTitle return s a title text
     *
     * @param string $title     the title name of the page
     * @param bool   $addSuffix whether to add title as suffix
     * @return string
     */
    public static function getPageTitle(string $title = '', bool $addSuffix = true): string {
        if (trim($title) === '') {
            return config('blog.title');
        }
        if ($addSuffix) {
            return $title . ' - ' . config('blog.title');
        }

        return $title;
    }
}