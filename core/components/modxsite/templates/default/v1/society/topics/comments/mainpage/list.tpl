{extends "../index.tpl"}

{block name=params append}

    {$params = array_merge($params, [
        "limit" => 4,
        "page"  => 0
    ])}
    
    {* Обрезаем комментарии *}
    {$truncate = true}
    
{/block}

{block name=paging}{/block}
