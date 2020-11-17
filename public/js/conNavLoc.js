//-------------------
//  弹出菜单控制
//-------------------
var mynav = document.getElementsByClassName('mynav')[0];
var linkhrefs = {};

(function () {
	var links = mynav.getElementsByTagName('a');
	for (var i = 0; i < links.length; ++i) {
		linkhrefs[i] = links[i].href;
		links[i].href = 'javascript:void(0);';
		links[i].addEventListener('click', function () {
			menuClick(i);
		});
		links[i].setAttribute("onclick", "menuClick(" + i + ");");
	}
})();

function menuClick(index) {
	// 判断是否完整弹出
	if (mynav.offsetTop == 0) {
		window.location.href = linkhrefs[index];
	}
}

window.addEventListener("touchstart", function () { }, true);

//-------------------
//  地址栏相关
//-------------------
var locPrefix = '当前位置: ';
var locDOM = document.getElementsByClassName('myloc')[0];
var loclink = document.getElementById('loclink');
var loclink_full = loclink.innerHTML.replace(locPrefix, '').trim();

var loclist = loclink_full.split(' &gt; ');

function locRedraw() {
	function checkLocLength() {
		return (locDOM.offsetWidth > window.innerWidth);
	}
	loclink.innerHTML = locPrefix + loclink_full;
	if (checkLocLength()) {
		for (var i = 0; i < loclist.length; ++i) {
			if (!i) {
				loclink.innerHTML = '';
			} else {
				loclink.innerHTML = loclist[i - 1].replace(/(<a.*>).*(<\/a>)/g, '$1...$2');
			}
			for (var j = i; j < loclist.length; ++j) {
				loclink.innerHTML += (j ? ' &gt; ' : '') + loclist[j];
			}
			if (!checkLocLength())
				break;
		}
	}
}

window.addEventListener('resize', locRedraw);
locRedraw();

// initial anime
locDOM.style.left = "-100%";
locDOM.style.opacity = "1";
setTimeout(function () {
	locDOM.style.transition = "left 300ms ease-in-out";
	locDOM.style.left = "0";
}, 10);
