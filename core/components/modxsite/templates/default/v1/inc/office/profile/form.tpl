{block profileFormHeaders}{/block}
{*
    Форма создания/редактирования пользователя
*}

{block profileFormWrapper}

    {block profileFormParams}
        {$title = "Регистрация"}
        {$submitButtonTitle = "Зарегистрироваться"}
        {$processor="web/society/users/create"}
        {$success_message = "Вы успешно зарегистрированы"}
    {/block}
    
    
    {block profileFormRequest}
        {$request = []}
        
        {if $smarty.session.social_profile && $smarty.session.social_profile.provider && $smarty.session.social_profile.profile}
            {$social_profile = $modx->newObject('modHybridAuthUserProfile', $smarty.session.social_profile.profile)}
            {$request.email = $social_profile->email}
            {$request.fullname = trim("{$social_profile->firstName} {$social_profile->lastName}")}
            {$request.username = $social_profile->displayName}
        {/if}
         
        {if $smarty.post.reg_submit}
            {$request = array_merge($request, [
            ])}
            
            {$processor_params = [
                "fullname"  => $smarty.post.fullname,
                "company"  => $smarty.post.company
            ]}
            
        {else}
            {$processor_params = []}
        {/if}
        
    {/block}
    
    
    {block profileFormProcessor}
    
        {$request = array_merge($request, $processor_params)}
    
        {*
            Результат выполнения процессора
        *}
        {if $smarty.post.reg_submit}
            {processor action=$processor ns="modxsite" params=$processor_params assign=reg_result}
            {$modx->error->reset()}
            
            {if $reg_result.success}
                {include file="common/message/success.tpl" message=$reg_result.message|default:$success_message}
            {else}
                {include file="common/message/error.tpl" message=$reg_result.message|default:"Ошибка выполнения запроса"}
            {/if}
        {/if}
    {/block}
    
    
    {block profileFormBody}
        <form id="reg_form" class="form-horizontal" method="post" action="">
    
            <div class="panel panel-default">
                
                <div class="panel-heading">
                    {$title}             
                </div>                
                        
                        
                <div class="panel-body">
                    {*
                        <input type="hidden" name="pub_action" value="registration" />
                    *}
                    
                    
                    {block profileFormSocietyAuth}
                        <div class="alert alert-info">
                         
                            {if $social_profile}
                                {*
                                    Если пользователь авторизован в соцсети, сообщаем ему об этом, чтобы меньше путаницы было
                                *} 
                                <p class="text-primary">Вы успешно авторизованы в социальной сети, осталось для регистрации заполнить пару полей.</p>
                            {else}
                                {*
                                    Иначе выводим иконки для регистрации через соцсети
                                *}
                                <div>
                                    <span class="text-primary">Так же можно зарегистрироваться через социальные сети: </span>
                                    
                                    {include file="inc/login/social_auth_button.tpl"}
                                    
                                </div>
                            {/if} 
                            
                        </div>
                    {/block}
                     
                    
                    {block profileFormFieldsLogin}
                        <div class="form-group {if $reg_result.field_errors.username}has-error{/if}">
                            <label class="col-md-3">Логин<span class="text-danger">*</span><small></small></label>
                            <div class="col-md-9">
                                <input type="text" class="form-control input-hg" name="username" value="{$request.username}" autocomplete="off">
                                {if $reg_result.field_errors.username}
                                    <p class="text-danger">{$reg_result.field_errors.username}</p>
                                {/if}
                            </div>
                        </div>
                    {/block}
                     
                     
                    {block profileFormFieldsEmail}
                        <div class="form-group {if $reg_result.field_errors.email}has-error{/if}">
                            <label class="col-md-3">Емейл<span class="text-danger">*</span> <small></small></label>
                            <div class="col-md-9">
                                <input type="email" class="form-control input-hg" name="email" value="{$request.email}" autocomplete="off">
                                {if $reg_result.field_errors.email}
                                    <p class="text-danger">{$reg_result.field_errors.email}</p>
                                {/if}
                            </div>
                        </div> 
                    {/block}
                    
                    <div class="form-group {if $reg_result.field_errors.fullname}has-error{/if}">
                        <label class="col-md-3">ФИО<span class="text-danger">*</span><small></small></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control input-hg" name="fullname" value="{$request.fullname}" autocomplete="off">
                            {if $reg_result.field_errors.fullname}
                                <p class="text-danger">{$reg_result.field_errors.fullname}</p>
                            {/if}
                        </div>
                    </div>
                    
                    
                    {block profileFormFieldsPassword}{/block}
                    
                    
                    <div class="form-group {if $reg_result.field_errors.company}has-error{/if}">
                        <label class="col-md-3">Компания<small></small></label>
                        <div class="col-md-9">
                            <input type="text" class="form-control input-hg" name="company" value="{$request.company}">
                            {if $reg_result.field_errors.company}
                                <p class="text-danger">{$reg_result.field_errors.company}</p>
                            {/if}
                        </div>
                    </div>
                    
                    {block profileFormFieldsCaptcha}
                        {if !$social_profile}
                            
                            {if $request.captcha && !$reg_result.field_errors.captcha}
                                <input type="hidden" name="captcha" value="{$request.captcha}"/>
                            {else}
                                <div class="form-group {if $reg_result.field_errors.captcha}has-error{/if}">
                                    <label class="col-md-3">Проверочный код с картинки<span class="text-danger">*</span> <small></small></label>
                                    
                                    <div class="col-md-6">
                                        <input type="text" class="form-control input-hg" name="captcha" value="" autocomplete="off">
                                        {if $reg_result.field_errors.captcha}
                                            <p class="text-danger">{$reg_result.field_errors.captcha}</p>
                                        {/if}
                                    </div>
                                    
                                    <div class="col-md-3">
                                        {chunk name=modcaptcha}
                                    </div>
                                </div>
                            {/if}
                                          
                        {/if}
                    {/block}
                    
                    {block profileFormFieldsNotices}{/block}
                
                </div>
                
                <div class="panel-footer">
                    <input type="submit" class="btn btn-success btn-wide" name="reg_submit" value="{$submitButtonTitle}" />
                </div> 
                 
            </div>
            
        </form>
    {/block}
    
    
    {block profileFormFooter}
        {if !$reg_result.success}   
            <div class="alert alert-info">
                Если вы были зарегистрированы ранее, можете попробовать <a href="[[~108]]">восстановить пароль</a>
            </div>
        {/if}
    {/block}
    
{/block}
