{extends file="messages/society/layout.tpl"}

{block name=content}

    {$options = $auth_params|default:[]} 
    
    {$topic_url = $modx->makeUrl($topic.id, '', $options, 'full')}
    
    В вашем топике был создан новый <a href="{$topic_url}#comment-{$comment.id}">комментарий</a>. <br /><br />
    
    <blockquote>{$comment.text}</blockquote>
    <br /><br />
{/block}
 


 

<hr />
С уважением, администрация сайта.<br />
<a href="{$site_url}">{$site_url}</a>