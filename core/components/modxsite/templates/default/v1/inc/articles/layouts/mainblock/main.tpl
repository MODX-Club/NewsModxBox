{extends "../list/default.tpl"}


{block article_params append}
    
    {$article_outer_class = "{$article_outer_class} col-xs-12"}
    
{/block}

 
{block article_wrapper}
    <div class="row">
        {$smarty.block.parent}
    </div>
{/block}
 

{block article_image}
    <div class="col-xs-12 col-sm-7 col-md-12 col-lg-7">
        {$smarty.block.parent}
    </div>
{/block}

{block article_content_wrapper}
    <div class="col-xs-12 col-sm-5 col-md-12 col-lg-5">
        {$smarty.block.parent}
    </div>
{/block}  
                

{block article_fetch__}         
    sdfsdf
    
    <div class="col-xs-12">
        <div class="row">
            <div class="col-xs-6">
                <img class="img img-responsive" src="{$thumb}"/>
            </div>
            
        </div>
    </div>
{/block}