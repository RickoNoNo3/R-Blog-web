<?php

namespace app\helper;

class AdminHelper {
    public static function setPswd(string $pswd) {
        session('pswd', $pswd);
    }

    public static function checkPswd(): bool {
        if (session('?pswd') === false) {
            return false;
        }
        return session('pswd') === config('blog.admin_pswd');
    }
}