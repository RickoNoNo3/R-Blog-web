<?php

namespace app\controller\api;

use app\BaseController;
use app\helper\AdminHelper;
use app\helper\BlogCoreHelper;
use app\helper\GlobalHelper;
use think\facade\Request;
use think\Response;
use think\response\Json;

/**
 * api.core
 *
 * 每一个接口的返回值,
 * 要么是BlogCore自己的json,
 * 要么是{"num": x}, {"text": x}中的一个.
 *
 * @package app\controller\api
 */
class Core extends BaseController {
    public function draw(int $id) {
        return $this->callHelperByAdmin('number', ['draw', $id], false);
    }

    public function drawCore() {
        return $this->callHelper('text', ['drawCore']);
    }

    public function new(int $type, int $dirId = 0) {
        return $this->callHelperByAdmin('number', ['new', $type, $dirId]);
    }

    public function edit(int $type, int $id) {
        return $this->callHelperByAdmin('number', ['edit', $type, $id]);
    }

    public function remove() {
        return $this->callHelperByAdmin('number', ['remove']);
    }

    public function move(int $dirId = 0) {
        return $this->callHelperByAdmin('number', ['move', $dirId]);
    }

    public function drag(int $id) {
        return $this->callHelperByAdmin('text', ['drag', $id], false);
    }

    public function read(int $type = 1, int $id = 0) {
        return $this->callHelper('json', ['read', $type, $id], false);
    }

    public function link(int $type = 1, int $id = 0) {
        return $this->callHelper('json', ['link', $type, $id], false);
    }

    /**
     * 使用前置管理员权限验证, 调用callHelper
     *
     * @param string      $fmt            返回值格式, 可取 [json, number, text]
     * @param array       $args           命令行参数, 第0个必须是命令名
     * @param bool        $autoGetInput 是否自动获取请求体数据作为content
     * @param string|null $content        手动指定的content
     * @return Response|Json 响应结果
     */
    private function callHelperByAdmin(string $fmt, array $args, bool $autoGetInput = true, string $content = null) {
        if (!AdminHelper::checkPswd()) {
            return response('', 404);
        }
        return $this->callHelper($fmt, $args, $autoGetInput, $content);
    }

    /**
     * BlogCoreHelper的二次封装, 以最简形式进行BlogCore的调用和返回
     *
     * @param string      $fmt            返回值格式, 可取 [json, number, text]
     * @param array       $args           命令行参数, 第0个必须是命令名
     * @param bool        $autoGetInput 是否自动获取请求体数据作为content
     * @param string|null $content        手动指定的content
     * @return Response|Json 响应结果
     */
    private function callHelper(string $fmt, array $args, bool $autoGetInput = true, string $content = null) {
        if ($autoGetInput) {
            $content = trim(Request::getContent());
            if ($content === '') {
                return response('', 400);
            }
        }
        switch ($fmt) {
            case 'json':
                $res = BlogCoreHelper::withJson($args, $content);
                break;
            case 'number':
                $res = BlogCoreHelper::withNumber($args, $content);
                break;
            case 'text':
                $res = BlogCoreHelper::withText($args, $content);
                break;
            default:
                return response('', 404);
        }
        return ($res !== false) ? json($res) : response('', 500);
    }

}
