// alert(navigator.userAgent);

var BlogPopWindows = [];

var BlogPage = {
    Ajax: {
        /**
         * 封装的Ajax函数
         */
        call: function (url, data, success, error, complete, dataType, contentType, method) {
            $.ajax({
                url: url,
                method: method ? method : 'post',
                contentType: contentType ? contentType : 'text/plain',
                data: data ? data : '',
                dataType: dataType ? dataType : 'json',
                success: function (res) {
                    if (typeof success === 'function') {
                        success(res);
                    } else {
                        location.reload();
                    }
                },
                error: function () {
                    if (typeof error === 'function') {
                        error();
                    } else {
                        BlogPage.PopWindow.openAsNote('callAjaxFail', '失败', '请检查您的操作是否有误, 或稍后再试.');
                    }
                },
                complete: complete,
            });
        },
    },
    PopWindow: {
        /**
         * 弹出一个窗口
         * @param id 窗口id
         * @param title 标题
         * @param html 内嵌的html
         * @param done 弹出后的回调函数
         * @param cancel 点击取消按钮或背景时的回调函数
         * @param ok 点击确定按钮时的回调函数
         * @returns {boolean}
         */
        open: function (id, title, html, done, ok, cancel) {
            if (BlogPage.PopWindow.find(id) !== -1) {
                return false;
            }
            try {
                var win = {
                    id: id,
                    node: null,
                    cancel: function () {
                        if (typeof cancel === 'function')
                            cancel();
                        BlogPage.PopWindow.close(id);
                    },
                    ok: function () {
                        if (typeof ok === 'function')
                            ok(node.children('.pop-content'));
                        BlogPage.PopWindow.close(id);
                    },
                };
                var node =
                    $('<div class="pop-window"></div>').attr('id', 'PopWindow-' + id).append(
                        $('<div class="pop-background"></div>').click(win.cancel)
                    ).append(
                        $('<div class="mybox pop-content"></div>').append(
                            $('<p class="pop-title"></p>').text(title)
                        ).append(
                            $('<div class="pop-html"></div>').html(html)
                        ).append(
                            $('<div class="btn-bar"></div>').append(
                                $('<a href="javascript:void(0);" class="button colorful" value="Cancel">Cancel</a>').click(win.cancel)
                            ).append(
                                $('<a href="javascript:void(0);" class="button colorful" value="OK">OK</a>').click(win.ok)
                            )
                        )
                    );
                win.node = node;
                $(top.document.body).append(node);
                BlogPopWindows.push(win);
                node.fadeTo(100, 1);
                if (typeof done === 'function')
                    done(node.children('.pop-content'));
            } catch (e) {
                console.log(e);
                return false;
            }
            return true;
        },
        /**
         * 通过窗口id关闭一个弹窗
         * @param id
         * @returns {boolean}
         */
        close: function (id) {
            var index = BlogPage.PopWindow.find(id);
            if (index !== -1) {
                BlogPopWindows[index].node.fadeTo(100, 0, null, function () {
                    $(BlogPopWindows[index].node).remove();
                    BlogPopWindows.splice(index, 1);
                });
                return true;
            } else {
                return false;
            }
        },
        /**
         * 通过窗口id寻找一个弹窗
         * @param id 窗口id
         * @returns {number} 成功返回其处于BlogPopWindows的下标, 失败返回-1
         */
        find: function (id) {
            for (var i = 0; i < BlogPopWindows.length; i++) {
                if (BlogPopWindows[i].id === id) {
                    return i;
                }
            }
            return -1;
        },
        /**
         * 建立一个输入框弹窗(open的二次封装)
         * @param id id
         * @param title 标题
         * @param type input的类型
         * @param text 如果是文本输入框, 可以填充一个默认值, 可为空
         * @param ok 回调函数, 用户确认后触发, 带一个参数, 为用户输入的值
         * @param cancel 回调函数, 用户取消时触发
         */
        openAsInput: function (id, title, type, text, ok, cancel) {
            BlogPage.PopWindow.open(id, title, (
                $('<input type="' + type + '">')
            ), function (JQEle) {
                var inputEle = JQEle.find('input');
                inputEle.keypress(function (e) {
                    if (e.keyCode === 13) {
                        JQEle.find('.btn-bar>.button[value=OK]').click();
                    }
                })
                if (text !== null) {
                    inputEle.val(text);
                }
                inputEle.focus();
            }, function (JQEle) {
                if (typeof ok === 'function')
                    ok(JQEle.find('input').val());
            }, function () {
                if (typeof cancel === 'function')
                    cancel(null);
            });
        },
        /**
         * 显示一个提示(note)
         * @param id id
         * @param title 标题
         * @param content 提示内容(可以是html)
         * @param ok 回调函数, 用户确认后触发, 带有一个JQEle参数
         * @param cancel 回调函数, 用户取消时触发
         */
        openAsNote: function (id, title, content, ok, cancel) {
            BlogPage.PopWindow.open(id, title, (
                $('<div></div>').html(content)
            ), null, function (JQEle) {
                if (typeof ok === 'function')
                    ok(JQEle);
            }, function () {
                if (typeof cancel === 'function')
                    cancel();
            });
        },
        /**
         * 显示目录选择器
         * @param ok 回调函数, 用户确认后触发, 带有一个dirId参数
         * @param cancel 回调函数, 用户取消时触发
         */
        openAsDirSelector(ok, cancel) {
            BlogPage.Ajax.call(
                '/tool/dirSelector',
                null,
                function (res) {
                    BlogPage.PopWindow.open('dirSelector', '目录选择', (
                        $(res)
                    ), null, function (JQEle) {
                        if (typeof ok === 'function') {
                            ok(JQEle.find('.dir-selector .radio[active]').attr('dir-id'));
                        }
                    }, function () {
                        if (typeof cancel === 'function')
                            cancel();
                    });
                },
                function () {
                    alert('?');
                    BlogPage.PopWindow.openAsNote('noDirSelector', '请求失败', '无法打开目录选择器.');
                }, null, 'html'
            )
        }
    },
};
