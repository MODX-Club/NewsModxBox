{*
    Выводим активность пользователя (комментарии)
*}
{extends file="society/topics/comments/index.tpl"}

{block name=params append}
    {$username = $modx->getOption('RouteUsername')}
    {$params = array_merge($params, [
        "where" => [
            "CreatedBy.username" => $username
        ]
    ])}
    
    {*$comments_inner_tpl = $comments_inner_tpl|default:"society/threads/comments/profile/stream/inner.tpl"*}
    
{/block}

