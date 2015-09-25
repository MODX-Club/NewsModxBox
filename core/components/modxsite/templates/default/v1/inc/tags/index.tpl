<div class="tagcloud text-center">
       
    {$params = [
        limit   => $limit|default:50,
        sort    => $sort|default:"pagetitle",
        dir     => $dir|default:"ASC"
    ]}
    
    {processor action="web/resources/tags/getdata" ns=modxsite params=$params assign=result}
    
    {foreach $result.object as $tag}
        <span class="tl-tag tl-tag-weight{$tag.rating}"><a href="{$tag.uri}">{$tag.pagetitle}</a></span>
    {/foreach} 

</div>