{* Шаблон главной страницы. Расширяет шаблон layout.tpl *}
{extends file="layout.tpl"}

{block params append}
    {$body_class = 'mainpage'}
{/block}

{block name="title"}{$site_name}{/block}

{block name=pagetitle}{/block}

  
{* Переопределяем блок контента *}
{block name=content_wraper} 
      
    {*
        Получаем главные новости.
    *}
    
    {include "inc/articles/sections/list/mainpage/mainblock/list.tpl" iteration='1' pagination=false}
    
    
    {*
        Получаем все категории для вывода
    *}
    
    {$params = [
        show_on_pain_page => true
    ]} 
    
    {processor action="web/resources/articles/sections/getdata" ns=modxsite params=$params assign=sections_result} 
    
    {foreach $sections_result.object as $section name=sections}
        
        {include "inc/articles/sections/list/mainpage/list.tpl" parent=$section.id show_top_banner=0 exclude=$modx->resource->getOption('MainNewsIDs') pagination=false} 
        
    {/foreach}
      
    
    {*
        Рубрика Эксперт
    *}
    {$params = [
        "where" => [
            "id" => 86775
        ]
    ]}
    
    {processor action="web/resources/articles/sections/getdata" ns=modxsite params=$params assign=sections_result} 
    
    {foreach $sections_result.object as $section name=sections}
        
        {include "inc/articles/sections/list/mainpage/horizontal/list.tpl" parent=$section.id show_top_banner=0 exclude=$modx->resource->getOption('MainNewsIDs') pagination=false} 
        
    {/foreach}
      
    
    {include "inc/articles/blocks/short.tpl"}
    
    
    
{/block}


{block short_news}{/block}
