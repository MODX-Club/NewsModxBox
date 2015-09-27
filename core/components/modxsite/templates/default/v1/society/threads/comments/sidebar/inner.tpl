<div id="comment-{$comment.id}" class="comment inner-tpl">
    
    {*<pre>
        {print_r($comment)}
    </pre>
    *}
    
    <div class="comment-body ">
        <div class="comment-header">
            <div class="comment-dot-wrapper"><div class="comment-dot"></div></div>
            <strong class="login">
                
                <a href="{$modx->makeUrl(626)}{$comment.author_username}">
{*                    <img width="24" src="{$comment.author_avatar}" alt="" class="picleft"/> *}
                    {$comment.author_fullname|default:$comment.author_username}
                </a>
                
            </strong>
            
            <span class="sep">|</span>
			<span class="comment-createdon"><em>{prettydate date=strtotime({$comment.createdon})}</em></span>
            <span class="sep">|</span>
			<span class="comment-link"><a href="{$comment.resource_uri|default:$modx->resource->uri}#comment-{$comment.id}">#</a></span>
{*            <i class="comments_count">{(int)$comment.thread_comments_count}</i>*}
		</div>
        <div class="clearfix"></div>
        <div class="comment-info">
            {$comment.text|truncate:120|strip_tags:true}
        </div>
    
        <div class="clear"></div>

    </div>  
</div>
