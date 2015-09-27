{*
    Выводим все разделы
    
    !!! Depricated
*}

{*
    Получаем все категории для вывода
    "where" => [
        "id:not in" => $column_sections
    ],
    show_on_pain_page => true
    
    {$modx->log(1, 'inc/articles/sections/list.tpl')}
*}



{$modx->runSnippet('Wayfinder', [
    "level" => 1
])}

{*
    
    {$params = [
        "sort"  => "pagetitle"
    ]}
    
    {processor action="web/resources/articles/sections/getdata" ns=modxsite params=$params assign=sections_result}
     
    
    <ul>
    { foreach $sections_result.object as $section name=sections}
    
        { * $iteration = $smarty.foreach.sections.iteration * }
        
        { *include "inc/articles/sections/list/mainpage/list.tpl" parent=$section.id * } 
         
        {$title = $section.longtitle|default:$section.pagetitle}
        <li>
            <a href="{$section.uri}" title="{$title|escape}">{$title}</a>
        </li> 
        
    {/foreach }
    </ul>
*}

