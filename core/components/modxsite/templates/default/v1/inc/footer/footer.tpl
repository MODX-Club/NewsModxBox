 
<footer class="footer">
    
    {block tags}
        {if $modx->resource->template != 2}
            {include "inc/tags/index.tpl"}
        {/if}
    {/block}
    
    <hr />
    
    {include "inc/menu/footer.tpl" outerClass='menu-2'}
    
    {*include "inc/menu/footer.tpl"*}
    
    <div class="text-center grey pointed">
        Настоящий ресурс может содержать материалы 18+
    </div>
    
    <div class="text-center grey">
        &copy; Все права защищены. {$site_name}
        
        <div class="counters">
        
            {include "inc/footer/counters.tpl"}
        
        </div>
        
    </div>
    
</footer>