{extends "inc/mainpage/articles/sections/list/inner.tpl"}

{block inner_content}
    
    {$title = $object.longtitle|default:$object.pagetitle}
    {$summary = trim(strip_tags($object.summary))}
    
    <div class="col-xs-12 row-article {if $iteration == '1'}big{/if}">
        
        <div class="row">
            
            <div class="article">
                {if $iteration == '1'}
                    <div class="col-xs-12">
                        <a href="{$object.uri}" title="{$title|escape}">
                            <h4 class="title">{$title}</h4>
                        </a> 
                    </div>
                    
                
                    <div class="col-sm-12 col-md-4">
                        <a href="{$object.uri}">
                            <img src="{$src}" class="img img-responsive">
                        </a> 
                    </div>
                    
                    <div class="col-sm-12 col-md-8">
                        <div class="summary">{$summary}</div>
                    </div>
                {else}
                
                    <div class="col-xs-12 col-md-3">
                        <a href="{$object.uri}">
                            <img src="{$src}" class="img img-responsive">
                        </a> 
                    </div>
                    
                    <div class="col-xs-12 col-md-9">
                        <a href="{$object.uri}" title="{$title|escape}">
                            <h4 class="title">{$title}</h4>
                        </a> 
                        
                        <div class="summary">{$summary}</div>
                    </div>
                
                {/if}
            </div>
            
        </div>
        
    </div>
    
     
{/block}
