<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

$blog_core_loc = "C:/Users/rick/Programs/0.go/blog/built/blog_core/blog.exe";
$CDN = '/';
return [
	// 内容分发
	'cdn'             => $CDN,

    // 页面样式
    'title'           => "R崽的博客",
    'style'           => $CDN . 'css/myStyles.css',
    'style_mobile'    => $CDN . 'css/myStyles.mob.css',
    'style_highlight' => $CDN . 'css/highlight/darcula.my.css',
    'bg_img'          => $CDN . 'img/bg.jpg',
    'icons'           => [
        0 => 'description',
        1 => 'folder',
    ],

    // 基础设置
    'admin_pswd'      => 'tydhc199963',

    // core相关
    'blog_core_loc'   => $blog_core_loc,
    'blog_core_dir'   => str_replace('blog.exe', '', $blog_core_loc),
];
