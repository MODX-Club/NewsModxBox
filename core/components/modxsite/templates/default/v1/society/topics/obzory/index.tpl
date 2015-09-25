{*
    Выводим только обзоры бань
*}

{extends file="society/topics/index.tpl"}

{block name=params append}
 
    {$processor = "web/society/topics/obzory/getdata"}
    
    {$params.facility_type = 1298}
    
{/block}

