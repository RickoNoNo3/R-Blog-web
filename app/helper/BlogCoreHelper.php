<?php


namespace app\helper\blog_core;


use JsonException;
use think\Exception;
use think\facade\Log;
use Throwable;

class BlogCoreHelper
{
    /**
     * @param array $args 调用参数, 首个必须是命令名称
     * @param string|null $stdin Blog Core 的 Stdin, 可为空
     * @return false|string Blog Core 的 Stdout
     */
    public static function call(array $args, string $stdin = null)
    {
        // 开启进程
        $process = proc_open(array_merge(
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
                return $output;
            }
        }
        return false;
    }

    public static function withJson(array $args, string $stdin = null)
    {
        try {
            if (($res = self::call($args, $stdin)) === false) {
                throw new Exception('bad command');
            }
            $res = json_decode($res, true, 5120, JSON_THROW_ON_ERROR);
            if ($res === false) {
                throw new JsonException('bad json');
            }
        } catch (Throwable $e) {
            return false;
        }
        return $res;
    }

    public static function withCount(array $args, string $stdin = null)
    {
        try {
            if (($res = self::call($args, $stdin)) === false) {
                throw new Exception('bad command');
            }
            $res = (int)$res;
        } catch (Throwable $e) {
            return false;
        }
        return ['cnt' => $res];
    }

    public function withId(array $args, string $stdin = null)
    {
        try {
            if (($res = self::call($args, $stdin)) === false) {
                throw new Exception('bad command');
            }
            $res = (int)$res;
        } catch (Throwable $e) {
            return false;
        }
        return ['id' => $res];
    }
}