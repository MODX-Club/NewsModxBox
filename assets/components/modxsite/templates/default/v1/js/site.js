
var site = {
	sb: {
		scroll: null,
		count: 0,
		now: 2,
		init: function() {
			site.sb.scroll = $("#sb_block .scroll");
			site.sb.scroll.scrollTop(101);
			site.sb.count = parseInt( site.sb.scroll.find("a").length );
			site.sb.now = 2;

			$("#sb_block .sb-down").click(function() {
				site.sb.scroll.append(site.sb.scroll.find("a").eq(0));
				site.sb.scroll.stop().scrollTop(0);
				site.sb.scroll.animate({scrollTop : '101px'}, 400);
				return false;
			});
			$("#sb_block .sb-up").click(function() {
				site.sb.scroll.prepend(site.sb.scroll.find("a").eq(site.sb.count - 1));
				site.sb.scroll.stop().scrollTop(202);
				site.sb.scroll.animate({scrollTop : '101px'}, 400);
				return false;
			});
		}
	}
}