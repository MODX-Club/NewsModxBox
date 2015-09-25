{*
    Горизонтальный вывод новостей.
    Сейчас это используется для вывода в рубрике Эксперт.
    Необходимо выводить фото автора-эксперта.
*}
{extends "./default.tpl"}

{block article_params append}
    
    {$article_outer_class = "{$article_outer_class} row"}
    
{/block}

{block article_thumb}
    {$thumb = $modx->runSnippet('phpThumbOf', [
        "input" => $object.public_image|default:$object.imageDefault,
        "options"   => "&zc=C&w=80&h=80"
    ])}
{/block}

 
 
    {* 
    block article_image}
        <div class="col-xs-2">
            {$smarty.block.parent}
        </div>
    {/block}
    
    { block article_content_wrapper}
        <div class="col-xs-10">
            {$smarty.block.parent}
        </div>
         
        
    { /block *} 


