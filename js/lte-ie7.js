/* Load this script using conditional IE comments if you need to support IE 7 and IE 6. */

window.onload = function() {
	function addIcon(el, entity) {
		var html = el.innerHTML;
		el.innerHTML = '<span style="font-family: \'examiner\'">' + entity + '</span>' + html;
	}
	var icons = {
			'icon-file' : '&#x74;',
			'icon-checkmark' : '&#x64;',
			'icon-plus' : '&#x61;',
			'icon-users' : '&#x75;',
			'icon-bars' : '&#x63;',
			'icon-wrench' : '&#x73;',
			'icon-user-add' : '&#x69;',
			'icon-add-to-list' : '&#x71;',
			'icon-warning' : '&#x77;',
			'icon-reply' : '&#x62;',
			'icon-gear' : '&#x70;',
			'icon-checkmark-2' : '&#x7a;',
			'icon-cross' : '&#x78;',
			'icon-erase' : '&#x65;',
			'icon-cross-2' : '&#x76;',
			'icon-pencil' : '&#x6d;',
			'icon-edit' : '&#x6e;',
			'icon-home' : '&#x68;',
			'icon-info' : '&#x79;',
			'icon-logout' : '&#x6c;',
			'icon-key' : '&#x6b;',
			'icon-cancel' : '&#x2c;',
			'icon-cancel-2' : '&#x2e;',
			'icon-locked' : '&#x3b;',
			'icon-spinner' : '&#x67;'
		},
		els = document.getElementsByTagName('*'),
		i, attr, html, c, el;
	for (i = 0; ; i += 1) {
		el = els[i];
		if(!el) {
			break;
		}
		attr = el.getAttribute('data-icon');
		if (attr) {
			addIcon(el, attr);
		}
		c = el.className;
		c = c.match(/icon-[^\s'"]+/);
		if (c && icons[c[0]]) {
			addIcon(el, icons[c[0]]);
		}
	}
};