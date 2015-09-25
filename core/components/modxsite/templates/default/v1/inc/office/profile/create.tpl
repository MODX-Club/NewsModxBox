{*
    Регистрация нового пользователя
*} 

{extends "inc/office/profile/form.tpl"}


{block profileFormWrapper}
    {if $modx->user->id}
        {include file="common/message/success.tpl" message="Вы уже авторизованы"}
    {else}
        {$smarty.block.parent}
    {/if}    
{/block}


{block profileFormRequest append}

    {if $smarty.post.reg_submit} 
        {$processor_params = array_merge($processor_params, [
            "username"  => $smarty.post.username,
            "email"     => $smarty.post.email,
            "captcha"  => $smarty.post.captcha
        ])}
    {/if}
    
{/block}


{block profileFormBody}
    {*
        Если процессор не был успешно выполнен, выводим форму для регистрации
    *}
    {if !$reg_result.success}
        {$smarty.block.parent}
    {/if} 
{/block}

