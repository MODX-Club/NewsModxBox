{*
    Вывод статей по тегу
*}
{extends "../list.tpl"}

{block name=news_params append}
    {$title = $modx->resource->pagetitle}
    
    {$params = array_merge($params, [
        "limit"     => $limit|default:9,
        "tag"       => $modx->resource->id,
        "page"      => $smarty.get.page
    ])} 
    
    {$pagination = true}
    
    {*$inner_tpl = "inc/articles/sections/list/inner.tpl"}
    {$outer_tpl = "inc/articles/sections/list/outer.tpl"*}
    
{/block}

