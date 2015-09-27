{*if $modx->user->isAuthenticated($modx->context->key)*}

    {if $modx->user->id}
    
        {include "inc/login/logout.tpl"}
        
    {else}
        <li>
            <a href="javascript:;" class="pseudo" role="button" class="pdeudo" data-toggle="modal" data-target="#LoginModal" rel="noindex nofollow">Вход</a>
        </li>
    {/if}  

{*
<noindex>
    <ul class="entrance-modal">
        {if $modx->user->id}
        
            {include "inc/login/logout.tpl"}
            
        {else}
            <li>
                <a href="javascript:;" class="pseudo" role="button" class="pdeudo" data-toggle="modal" data-target="#LoginModal">Вход</a>
            </li>
            <li>
                <a href="[[~104]]">Регистрация</a>
            </li>
        {/if}
        <li>
            <a href="{$modx->makeUrl(43)}">RSS</a>
        </li>
    </ul> 
</noindex>
*}

