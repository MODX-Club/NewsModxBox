{*
        Коротко
    *}
    {$params = [
        "where" => [
            "id" => 86855
        ],
        showhidden => 1
    ]}
    
    {processor action="web/resources/articles/sections/getdata" ns=modxsite params=$params assign=sections_result} 
    
    {foreach $sections_result.object as $section name=sections}
        
        {include "inc/articles/sections/list/mainpage/short/list.tpl" parent=$section.id show_top_banner=0 exclude=$modx->resource->getOption('MainNewsIDs') pagination=false} 
        
    {/foreach}