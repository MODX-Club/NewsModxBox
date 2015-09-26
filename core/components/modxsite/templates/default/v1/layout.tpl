<!DOCTYPE html>
{*
    Базовый шаблон сайта.
    Расширить можно в любом другом шаблоне просто прописав в начале файла {extends file="layout.tpl"}
    Статьи по modxSmarty: http://modxclub.ru/tag/modxSmarty/
*}

{block name=page_params}

    {$server_protocol = $modx->getOption('server_protocol')}
    {$site_url = $modx->getOption('site_url')}
    {$http_host = $modx->getOption('http_host')}
    {$mobile_host = $modx->getOption('mobile_host')}
    {$mobile_host_url = "{$server_protocol}://{$mobile_host}/"}
    {$site_name = $modx->getOption('site_name')}
    {$site_start = $modx->getOption('site_start')}
    {$cultureKey = $modx->getOption('cultureKey')}
    {$assets_url = $modx->getOption('assets_url')}
    
    {*
        container-fluid OR container
    *}
    {$container_class = "container"}
    
    {$canonical_site_url = $site_url}
    
    {$left_block_class = 'col-xs-12 col-md-9'}
    {$right_block_class = 'col-xs-12 col-md-3'}
    
    {$logo_text = "NewsModxBox"}
    
{/block}

<html lang="{$cultureKey}">

{block name=head}
    <head> 
        {block name=meta}
            
            {* Заголовок специально в блок загнан, чтобы в разных шаблонах его можно было бы переопределить *}
            <title>{block name="title"}{$meta_title} | {$site_name}{/block}</title>
            {*snippet name="MetaX@MetaX"*} 
            <base href="[[!++site_url]]" />
            <meta name="robots" content="{$meta_robots}" />
            <link rel="shortcut icon" href="{$template_url}img/favicon.ico"/>
        	<link rel="canonical" href="{$meta_canonical}" />
        	<meta http-equiv="content-language" content="ru" />
        	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        	<meta http-equiv="pragma" content="cache" />
        	<meta http-equiv="cache-control" content="cache" />
        	<meta http-equiv="Content-Style-Type" content="text/css" />
        	<meta http-equiv="Content-Script-Type" content="text/javascript" />
        	<meta name="keywords" content="" />
            <link rel="alternate" type="application/rss+xml" href="{$modx->makeUrl(43, '', '', 'full')}">
        {/block}
        
        
        
        {block name=jquery}
            {*
                <script type="text/javascript" src="http://yandex.st/jquery/1.8.3/jquery.min.js"></script>
            *}
        {/block}
        
        
        
        {block name=bootstrap}
            {* bootstrap meta *}
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            {* eof bootstrap meta *}
        {/block} 
        
        
        {block name=shopmodx_scripts}
             
            
        {/block} 
        
        
        {block name=fonts} 
        {/block}
        
        
        {block name=styles}
            {*
                <link rel="stylesheet" type="text/css" href="{$template_url}css/style_nd.css">
                <link rel="stylesheet" type="text/css" href="{$template_url}css/style.css">
            *}
            <link rel="stylesheet" href="{$template_url}bundle/styles/styles.css">
        {/block}
        
        
        {block name=headers}
            
            {*
            <script type="text/javascript" src="{$template_url}js/site.js"></script>
            *}
            
            {$modx->getChunk('headers')}
            
        {/block}
        
        {block GoogleAnalytics}
            {literal}
            {/literal}
        {/block}
        
    </head>
{/block}

{* Разметка сетки отделена от разметки функц. блоков. Тем самым наш контент не привязан к сетке. Добавлены только теги header и footer. *}

{block name=body}
    <body id="doc_{$modx->resource->id}" class="{$body_class}">
        
        {block header}
              
            
            <div class="{$container_class} wrapper">    
                <header class="header top"> 
                    <div class="row">
                        
                        <div class="col-sm-3 col-md-2">
                            {block page_logo}
                                <a href="{$site_url}" title="{$site_name|escape}">
                                    <h2 class="logo h3">{block page_logo_text}{$logo_text}{/block}</h2>
                                </a>
                            {/block}
                        </div>
                        
                        <div class="col-sm-6 col-md-7">
                            
                            {block pagie_slogan}
                                <h3 class="slogan">{block pagie_slogan_text}Платформа для новостных порталов{/block}</h3>
                            {/block}
                            
                            {block header_info}
                                <table class="info">
                                    <tbody>
                                        <tr>
                                            <td class="date">
                                                {strftime("%d %b %Y года %A")}
                                            </td> 
                                            {*
                                                Погода. Требуется наличие php-imagick
                                                Можно передавать параметр region
                                            *}
                                                <td>
                                                    {snippet name=smarty params=['tpl' => 'inc/blocks/weather/index.tpl'] as_tag=1}
                                                </td>
                                        </tr>
                                    </tbody>
                                </table>
                            {/block}
                        </div>
                        
                        <div class="col-sm-3 col-md-3">
                            {block header_currencies}
                                <div class="currencies row text-nowrap">
                                    <div class="col-xs-12 col-sm-4 col-md-3" title="Доллары США">$ {$modx->getOption('modxsite.cerrencies.usd')}</div>
                                    <div class="col-xs-12 col-sm-4 col-md-3" title="Евро">&euro; {$modx->getOption('modxsite.cerrencies.eur')}</div>
                                    <div class="col-xs-12 col-sm-4 col-md-3" title="Китайские Юани">元 {$modx->getOption('modxsite.cerrencies.cny')}</div>
                                </div>
                            {/block}
                            
                            {block header_search}
                                <form method="get" action="{$modx->makeUrl(84117)}">
                                    <div class="input-group search">
                                        <input type="text" class="form-control" placeholder="Поиск..." aria-describedby="basic-addon2" name="query">
                                        <span class="input-group-addon" id="basic-addon2">
                                            <button type="submit" class="glyphicon glyphicon-search" id="basic-addon2"></button>
                                        </span>
                                    </div>
                                </form>
                            {/block}
                            
                        </div>
                        
                        {*
                        <div class="col-sm-8 col-md-10">
                        
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="pull-right Login">
                                        [[!smarty?tpl=`inc/login/auth.tpl`]]
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                            
                                <div class="col-sm-12 col-md-8 text-center">
                                    <table class="info">
                                        <tbody>
                                            <tr>
                                                <td class="date">
                                                    {strftime("%d %b %Y года %A")}
                                                </td>
                                                <td>
                                                    Курс Валют<br />
                                                    USD {$modx->getOption('modxsite.cerrencies.usd')} &nbsp;EUR {$modx->getOption('modxsite.cerrencies.eur')}
                                                </td>
                                                <td>
                                                    {if $weather = json_decode($modx->getOption('modxsite.weather', true))}
                                                        Погода {$weather->temp} {$weather->weather}
                                                    {/if}
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                                <div class="col-sm-12 col-md-4"> 
                                    <form action="[[~84117]]" method="get">
                                        <div class="search">
                                            <button type="submit" class="submit pull-right"></button>
                                            <input type="text" class="text" name="query" placeholder="Поиск..."/>
                                        </div>
                                    </form> 
                                </div>
                                
                            </div>
                        
                        </div>
                        *}
                        
                    </div> 
            </header>
            </div>
             
        
        {/block}
        
        
        
            
             
                <div class="{$container_class} wrapper">
                    {block main_menu}
                        <div class="menu">
                            <nav class="navbar-default navbar-mainmenu">
                                <div class="">
                                    <div class="navbar-header">
                                        <button aria-controls="navbar" aria-expanded="false" data-target="#mainmenu-navbar" data-toggle="collapse" class="navbar-toggle collapsed" type="button">
                                          <span class="sr-only"></span>
                                          <span class="icon-bar"></span>
                                          <span class="icon-bar"></span>
                                          <span class="icon-bar"></span>
                                        </button> 
                                    </div>
                                
                                    <!-- Collect the nav links, forms, and other content for toggling -->
                                    <div class="collapse navbar-collapse" id="mainmenu-navbar">
                                        {*snippet name="wayfinder@MainMenu"*}
                                        {include "inc/menu/mainmenu.tpl"}
                                    </div><!-- /.navbar-collapse -->
                                </div><!-- /.container-fluid -->
                            </nav>
                        </div>
                    {/block}    
                </div>
                
                
                
                {block content_outer_wrapper}
                    <div class="{$container_class} wrapper">
                        <div class="main">
                            
                            <div class="container-fluide">
                                
                                {block name=Breadcrumbs}
                                    {snippet name="Breadcrumbs@Breadcrumbs"}
                                {/block}
                                
                                {block name=pagetitle}
                                    {*
                                    <div class="newsHd"><h2>{$modx->resource->longtitle|default:$modx->resource->pagetitle}</h2><div class="clear"></div></div>
                                    *}
                                {/block}
                                
                                
                                
                                {block name=content_wraper}
                                
                                    <div class="row">
                                        {block content_outer}
                                            <div class="{$left_block_class}">
                                                {block content}
                                                    {$modx->resource->content}
                                                {/block}
                                            </div>
                                        {/block}
                                        
                                        {block sidebar_outer}
                                            <div class="{$right_block_class}">
                                                
                                                {block sidebar}
                                                    
                                                    {snippet name=Smarty params=['tpl' => 'inc/blocks/sidebar/index.tpl'] as_tag=1}
                                                    
                                                {/block}
                                                
                                            </div>
                                        {/block}
                                    </div>
                                
                                {/block}
                                
                                {*
                                    Второй блок после контента.
                                    http://joxi.ru/Vm6ye0Ru5BEWrZ
                                *}
                                {block post_content}{/block}
                                
                                
                                {*
                                    Короткая новость.
                                    http://joxi.ru/nAyz3VMFdw9LrZ
                                *}
                                {block short_news}
                                    <div class="">
                                        {include "inc/articles/blocks/short.tpl"}
                                    </div>
                                {/block}
                                
                            </div> 
                        
                        </div>
                    </div>
                {/block}    
    
            
            {* Eof [[$head]] *}
            
            
            <div class="{$container_class} wrapper">
                {block name=footer}
                    <div class="row">
                        {include "inc/footer/footer.tpl"}
                    </div>
                {/block}
            </div>    
            
            {block name=modals}
                [[!smarty?tpl=`inc/modals/login.tpl`]]
            {/block}
        
            {block name=footers}
                {*
                *}
                
                <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
                
                <script src="{$template_url}vendor/bootstrap/dist/js/bootstrap.min.js"></script>
            
                {block alertify}
                <script src="{$template_url}vendor/AlertifyJS/build/alertify.min.js"></script>
                {/block}
                <script src="{$template_url}bundle/app.js"></script>  
                {$smarty.block.child}
            {/block}
            
            
        
            {block markitup}
                <script src="{$template_url}libs/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
                <script src="{$template_url}libs/markitup/jquery.markitup.js" type="text/javascript"></script>
                <link href="{$template_url}libs/markitup/skins/synio/style.css" rel="stylesheet">
                <link href="{$template_url}libs/markitup/sets/synio/style.css" rel="stylesheet">
            {/block}
            
            {block editors}
        
                
                {*
                    Написать публикацию
                *}
                
                {include "inc/blocks/scripts/topics/create.tpl"}
                
                {literal}
                    <script type="text/javascript">
                        jQuery(function($){
                    		// Подключаем редактор
                			$('.comment-markitup-editor').markItUp({
                        		onShiftEnter:  	{keepDefault:false, replaceWith:'<br />\n'},
                    			onTab:    		{keepDefault:false, replaceWith:'    '},
                    			markupSet:  [
                                    {name: 'жирный', className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
                        			{name: 'курсив', className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
                    				{name: 'зачеркнутый', className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
                    				{name: "подчеркнутый", className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
                    				{separator:'---------------' },
                    				{name: "цитировать", className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
                    				{name: "код", className:'editor-code', openWith:'<code>', closeWith:'</code>' },
                                    {name: "вставить ссылку", className:'editor-link', key:'L', openWith:'<a href="[![Введите ссылку:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' }
                			    ]
                    		});
                		});
                	</script>
                {/literal} 
            
    
    
                <script type="text/javascript">
                    
                    var D = {
                        
                        inRequest: false
                        
                        ,init: function(block){
                            this.block = block;
                            
                            this.block.on('click', '.comment-button', this, this.onClick);
                            
                            this.form = $('#reply form');
                            
                            this.form.on('submit', this, this.onSubmit);
                             
                        }
                        
                        ,onClick: function(e){ 
                            
                            // console.log(this);
                            
                            var a = $(this);
                            var wrapper = a.parent();
                            var reply_form = $('#reply');
                            var comment_id = wrapper.attr('data-comment-id');
                            
                            reply_form.find('#form_comment_reply').val(comment_id);
                            // 
                            // console.log(a);
                            // console.log(wrapper);
                            // console.log(comment_id);
                            
                            
                            
                            wrapper.after(reply_form);
                            
                            $('#reply').show();
                            return false;
                        }
                        
                        ,onSubmit: function(e){
                            
                            // console.log(this);
                            
                            var scope = e.data;
                            
                            var form = $(this);
                            
                            
                            if(!scope.inRequest){ 
                                
                                scope.inRequest = true;
                                var data = form.serialize();
                                var comment_parent = form.find("[name=parent]").val();
                                
                                scope.form.find('.has-error').removeClass('has-error');
                                
                                $.ajax({
                                    url: "{$assets_url}components/modxsite/connectors/connector.php"
                                    ,type: "post"
                                    ,dataType: "json"
                                    ,data: data
                                    ,error: function(){
                                        scope.inRequest = false;
                                        
                                        alertify.error("Ошибка выполнения запроса");
                                        
                                        return false;
                                    }
                                    ,success: function(response){ 
                                        scope.inRequest = false;
                                        
                                        if(!response.success){
                                            
                                            alertify.error(response.message || "Ошибка выполнения запроса");
                                            
                                            return;
                                        }
                                        
                                        // else
                                        alertify.success(response.message || "Успешно");
                                        scope.form[0].reset();
                                        
                                        $('#reply').hide();
                                        
                                        
                                        // Подставляем код комментария
                                        if(response.object && response.object.comment_html){
                                            
                                            // Подставляем или в ответ, или новым блоком
                                            
                                            var outer_block, inner_block;
                                            
                                            if(comment_parent > 0){
                                                inner_block = $('#comment-' + comment_parent);
                                                
                                                outer_block = inner_block.find('.outer-tpl:first');
                                                
                                                if(!outer_block.length){
                                                    outer_block = $('<div class="comment-list outer-tpl"></div>');
                                                }
                                            }
                                            else{
                                                
                                                inner_block = $('#comments-wrapper');
                                            }
                                            
                                            
                                            if(!outer_block || !outer_block.length){
                                                outer_block = $('<div class="comment-list outer-tpl"></div>');
                                            }
                                            
                                            outer_block.append(response.object.comment_html);
                                            
                                            inner_block.append(outer_block);
                                            
                                        }
                                        
                                        return;
                                    }
                                });
                            } 
                            
                            return false;
                        }
                         
                    };
                    
                    D = Object.create(D)
                        .init($('.topic_list'));
                    
                    
                    // Голосования за комментарии
                    var Votes = {
                        
                        inRequest: false
                        
                        ,init: function(selector){
                            console.log(selector);
                            $(document).on('click', selector, this, this.onClick);
                             
                        }
                        
                        ,onClick: function(e){ 
                            
                            console.log(this);
                            
                            var $this = $(this);
                            
                            var vote_direction = $this.data('vote-direction');
                            
                            console.log(vote_direction);
                            
                            var wrapper = $this.parents('.comment.inner-tpl:first');
                            var comment_id = wrapper.attr('id').split('-')[1];
                            
                            console.log(comment_id);
                            
                            var rating_field = wrapper.find('[data-type="total-rating"]');
                            
                            var value;
                            
                            switch(vote_direction){
                                
                                case 'up':
                                    value = 1;
                                    break;
                                
                                case 'down':
                                    value = -1;
                                    break;
                                    
                                default: value = 0;
                            }
                             
                            
                            if(comment_id > 0){
                                var action = 'topics/comments/votes/create';
                                var data = {
                                    'vote_direction': vote_direction
                                    ,'target_id'    : comment_id
                                };
                                ShopMODX.Request.run(action, data).
                                    then(function(resp){
                                        if(resp.success){
                                            alertify.success(resp.message || "Ваш голос принят!");
                                            rating_field.text(Number(rating_field.text()) + value);
                                        }
                                    });

                            }
                            
                            return false;
                        }
                         
                    };
                    
                    Object.create(Votes)
                        .init('.comment_vote');
                    
                </script>
    
            
            {/block}
             
    
    </body>
{/block}
</html>