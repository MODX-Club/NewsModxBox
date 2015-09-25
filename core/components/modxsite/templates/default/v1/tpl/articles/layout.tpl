{extends "tpl/index.tpl"}

{*block name=Breadcrumbs}
    {snippet name="Breadcrumbs@Breadcrumbs"}
{/block*}

{*
    Если это рубрика Бизнес-идеи, добавляем соответствующий заголовок
*}
{block params append}
    {if $modx->resource->parent == 86816}
        {$meta_title = "Бизнес-идея: {$meta_title}"}
    {/if}
{/block}

{block name=content}

    [[!smarty?tpl=`society/topics/topic/index.tpl`]]
    
{/block}



{block post_content}
    
    {*
        Выводим последние несколько новостей
        {include "inc/articles/blocks/related.tpl"}
    *} 
    
    {snippet name=smarty params=['tpl'  => 'inc/articles/blocks/related.tpl', 'left_block_class' => $left_block_class, 'right_block_class' => $right_block_class] as_tag=1}
    
    
    {*if $modx->resource->parent == 86855}
    
        {include "inc/articles/blocks/short.tpl"}
        
    {/if*}

            
{/block}



{block name=styles append}

    {* Gallery *}
        <link rel="stylesheet" href="//blueimp.github.io/Gallery/css/blueimp-gallery.min.css">
        <link rel="stylesheet" href="{$template_url}/vendor/BIGallery/css/bootstrap-image-gallery.min.css">
    {* Eof Gallery *}
    
{/block}


{block name=footers}
    
    {* Gallery *}
        <script src="//blueimp.github.io/Gallery/js/jquery.blueimp-gallery.min.js"></script>
        <script src="{$template_url}/vendor/BIGallery/js/bootstrap-image-gallery.min.js"></script>
    {* Eof Gallery *}

{/block}
 

 