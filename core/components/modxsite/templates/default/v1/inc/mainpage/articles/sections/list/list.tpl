{extends "inc/articles/sections/list/list.tpl"}

{block name=params append}
    
    {$params.parent = $id|default:0}  
    {$params.limit = $local_limit|default:3}  
    
    {if $exclude}
        {$params.where['id:not in'] = $exclude}
    {/if}
    
    {*$params.sort = "publishedonss"}  
    {$params.dir = "desc"*}  
    
{/block}

{block name=pagination}{/block}
