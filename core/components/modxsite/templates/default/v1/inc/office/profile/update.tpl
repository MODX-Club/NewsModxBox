{*
    Обновление профиля и настроек подписки
*}

{extends "inc/office/profile/form.tpl"}


{block profileFormParams append}
    {$title = "Обновление профиля"}
    {$submitButtonTitle = "Сохранить"}
    {$success_message = "Профиль успешно обновлен"}
    {$processor="web/society/users/own_profile/update"}
    
    {$user = $modx->user}
    {$profile = $user->Profile}
    {$extended = $profile->get('extended')}
    
{/block}


{block profileFormRequest append}

    {if $smarty.post.reg_submit} 
        {$processor_params = array_merge($processor_params, [
            "notices"   => $smarty.post.notices,
            "new_password"   => $smarty.post.new_password
        ])}
    {else}
    
    
        {$request = array_merge($request, [
            "fullname"  => $profile->fullname,
            "company"  => $extended.company
        ])} 
    
        {$notices = []}
        {foreach $user->Notices as $Notice}
            {if $Notice->active}
                {$notices[] = $Notice->notice_id}
            {/if}
        {/foreach}
        {$request.notices = $notices}
    {/if}
    
{/block}
    

{block profileFormWrapper}
    {if !$modx->user->id}
        {include file="common/message/error.tpl" message="Необходимо авторизоваться."}
    {else}
        {$smarty.block.parent}
    {/if}    
{/block}


{block profileFormFieldsNotices}
     
            
    {*
        Получаем текущие настройки уведомлений пользователя
    *}
    {$notices = $request.notices}
    
    {*
        Выводим настройки для редактирования
    *} 
    
    <div class="form-group {if $reg_result.field_errors.email}has-error{/if}">
        <label class="col-md-3">Настройки уведомлений<small></small></label>
        <div class="col-md-9">
            {$q = $modx->newQuery('SocietyNoticeType', [
                'active'    => 1
            ])}
            {$ok = $q->sortby('rank')}
            {foreach $modx->getCollection('SocietyNoticeType', $q) as $SocietyNoticeType}
                {$field_id = "notice_type_{$SocietyNoticeType->id}"}
                <p>   
                    <input type="checkbox" data-toggle="checkbox" id="{$field_id}" name="notices[]" value="{$SocietyNoticeType->id}" {if in_array($SocietyNoticeType->id, $notices)}checked="checked"{/if}>
                    {$SocietyNoticeType->comment} 
                </p> 
            {/foreach}
        </div>
    </div> 
    
    
{/block}


{block profileFormFieldsPassword} 
    
    <div class="form-group {if $reg_result.field_errors.new_password}has-error{/if}">
        <label class="col-md-3">Новый пароль<small></small></label>
        <div class="col-md-9">
            <input type="password" autocomplete="off" value="" name="new_password" class="form-control input-hg">
            {if $reg_result.field_errors.new_password}
                <p class="text-danger">{$reg_result.field_errors.new_password}</p>
            {/if}
        </div>
    </div>
    
{/block}


{block profileFormSocietyAuth}{/block}

{block profileFormFieldsLogin}{/block}

{block profileFormFieldsEmail}{/block}

{block profileFormFieldsCaptcha}{/block}

{block profileFormFooter}{/block}
