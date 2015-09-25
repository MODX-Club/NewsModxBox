{*
    Выводим топики только определенного блога
*}

{extends file="society/topics/index.tpl"}

{block name=params append}

    {$params=array_merge($params,[
        "blog" => $modx->resource->id
    ])}
    
    {$processor = "web/society/topics/getdata"}
    
{/block}