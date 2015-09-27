{extends "./layout.tpl"}
    
           
{block banner}

    {*
        Получаем еще одну новость для бокового баннера
    *}
    
    
    {$params2 = [
        "parent"    => 86844,
        "current"   => 1,
        "summary"   => 1,
        "where"     => null
    ]}
    
    {$ids = (array)$modx->resource->getOption('MainNewsIDs', null, [])}
    
  
    {$ids[] = $modx->resource->id}
     
    {$params2.where['id:not in'] = $ids} 
    
    {*
        Если это раздел Коротко (все новости), 
        то получаем вторую новость, а не первую (первую пропускаем)
    *}
    {if $modx->resource->id == 185}
        {$params2.start = 1}
    {/if}
    
    
    {processor action="web/resources/articles/getdata" ns="modxsite" params=$params2 assign=banner_result}
    
    {if $banner_result.success && $banner_result.object}
    
        {$banner_article = $banner_result.object}
        
        {$ids[] = $banner_article.id}
        
        {include "inc/articles/layouts/banner-article.tpl" object=$banner_article}
                    
                    
        {*
            Устанавливаем полученные айдишники, 
            чтобы на главной не выводились эти новости в других рубриках.
        *}
        {$modx->resource->setOption('MainNewsIDs', $ids)}
        
    {/if}
     
     
{/block}