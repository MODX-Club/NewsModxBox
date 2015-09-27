{*
    Создание новой публикации
*}

{extends file="society/topics/form.tpl"}


{block name=TopicForm}
    
    {if !$topic_save_result.success}
        {$smarty.block.parent}
    {else}
        {if $id = $topic_save_result.object.id}
            <p>
                <a class="btn btn-primary" href="{$modx->makeUrl($id)}">Перейти к просмотру публикации</a>
            </p>
        {/if}
    {/if}
    
{/block}


