{*
    Сопутствующие новости
*}

{extends "./default.tpl"}
 

{block article_params append}
    
    {$article_outer_class = "{$article_outer_class} col-xs-12"}
    
{/block}

{block article_image}
    <div class="col-sm-3">
        {$smarty.block.parent}
    </div>
{/block}

{block article_content_wrapper}
    <div class="col-sm-9">
        {$smarty.block.parent}
    </div>
{/block}


{block article_fetch}
    <div class="row">
        {$smarty.block.parent}
    </div>
{/block}

