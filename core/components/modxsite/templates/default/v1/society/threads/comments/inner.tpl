{block comment_inner_params}
    {$comment_link = "{$comment.resource_uri|default:$modx->resource->uri}#comment-{$comment.id}"}
{/block}

<div id="comment-{$comment.id}" class="comment inner-tpl">
     
    <div class="comment-body panel panel-default">
        <div class="comment-header panel-heading">
            <div class="comment-dot-wrapper"><div class="comment-dot"></div></div>
            <strong class="login">
                
                <a href="{$profiles_link}{$comment.author_username}">
                    {if $comment.author_avatar}
                        <img width="24" src="{$comment.author_avatar}" alt="" class="picleft">
                    {/if}
                    {$comment.author_fullname|default:$comment.author_username}
                </a>
                
            </strong>
            
    		<!--span class="comment-author"><a href="/users/tralsheg">tralsheg</a></span-->
            <span class="sep">|</span>
			<span class="comment-createdon"><em>{$comment.createdon}</em></span> 
            
            <span class="sep"> | </span>&nbsp;
            {$rating = (float)($comment.rating)}
            
            {$votes = []}
            {if $comment.positive_votes}
                {$votes[] = "{$comment.positive_votes} за"}
            {/if}
            {if $comment.negative_votes}
                {$votes[] = "{$comment.negative_votes} против"}
            {/if}
            {if $comment.neutral_votes}
                {$votes[] = "{$comment.neutral_votes} {$comment.neutral_votes|spell:'воздержался':'воздержалось':'воздержалось'}"}
            {/if}
            
            <a href="javascript:;" class="glyphicon glyphicon-thumbs-down comment_vote" rel="nofollow" data-vote-direction="down"></a>
                <span title="{implode(', ',$votes)}" class="rating act {if $rating > 0}text-primary{elseif $rating < 0}text-danger{/if}" data-type="total-rating">{$rating}</span>
            <a href="javascript:;" class="glyphicon glyphicon-thumbs-up comment_vote" rel="nofollow" data-vote-direction="up"></a>
            
            <span class="sep">|</span>
			<span class="comment-link"><a href="{$comment_link}"><i class="glyphicon glyphicon-link"></i></a></span>
		</div>
        
        <div class="panel-body">
        
            {if !$comment.deleted}
            
        		<div class="comment-text {if $modx->hasPermission('moderate_comments') || $comment.createdby == $modx->user->id}editable{/if}">
                	{$text = $comment.text}
                    {if $truncate}
                        {$text = $text|truncate:300:'..':false:true}
                    {/if}
                    {$text}
        		</div>
            {else}
                <div class="comment-text">
                    <p>НЛО прилетело и опубликовало эту запись</p>
                </div>
            {/if}
            
        </div>
     

        
        <div class="panel-footer">
            {block name=added}
                {if $canReply}
                    <div class="comment-reply" data-comment-obj="society-comment-cont" data-comment-id="{$comment.id}">
                        {*
                    	    <a data-comment-obj="society-comment-action-reply" class="btn btn-primary btn-sm comment-button" href="javascript:void(0)"><i class="glyphicon glyphicon-pencil"></i> Ответить</a>
                        *}
                    	<a data-comment-obj="society-comment-action-reply" class="btn btn-primary btn-sm comment-button" href="javascript:void(0)"><i class="glyphicon glyphicon-plus"></i> Ответить</a>
                        
                        {*if ($modx->hasPermission('moderate_comments') || $comment.createdby == $modx->user->id) && !$comment.deleted}
                            <a data-comment-obj="society-comment-action-edit" href="javascript:void(0)">Редактировать</a>
                        {/if*}
                        
                        {if ($modx->hasPermission('moderate_comments')) && !$comment.deleted}
                            <a data-comment-obj="society-comment-action-edit" href="javascript:void(0)">Редактировать</a>
                        {/if}
                        
                        {if !$comment.deleted && $modx->hasPermission('set_comments_as_deleted')}
                            <a data-comment-obj="society-comment-action-hide" href="javascript:void(0)">Скрыть</a>
                        {/if}
                        
                        {if $modx->hasPermission('delete_comments')}
                            <a data-comment-obj="society-comment-action-remove" href="javascript:void(0)">Удалить</a>
                        {/if}
                        
                        <div data-comment-obj="society-comment-text" class="reply_block"></div>
                        
                    </div>
                {/if}
            {/block}    
        </div>
         
    </div>
    
 

    
    {*
    <div class="delimiter"></div>
        Если есть дочерние комментарии, выводим их
    *}
    {if $comment.children}
        {$comments = $comment.children}
        {$level = $level+1}
        {include file="society/threads/comments/outer.tpl"}
    {/if}
</div>
