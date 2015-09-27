{*
    Выводим содержимое топика
*}

{extends "society/threads/layout.tpl"}


{block name=params append}
    
    {$params=[
        "limit" => 1,
        "summary"       => 0,
        "showhidden"   => 1,
        "showunpublished"   => 1,
        "current"   => 1,
        "where" => [
            "id"    => $modx->resource->id
        ],
        "process_tags"  => 1,
        "cache"     => 0
    ]}
    
    {$processor = "web/society/topics/getdata"}
    
{/block}


{block thread_object_content}
 
    {if $object.publishedon}
        {$date = $object.publishedon}
    {else}
        {$date = $object.createdon}
    {/if}
        
            
    {*
        {$object.content}
    *}
    
    <div class="row">
        <div class="col-xs-12 col-sm-9 pull-right article main_article article_page">
        
            <div class="article_content">
                
                <h1 class="title">{$object.longtitle|default:$object.pagetitle}</h1>
                
                {*
                <h2 class="intro">
                    {$object.summary}
                </h2> 
                *}
                
                {*
                    Проверяем флаг "Не выводить изображение"
                *}
                {if !$object.tvs.hide_image.value}
                    
                    {*
                        Если изображение получено, то выводим его.
                    *}
                    {if $image = ($object.public_image)}
                        {$thumb = $modx->runSnippet('phpThumbOf', [
                            "input" => $image,
                            "options"   => "w=550"
                        ])}
                        <a href="{$object.uri}" title="{$title}">
                            <img src="{$thumb}" class="article-image img img-responsive"/>
                        </a>
                    {/if} 
                {/if}
                
                {*
                    <a href="{$object.uri}" title="{$title}" class="no_underline"><h4 class="title">{$object.pagetitle}</h4></a>
                *}

                
                <div class="article_content">
                    {$object.content}
                    
                    {*
                        Партнерская ссылка на товар или услугу
                    *}
                    
                    {if $sell_links = $object.sell_link}
                        <noindex>
                            {foreach $sell_links as $sell_link}
                                <a class="btn btn-primary" href="{$sell_link.link|trim}" rel="nofollow" target="_blank">{if $icon_class = $sell_link.icon_class}<i class="glyphicon {$icon_class}"></i> {/if}{$sell_link.title|default:"Детальный просмотр"}</a>
                            {/foreach}
                        </noindex>
                    {/if}
                    
                    {*
                        Ссылка на оригинал
                        Array
                        (
                            [scheme] => http
                            [host] => svpressa.ru
                            [path] => /post/article/114240/
                        )
    
                    *}
                    {if $original_source = $object.tvs.original_source.value}
                        {$parsed_url = parse_url($original_source)}
                        <noindex>
                            <p class="original_source">
                                <i>
                                    Источник: <a href="{$original_source}" target="_blank" rel="nofollow">{$parsed_url.host}</a>
                                </i>
                            </p>
                        </noindex>
                    {/if}
                    
                </div>
            
                {if $object.gallery}
                    
                    <!-- The Bootstrap Image Gallery lightbox, should be a child element of the document body -->
                    <div id="blueimp-gallery" class="blueimp-gallery">
                        <!-- The container for the modal slides -->
                        <div class="slides"></div>
                        <!-- Controls for the borderless lightbox -->
                        <h3 class="title"></h3>
                        <a class="prev">‹</a>
                        <a class="next">›</a>
                        <a class="close">×</a>
                        <a class="play-pause"></a>
                        <ol class="indicator"></ol>
                        <!-- The modal dialog, which will be used to wrap the lightbox content -->
                        <div class="modal fade">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" aria-hidden="true">&times;</button>
                                        <h4 class="modal-title"></h4>
                                    </div>
                                    <div class="modal-body next"></div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default btn-sm pull-left prev">
                                            <i class="glyphicon glyphicon-chevron-left"></i>
                                            Назад
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm next">
                                            Вперед
                                            <i class="glyphicon glyphicon-chevron-right"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Slides Container -->
                    <div id="links" class="row">
                        
                        {foreach $object.gallery as $item}
                            {$image = $item.image}
                            {$title = $item.title}
                            {$thumb = $modx->runSnippet('phpthumbof', [
                                'input' => $image,
                                "options"   => "h=150&w=150&zc=C"
                            ])}
                            <div class="col-xs-6 col-sm-3 col-md-2 text-center" style="margin-bottom: 20px">
                                <a href="{$image}" title="{$title|escape}" data-gallery>
                                    <img src="{$thumb}" class="img img-responsive" alt="{$title|escape}">
                                </a>
                            </div>
                            
                        {/foreach} 
                        
                        
                    </div> 
                         
                {/if}
             
            </div>
            
            
            <div>
                
                <p class="text-right">
                    {*if $object.pseudonym}
                        {$object.pseudonym}
                    {else}
                        {$modx->getOption('modxsite.default_pseudonym', null)}
                    {/if*}
                    
                    {*
                        {$object.pseudonym|default:$object.author_fullname|default:$object.author_username}
                        <a href="">{$object.author_fullname|default:$object.author_username}</a>
                    *}
                </p>
                
                {include "society/topics/layouts/info.tpl"}
                  
                {*
                <pre>
                    {print_r($object)}
                </pre>  
                *}  
                
                
                
                <div class="row">
                    <div class="col-sm-8 tags">
                        {if $object.tags_array} 
                            Тэги: 
                            {foreach $object.tags_array as $tag name=tags}
                                <a href="{$tag.uri}" class="link">{$tag.tag}</a>{if !$smarty.foreach.tags.last}, {/if}
                            {/foreach}  
                        {/if} 
                    </div>
                    <div class="col-sm-4 text-right">
                         
                        {literal}
                            <div id="ya_share"></div>
                            
                            <div class="yashare-auto-init" data-yashareL10n="ru" data-yashareQuickServices="yaru,vkontakte,facebook,twitter,odnoklassniki,moimir" data-yashareTheme="counter"></div>
                            
                            <script type="text/javascript">
                                window.addEventListener('DOMContentLoaded',function(){
                                    $('<script type="text/javascript" src="//yandex.st/share/share.js" charset="utf-8"><\/script>').appendTo('#ya_share');
                                });
                            </script>
                        {/literal}
                        
                    </div>
                </div>
                {*
                    <div class="row">
                        <div class="col-xs-8 col-md-9">
                        </div>
                        <div class="col-xs-4 col-md-3 text-right">
                            {date('Y-m-d H:i:s', $date)}
                        </div>
                    </div>
                *}
                 
                
            </div> 
             
            {include "society/threads/comments/thread_comments.tpl"} 
            
        </div>
        
        <div class="col-xs-12 col-sm-3 pull-right">
            {include "inc/articles/sidebar/short/list.tpl"}
        </div>
        
        {block article_banner} 
            {if !$object.hide_adverts}
                <div class="col-xs-12 top_banner">
                    {include "inc/banners/adfox/690x152_top.tpl"}
                </div>
            {/if}
        
        {/block}
        
    </div>
     
    

{/block}


{block thread_comments}{/block}


{block thread_comments_post_content}
    
    {*
        Выводим последние несколько новостей
        {block article_famost_news}
            
            <h2 class="hr" style="margin-bottom:30px;">Читайте далее</h2>
            
            {include "inc/articles/related/list.tpl" limit=5 section=$object.parent exclude=$object.id pagination=false}
        {/block}
    *}
    
{/block}


{block thread_request}
    {processor action=$processor ns="modxsite" params=$params assign=result}
    {if $result.success && $result.object}
        {$object = $result.object}
    {/if}
{/block}





