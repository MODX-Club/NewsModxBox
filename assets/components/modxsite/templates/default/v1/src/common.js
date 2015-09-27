require('es5-shim');
var $ = require('jQuery');
var Request = require("./utils/request");
var Env = require('./constants/Env');

Request = Object.create(Request).init({
  connectorsUrl: Env.siteConnectorsUrl,
  connector: Env.connector
});

window.ShopMODX = {
    Request: Request,
    Env: Env
};

// Форматирование даты
Date.prototype.yyyymmdd = function() {
    var yyyy = this.getFullYear().toString();
    var mm = (this.getMonth()+1).toString(); // getMonth() is zero-based
    var dd  = this.getDate().toString();
    return yyyy + '-' + (mm[1]?mm:"0"+mm[0]) + '-' +(dd[1]?dd:"0"+dd[0]); // padding
};


$(function() {

  $('[data-smodx-behav="recount"]').remove();

  $("#loginLoginForm [type=submit]").on('click', function() {
    var form = $(this).parents('form:first');
    var data = [];

    form.serialize().split('&').map(function(node) {
      if (node.match('action')) return;
      this.push(node);
    }, data);

    Request.run('login', data.join('&'))
      .then(function(resp) {
        if (resp.success) {
          var url = window.location.pathname;
          window.location.replace(url);
        }
      });

    return false;
  });


});

// Добавить в Избранное
window.add_favorite = function(a) {
    title=document.title;
    url=document.location;
    try {
    // Internet Explorer
    window.external.AddFavorite(url, title);
    }
    catch (e) {
    try {
    // Mozilla
    window.sidebar.addPanel(title, url, "");
    }
    catch (e) {
    // Opera и Firefox 23+
    if (typeof(opera)=="object" || window.sidebar) {
    a.rel="sidebar";
    a.title=title;
    a.url=url;
    a.href=url;
    return true;
    }
    else {
    // Unknown
    alert('Нажмите Ctrl-D чтобы добавить страницу в закладки');
    }
    }
    }
    return false;
} 

window.site = {
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



$(function() {

    $('[data-comment-action]').on('click', this, function(e){
        console.log(this);
        
        var el = $(this);
        var action = el.data('comment-action');
        
        // switch(el.data('comment-action')){
        //     
        // }
        
        Request.run('comments/' + action, {
            comment_id: $(this).data('comment-id')
        })
            .then(function(resp) {
                if (resp.success) {
                    alertify.success(resp.message || "Действие успешно выполнено");
                    var url = window.location.href.replace(/\?.*/, '');
                    window.location.replace(url);
                }
            });
  }); 


});

 




