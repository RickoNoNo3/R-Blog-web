<?php

namespace app\helper;

use think\facade\Log;
use Throwable;

class BlogCoreHelper {
    /**
     * 调用BlogCore命令行程序
     *
     * @param array       $args  调用参数, 首个必须是命令名称
     * @param string|null $stdin Blog Core 的 Stdin, 可为空
     * @return false|string Blog Core 的 Stdout, 失败为false
     */
    public static function call(array $args, string $stdin = null) {
        $res = false;
        try {
            // 开启进程
            $process = proc_open(
                array_merge(
                    [config('blog.blog_core_loc')],
                    $args,
                ), [
                    0 => ['pipe', 'r'],
                    1 => ['pipe', 'w'],
                ], $pipes, config('blog.blog_core_dir'));

            if (is_resource($process)) {
                // 发送stdin
                if (isset($stdin)) {
                    fwrite($pipes[0], $stdin);
                    fclose($pipes[0]);
                }

                // 接收stdout
                $success = false;
                $output = stream_get_contents($pipes[1]);
                if (!isset($output) || $output === false) { // 无有效输出
                    Log::error('Fail to call blog core.');
                } else if ($output === '-1') { // 输出提示错误
                    Log::warning('Made a error blog core calling. with args [' . implode(', ', $args) . ']');
                } else { // 正确输出
                    Log::info('Called and got: ' . $output);
                    $success = true;
                }
                fclose($pipes[1]);

                // 关闭进程, 准备返回
                proc_close($process);
                if ($success && trim($output) !== '-1') {
                    $res = $output;
                }
            }
        } catch (Throwable $e) {
            /* do nothing */
        }
        return $res;
    }

    /**
     * 以预期返回值为json的命令调用BlogCore
     *
     * @param array       $args  调用参数, 首个必须是命令名称
     * @param string|null $stdin Blog Core 的 Stdin, 可为空
     * @return false|mixed json对象, 失败为false
     */
    public static function withJson(array $args, string $stdin = null) {
        if (($res = self::call($args, $stdin)) !== false) {
            $res = json_decode($res, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                return $res;
            }
        }
        return false;
    }

    /**
     * 以预期返回值为数字的命令调用BlogCore
     *
     * @param array       $args  调用参数, 首个必须是命令名称
     * @param string|null $stdin Blog Core 的 Stdin, 可为空
     * @return false|int[] num键值对, 失败为false
     */
    public static function withNumber(array $args, string $stdin = null) {
        $res = self::call($args, $stdin);
        return $res !== false ? ['num' => (int)$res] : false;
    }

    /**
     * 以预期返回值为字符串的命令调用BlogCore
     *
     * @param array       $args  调用参数, 首个必须是命令名称
     * @param string|null $stdin Blog Core 的 Stdin, 可为空
     * @return false|string[] text键值对, 失败为false
     */
    public static function withText(array $args, string $stdin = null) {
        $res = self::call($args, $stdin);
        return $res !== false ? ['text' => $res] : false;
    }
}