{*
Слева в столбце показываем все тексты Рубрик сайта – «Бизнес- 

Идея», «Проекты», «Полезно знать», где показываем ФОТО и максимум ТРИ строчки 
*}
{extends "../list.tpl"}


{block news_params append}
    
    {*
    <pre>
        {print_r($params, 1)}
    </pre>
    *}
    
    {$inner_tpl = 'inc/articles/layouts/list/sidebar.tpl'}
    
    {$params = array_merge($params, [
        "limit"  => 6,
        "page"  => 0,
        "parent" => false
    ])}
    
    
    {*
        Если это рубрика Коротко, то выводим все новости.
        Иначе только из определенных рубрик.
    *}
    
    {if $modx->resource->parent != 86855}
        {$params.where["parent:in"] = [
            86816,
            86773,
            86774
        ]}
    {/if}
    
    
    {$pagination = false}
    
{/block}


{block news_fetch}
    <div class="sidebar">
        {$smarty.block.parent}
    </div>
{/block}

