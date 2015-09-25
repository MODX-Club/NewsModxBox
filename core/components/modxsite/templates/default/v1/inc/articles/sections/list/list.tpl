{*
    Вывод разделов
    {extends "common/list/list.tpl"}
*}

{extends "../../list.tpl"}

{block name=news_params append}
    
    {$params = array_merge($params, [])}
    
    {$title = $modx->resource->pagetitle}
    
    {$processor = $articles_processor|default:'web/resources/articles/getdata'}
    
    {*
    {$inner_tpl = $local_inner_tpl|default:"inc/mainpage/articles/sections/list/inner.tpl"}
    {$outer_tpl = $local_outer_tpl|default:"inc/mainpage/articles/sections/list/outer.tpl"}
    *} 
     
    
{/block}

{block name=pagination}
    {if $pagination}
        {$smarty.block.parent}
    {/if}
{/block}


{* block news_fetch}

    {if $content = $modx->resource->content}
        <div class="list_content">
            {$content}
        </div>
    {/if}
    
    {$smarty.block.parent}
{/block *}
