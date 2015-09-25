{extends "tpl/index.tpl"}


{block name=content}
    
    {if $content = $modx->resource->content}
        <div class="section_content alert alert-info">
            {$content}
        </div>
    {/if}
    
    {*
        Если рубрика - Коротко, используем индивидуальное оформление
    *}
    {if in_array($modx->resource->id, [86855])}
        {$tpl = 'short/list.tpl'}
    {else}
        {$tpl = 'default/list.tpl'}
    {/if}
    
    {$tpl = "inc/articles/sections/list/by_section/{$tpl}"}
    
    [[!smarty?tpl=`{$tpl}`&limit=`12`]] 
    
{/block}


{block short_news}
    {*
        Если рубрика - Афиша или Коротко, то не выводим блок Коротко
    *}
    {if !in_array($modx->resource->id, [86855])}
        {$smarty.block.parent}
    {/if}
{/block}

 

