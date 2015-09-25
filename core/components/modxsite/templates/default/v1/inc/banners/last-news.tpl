{extends "inc/articles/list.tpl"}

{block news_params append}
    {$pagination=false}
    {$params.page=0}
    {$params.limit = $limit|default:10}
    {$params.in_news_list_only = 1}
{/block}
 

{block news_fetch}

    <div class="last-news" style="border: 1px solid #DDD; ">
        <div class="title">
            Последние новости
        </div> 
        
        <div class="last-news-list-wrapper font-small">
        
            {foreach $result.object as $object name=last_news}
                
                {$date = date('d/m/y', $object.publishedon|default:$object.createdon)}
                
                <div class="last-news-inner {if $smarty.foreach.last_news.last}last{/if}">
                    <a href="{$object.uri}">
                        <span style="font-weight: bold; color: #d61920;">{$date}</span>
                        {$object.longtitle|default:$object.pagetitle}
                    </a>
                </div>
                
            {/foreach}
            
            
            <a class="all_news_link" href="[[~185]]">Все новости</a>
            
        </div>
        
    </div>

{/block}
 