
{if $result.success && $result.object}
    
     
        
    <div class="row row-articles">
        
        <div class="col-md-12">
        
            {block name=block_header}
                <h3 class="section-title">
                    <span>{$title}</span>
                </h3>
            {/block}
            
            {block name="articles_fetch"}
            
                <div class="row">
                
                    {foreach $result.object as $object name=loop}
                        <div class="col-md-3">
                            {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
                        </div>
                    {/foreach}
                    
                </div>
            
            {/block}
            
        </div>
        
        
    </div> 

{/if}

