//-------------------
//  Login
//-------------------
function login() {
    BlogPage.PopWindow.openAsInput(
        'login',
        '请点击Cancel',
        'password',
        null,
        function (pswd) {
            BlogPage.Ajax.call(
                '/api.admin/login?pswd=' + pswd,
                null,
                function (data) {
                    if (data['text'] === 'ok') {
                        location.reload();
                    }
                },
            );
        },
    );
}

$(document.body).css('--menu-cnt', $('.mynav>.mynavline>.mymenubar>.mymenuli').length);
