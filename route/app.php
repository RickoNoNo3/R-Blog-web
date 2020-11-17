<?php
use think\facade\Route;

Route::any('index.php/', static function () { return response()->code(404); });

Route::redirect('blog/', 'dir/', 301);
Route::get('blog/dir/[:id]', 'blog/dir');
Route::get('blog/article/[:id]', 'blog/article');
