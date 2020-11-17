R-Blog-web
===============

R-Blog的Web服务器封装. 要求环境为php7.4+.

## 提供的功能

1. 网页博客(基于[R-Blog Core](https://github.com/rickonono3/R-Blog))
2. Web API, 供其他客户端连接网络博客使用, 连接地址前缀为`/api.core/`, 可用API如下(功能与同名Core的参数命令相同):

| API Path | 请求参数   | 请求体                                   | 响应(contentType\|content)               | 管理员 | 备注 |
| -------- | ---------- | ---------------------------------------- | ---------------------------------------- | ------ | ---- |
| draw     | id         | -                                        | json\|{"number": 成功时的id}             | 是     |      |
| drawCore | -          | (text)需要渲染的markdown文本             | json\|{"text": 渲染好的html}             | -      |      |
| new      | type&dirId | (text)需要新建的目录名文本或markdown文本 | json\|{"number": 成功时的id}             | 是     |      |
| edit     | type&id    | (text)修改后的目录名文本或markdown文本   | json\|{"number": 成功时的id}             | 是     |      |
| remove   |            | (text)多行, 每行为`type id`              | json\|{"number": 成功删除的数量}         | 是     |      |
| move     | dirId      | (text)多行, 每行为`type id`              | json\|{"number": 成功移动的数量}         | 是     |      |
| drag     | id         | -                                        | json\|{"text": 该文章的原始markdown文本} | 是     |      |
| read     | type&id    | -                                        | json\|BlogCore的原始输出                 | -      |      |
| link     | type&id    | -                                        | json\|BlogCore的原始输出                 | -      |      |



## 使用方法

1. 安装php7.4+和composer

2. 在项目根目录执行`composer update`

3. 酌情修改配置文件(`config/blog.php`)

4. 在项目根目录执行`php think run [-p <port>]`, 即可启动服务器

## 版本对照

| 封装版本(R-Blog-web) | 对应内核版本(R-Blog) |
| -------------------- | -------------------- |
| 0.1.0                | 0.4.1                |

## 鸣谢

ThinkPHP 6