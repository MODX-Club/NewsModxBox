{*
    Выводим сайтмап с разбивкой
*}

{extends "common/list/list.tpl"}

{block list_headers}<?xml version="1.0" encoding="UTF-8"?>{/block}

{block params append}
    {$site_url = $modx->getOption('site_url')}
    {$site_start = $modx->getOption('site_start')}
    
    {$params = array_merge($params, [
        "parent"    => 0,
        "sort"      => "publishedon",
        "dir"       => "DESC",
        "showhidden"    => true,
        "limit"     => 1000,
        "where" => [
            "searchable"    => 1
        ]
    ])}
    
    {$processor = "web/resources/getdata"}
    
    {$pagination = false}
{/block}

{block name=request append} 
    {if !$result.success || !$result.object}
        {$modx->sendErrorPage()}
    {/if}
{/block}

{block fetch}
    {if !$params.page}
        {$pages = ceil($result.total / $result.limit)}
         
        {$i = 0} 
        
        <sitemapindex xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
            {while $pages > $i}
                <sitemap>
                    <loc>{$site_url}{$modx->resource->uri}?page={$i+1}</loc>
                    <lastmod>{date('Y-m-d', time() - (60*60*24*$i))}</lastmod>
                </sitemap>
                {$i = $i + 1}
            {/while}
        </sitemapindex>
    {else}
        <urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
        
            {foreach $result.object as $object}
                {if $site_start == $object.id}
                    {$url = ''}
                {else}
                    {$url = $object.uri}
                {/if}
                {if $object.publishedon}
                    {$publishedon = $object.publishedon}
                {else}
                    {$publishedon = $object.createdon}
                {/if}
                {if date('Y-m-d', $publishedon) == date('Y-m-d')}
                    {$prior = '1.0'}
                {else if $publishedon > time() - (60*60*24*3)}
                    {$prior = '0.8'}
                {else if $publishedon > time() - (60*60*24*7)}
                    {$prior = '0.5'}
                {else}
                    {$prior = '0.3'}
                {/if}
                <url>
                    <loc>{$site_url}{$url}</loc>
                    <lastmod>{date('c', $publishedon)}</lastmod>
                    <changefreq>monthly</changefreq>
                    <priority>{$prior}</priority>
                </url> 
            
            {/foreach}
        </urlset>
    {/if}
    
{/block}
