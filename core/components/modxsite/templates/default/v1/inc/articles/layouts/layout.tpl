{block article_headers}{/block}
{*
    Базовый шаблон для вывода карточки новости
*}

{block article_params}
    
    {$title = $object.longtitle|default:$object.pagetitle}
    
    {$image_class = "img img-responsive"}
    
    {$article_outer_class = 'article'}
    
{/block}

{block article_thumb}
    {$thumb = $modx->runSnippet('phpThumbOf', [
        "input" => $object.public_image|default:$object.imageDefault,
        "options"   => "&iar=1&zc=T&w=600&h=400"
    ])}
{/block}

{block article_fetch}
    <div class="{$article_outer_class}">
        
        {block article_wrapper}
        
            {block article_image}
                <a class="article_image_wrapper" href="{$object.uri}" title="{$title|escape}">
                    <img class="{$image_class}" src="{$thumb}" title="{$title|escape}"/>
                </a>
            {/block}
        
            {block article_content_wrapper}
                <div class="article_content_wrapper">
                    {block article_title}
                        <a class="article_title_wrapper" href="{$object.uri}" title="{$title|escape}">
                            <h3 class="title">
                                {$title}
                            </h3>
                        </a>
                    {/block}
                    
                    {block article_summary}
                        <a class="summary" href="{$object.uri}" title="{$title|escape}">
                            {$object.summary|default:$object.introtext}
                        </a>
                    {/block}
                    
                    {block article_content}{/block}
                </div>
            
            {/block}
        
        {/block}
        
    </div>
{/block}

