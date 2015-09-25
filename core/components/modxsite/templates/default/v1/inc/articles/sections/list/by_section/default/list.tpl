{*
    Вывод рубрики по умолчанию
*}

{extends "../list.tpl"}


{block name=news_params append} 
    
    
    {*
        {$params = array_merge($params, [ 
        ])}
    *}
    
    {$outer_tpl = "inc/articles/sections/list/by_section/default/outer.tpl"} 
    {$inner_tpl = "inc/articles/layouts/list/inline.tpl"}
    
{/block}


