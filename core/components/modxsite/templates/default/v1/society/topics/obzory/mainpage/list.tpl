{extends "../index.tpl"}

{block name=params append}
 
    {$paging = false}
 
    {$params = array_merge($params, [
        "limit" => 2,
        "page"  => 0
    ])}
    
    {* Обрезаем интро *}
    {$truncate = true}
    
{/block}

{block topics_list_fetch}
    <div class="row">
        {$smarty.block.parent}
    </div>
{/block}
