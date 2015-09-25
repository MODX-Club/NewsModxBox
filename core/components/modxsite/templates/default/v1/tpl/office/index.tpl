{extends "layout.tpl"}

{block params append}
    {$container_class = "container-fluid"}
{/block}

{block header}{/block}

{block headers append}
    <script src="{$template_url}libs/visjs/dist/vis.js"></script>
    <link href="{$template_url}libs/visjs/dist/vis.css" rel="stylesheet" type="text/css" />
{/block}

{block name=Breadcrumbs}{/block}

{block short_news}{/block}


{block content_wraper}
    
    [[!smarty?tpl=`inc/office/planing/index.tpl`]]

{/block}

{*block name=content_outer_wrapper}
    <div class="container-fluid" style="margin-top: 30px;">
        
        {block content}
            [[!smarty?tpl=`inc/office/planing/index.tpl`]]
        {/block}
    </div>
{/block*}


{block name=footer}
    
    {include "inc/footer/counters.tpl"}
{/block}

 
