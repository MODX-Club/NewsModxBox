{block comments_header}{/block}
{*
    Выводим все комментарии по топикам
*}

{block name=params}

    {$params = [
        "limit" => 20
    ]}
    
    {if (int)$smarty.get.page > 1}
        {$params.start = ((int)$smarty.get.page-1)*$params.limit}
    {/if}
    
{/block}


{processor action="web/society/topics/comments/getdata" ns="modxsite" params=$params assign=comments_result}

{$comments = $comments_result.object}

{block name="pre_content"}{/block}

{* Выводим комментарии *}
{$comments_inner_tpl = $comments_inner_tpl|default:"society/threads/comments/all/inner.tpl"}

{block comments_fetch}
    {include file="society/threads/comments/outer.tpl" truncate=$truncate}
{/block}


{block name=paging}
    
    {*
        Есть фейковые страницы (типа профилей, их комментов и т.п.).
        Надо реализовать постраничность с учетом фейковоро адреса.
        Пока просто на уровне ID-шников будем рулить.
    *}
    
    {$id = $modx->resource->id}
    
    
    {* Активность *}
    {if $id == 990}
        {$username = $modx->getOption('RouteUsername')}
        {$href = "profile/{$username}/stream"}
        {$href_resource_id = 1}
    {else}
        {$href = ""}
        {$href_resource_id = $modx->resource->id}
    {/if}
    
    {pagination items=$comments_result.total limit=$params.limit current=$smarty.get.page|default:1 resource_id=$href_resource_id prev_next=true assign=pagination}
    
    
    
    <div class="pagination">
        {assign var=prev value=1}
    
        {if $pagination.prev.id}
        <a href="{$href}{$pagination.prev.href}">предыдущая</a>
        {/if}
       
        {foreach from=$pagination.pages item=page name=pagination}                 
            {if (($page.id-$prev) > 1)}
            <span>...</span>
            {/if}
            {if $page.type == 'current'}
            <span class="current">{$page.id}</span>
            {else}
            <a href="{$href}{$page.href}">{$page.id}</a>
            {/if}
        {assign var=prev value=$page.id}    
        {/foreach} 
    
        {if $pagination.next.id}
        <a href="{$href}{$pagination.next.href}">следующая</a>
        {/if}
         
    </div>
{/block}