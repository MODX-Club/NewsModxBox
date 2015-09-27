{extends file="messages/society/layout.tpl"}

{block name=content}

    {$options = $auth_params|default:[]} 
    
    {$topic_url = $modx->makeUrl($topic.id, '', $options, 'full')}
    
    В топике, где вы принимали участие, был создан новый <a href="{$topic_url}#comment-{$comment.id}">комментарий</a>. <br /><br />
    
    <blockquote>{$comment.text}</blockquote>
    <br /><br />
{/block}
