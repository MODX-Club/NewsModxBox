
 

 

{*
    Получаем типы сущностей
*}

{$params = [
    "limit" => 0
]}

{processor action="economy/web/essences/types/getdata" ns="modeconomy" params=$params assign=result}
 

<div class="network">
    
    <div id="network-popUp">
      <span id="operation">Элемент</span> <br>
        <table style="margin:auto;">
        <tr>
            <td>id</td><td><input id="node-id" value="new value" readonly="readonly"/></td>
        </tr>
        <tr>
            <td>Название</td><td><input id="node-label" value="Новый элемент" /></td>
        </tr>
        <tr>
            <td>Тип ресурса</td>
            <td>
                <select id="node-type" name="type">
                    <option value="">Выберите из списка</option>
                    {foreach $result.object as $type}
                        <option value="{$type.id}">{$type.name}</option>
                    {/foreach}
                </select>
            </td>
        </tr> 
        
        </table>
      <input type="button" value="Сохранить" id="saveButton" />
      <input type="button" value="Отмена" id="cancelButton" />
    </div>
    
    
    <div id="edge-popUp">
        <form>
            <span class="operation">Данные связи</span> <br>
            <table style="margin:auto;">
            <tr>
                <td>from_id</td><td><input class="from-id" value="" name="essence_id" readonly="readonly"/></td>
            </tr> 
            <tr>
                <td>to_id</td><td><input class="to-id" value="" name="resource_id" readonly="readonly"/></td>
            </tr> 
            <tr>
                <td>Дата С</td><td><input class="datefrom" value="{date('Y-m-d')}" name="datefrom"  placeholder="YYYY-MM-DD"/></td>
            </tr> 
            <tr>
                <td>Дата по</td><td><input class="datefrom" value=""  name="datetill" placeholder="YYYY-MM-DD"/></td>
            </tr>
            <tr>
                <td>Количество</td><td><input value=""  name="quantity" /></td>
            </tr>
            
            </table>
          <input type="button" value="Сохранить" class="saveButton" />
          <input type="button" value="Отмена" class="cancelButton" />
        </form>
    </div>
    
    
    <div id="mynetwork" style="height: 800px;"></div>
    
</div> 


{*
    Получаем ресурсы объекта
*}

{$params = [
    "limit"     => 0
]}

{processor action="economy/web/essences/getdata" ns="modeconomy" params=$params assign=result}

{$nodes = []}

{foreach $result.object as $node}
    {$node.label = $node.name|default:$node.resource_name}
    {$n =[
        "id" => $node.id,
        "type" => $node.type,
        "name" => $node.name,
        "description" => $node.description,
        "label" => "{$node.label}"
        ,
        scaling => [
            min => 10,
            max => 30
        ]
    ]}
    
    {if $node.x && $node.y}
        {$n = array_merge($n, [
            x => $node.x
            ,y => $node.y
        ])}
    {/if}
    
    {if $node.type_icon}
        {$n = array_merge($n, [
            "shape" => "circularImage",
            "shadow"    => false,
            "image" => "{$template_url}img/modeconomy/{$node.type_icon}",
            "color" => [
            ],
            "font" => [
                "background"    => "#ffffff",
                "strokeWidth"   => 10
            ]
        ])}
    {/if}
    
    {$nodes[] = $n}
{/foreach}
 

{*
    Получаем связи
*}

{$params = [
    "limit"     => 0
]}

{processor action="economy/web/essences/processes/getdata" ns="modeconomy" params=$params assign=result}

{$edges = []}

{foreach $result.object as $edge}

    {if $edge.quantity > 0}
        {$color = 'green'}
    {else if 0 > $edge.quantity}
        {$color = 'red'}
    {else}
        {$color = 'blue'}
    {/if}
    
    {if $edge.quantity != 0}
        {$label = $edge.quantity}
    {else}
        {$label = ''}
    {/if}
    

    {$edges[] = [
        "id" => $edge.id,
        "from" => $edge.essence_id,
        "to" => $edge.resource_id,
        "label" => $label,
        "color" => $color
    ]}
{/foreach}

{*
<pre>
    {print_r($edges)}
</pre>
*}



<script type="text/javascript">
 
    
    var nodes = {json_encode($nodes)};

    // create an array with edges
    var edges = {json_encode($edges)};
    
    var data = {
        nodes:nodes, 
        edges:edges
    };
    
    window.addEventListener('DOMContentLoaded',function(){
        draw(data);
    });

</script>
