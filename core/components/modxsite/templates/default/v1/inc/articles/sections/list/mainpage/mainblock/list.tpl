{extends "../list.tpl"}


{*
    Здесь мы получаем Одну главную новость и две из рубрики Бизнес-идеи
*}


{block name=news_params append}
    
    {$params = array_merge($params, [
        "limit" => 1
    ])}
    
    {$processor = "web/resources/articles/main/getdata"}
    
    {$aditional_params = array_merge($params, [
        "limit"     => 2,
        "parent"    => 86816
    ])}
    
    {$aditional_processor = "web/resources/articles/getdata"}
    
{/block}
 

{block news_request append} 
    {*
        Получаем дополнительно две новости из рубрики Бизнес-идеи
    *}
    
    {$aditional_params.where['id:not in'] = [current(array_keys($result.object))]}
    
    {processor action=$aditional_processor ns=modxsite params=$aditional_params assign=aditional_result}
    
    {$result.object = $result.object + $aditional_result.object}
    
    {$ids = array_keys($result.object)}
    
    
    {*
        Устанавливаем полученные айдишники, 
        чтобы на главной не выводились эти новости в других рубриках.
    *}
    {$modx->resource->setOption('MainNewsIDs', $ids)}
{/block}

{block name=news_fetch}
    
    <div class="row main_news">
        
        <div class="col-xs-12 col-md-6">
            
            <div class="row">
                
                {foreach $result.object as $object name=articles}
                    
                    {if $smarty.foreach.articles.iteration == '1'} 
                        {include "inc/articles/layouts/mainblock/main.tpl"}
                    {else}
                        {include "inc/articles/layouts/mainblock/minor.tpl"}
                    {/if}
                    
                {/foreach}
                
            </div>
            
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-3">
            
            {$params = [
                "limit" => 8
            ]}
            
            {processor action="web/society/topics/comments/getdata" ns="modxsite" params=$params assign=comments_result}
            
            <div class="panel panel-default panel-comments">
                <div class="panel-heading">
                    Последние комментарии
                </div>
                <div class="panel-body">
                    
                    {foreach $comments_result.object as $comment}
                        <div class="comment">
                            <span class="author">{$comment.author_fullname|default:$comment.author_username}</span> <a href="{$comment.resource_uri}#comment-{$comment.id}">
                                {$comment.text|strip_tags|truncate:80}
                            </a>
                        </div>
                    {/foreach}
                    
                </div>
                <div class="panel-footer">
                    <a href="{$modx->makeUrl(86893)}">Все комментарии</a>
                </div>
            </div>
            
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-3">
            {include "inc/banners/article.tpl"}
            
            {if $show_top_banner && !$modx->getOption('top_banner_showed')}
                {include "inc/banners/horizontal/small/layout.tpl"}
            {/if}
        </div>
        
    </div>
    
{/block}






{*
    Старый вариант с выводом комментариев
*}
{block name=___news_fetch}
    
    <div class="row main_news">
        
        <div class="col-xs-12 col-md-6">
            
            <div class="row">
                
                {foreach $result.object as $object name=articles}
                    
                    {if $smarty.foreach.articles.iteration == '1'} 
                        {include "inc/articles/layouts/mainblock/main.tpl"}
                    {else}
                        {include "inc/articles/layouts/mainblock/minor.tpl"}
                    {/if}
                    
                {/foreach}
                
            </div>
            
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-3">
            
            {$params = [
                "limit" => 8
            ]}
            
            {processor action="web/society/topics/comments/getdata" ns="modxsite" params=$params assign=comments_result}
            
            <div class="panel panel-default panel-comments">
                <div class="panel-heading">
                    Последние комментарии
                </div>
                <div class="panel-body">
                    
                    {foreach $comments_result.object as $comment}
                        <div class="comment">
                            <span class="author">{$comment.author_fullname|default:$comment.author_username}</span> <a href="{$comment.resource_uri}#comment-{$comment.id}">
                                {$comment.text|strip_tags|truncate:80}
                            </a>
                        </div>
                    {/foreach}
                    
                </div>
                <div class="panel-footer">
                    <a href="#">Все комментарии</a>
                </div>
            </div>
            
        </div>
        
        <div class="col-xs-12 col-sm-6 col-md-3">
            {include "inc/banners/article.tpl"}
            
            {include "inc/banners/horizontal/small/layout.tpl"}
        </div>
        
    </div>
    
{/block}
