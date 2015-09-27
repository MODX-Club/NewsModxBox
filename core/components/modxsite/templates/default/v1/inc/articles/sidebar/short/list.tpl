{extends "../list.tpl"}

{block news_params append}
    
    {$inner_tpl = 'inc/articles/layouts/list/sidebar/short.tpl'}
     
    {$params = array_merge($params, [
        "limit"  => $limit|default:16
    ])}
    
{/block}

