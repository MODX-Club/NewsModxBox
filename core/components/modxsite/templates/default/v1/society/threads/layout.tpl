{block thread_header}{/block}
{*
    Общий шаблон для страниц, у которых могут быть комментарии
*}

{block thread_params}
    {$canReply = (bool)$modx->user->id}
    {$users_url = $modx->makeUrl(626)}
    
    {block name=params}{/block}
{/block}

{block thread_request}{/block}

{block thread_body}

    {if $object}
        
    
        {if $object.thread_id}
        
            {$params = [
                "thread_id" => $object.thread_id,
                "showdeleted"    => $modx->hasPermission('modsociety.moderate_comments'),
                "limit"         => 0
            ]}
            
            {processor action="web/society/threads/comments/getdata" ns="modxsite" params=$params assign=comments_result}
              
        {/if}
        
        
        <div id="topic_list_{$modx->resource->id}" class="topic_list">
            
            <div>
                  
                 
                
                {block thread_content}
                     
                    
                     
                    {block thread_object_content}
                        {$object.content}
                    {/block}
                    
                     
                    
                    {block thread_post_content}{/block}
                    
                     
                {/block}
            
                 
                {block thread_footer}
                    {*include file="society/topics/layouts/info.tpl"*}
                {/block}
                  
                
                
            </div>
            
            {block thread_comments}
                {include "society/threads/comments/thread_comments.tpl"}
            {/block}
            
        
            {block thread_comments_post_content}
            {/block}
            
        </div>
            
        
        
        
        {*
        
            				{name: ls.lang.get('panel_b'), className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
            				{name: ls.lang.get('panel_i'), className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
            				{name: ls.lang.get('panel_s'), className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
            				{name: ls.lang.get('panel_u'), className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
            				{separator:'---------------' },
            				{name: ls.lang.get('panel_quote'), className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
            				{name: ls.lang.get('panel_code'), className:'editor-code', openWith:'<code>', closeWith:'</code>' },
            				{name: ls.lang.get('panel_image'), className:'editor-picture', key:'P', beforeInsert: function(h) { jQuery('#window_upload_img').jqmShow(); } },
            				{name: ls.lang.get('panel_url'), className:'editor-link', key:'L', openWith:'<a href="[!['+ls.lang.get('panel_url_promt')+':!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
            				{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },
            				{separator:'---------------' },
            				{name: ls.lang.get('panel_clear_tags'), className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } }
            
        *}
        
        
        
    {else}
        <h4 class="error">{$result.message|default:"Не были получены данные топика"}</h4>
    {/if}


    
    
{/block}

{block thread_footer}{/block}
