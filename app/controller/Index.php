<?php

namespace app\controller;

use app\BaseController;
use app\helper\GlobalHelper;

class Index extends BaseController {
    public function index() {
        return view('', [
            'info'  => GlobalHelper::getInfo('<a href="/">主页</a>'),
            'intro' =>
                <<<intro
<h1>建博客的一些心得</h1>
<p>欢迎你来我的博客！</p>
<p>早在刚上大学的时候就听学长说过，如果我有一个自己的博客，而且经常在里面写东西的话，对自己会很有帮助。但我又不是很想在cnblog或者csdn或者github种种平台上搞个博客就那么随便用，最终还是决定自己搭一个。</p>
<p>然而，由于人类的本质是鸽子（不是）的一部分原因，搭建这个博客的时候我已经大三了。毕竟也已经大三了，前端的部分学的差不多了，后端的Nginx和Golang也算会用，所以搭建博客不是一个特别棘手的问题，更像是把自己的所学（包括软件工程的内容）逐步尝试应用到实际中来，作为一个技术经验的积累。</p>
<p>当然写文章也是很重要的。搭好了博客总得写点什么东西，这样才叫充实。说到写文章，我在这个博客里写的东西应该会比较杂，而且这个博客又是在不成熟的技术条件下搭建的，分类功能难免有些缺陷。自己的专业水平又不是和大犇们一样强，写的东西可能也没什么水平。我会尽量克服这些问题，也请见谅。</p>
<p>玩的开心~！</p>
<p>2019.9.9</p>
intro
            ,
        ]);
    }

}
