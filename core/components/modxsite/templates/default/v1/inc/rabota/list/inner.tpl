<tr>
    <td>
        <a href="{$modx->resource->uri}vacancy_{$object.id}.html" class="text-primary" target="_blank">{$object.name}</a>
        
        {if $object.employer.name}
            <br /><small>{$object.employer.name}{if $object.area.name}, ({$object.area.name}){/if}, {date('Y-m-d', $object.created_at|strtotime)}</small>
        {/if}
    </td>
    <td>
        {if $object.salary}
            {if $object.salary.from}
                От {$object.salary.from}
            {/if}
            {if $object.salary.to}
                До {$object.salary.to}
            {/if} RUR
        {else}
            По договоренности
        {/if}
    </td>
</tr>
