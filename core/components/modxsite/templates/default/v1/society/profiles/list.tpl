{block profiles_header}{/block}
{*
    Список всех пользователей
*}

{block profiles_params}
    {$params = [
        "getPage"   => 1,
        "page"      => $smarty.get.page,
        "showinactive"  => $modx->hasPermission('view_inactive_users'),
        "showblocked"  => $modx->hasPermission('view_blocked_users')
    ]}
{/block}

{processor action="web/society/users/getdata" ns="modxsite" params=$params assign=result}

{$profiles_link = $modx->makeUrl(86895)}

{$can_view_profile = $modx->hasPermission('modxclub.view_profile')}

{block profiles_fetch}
    
    <div class="table-responsive">
        <table class="table table-striped users">
            <tbody>
            
                {foreach $result.object as $object}
                    
                    <tr>
                        
                        <td class="w10">
                            {$image = $object.photo}
                            {$thumb = $modx->runSnippet('phpthumbof', [
                                "input" => $image,
                                "options"   => "&q=100&zc=C&far=C&w=50&h=50"
                            ])}
                            <a href="{$profiles_link}{$object.username}" title="{$object.fullname|default:$object.username}"><img src="{$thumb}"/></a>
                        </td>
                    
                        <td class="w10">
                            {if !$object.active}
                                <a href="{$profiles_link}{$object.username}" style="color:#CCC;">{$object.username}</a>
                            {else if $object.blocked}
                                <s><a href="{$profiles_link}{$object.username}">{$object.username}</a></s>
                            {else}
                                <a href="{$profiles_link}{$object.username}">{$object.username}</a>
                            {/if}
                            <p class="realname">{$object.fullname}</p>
                        </td>
                        
                        <td>
                            {if $object.regdate}
                                <h5>Зарегистрирован</h5>
                                {date('Y-m-d', $object.regdate)}
                            {/if}
                        </td>
                        
                        <td>
                            {if $object.blockeduntil && $object.blockeduntil > time()}
                                <h5>Заблокирован до</h5>
                                {date('Y-m-d H:i:s', $object.blockeduntil)}
                            {/if}
                        </td>
                        
                        <td>
                            {if $can_view_profile} 
                                {$object.email}
                            {/if}
                        </td>
                        
                    </tr>
                    
                    
                {/foreach}
                
            </tbody>
        </table>
        
    </div>

{/block}

{block profiles_pagination}
    {ph name="page.nav"}
{/block}
