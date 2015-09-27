{*
    Выводим одну новость в нижней полосе
*}

{extends "../list.tpl"}


{block name=news_params append}
    
    {$params.limit = 1}
    {$params.current = 1}
    
    {$section_outer_class = "{$section_outer_class} short"}
     
{/block}


{block news_request append} 
    {$object = $result.object}
{/block}


{block section_title append}<a href="{$object.uri}" title="{$object.pagetitle|escape}"><span class="short-title">{$object.pagetitle}</span></a>{/block}

{block section_title_wrapper}
    <div class="section-title-wrapper row">
        {block section_title}<a href="{$section.uri}" title="{$section.pagetitle|escape}"><h2 class="section-title">{$section.pagetitle}</h2></a>{/block}
    </div> 
{/block}


{block section_fetch_objects}{/block}
