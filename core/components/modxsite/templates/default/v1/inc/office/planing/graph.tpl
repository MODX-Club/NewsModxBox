
{*
    Получаем профиты с разбивкой по дням
*}

{$days = 30}

{$params = [
    "essence_id" => 3,
    "days" => $days
]}

{processor action="economy/web/essences/count_profit" ns="modeconomy" params=$params assign=result}

<div id="visualization" class="row"></div>
 
{*
<pre>
    {print_r($result.object.types, 1)}
</pre>
    {foreach $result.object.types as $id => $type name=groups}
        groups.add({
            id: {$id},
            content: '{$type.name|escape}',
            options: {
                drawPoints: {
                    style: 'circle'
                },
                interpolation: {
                    parametrization: 'centripetal'
                }
            }
        });
    {/foreach}
*}



<script type="text/javascript"> 
    

    
window.addEventListener('DOMContentLoaded',function(){
    
    // draw_graphs({json_encode($result.object)});
    
    
});

</script>




{* 

        // {foreach $result.object.types as $id => $group}
        //     {$data = []}
        //      
        //     {foreach $result.object.days as $date => $day}
        //         {if $d = $day[$id]}
        //             {$data[] = [
        //                 "x" => "{$date}",
        //                 "y" => $d.quantity,
        //                 "label" => [
        //                     "content" => "{$d.quantity}",
        //                     "className" => "red"
        //                 ],
        //                 "group" => $id
        //             ]}
        //         {/if}
        //     {/foreach}
        //     {if $data}
        //         dataset.add({json_encode($data)});
        //     {/if}
        // {/foreach}
        
        
        // for (var i = 0; i < names.length; i++) {
        //     dataset.add( [
        //         { x: '2015-06-' + (12 + i), y: 0 , group: i},
        //         { x: '2015-06-' + (12 + i), y: 40, group: i},
        //         { x: '2015-06-' + (13 + i), y: 0 , group: i},
        //     ]);
        // }
        
*}
         
