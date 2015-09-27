{*
    Шаблон вакансии.
    Переменная $vacancy формируется во вьюхе
*}

{extends "tpl/index.tpl"}



{block content}
    
    {if $vacancy}
        <h3>{$vacancy.name}</h3>
    
        <div class="row">
            
            <div class="col-md-4 alert alert-info">
                <small>Уровень зарплаты</small>
                <h4 class="no-margin">
                    {if $vacancy.salary}
                        
                        {if $vacancy.salary.from}От {$vacancy.salary.from}{/if}
                        {if $vacancy.salary.to}До {$vacancy.salary.to}{/if}
                        {$vacancy.salary.currency}
                        
                    {else}
                        По договоренности
                    {/if}
                </h4>
            </div>
            
            <div class="col-md-4 alert alert-info">
                <small>Регион</small>
                <h4 class="no-margin">
                    {$vacancy.area.name}
                </h4>
            </div>
            
            <div class="col-md-4 alert alert-info">
                <small>Требуемый опыт работы </small>
                <h4 class="no-margin">
                    {$vacancy.employment.name}
                </h4>
            </div>
            
        </div>
        
        {$vacancy.description}
        
        <a href="{$vacancy.alternate_url}" class="btn btn-danger" target="_blank" rel="nofollow">Откликнуться на вакансию</a>
        <a href="{$modx->makeUrl($modx->resource->parent)}" class="btn btn-info">Поиск вакансий</a>
    {else}
        <div class="alert alert-danger">
            Не были получены данные вакансии
        </div>
    {/if}
    
{/block}
