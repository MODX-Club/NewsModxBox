<div id="comment-{$comment.id}" class="comment inner-tpl">
    
    <div class="comment-body ">
        <div class="comment-header">
            <div class="comment-dot-wrapper"><div class="comment-dot"></div></div>
            <strong class="login">
                
                <a href="{$modx->makeUrl(626)}{$comment.author_username}">
                    <img width="24" src="{$comment.author_avatar}" alt="" align="left" class="picleft">
                    {$comment.author_fullname|default:$comment.author_username}
                </a>
                
            </strong>
             
            <span class="sep">|</span>
			<span class="comment-createdon"><em>{$comment.createdon}</em></span>
            <span class="sep">|</span>
			<span class="comment-link"><a href="{$comment.resource_uri|default:$modx->resource->uri}#comment-{$comment.id}">#</a></span>
		</div>
        <div class="clearfix"></div>
        <div class="comment-info">
         
        </div>
        
        {if !$comment.deleted}
        
    		<div class="comment-text {if $modx->hasPermission('moderate_comments') || $comment.createdby == $modx->user->id}editable{/if}">
                {$max_length = 200}
            	{$text = strip_tags($comment.text)}
                {$strlen = mb_strlen($text, 'utf-8')}
                
                {if $strlen > $max_length}
                    {$text = "{mb_substr($text, 0, $max_length, 'utf-8')}..."}
                {/if}
                
                {$text}
    		</div>
        {else}
            <div class="comment-text">
                <p>НЛО прилетело и опубликовало эту запись</p>
            </div>
        {/if}
    
        <div class="clear"></div>

    </div>
   
    <div class="delimiter"></div>
     
</div>
