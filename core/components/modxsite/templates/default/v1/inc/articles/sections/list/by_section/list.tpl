{*
    Вывод статей по разделу
*}
{extends "../list.tpl"}

{block name=news_params append}
    {$title = $modx->resource->pagetitle}
    
    {$params = array_merge($params, [
        "limit"     => $limit|default:9,
        "section"   => $modx->resource->id,
        "page"      => $smarty.get.page
    ])}
    
    {$pagination = true}
     
    
{/block}


{block news_fetch}
    
    {$smarty.block.parent}
    
    {block write_topic_link}
        <a class="btn btn-info pull-right" href="[[~[[++modxsite.write_topic_id]] ]]"><i class="glyphicon glyphicon-pencil"></i> Написать статью</a> 
    {/block}
    
{/block}

