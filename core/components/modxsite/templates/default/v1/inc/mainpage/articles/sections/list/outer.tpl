{block articles_section_params}
    {$show_banner = false}
{/block}

{block articles_section_wrapper}

    {if $result.success && $result.object}
        
        
        <div class="articles-section">
            
                
            {block name=block_header}
                <h3 class="section-title">
                    <span>{$title}</span>
                </h3>
            {/block}
            
            {block name="articles_fetch"}
            
                <div class="row">
                    {foreach $result.object as $object name=loop}
                        <div class="col-md-4">
                            {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
                        </div>
                        
                        {*
                            Не убирать это, иначе статьи поедут, когда высота у них не одинаковая
                            {if $smarty.foreach.loop.iteration%3 == '0' AND $smarty.foreach.loop.total > $smarty.foreach.loop.iteration}
                                </div>
                                <div class="row">
                            {/if}
                        *}
                        
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

