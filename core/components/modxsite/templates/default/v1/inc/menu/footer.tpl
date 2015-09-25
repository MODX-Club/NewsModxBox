{extends "common/list/list.tpl"}

{block name=params append}
    
    {$params = [
        "limit"     => 0,
        "cache"     => 1,
        "where"     => [
            "parent:in" => [0, 80, 138]
        ],
        "makeLinks" => 1
    ]}
    
    {$processor = "web/resources/getdata"}  
     
    
{/block}

{block name=request}
    {processor action=$processor ns=$ns params=$params assign=result}
{/block}

{block name=fetch}
    
    {if $result.success && $result.object}
        <ul class="{$outerClass|default:'bottom-ul-menu'}">
            {foreach $result.object as $object} 
                <li >
                    <a href="{$object.link}" title="{$object.pagetitle}">
                        {$object.menutitle|default:$object.pagetitle}
                    </a>
                </li>
            {/foreach}
        </ul>
    {/if}
    
{/block}

{block name=pagination}{/block}
