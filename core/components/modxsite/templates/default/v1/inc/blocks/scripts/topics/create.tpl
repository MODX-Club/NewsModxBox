{*
    Создание нового топика
    {$modx->regClientCSS("{$template_url}libs/select2/select2-3.5.1/select2.css")}
    
    <script type="text/javascript" src="{$template_url}libs/select2/select2-3.5.1/select2.js"></script>
*}




{*block markitup}
        <script>
            // alert($.noConflict());
            // $.noConflict();
            jQuery.noConflict();
        </script>
    <script src="{$template_url}libs/jquery/jquery-1.8.3.min.js" type="text/javascript"></script>
    <script src="{$template_url}libs/markitup/jquery.markitup.js" type="text/javascript"></script>
    <link href="{$template_url}libs/markitup/skins/synio/style.css" rel="stylesheet">
    <link href="{$template_url}libs/markitup/sets/synio/style.css" rel="stylesheet">
{/block*}

{literal}
    <script type="text/javascript">
        jQuery(function($){
    		// ls.lang.load({"panel_b":"\u0436\u0438\u0440\u043d\u044b\u0439","panel_i":"\u043a\u0443\u0440\u0441\u0438\u0432","panel_u":"\u043f\u043e\u0434\u0447\u0435\u0440\u043a\u043d\u0443\u0442\u044b\u0439","panel_s":"\u0437\u0430\u0447\u0435\u0440\u043a\u043d\u0443\u0442\u044b\u0439","panel_url":"\u0432\u0441\u0442\u0430\u0432\u0438\u0442\u044c \u0441\u0441\u044b\u043b\u043a\u0443","panel_url_promt":"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0441\u0441\u044b\u043b\u043a\u0443","panel_code":"\u043a\u043e\u0434","panel_video":"\u0432\u0438\u0434\u0435\u043e","panel_image":"\u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435","panel_cut":"\u043a\u0430\u0442","panel_quote":"\u0446\u0438\u0442\u0438\u0440\u043e\u0432\u0430\u0442\u044c","panel_list":"\u0421\u043f\u0438\u0441\u043e\u043a","panel_list_ul":"UL LI","panel_list_ol":"OL LI","panel_title":"\u0417\u0430\u0433\u043e\u043b\u043e\u0432\u043e\u043a","panel_clear_tags":"\u043e\u0447\u0438\u0441\u0442\u0438\u0442\u044c \u043e\u0442 \u0442\u0435\u0433\u043e\u0432","panel_video_promt":"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0432\u0438\u0434\u0435\u043e","panel_list_li":"\u043f\u0443\u043d\u043a\u0442 \u0441\u043f\u0438\u0441\u043a\u0430","panel_image_promt":"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u0441\u0441\u044b\u043b\u043a\u0443 \u043d\u0430 \u0438\u0437\u043e\u0431\u0440\u0430\u0436\u0435\u043d\u0438\u0435","panel_user":"\u0432\u0441\u0442\u0430\u0432\u0438\u0442\u044c \u043f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044f","panel_user_promt":"\u0412\u0432\u0435\u0434\u0438\u0442\u0435 \u043b\u043e\u0433\u0438\u043d \u043f\u043e\u043b\u044c\u0437\u043e\u0432\u0430\u0442\u0435\u043b\u044f"});
    		// Подключаем редактор
    		$('.markitup-editor').markItUp({
                onShiftEnter:      {keepDefault:false, replaceWith:'<br />\n'},
    			onTab:    		{keepDefault:false, replaceWith:'    '},
                markupSet:  [
        			{name:'H4', className:'editor-h4', openWith:'<h4>', closeWith:'</h4>' },
    				{name:'H5', className:'editor-h5', openWith:'<h5>', closeWith:'</h5>' },
    				{name:'H6', className:'editor-h6', openWith:'<h6>', closeWith:'</h6>' },
    				{separator:'---------------' },
    				{name: 'жирный', className:'editor-bold', key:'B', openWith:'(!(<strong>|!|<b>)!)', closeWith:'(!(</strong>|!|</b>)!)' },
    				{name: 'курсив', className:'editor-italic', key:'I', openWith:'(!(<em>|!|<i>)!)', closeWith:'(!(</em>|!|</i>)!)'  },
    				{name: 'зачеркнутый', className:'editor-stroke', key:'S', openWith:'<s>', closeWith:'</s>' },
    				{name: "подчеркнутый", className:'editor-underline', key:'U', openWith:'<u>', closeWith:'</u>' },
    				{name: "цитировать", className:'editor-quote', key:'Q', replaceWith: function(m) { if (m.selectionOuter) return '<blockquote>'+m.selectionOuter+'</blockquote>'; else if (m.selection) return '<blockquote>'+m.selection+'</blockquote>'; else return '<blockquote></blockquote>' } },
    				{name: "код", className:'editor-code', openWith:'<code>', closeWith:'</code>' },
    				{separator:'---------------' },
    				{name: "список", className:'editor-ul', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ul>\n', closeBlockWith:'\n</ul>' },
    				{name: "нумерованный список", className:'editor-ol', openWith:'    <li>', closeWith:'</li>', multiline: true, openBlockWith:'<ol>\n', closeBlockWith:'\n</ol>' },
    				{name: "пункт списка", className:'editor-li', openWith:'<li>', closeWith:'</li>' },
    				{separator:'---------------' },
    				// {name: "изображение", className:'editor-picture', key:'P', beforeInsert: function(h) { jQuery('#window_upload_img').jqmShow(); } },
    				{name: "вставить ссылку", className:'editor-link', key:'L', openWith:'<a href="[![Введите ссылку:!:http://]!]"(!( title="[![Title]!]")!)>', closeWith:'</a>', placeHolder:'Your text to link...' },
    				{separator:'---------------' },
    				{name: "очистить текст от тегов", className:'editor-clean', replaceWith: function(markitup) { return markitup.selection.replace(/<(.*?)>/g, "") } }
    				// ,{name: "кат", className:'editor-cut', replaceWith: function(markitup) { if (markitup.selection) return '<cut name="'+markitup.selection+'">'; else return '<cut>' }}
    			]
            
    		});
    	});
    </script>
{/literal}

{*
    				{name: ls.lang.get('panel_video'), className:'editor-video', replaceWith:'<video>[!['+ls.lang.get('panel_video_promt')+':!:http://]!]</video>' },
    				{name: ls.lang.get('panel_user'), className:'editor-user', replaceWith:'<ls user="[!['+ls.lang.get('panel_user_promt')+']!]" />' },

*}


            
            {*
        	<script type="text/javascript">
        		jQuery(document).ready(function($){
        			// ls.blog.loadInfo($('#blog_id').val());
        		});
            </script>
            *}
            
            

        



<script type="text/javascript">
    
    (function(){ 
        
        var D = {
            init:function(obj){
                this.form = obj;
                
                this.inRequest = false; 
                
                /*
                    Публикация статьи
                    this.form.find('[type=submit]').on('click', this, this.onSubmit);
                */
                
                // this.form.find('[type=submit]').on('click', this, function(){
                //     console.log(this);
                // });
                
                // console.log(this.form.find('[type=submit]'));
                
                this.form.find('input').on('focus', function(){ 
                    $(this).parents('.form-group:first').removeClass('has-error');
                });
                
                // Переключение действия
                this.form.find('.action').on('click', this, function(e){ 
                    // $(this).parents('.form-group:first').removeClass('has-error');
                    
                    console.log(this);
                            
                    var item = $(this);
                    var name = item.attr('name');
                    
                    switch(name){
                        
                        case "submit_preview":
                            
                            // $("#topik_preview").html($('.markItUpEditor').val().replace(/[\n\r]+/g, '<br />'));
                            
                            ShopMODX.Request.run('topics/preview/getcode', {
                                code: $('.markItUpEditor').val()
                            }).
                            then(function(resp){
                                console.log(resp);
                                if(resp.success){
                                    // alertify.success(resp.message || "Вы успешно авторизованы!");
                                    try{
                                        $("#topik_preview").html(resp.response.object.code);
                                    }
                                    catch(e){
                                        alertify.error("Ошибка разбора кода ответа");
                                    }
                                }
                            });
                            
                            return false;
                        
                        default:
                            // $("[name=action_type]").val(name);
                    }
                     
                    return true;
                }); 
                
                
                return this;
            } 
            
            
            ,request: function(data){
                scope = this; 
                
                if(!scope.inRequest){
                    
                    var form = scope.form; 
                    
                    scope.inRequest = true;
                    
                    scope.form.find('.has-error').removeClass('has-error');
                    
                    $.ajax({
                        url: "assets/components/modxsite/connectors/connector.php"
                        ,type: "post"
                        ,dataType: "json"
                        ,data: data
                        ,error: function(){
                            scope.inRequest = false;
                            
                            alertify.error("Ошибка выполнения запроса");
                            
                            return false;
                        }
                        ,success: function(response){ 
                            scope.inRequest = false;
                            
                            if(!response.success){
                                alertify.error(response.message || "Ошибка выполнения запроса");
                                
                                if(response.data && response.data.length){
                                    for(var i in response.data){
                                        var field, msg;
                                        field = response.data[i].id;
                                        msg = response.data[i].msg; 
                                        if(field && msg){  
                                            alertify.error(msg);
                                            
                                            form.find('[name=' + field + ']')
                                                .val('')
                                                .attr('placeholder', msg)
                                                .parents('.form-group:first')
                                                .addClass('has-error');
                                        }
                                    }
                                }
                                
                                return;
                            }
                            
                            // else
                            alertify.success(response.message || "Успешно");
                            
                            window.location.replace('/?id=' + response.object.id);
                            // scope.form[0].reset();
                            
                            return;
                        }
                    });
                }
                
            }
            
            ,onSubmit: function(e){
                
                console.log(this);
                console.log(e);
                
                var scope = e.data;
                
                var form = e.data.form;
                var data = form.serialize(); 
                
                form.find('[name=action_type]').val('');
                
                scope.request(data);
                 
                return false;
            }
        }
    
        D = Object.create(D).init($('#form-topic-add'));

    })();     
    
</script>


