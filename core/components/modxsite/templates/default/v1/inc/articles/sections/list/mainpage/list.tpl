{extends "../list.tpl"}

{*
    В шаблонизации учитываем следующие моменты:
    1. Порядковый номер итерации. Если == 1, то выводим с большой картинкой.
    2. Выводить ли справа блок.
    3. Выводить ли вверху блок.
*}

{block name=news_params append}
    
    {*
        Кол-во статей на строчку с баннером
    *}
    {$articles_per_section_with_banner = 4}
    
    {*
        Кол-во статей на строчку без баннера
    *}
    {$articles_per_section_without_banner = 6}
    
    
    {$params.show_hidden_on_mainpage = 0}
    
    {if $iteration == '1'}
        {$params.limit = 3}
    {else}
        
        {*
            Определяем выводить ли баннер справа или нет.
        *}
        
        {$show_right_banner = $section.tvs.show_right_banner.value}
            
        {*
            Если выводить, то сетка будет 8*4 и по три статьи в ряд.
            Если нет, то сетка 12 и выводить 4 статьи.
        *}
        
        {if $show_right_banner}
            {$params.limit = $articles_per_section_with_banner}
        {else}
            {$params.limit = $articles_per_section_without_banner}
        {/if}
        
    {/if}
    
    
    {*
        Исключения
    *}
    {if $exclude}
        {$params.where['id:not in'] = $exclude}
    {/if}
    
    {$inner_tpl = $local_inner_tpl|default:"inc/mainpage/articles/sections/list/inner.tpl"}
    {$outer_tpl = $local_outer_tpl|default:"inc/mainpage/articles/sections/list/outer.tpl"}
    
    {$section_outer_class = "row article-section {if !$show_right_banner}row-articles{/if}"} 
    {$section_inner_class = "{if $show_right_banner || $iteration == '1'}col-md-9{else}col-md-12{/if}"} 
    
{/block}


{block news_request append} 
    {if $iteration == '1'}
    
        {*
            Фиксируем ID-шники новостей из ТОПового блока.
            Во всех остальных категорях на главной мы их будем исключать
        *}
    
        {if $ids = array_keys($result.object)}
            {$modx->resource->setOption('MainNewsIDs', $ids)}
        {/if}
        
    {/if}
    
    {*
    <pre>
        {print_r($result, 1)}
    </pre>
    *}
    
{/block}


{block name=news_fetch} 
    
    {*
        Выводим рубрику
    *} 
    
    <div class="{$section_outer_class} s_{$section.id}">

        <div class="{$section_inner_class}"> 
            
            <div class="bg">
                
                {*if $show_top_banner && $smarty.foreach.sections.iteration == '1'*}
                
                {if $show_top_banner && !$modx->getOption('top_banner_showed')}
                    {$modx->setOption('top_banner_showed', 1)}
                    <div class="top_banner">
                        [[!smarty?tpl=`inc/banners/adfox/690x152_top.tpl`]]
                    </div>
                {/if}
                 
                
                
                {block section_title_wrapper}
                    <a href="{$section.uri}" class="section-title-wrapper">
                        {block section_title}<h2 class="section-title">{$section.pagetitle}</h2>{/block}
                    </a> 
                {/block}
                
                
                {*
                    <pre>
                        {$params.section = null}
                        {var_export($params, 1)}
                    </pre>
                    
                    <pre>
                        {print_r($result, 1)}
                    </pre>
                *}
                
                {block section_fetch_objects}
                    <div class="row">
                        {foreach $result.object as $object name=articles}
                            <div class="{if $show_right_banner}col-sm-{12/$articles_per_section_with_banner*2} col-md-{12/$articles_per_section_with_banner}{else}col-sm-{12/$articles_per_section_without_banner * 2} col-md-{12/$articles_per_section_without_banner}{/if}">
                                {*include "inc/mainpage/articles/sections/list/inner.tpl"*}
                                
                                {if $show_right_banner}
                                    {include "inc/articles/layouts/list/default.tpl"}
                                {else}
                                    {include "inc/articles/layouts/list/row-section.tpl"}
                                {/if}
                            </div> 
                        {/foreach}
                    </div>
                {/block}
                
            </div>
            
        </div>
        
        {if $show_right_banner}
            <div class="col-md-3">
                
                {$i = $modx->resource->getOption('banner_iterator')|default:0}
                
                {$i = $i+1}
                
                {if 4 > $i}
                    {include "inc/banners/adfox/sidebar/banner{$i}.tpl"}
                
                {/if}
                
                {$ok = $modx->resource->setOption('banner_iterator', $i)}
                 
            </div>
        {/if}
        
    </div>
    
{/block}
