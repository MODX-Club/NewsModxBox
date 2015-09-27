{*
    Поиск по сайту
*}
{extends "inc/articles/list.tpl"}

{block news_fetch}
    
    {*
        <div class="panel panel-info">
            <div class="panel-body">
                <form action="" method="get">
                    <input class="form-control" type="text" name="query" value="{$smarty.get.query}"/>
                </form>
            </div>
        </div>
    *}
    
    <form role="form" action="" method="GET" style="margin-bottom: 30px;">
        <div class="input-group input-rs-search">
            <input type="text" id="form-top-search" name="query" placeholder="Поиск..." value="{$smarty.get.query}" class="form-control">
            <span class="input-group-btn">
                <button type="submit" class="btn btn-primary" name="sub"><i class="glyphicon glyphicon-search"></i></button>
            </span>
        </div>
    </form>
    
    {if $result.success && $result.object}
        {$smarty.block.parent}
    {else}
        <div class="alert alert-danger">
            Извините, по вашему запросу ничего не найдено.
        </div>
    {/if}
    
{/block}

