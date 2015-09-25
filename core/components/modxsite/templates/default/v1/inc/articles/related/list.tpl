{*
    Сопутствующие новости
*}

{extends "../list.tpl"}


{block news_params append}

    {$params = array_merge($params, [ 
        "page"      => 0
    ])}
    
    {if $exclude}
        {$params.where['id:!='] = (int)$exclude}
    {/if}

    {$processor = $processor|default:"web/resources/articles/getdata"}
    
    {*
        Если это рубрика Коротко, то выводим только короткие статьи
    *}
    {if $modx->resource->parent == 86855}
        {$inner_tpl = "inc/articles/layouts/list/short.tpl"}
    {else}
        {$inner_tpl = "inc/articles/layouts/list/related.tpl"}
    {/if}
    
     
{/block}
