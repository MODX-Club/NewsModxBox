{extends "common/list/list.tpl"}


{block name=params append}
     
    
    {*$processor = "web/getdata"*}
    
    {$outer_tpl = "common/list/outer.tpl"}
    {$inner_tpl = "common/list/inner.tpl"}
    
    {$show_no_records_error = true}
    {$no_records_error = "Записи не были получены"}
    
{/block}
