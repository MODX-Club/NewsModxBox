{*
    Получаем профиты с разбивкой по дням
*}

{$days = 30}

{$params = [
    "essence_id" => 3,
    "days" => $days
]}

{processor action="economy/web/essences/count_profit" ns="modeconomy" params=$params assign=result}


{$date = date('Y-m-d')}

{$items = []}

{*foreach $result.object.types as $id => $type}
    groups.add({
        id: {$id},
        content: '{$type.name|escape}'
    });
    
    {$items[] = [
        "x" => $date,
        "y" => $type.quantity,
        "label" => [
            "content" => "{$type.quantity}"
        ],
        "group" => $id
    ]}

{/foreach*}  


<div id="total-graph-visualization"></div>

<script type="text/javascript">
 

window.addEventListener('DOMContentLoaded',function(){
    // drawTotalGraph({json_encode($result.object.types)});
});

</script>


