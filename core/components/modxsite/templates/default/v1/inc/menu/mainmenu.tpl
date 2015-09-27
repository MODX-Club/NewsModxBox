{extends "common/list/list.tpl"}

{block name=params append}
    
    {$server_protocol = $modx->getOption('server_protocol')}
    {$mobile_host = $modx->getOption('mobile_host')}
    {$mobile_host_url = "{$server_protocol}://{$mobile_host}/"}
    
    {$params = [
        "limit"     => 0,
        "cache"     => 1,
        "includeTVs"     => 0,
        "where"     => [
            "parent:in"     => [0, 80],
            "id:not in"     => [80,86855,86958]
        ],
        "sort"      => "menuindex",
        "makeLinks" => true
    ]}
    
    {$processor = "web/resources/getdata"}  
     
    
{/block}

{block name=request}
    {processor action=$processor ns=$ns params=$params assign=result}
{/block}

{block name=fetch}
    
    {$site_start = $modx->getOption('site_start')}
    
    {if $result.success && $result.object}
        <ul class="nav navbar-nav">
            {foreach $result.object as $object}
                {*$params = array_merge($params, [
                    "where"     => [
                        "parent"    => $object.id
                    ]
                ])} 
                
                {processor action="web/resources/getdata" ns="modxsite" params=$params assign=submenu_result}
                {if $submenu_result && $submenu_result.object}
                    {$class = 'dropdown'}
                    {$attrs = 'class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"'}
                    {$caret = '<span class="caret"></span>'}
                {else}
                    {$class = ''}
                    {$attrs = ''}
                    {$caret = ''}
                {/if*}
                
                <li class="{$class} {if $modx->resource->id == $object.id OR $modx->resource->parent == $object.id}active{/if}">
                    <a href="{if $object.id == $site_start}/{else}{$object.link}{/if}" title="{$object.pagetitle|@escape}" {$attrs} {$object.attributes}>{$object.menutitle|default:$object.pagetitle}
                         
                    </a>
                    {*if $submenu_result && $submenu_result.object}
                        <ul class="dropdown-menu" role="menu">
                            {foreach $submenu_result.object as $sub_object}
                                <li>
                                    <a href="{$sub_object.link}" title="{$sub_object.pagetitle|@escape}">{$sub_object.menutitle|default:$sub_object.pagetitle}</a>
                                </li>
                            {/foreach}
                        </ul>
                    {/if*}
                </li>
            {/foreach}
        </ul>
    {/if}
    
        <ul class="nav navbar-nav navbar-right small">
            [[!smarty?tpl=`inc/login/auth.tpl`]]
            <li><a href="{$modx->makeUrl(43)}">RSS</a></li>
            
            {*
            <li><a href="{$mobile_host_url}?view_mode=mobile">Мобильная версия</a></li>
            *}
        </ul>
    
{/block}

{block name=pagination}{/block}
