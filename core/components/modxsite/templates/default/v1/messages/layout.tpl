{block name=params}
    {$site_url = $modx->getOption('site_url')}
    {$site_name = $modx->getOption('site_name')}
    
    {if $auth_user_id}
        {$auth_email = $modx->getObject('modUser', $auth_user_id)->Profile->email}
        {$auth_link_salt = $modx->getOption('modsociety.auth_link_salt')}
        {$str = "{$auth_user_id}{$modx->site_id}{$auth_link_salt}"}
        {$auth_key = md5($str)}
        
        {$auth_params = [
            "u" => $auth_user_id,
            "e" => $auth_email,
            "auth_key" => $auth_key
        ]}
        
    {/if} 
    
{/block}

{block name=header}
    Здравствуйте!<br /><br />
{/block}

{block name=content}{/block}

{block name=footer}
    <hr />
    С уважением, <br />
    Администрация сайта <a href="{$site_url}">{$site_name}</a>.<br />
    
{/block}
