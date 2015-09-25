{block news_headers}{/block}

{*
    Пока оставляю как заготовку.
    В настоящий момент используется блоком последних новостей и RSS
*}

{block news_params}

    {$pagination = $pagination|default:false}
    
    {if $pagination}
        {$page = $page|default:(int)$smarty.get.page}
    {else}
        {$page = 0}
    {/if}
    
    {$params = [
        "limit"     => $limit|default:12,
        "parent"    => $parent|default:0,
        "section"   => $section_id|default:0,
        "page"      => $page,
        "query"     => $query|default:$smarty.get.query,
        "top_news_only"     => $top_news_only|default:false,
        "main_news_only"     => $main_news_only|default:false,
        "in_news_list_only" => $in_news_list_only|default:0,
        "summary"   => true,
        "truncLen"  => 100,
        "sort"      => "modResource.publishedon",
        "dir"       => "DESC",
        "cache"     => 1
    ]}
    
    {$exclude = (array)$exclude|default:[$modx->resource->id]}
    
    {if $ids = (array)$modx->resource->getOption('MainNewsIDs', null)}
        {$exclude = array_merge($exclude, $ids)}
    {/if} 
    
    {*
    <pre>
        {print_r($ids)}
    </pre>
    *}
    
    {if $exclude}
        {*
            <pre>
                {print_r($exclude)}
            </pre>
        *}
        {$params.where['id:not in'] = $exclude}
    {/if}

    {$processor = $processor|default:"web/resources/articles/getdata"}
    
    {*
        {$inner_tpl = "inc/articles/layouts/list/default.tpl"}
        {$outer_tpl = "inc/articles/sections/list/outer.tpl"}
    *}
    {$inner_tpl = "inc/articles/inner.tpl"}
    {$outer_tpl = "inc/articles/outer.tpl"}
    
       
    {$show_no_records_error = $show_no_records_error|default:false}
    {$no_records_error = "Записи не были получены"}
     
    {$fetch = 1}
    
{/block}

 
{block name=news_request} 
    {*
    <pre>
        {print_r($params, 1)}
    </pre>
    *}
    
    {processor action=$processor ns=modxsite params=$params assign=result} 
            
    {*
        Устанавливаем полученные айдишники, 
        чтобы на главной не выводились эти новости в других рубриках.
    *}
    
    {if $result.success && $result.object}
        {$ids = array_merge($ids, array_keys($result.object))}
        {$modx->resource->setOption('MainNewsIDs', $ids)}
    {/if}
    
{/block}


{block news_fetch}
    
    {*
    <pre>
        {print_r($params, 1)}
    </pre>
    <pre>
        {print_r($result, 1)}
    </pre>
    *}

    {if $result.success && $result.object}
        {include $outer_tpl}
    {/if}
{/block}


{block name=pagination}
    {if $pagination}
        {include "common/pagination/pagination.tpl"}
    {/if}
{/block}

 




