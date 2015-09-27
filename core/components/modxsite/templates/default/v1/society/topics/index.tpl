{*
    Получаем все топики
*}

{block name=params nocache}

    {$params=[
        "limit" => 10,
        "page"      => $smarty.get.page,
        "sort"  => "createdon",
        "dir"   => "DESC"
    ]}
    
    {$processor = "web/society/topics/getdata"}
    
    {$topics_list_tpl = $topics_list_tpl|default:"society/topics/layouts/list.tpl"}
    
    {$paging = true}
    
{/block}

{block name=pre_content}{/block}

{processor action=$processor ns="modxsite" params=$params assign=result}

{$users_url = $modx->makeUrl(626)} 
 
{block name=topics_list}
    {block topics_list_fetch}
        {foreach $result.object as $object}
            {include file=$topics_list_tpl}
        {/foreach}
    {/block}
    
    {block name=pageing}
        {if $paging}
            {include "common/pagination/pagination.tpl"}
        {/if}
    {/block}

{/block}


