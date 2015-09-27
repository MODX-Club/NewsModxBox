{*
    Выводим последние топики на главной
*}
{extends "../index.tpl"}

{block name=params append}
 
    {$paging = false}
 
    {$params = array_merge($params, [
        "approved"  => 1,
        "limit" => 5,
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

