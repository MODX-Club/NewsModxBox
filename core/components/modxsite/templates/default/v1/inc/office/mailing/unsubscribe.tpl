{*
    Отписка от новостей
*}

{$key = $smarty.get.key}
{$uid = (int)$smarty.get.uid}

{if !$key}
    {include "common/message/error.tpl" message="Не был указан ключ"}
{else if !$uid}
    {include "common/message/error.tpl" message="Не был указан пользователь"}
{else}

    {$auth_link_salt = $modx->getOption('modsociety.auth_link_salt')}
    {$user = $modx->getObject('modUser', $uid)}
    
    {if !$user}
        {include "common/message/error.tpl" message="Не был получен пользователь"}
    {* Проверяем подпись *}
    {else if md5("{$user->id}{$user->Profile->email}{$auth_link_salt}") != $key}
        {include "common/message/error.tpl" message="Неверная подпись"}
    {else}
        
        {foreach $user->Notices as $notice}
            {$ok = $notice->remove()}
        {/foreach}
        
        {include "common/message/success.tpl" message="Вы успешно отписаны от рассылки.<br />Настроить подписку вы можете в своем профиле."}
        
    {/if}
    
{/if}
