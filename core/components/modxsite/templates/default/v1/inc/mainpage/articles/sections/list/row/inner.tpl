{extends "../inner.tpl"}

{*block params} 

    {$options = "q=99&w=150&h=112"}  
    
{/block*}

{*block inner_content} 
    
    <td width="20%" valign="top">
        <a style="text-decoration: none; color: black;" href="{$object.uri}">
            <img border="0" src="{$src}" style=" margin-right: 10px; margin-bottom: 10px;"><br>
            <p style="font-weight: bold; font-size: 12px; text-decoration: none;" class="h3">{$object.longtitle|default:$object.pagetitle}</p>
        </a>
    </td>

{/block*}
