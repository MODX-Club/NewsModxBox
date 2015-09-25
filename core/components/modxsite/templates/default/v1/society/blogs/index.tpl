{*
    Получаем все блоги
*}

{block name=params}
    {$params=[
        "limit" => 10,
        "getPage"   => 1,
        "page"  => $smarty.get.page,
        "sort"  => "menuindex",
        "dir"   => "ASC",
        "where" => [
            "template" => 14
        ]
    ]}
{/block}


{block name=pre_content}{/block}


{processor action="web/society/blogs/getdata" ns="modxsite" params=$params assign=result}

{$users_url = $modx->makeUrl(626)}

{foreach $result.object as $object}
    
    {block name=blog_tpl}
        <div class="blog-item">
            <h4><a href="{$object.uri}">{$object.pagetitle}</a></h4>
            <p class="blog-descr">{$object.content}</p>
            <p class="author">
                <a href="{$users_url}{$object.author}/">
                    {$src = $object.author_avatar}
                    {if $src}
                        <img src="{$src}" width="24" height="24" float="left"/>
                    {/if}
                    {$object.author}
                </a>
            </p>
            
        </div>
        <hr/>
    {/block}
{/foreach}

{block name=pageing}
    {ph name="page.nav"}
{/block}

