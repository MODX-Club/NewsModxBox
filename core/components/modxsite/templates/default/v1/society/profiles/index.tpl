{$username=$modx->getOption('RouteUsername')}

{if $username}

    {$user=$modx->getObject('modUser',[
        "username" => $username
    ])}
    
    {if $user}
    
        {$profile=$user->Profile}
        
        {$extended=$profile->get('extended')}
        
        {$photo = $extended.main_photo}
            
        {if !$photo}
            {$avatar = $profile->photo}
        {/if}
            
        {if !($photo || $avatar)}
            {$photo="avatarDefault.png"}
        {/if}

        {$baseUrl = $modx->runSnippet('getSourcePath', [
            "id"    => $modx->getOption('modavatar.default_media_source', null, 1)
        ])}
         
        <div class="profile panel panel-default">
        
            <div class="panel-heading">
                <h2>{$profile->fullname|default:$user->username}</h2> 
            </div>
            
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-3">
                        {if $photo || $avatar}
                            {$image = "{$baseUrl}{$photo|default:$avatar}"}
                            {$thumb = $modx->runSnippet('phpThumbOf', [
                                "input" => $image,
                                "options"   => "w=300"
                            ])}
                            <img class="avatar img-responsive" src="{$thumb}"/>
                        {/if}
                        
                        {$comments_count = $modx->getCount('SocietyComment', [
                            "createdby" => $user->id,
                            "deleted"   => 0
                        ])}
                        
                        {if $comments_count}
                            Написал <a href="people/{$username}/stream/">{$comments_count} {$comments_count|spell:"комментарий":"комментария":"комментариев"}</a>
                        {else}
                            {$comments_count} {$comments_count|spell:"комментарий":"комментария":"комментариев"}
                        {/if}
                        
                    </div>
                    
                    <div class="col-md-6" >
                        {if $profile->comment}
                            <h4>О себе:</h4>
                            <p>{$profile->comment}</p>
                        {/if}
                    </div>
                    
                    {*
                        Группы пользователя
                    *}
                    <div class="col-md-3">
                        
                        {$q = $modx->newQuery('modUserGroup')}
                        {$ok = $q->innerJoin('modUserGroupMember', 'UserGroupMembers')}
                        {$ok = $q->where([
                            "UserGroupMembers.member"   => $user->id,
                            "parent"    => 8
                        ])}
                        {$ok = $q->sortby("modUserGroup.rank")}
                        
                        {if $groups = $modx->getCollection('modUserGroup', $q)}
                            <h4>Привилегии:</h4>
                            
                            <ul>
                                {foreach $groups as $group}
                                    <li>{$group->name}</li>
                                {/foreach}
                            </ul>
                        
                        {else}
                         
                        
                        {/if}
                        
                        
                        
                        
                    </div>
                    
                </div>
                
                {if $regedon = $user->createdon}
                    <p>Зарегистрирован: {date('Y-m-d', $regedon)}</p>
                {/if}
                <p>Количество входов: {$profile->logincount}</p>
                
                <p></p>
                <div class="row">
                    <div class="col-md-5 col-sm-5">
                    
                        <h4>Контакты:</h4>
                        
                        {if $modx->hasPermission('modxclub.view_profile')}
                            {if $profile->email
                                || $profile->fax
                                || $profile->phone
                                || $profile->mobilephone
                                || $profile->website}
                                <ul class="profile-contacts">
                                    {if !empty($profile->email)}
                                        <li class="pc-email"><a href="mailto:{$profile->email}">{$profile->email}</a></li>
                                    {/if}
                                    {if !empty($profile->fax)}
                                        <li class="pc-skype"><a href="skype:{$profile->fax}?chat">{$profile->fax}</a></li>
                                    {/if}
                                    {if !empty($profile->phone)}
                                        <li class="pc-phone"><span>{$profile->phone}</span></li>
                                    {/if}
                                    {if !empty($profile->mobilephone)}
                                        <li class="pc-mobilephone"><span>{$profile->mobilephone}</span></li>
                                    {/if}
                                    {if !empty($profile->website)}
                                        <li class="pc-website"><a href="{$profile->website}">{$profile->website}</a></li>
                                    {/if}
                                </ul>
                            {/if}
                        
                        {else}
                            <p class="text-danger">
                                У вас нет прав видеть контактные данные пользователей.
                            </p>
                            <p>Если у вас есть вопросы или предложения, можете воспользоваться формой обратной связи на <a href="{$modx->makeUrl(84113)}">странице контактов</a>.</p>
                        {/if}
                    
                    </div>
                    <div class="col-md-2 col-sm-2">
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-11">
                        {if $profile->gender
                            || $profile->dob
                            || $profile->city
                            || $profile->country}
                            <h4>Личное:</h4>
                            <ul class="profile-dotted-list">
                                {if !empty($profile->gender)}
                                    {$gender=[1=>'мужской',2=>'женский']}
                                    <li><span>Пол:<span> {$gender[$profile->gender]}</span></span></li>
                                {/if}
                                {if !empty($profile->dob)}
                                    <li><span>Дата рождения:<span> {$profile->dob|date_format:'%d-%m-%Y'}</span></span></li>
                                {/if}
                                {if !empty($profile->country)}
                                    <li><span>Страна:<span> {$profile->country}</span></span></li>
                                {/if}
                                {if !empty($profile->city)}
                                    <li><span>Город:<span> {$profile->city}</span></span></li>
                                {/if}
                            </ul>
                        {/if}
                    </div>
                </div>
            </div>
            
        </div>
    {/if}
    

{/if}
