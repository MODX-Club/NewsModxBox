{*
    Выводим последние несколько новостей
*} 
        
<div class="row related_articles">
    
    <div class="{$left_block_class}">
        <h2 class="hr" style="margin-bottom:30px;">Читайте далее </h2>
        {*
        {include "inc/articles/related/list.tpl" limit=5 section=$modx->resource->parent exclude=$modx->resource->id pagination=false}
        *}
        {include "inc/articles/related/list.tpl" limit=5 section=null parent=$modx->resource->parent exclude=$modx->resource->id pagination=false}
    </div>
    
    <div class="{$right_block_class}">
        {include "inc/banners/article.tpl"}
    </div>
    
</div>