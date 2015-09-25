{extends "../inner.tpl"}

{block params}
 
    
    {$options = "q=99&w=600&h=400&iar=1"} 
     
    
{/block}

{block inner_content}
    
    <div class="col-md-8">
        <img src="{$src}" class="img-responsive">
    </div>
    
    <div class="col-md-4">
    
        <h4 class="title">{$object.pagetitle}</h4>
        
        <div class="summary">
            {$object.summary}
        </div>
        
        <div class="row data">
            <div class="col-md-6 date">
                {$date}
            </div>
            <div class="col-md-6 text-right">
                <a href="{$object.uri}" class="more">Читать далее...</a>
            </div>
        </div>
        
    </div>
     
    
        
         
    
     
{/block}
