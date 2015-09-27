{extends file="tpl/index.tpl"}


{block name=page_params append}

    {$comment = $comments|@current}
    
    {$last_modified = strtotime($comment.createdon)}
    
    {$title = $comment.text|@strip_tags|@truncate}
    
    {$pagetitle = "{$title} | {$comment.resource_pagetitle} | {$site_name}"} 
    
{/block}


{block name=title}{$pagetitle}{/block}

{block name=pagetitle}<h1>{$title}</h1>{/block}

{block name=content}

    {include file="society/threads/comments/outer.tpl" comments_inner_tpl="tpl/comment/inner.tpl"} 
    
{/block}

{block description}{$description = "Комментарий к топику \"{$comment.resource_pagetitle}\""}{$description|strip_tags|escape}{/block}

