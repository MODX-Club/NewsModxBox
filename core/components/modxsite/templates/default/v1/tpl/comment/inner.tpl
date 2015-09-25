{extends "society/threads/comments/inner.tpl"}

{block added}
    <a href="{$comment.resource_uri}#comment-{$comment.id}">Комментарий</a> к топику <a href="{$comment.resource_uri}">{$comment.resource_pagetitle}</a>
{/block}