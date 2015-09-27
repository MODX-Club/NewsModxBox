{*
    Вывод для афиши
*}

{extends "../list.tpl"}


{block name=news_params append} 
    
    {$params = array_merge($params, [
        "limit" => 20
    ])}
    
    {*
    *}
    
    {$outer_tpl = "inc/articles/sections/list/by_section/afisha/outer.tpl"} 
    {$inner_tpl = "inc/articles/sections/list/by_section/afisha/inner.tpl"}
    
{/block}

