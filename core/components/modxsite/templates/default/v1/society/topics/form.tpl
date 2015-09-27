
{block TopicFormWrapper}

    
    {block name=TopicFormParams}
        
        {$processor = 'web/society/topics/articles/create'}
        
        {$request = []}
        
        {$title = "Новая публикация"}
        
    {/block}
    
    
    {block TopicFormRequest}
        
        
        {if $smarty.post.topic_publish}
            {$processor_params = [
                "pagetitle" => $smarty.post.pagetitle,
                "content" => $smarty.post.content
            ]}
            
            {$request = array_merge($request, $processor_params)}
            
        {/if}
        
    {/block}
    
    {block TopicFormProcessor}
        
        {if $smarty.post.topic_publish}
            {processor action=$processor ns=modxsite params=$processor_params assign=topic_save_result}
        {/if}
        
    {/block}
    
    
    <h2>{$title}</h2>
    
    
    {if $topic_save_result}
        
        {if $topic_save_result.success}
            {include "common/message/success.tpl" message=$topic_save_result.message|default:"Публикация успешно сохранена"}
        {else}
            {include "common/message/error.tpl" message=$topic_save_result.message|default:"Ошибка выполнения запроса"}
            
            {if $topic_save_result.field_errors}
                <div class="alert alert-warning">
                    {foreach $topic_save_result.field_errors as $field => $message}
                        <p>{$message}</p>
                    {/foreach}
                </div>
            {/if}
        {/if} 
        
    {/if}
    
    {block TopicForm}
        
        {*
            Проверяем авторизован ли пользователь
        *}
        
        {if $modx->user->Profile}
            {$extended = $modx->user->Profile->get('extended')}
        {else}
            {$extended = []}
        {/if}
        
        {if !$modx->user->id}
        
            <div class="alert alert-danger"> 
                <a data-target="#LoginModal" data-toggle="modal" href="javascript:;">Авторизуйтесь</a> или <a href="[[~[[++modhybridauth.registration_page_id]] ]]">зарегистрируйтесь</a> (можно через соцсети {include file="inc/login/social_auth_button.tpl"}), чтобы создать публикацию.
            </div>
        
        {else if !$modx->user->Profile->fullname || !$extended.company}
            <div class="alert alert-danger"> 
                {if !$modx->user->Profile->fullname}
                    <p>Не указано ФИО.</p>
                {/if}
                {if !$extended.company}
                    <p>Не указана компания.</p>
                {/if}
                <p>Пожалуйста, <a href="[[~[[++modxsite.profile_resource_id]] ]]">обновите профиль</a>.</p>
            </div>
        {else}
        
            <form class="wrapper-content" id="form-topic-add" enctype="multipart/form-data" method="POST" action="">
             
                <input type="hidden" name="pub_action" value="{$action|default:'topic/save'}" />
            	<input type="hidden" name="topic_id" value="{$topic->id}" />
                
                
                {*
                    Получаем все доступные блоги
                    {$params=[
                        "check_for_post"    => 1,
                        "limit" => 0
                    ]} 
                    
                    {if $topic->TopicBlogs} 
                        {$blog = $topic->TopicBlogs|@current}
                        {$blog_id = $blog->blogid}
                        {$params.blogs = $blog_id}
                    {else}
                        {$blog_id = 0}
                    {/if} 
                    
                    
                    {if $modx->hasPermission('write_in_blogs')}
                    	<p><label for="blogs">В какой блог публикуем?</label>
                    	<select class="form-control input-width-full"  id="blogs" name="blogs">
                                
                            {if !$blog_id}
                        		<option value="0">Мой персональный блог</option>
                            {/if}
                            
                            {processor action="web/society/blogs/getdata" ns="modxsite" params=$params assign=result}
                             
                            {foreach $result.object as $blog}
                    			<option value="{$blog.id}" {if $blog_id == $blog.id}selected{/if}>{$blog.pagetitle}</option>
                            {/foreach}
                                
                    	</select>
                    	<small class="note">Для того чтобы написать в определенный блог, вы должны, для начала, вступить в него.</small></p>
                    {else}
                        <input type="hidden" name="blogs" value="{$blog_id}" />
                    {/if}
                *}
                
            
            	
            	
            	<div class="form-group {if $topic_save_result.field_errors.pagetitle}has-error{/if}">
                    <label for="topic_title">Заголовок:</label>
                	<input type="text" class="form-control input-text input-width-full" value="{$request.pagetitle}" name="pagetitle" id="topic_title">
                	<small class="note">Заголовок должен быть наполнен смыслом, чтобы можно было понять, о чем будет публикация.</small>
                </div>
            
            	<div class="form-group">
                	<label for="topic_text">Текст:</label>
                	<textarea rows="20" class="form-control mce-editor markitup-editor input-width-full" id="topic_text" name="content">{$request.content}</textarea> 
                    
                    <p>
                        <a onclick="$('#tags-help').toggle(); return false;" class="btn btn-info link-dotted help-link" href="javascript:;"><i class=" glyphicon glyphicon-info-sign"></i> Доступны html-теги</a>
                    </p>
                    <dl id="tags-help" class="help clearfix alert alert-info" style="display:none;">
                        {*
                    	<dt class="help-col help-wide">
                    		<h3>Специальные теги</h3>
                        </dt>
                    	<dt class="help-col help-left">
                        
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;cut&gt;</strong></h4>
                    			Используется для больших текстов, скрывает под кат часть текста, следующую за тегом (будет написано «Читать дальше»).
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;cut name="Подробности"&gt;</strong></h4>
                    			Так можно превратить надпись «Читать дальше» в любой текст.
                    		</div>
                            
                        		<div class="help-item">
                        			<h4><a data-insert="&lt;video&gt;&lt;/video&gt;" class="link-dashed js-tags-help-link" href="#">&lt;video&gt;http://...&lt;/video&gt;</a></h4>
                        			Добавляет в пост видео со следующих хостингов: YouTube, RuTube, Vimeo и Я.Видео. <br>Вставляйте между тегами только прямую ссылку на видеоролик.
                        		</div>
                        		<div class="help-item">
                        			<h4><a data-insert="&lt;ls user=&quot;&quot; /&gt;" class="link-dashed js-tags-help-link" href="#">&lt;ls user="Ник" /&gt;</a></h4>
                        			Выводит имя пользователя посреди текста.
                        		</div>
                            
                    	</dt>
                        *}
                            
                    	<dt style="margin-top: 20px;" class="help-col help-wide">
                    		<h3>Стандартные теги</h3>
                    	</dt>
                    	<dt class="help-col help-left">
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;h4&gt;&lt;/h4&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;h5&gt;&lt;/h5&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;h6&gt;&lt;/h6&gt;</strong></h4>
                    			Заголовки разного уровня.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;img src="" /&gt;</strong></h4>
                    			Вставка изображения, в атрибуте src нужно указывать полный путь к изображению. Возможно выравнивание картинки атрибутом align.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;a href="http://..."&gt;Ссылка&lt;/a&gt;</strong></h4>
                    			Вставка ссылки, в атрибуте href указывается желаемый интернет-адрес или якорь (anchor) для навигации по странице.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;b&gt;&lt;/b&gt;</strong></h4>
                    			Выделение важного текста, на странице выделяется жирным начертанием.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;i&gt;&lt;/i&gt;</strong></h4>
                    			Выделение важного текста, на странице выделяется курсивом.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;s&gt;&lt;/s&gt;</strong></h4>
                    			Текст между этими тегами будет отображаться как зачеркнутый.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;u&gt;&lt;/u&gt;</strong></h4>
                    			Текст между этими тегами будет отображаться как подчеркнутый.
                    		</div>
                    	</dt>
                    	<dd class="help-col help-right">
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;hr /&gt;</strong></h4>
                    			Тег для вставки горизонтальной линии.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;blockquote&gt;&lt;/blockquote&gt;</strong></h4>
                    			Используйте этот тег для выделения цитат.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;table&gt;&lt;/table&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;th&gt;&lt;/th&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;td&gt;&lt;/td&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;tr&gt;&lt;/tr&gt;</strong></h4>
                    			Набор тегов для создания таблицы. Тег &lt;td&gt; обозначает ячейку таблицы, тег &lt;th&gt; - ячейку в заголовке, &lt;tr&gt; - строчку таблицы. Все содержимое таблицы помещайте в тег &lt;table&gt;.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;ul&gt;&lt;/ul&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;li&gt;&lt;/li&gt;</strong></h4>
                    			Ненумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ul&gt;.
                    		</div>
                    		<div class="help-item">
                    			<h4><strong class="text-warning">&lt;ol&gt;&lt;/ol&gt;</strong></h4>
                    			<h4><strong class="text-warning">&lt;li&gt;&lt;/li&gt;</strong></h4>
                    			Нумерованный список; каждый элемент списка задается тегом &lt;li&gt;, набор элементов списка помещайте в тег &lt;ol&gt;.
                    		</div>
                    	</dd>
                    </dl> 
                </div>
                
            
        	
                
                {*
                    Теги топика
                    <div class="form-group"> 
                    	<label for="topic_tags">Теги:</label>
                        
                    	<input type="text" class="form-control input-width-full" value="{$topic->topic_tags}" name="topic_tags" id="topic_tags" > 
                    	<small class="note">Теги нужно разделять запятой. Например: google, вконтакте, кирпич</small>
                    </div>
                *}
             
            
                {*
                    Источник
                    {if $topic->id} 
                        {$original_source = $topic->getTVValue('original_source')}
                    {/if}
                     
                    <div class="form-group">
                    	<label for="original_source">Источник:</label>
                        
                    	<input type="text" class="form-control input-width-full" value="{$original_source}" name="original_source" id="original_source"  placeholder="Начинаться должна с http:// или https:// и вести на конечную страницу">
                    	<small class="note">Ссылка на оригинал статьи (полная), если статья репостится из другого источника.</small>
                    </div>
                *}
            
            
            
            
                {*
                    Не отправлять
                    {block no_send_mails_field}
                        {if $modx->hasPermission('modxclub.send_notices')}
                            <br />
                            <div>
                                <input type="checkbox" id="no_send_emails" value="1" name="no_send_emails"/>
                                <label for="no_send_emails">Не отправлять емейл-рассылку пользователям портала</label>
                            </div>
                        {/if}
                    {/block}
                     
                    {if $modx->hasPermission('change_links_follow')}
                        <br />
                        <div>
                            <input type="checkbox" id="links_follow" value="1" checked="checked" name="links_follow"/>
                            <label for="links_follow">Индексируемые ли ссылки.</label><br />
                        	<small class="note">Для всех сторонних статей, ссылки в которых не должны индексироваться поисковиками, галочку надо снимать.</small>
                        </div>
                    {/if}
                *}
            
            
                {*
                    Типы уведомлений
                    {block topic_edit_notices}
                        {if $modx->hasPermission('select_notices')}
                            <br />
                            
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    Учитывать настройки уведомлений. Если указать, будут отправлены уведомления только для выбранных типов.
                                </div>
                                
                                <div class="panel-body">
                                    {$q = $modx->newQuery('SocietyNoticeType', [
                                        "target"    => 'modResource'
                                    ])}
                                    {$ok = $q->sortby('rank')}
                                    {foreach $modx->getCollection('SocietyNoticeType', $q) as $SocietyNoticeType}
                                        {$field_id = "notice_type_{$SocietyNoticeType->id}"}
                                        <div class="form-group">   
                                            <input type="checkbox" data-toggle="checkbox" id="{$field_id}" name="notices[]" value="{$SocietyNoticeType->id}">
                                            {$SocietyNoticeType->comment} 
                                        </div> 
                                    {/foreach}
                                </div>
                            </div>
                        {/if}
                    {/block}
                *}
        
                {*
            	<p><label><input type="checkbox" value="1" class="input-checkbox" name="topic_forbid_comment" id="topic_forbid_comment">
            	Запретить комментировать</label>
            	<small class="note">Если отметить эту галку, то нельзя будет оставлять комментарии к публикации</small></p>
            	
                <p><label><input type="checkbox" value="1" class="input-checkbox" name="topic_publish_index" id="topic_publish_index">
            	Принудительно вывести на главную</label>
            	<small class="note">Если отметить эту галку, то публикация сразу попадёт на главную страницу (опция доступна только администраторам)</small></p>
            
            	<input type="hidden" value="topic" name="topic_type">
            	<input type="hidden" name="action_type" value="" />
                *}
            	
                <br />
            	 
            	<button class="btn btn-info action" name="submit_preview" type="button">Предпросмотр</button> 
                {*
        	        <button class="btn btn-warning action" id="submit_topic_save" name="save_draft" type="button">Сохранить в черновиках</button>
                *}
            	<button class="btn btn-success" id="submit_topic_publish" name="topic_publish" value="1" type="submit">Опубликовать</button>
            	
            	
                
                {*
                *}
            
            </form>
            
        {/if}
        
        <div id="topik_preview"></div>
        
    {/block}
        
    


{/block}



