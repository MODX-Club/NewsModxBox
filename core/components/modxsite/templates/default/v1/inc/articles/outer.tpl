{block articles_section_params}
    {$show_banner = false}
    {$show_title = false}
{/block}

{block articles_section_wrapper}

    {if $result.success && $result.object} 
        
        <div class="articles-section">
             
            {block name=block_header}
                {if $show_title}
                    <h3 class="section-title">
                        <span>{$title}</span>
                    </h3>
                {/if}
            {/block}
            
            {block name="articles_fetch"}
            
                <div class="row articles-list">
                    {foreach $result.object as $object name=loop}
                        <div class="col-xs-12">
                            {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
                        </div>
                        
                        {if $show_banner AND $smarty.foreach.loop.iteration == 3}
                            <div class="col-xs-12 top_banner">
                                [[!smarty?tpl=`inc/banners/adfox/690x152_top.tpl`]]
                            </div>
                        {/if}
                        
                    {/foreach}
                    
                </div>
            
            {/block}
                
        </div> 
    
    {/if}

{/block}

