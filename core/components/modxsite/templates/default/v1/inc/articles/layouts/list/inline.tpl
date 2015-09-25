{*
    Для вывода новости в строчку
*}
 
{extends "./default.tpl"}

{block article_params append}
    
    {$article_outer_class = "{$article_outer_class} row inline"}
    
{/block}
 

 
 
{block article_image}
    <div class="col-xs-3">
        {$smarty.block.parent}
    </div>
{/block}

{block article_content_wrapper}
    <div class="col-xs-9">
        {$smarty.block.parent}
    </div>
{/block} 




