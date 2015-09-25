<div class="row articles-list">
    {foreach $result.object as $object name=articles}
        {include $inner_tpl iteration=$smarty.foreach.articles.iteration}
    {/foreach}
</div>