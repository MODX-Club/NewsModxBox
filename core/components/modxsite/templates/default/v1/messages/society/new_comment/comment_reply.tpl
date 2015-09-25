{extends file="messages/society/layout.tpl"}

{block name=content}

    {$options = $auth_params|default:[]} 
    
    {$topic_url = $modx->makeUrl($topic.id, '', $options, 'full')}
    На ваш комментарий был получен <a href="{$topic_url}#comment-{$comment.id}">ответ</a>. <br /><br />
    <blockquote class="gmail_quote" style="margin: 0px 0px 0px 0.8ex; border-left-width: 1px; border-left-color: rgb(204, 204, 204); border-left-style: solid; padding-left: 1ex;">{$comment.text}</blockquote>
    <br /><br />
{/block}
 