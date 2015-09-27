{$level = $level|default:1}
{$comments_inner_tpl = $comments_inner_tpl|default:"society/threads/comments/inner.tpl"}
 
{$comments_link = $modx->makeUrl(86893)}
{$profiles_link = $modx->makeUrl(86895)}

<div class="comment-list outer-tpl level-{$level}">
    {*count($comments)*}
    
    {foreach $comments as $comment}
        {include file=$comments_inner_tpl}
    {/foreach}
</div>
