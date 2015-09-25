{*
    Информационная строка для топика
*}

{$src = $object.author_avatar}

{$users_url = $modx->makeUrl(86895)}

<div class="clearfix"></div>

<p class="date">
    <span class="login">
        <a title="" href="{$users_url}{$object.author_username}">{if $src}<img src="{$modx->runSnippet('phpThumbOf', ['input'   => $src, 'options' => '&q=99&w=24&h=24&zc=C'])}" width="24" height="24" alt=""/>{else}<i class="glyphicon glyphicon-user"></i>{/if}
            {$object.author_fullname|default:$object.author_username}
        </a>
    </span>
    <span class="sep"> | </span>
    
    {if $object.publishedon}
        {$date = $object.publishedon}
    {else}
        {$date = $object.createdon}
    {/if} 
    
    {date('Y-m-d', $date)}
	<span class="sep"> | </span>
	<span class="comments"><a title="" href="{$object.uri}#comments"><i class="glyphicon glyphicon-comment"></i> {(int)$object.comments_count} </a></span>
    
    
    {*
        Рейтинги. Для заведений отдельный рейтинги
        {block topic_ratings}
            {$rating = (float)($object.positive_votes-$object.negative_votes)}
        	<span class="sep"> | </span>&nbsp;
             
            <a href="javascript:;" class="glyphicon glyphicon-thumbs-down topic_vote" rel="nofollow" vote_direction="down"></a>
            	<span title="" class="rating act {if $rating > 0}text-success{elseif $rating < 0}text-danger{/if}">{$rating}</span>
            <a href="javascript:;" class="glyphicon glyphicon-thumbs-up topic_vote" rel="nofollow" vote_direction="up"></a>
        {/block}
    *}
    
    
    
    {if $object.createdby == $modx->user->id || $modx->hasPermission('moderate_topics')}
        
        {$topic_edit_url = $modx->makeUrl(1140)}
    	<span class="sep"> | </span>
        <a title="Редактировать топик" class="text-info" href="{$topic_edit_url}?tid={$object.id}"><i class="glyphicon glyphicon-edit"></i></a>
    
    {/if}
    
    {if $modx->hasPermission('moderate_topics')}
    	 
     
        {if !$object.published}
            <a title="Опубликовать топик" class="text-primary" href="javascript:;"><i class="glyphicon glyphicon-ok-sign"></i></a>
        {else}
            <a title="Снять с публикации топик" class="text-warning" href="javascript:;"><i class="glyphicon glyphicon-ban-circle"></i></a>
        {/if}
        <a title="Удалить топик" class="text-danger" href="javascript:;"><i class="glyphicon glyphicon-remove-sign"></i></a>
        
    {/if}
</p>

{if $topic_tags = $object.topic_tags}
    {$tags = explode(',', $topic_tags)}
    {$tags_array = []}
    
    {foreach $tags as $tag}
        {$tags_array[] = "<a href=\"tag/{$tag}/\" title=\"{$tag}\">{$tag}</a>"}
    {/foreach}
    <div>
        Теги: {implode(", ", $tags_array)}
    </div>
{/if}
