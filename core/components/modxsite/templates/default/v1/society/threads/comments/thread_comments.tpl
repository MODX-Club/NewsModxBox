
                <a name="comments"></a>
                    
                <h3>{(int)$object.comments_count} {((int)$object.comments_count)|spell:"комментарий":"комментария":"комментариев"}</h3>
                
                {*if $object.comments_count}
                {/if*}
                
                {*
                    Если ветка была получена, получаем имеющиеся комменты
                *} 
                
                {if !$modx->user->id}
                    {include "society/threads/comments/auth.tpl"}
                {/if}
                
                <div id="comments-wrapper">
                
                    {if $object.thread_id}
                        
                        {if $comments_result.success && $comments_result.object}
                            
                            {$comments = $comments_result.object}
                            
                            {* Выводим комментарии *}
                            {include file="society/threads/comments/outer.tpl"}
                             
                        {/if}
                        
                    {/if}
                
                </div>
                
                {if $modx->user->id}
                    <div class="comment-reply">
                        
                        {if $object.published}
                        <p id="comment_id_0" data-comment-id="0" class="reply-header">
                            <a class="btn btn-warning btn-sm comment-button" href="javascript:;"><i class="glyphicon glyphicon-comment"></i> Оставить комментарий</a>
                        </p>
                        {/if}
                        
                    </div>
                    
                
                    <div id="reply" class="reply" style="display:none;">    	
            			<form method="post" id="form_comment" onsubmit="return false;" enctype="multipart/form-data">
            				
            				
            				<textarea name="text" id="form_comment_text" class="form-control mce-editor comment-markitup-editor" rows="6"></textarea> 
            				
            				
            				<button type="submit"  name="submit_comment" 
            						id="comment-button-submit"  
            						class="btn btn-success"><i class="glyphicon glyphicon-ok"></i> Опубликовать комментарий</button>
            				
                            {*
                                <button type="button" onclick="ls.comments.preview();" class="button">предпросмотр</button>
                            *}
            				
            				<input type="hidden" name="parent" value="0" id="form_comment_reply" />
            				<input type="hidden" name="target_id" value="{$object.id}" />
            				<input type="hidden" name="pub_action" value="topics/comments/save" />
            			</form>
            		</div>
                
                {else if $object.comments_count > 3}
                    {include "society/threads/comments/auth.tpl"}
                {/if} 
                     
            