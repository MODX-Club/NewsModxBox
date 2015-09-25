{$image = $object.public_image|default:$object.imageDefault}

{block inner_lit_params} 

    {$options = "q=99&w=300&h=200&iar=1"} 
     
    
{/block}

{$src = $modx->runSnippet('phpthumbof', [
    "input" => $image,
    "options"   => $options
])}

{$date = date('d.m.Y', $object.publishedon)}
 
{block inner_content}
    {$title = $object.longtitle|default:$object.pagetitle}
    
    <div class="article">
        
        <a href="{$object.uri}" title="{$title|escape}" class="no_underline" >
            <img src="{$src}" class="img-responsive" title="{$title|escape}">
            
            <h4 class="title"><div class="title-inner">{$object.pagetitle}</div></h4>
        </a>
        
        
        <div class="summary">{trim(strip_tags($object.summary))}</div>
        
        <div class="data">{$date}</div>
        
    </div>
     
{/block}


