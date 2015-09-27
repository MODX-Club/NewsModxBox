{extends "inc/mainpage/articles/sections/list/outer.tpl"}


{block name=block_header}{/block}


{block articles_section_wrapper}

    <div class="row">
        <div class="col-xs-12 col-sm-9 pull-right">
            {$smarty.block.parent}
        </div>
        <div class="col-xs-12 col-sm-3 pull-right">
            
            {*
                Выводим новости в сайдбаре
                {include "inc/articles/sidebar/list.tpl"}
            *}
            [[!smarty?tpl=`inc/articles/sidebar/list.tpl`]]
            
            
        </div>
    </div>

{/block}


{block name="articles_fetch"}
 
    {foreach $result.object as $object name=loop}
    
        {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
        
    {/foreach} 

{/block}
