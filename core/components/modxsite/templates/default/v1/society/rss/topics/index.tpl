<?xml version="1.0" encoding="UTF-8"?>
{$link = $modx->makeUrl($modx->resource->id, '', '', 'full')}
<rss version="2.0" xmlns:atom="http://www.w3.org/2005/Atom" xmlns:dc="http://purl.org/dc/elements/1.1/">
<channel>
    <title>{$modx->getOption('site_name')} | {$modx->resource->longtitle}</title>
    <link>{$link}</link>
    <description><![CDATA[{$modx->resource->introtext}]]></description>
    <language>{$modx->getOption('cultureKey')}</language>
    <ttl>120</ttl>
    <atom:link href="{$link}" rel="self" type="application/rss+xml" />
    
    {$params = [
        "sort"  => "id",
        "dir"   => "DESC",
        "summary"   => true
    ]}
    
    {processor action="web/society/topics/getdata" ns="modxsite" params=$params assign=result}
    
    {foreach $result.object as $object}
        {$url = $modx->makeUrl($object.id, '', '', 'full')} 
        <item>
            <title>{$object.pagetitle}</title>
            <link>{$url}</link>
            <description>
              <![CDATA[{$object.summary}]]>
            </description>
            <pubDate>{date('Y-m-d\TH:i:sP', $object.publishedon)}</pubDate>
            <guid isPermaLink="false">{$url}</guid>
            <dc:creator>
                {$object.author}
            </dc:creator>
        </item>
        
    {/foreach}
    
    
</channel>
</rss>