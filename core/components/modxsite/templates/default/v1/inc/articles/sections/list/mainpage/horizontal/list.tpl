{*
    Для горизонтального вывода статей.
    Сейчас используется для рубрики Эксперт
*}

{extends "../list.tpl"}


{block news_params append}
    
    {$section_inner_class = "{$section_inner_class} horizontal"} 
    
{/block}

{block section_fetch_objects}
    {foreach $result.object as $object name=articles}
        {include "inc/articles/layouts/list/horizontal.tpl"}
    {/foreach}
{/block}

