<table class="table table-striped">
    <tbody>
        {foreach $result.object.items as $object}
            {include $inner_tpl}
        {/foreach}
    </tbody>
</table>

