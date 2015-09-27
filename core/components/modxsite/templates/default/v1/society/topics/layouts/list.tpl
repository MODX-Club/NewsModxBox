<div id="topic_list_{$object.id}" class="topic_list {$topic_list_col_class}">
    <div class="panel panel-default">
        
        <div class="panel-heading">
            {$title = "<h3 class=\"title\"><a href=\"{$object.uri}#overview\" class=\"entry-title\">{$object.pagetitle}</a></h3>"}
            
            {if !$object.published}
                {$title = "<s>{$title}</s>"}
            {/if}
            
            {$title} 
        </div>
        
        <div class="panel-body clearfix">
             
           
                
            {*
                Получаем блоги
            *}
            <p>
                <a href="{$object.blog_uri}"> <i class="glyphicon glyphicon-home"></i> {$object.blog_pagetitle}</a>
            </p>
            
            <div class="topic-body">
                {$short_text = $object.short_text}
                
                {if $truncate}
                    {$short_text = $short_text|truncate:500:'..':false:true}
                {/if}
                
                {$short_text}
            </div>
            
            <p>
                <a href="{$object.uri}">Читать дальше...</a>
            </p>
            
        </div>
        
        <div class="panel-footer entry-meta">
            {include file="society/topics/layouts/info.tpl"}
        </div>
        
        
    </div>
</div>