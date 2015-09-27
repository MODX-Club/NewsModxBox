{extends file="messages/layout.tpl"}

{block name=footer prepend}
    
    {if $auth_params}
        {$link = $modx->makeUrl(626, '', $auth_params, 'full')}
        
        Изменить настройки уведомлений вы можете по прямой ссылке: <a href="{$link}">{$link}</a>
        <br /><br />
    {/if}
    
{/block}

