{*
    Выводится на странице всех комментариев
*}

{extends file="society/threads/comments/inner.tpl"}

{block comment_inner_params append}
    {$comment_link = "{$comments_link}comment-{$comment.id}.html"}
{/block}

{block name=added}
    <div class="detail_link">
        {if $comment.resource_pagetitle && $comment.resource_uri}
            <a href="{$comment.resource_uri}#comment-{$comment.id}" title="Комментарий к записи «{$comment.resource_pagetitle|@escape}»">{$comment.resource_pagetitle}</a>
        {else}
            <a href="{$comments_link}comment-{$comment.id}.html">Детальный просмотр комментария</a>
        {/if}
    </div>
{/block}