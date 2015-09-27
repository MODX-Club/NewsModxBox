{extends "../outer.tpl"}

{block name=block_header}{/block}

{block name="articles_fetch"}
            
    <div class="row article">
    
        {foreach $result.object as $object name=loop}
        
            {if $smarty.foreach.loop.iteration == '1'}
            
                    {include "inc/mainpage/articles/sections/list/main/inner.tpl"}
                    
                </div>
                <div class="row">
            {else}
            
                <div class="col-md-4">
                    {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
                </div>
                
            {/if}
        
        {/foreach}
        
    </div>

{/block}
