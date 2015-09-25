{*
    Вывод рубрики Коротко
*}

{extends "../list.tpl"}


{block name=news_params append} 
    
    {*
        $section обнуляем, так как получаем статьи не только этой секции, но и все остальные, что в RSS выгружаются
    *}
    
    {$params = array_merge($params, [
        "limit" => 20,
        "section"   => 0
    ])}
    
    {$processor = "web/resources/articles/short/getdata"}
    
    {*
        {$inner_tpl = "inc/articles/sections/list/by_section/afisha/inner.tpl"}
        {$outer_tpl = "inc/articles/sections/list/by_section/afisha/outer.tpl"} 
    *}
    
    {$inner_tpl = "inc/articles/layouts/list/short.tpl"}
    
{/block}



