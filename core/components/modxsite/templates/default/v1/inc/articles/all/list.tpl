{*
    Коротко. Вывод всех новостей
*}

{extends "../list.tpl"}



{block news_params append}

    {$params = array_merge($params, [
        "limit"     => $limit|default:20
    ])}
    
    {if $ids = (array)$modx->resource->getOption('MainNewsIDs')}
        {$params.where['id:not in'] = $ids} 
    {/if} 
      
    {$inner_tpl = "inc/articles/layouts/list/short.tpl"} 
     
{/block}