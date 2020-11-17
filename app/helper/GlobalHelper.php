<?php


namespace app\service;

use app\model\Entity;
use think\facade\Request;

class GlobalHelper
{
    /**
     * PageTitle return s a title text
     * @param string $title the title name of the page
     * @param bool $addSuffix whether to add title as suffix
     * @return string
     */
    public function getPageTitle(string $title = '', bool $addSuffix = true): string
    {
        if (trim($title) === '') {
            return config('blog.title');
        }
        if ($addSuffix) {
            return $title . ' - ' . config('blog.title');
        }

        return $title;
    }

    public function getInfoForBlog(Entity $entity): array
    {
        return $this->getInfo($entity->getLinkLoc(), $entity->title);
    }

    public function getInfo(string $loc = '?', string $title = ''): array
    {
        $is_mobile = Request::isMobile();
        $is_ie = !$is_mobile && Request::header('user-agent')
            && preg_match("/(" . implode('|', ['edge', 'trident']) . ")/i", strtolower(Request::header('user-agent')));
        return [
            'title' => $this->getPageTitle($title),
            'is_mobile' => $is_mobile,
            'is_ie' => $is_ie,
            'loc' => $loc,
        ];
    }
}