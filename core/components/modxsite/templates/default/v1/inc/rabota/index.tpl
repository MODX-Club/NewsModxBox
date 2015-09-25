{*
    https://github.com/hhru/api
*}

{extends "common/list/list.tpl"}



{block name=params append}


    {*
        Редиректим старые адреса с параметром
    *}
    {if $vacancyid = (int)$smarty.get.vacancyid}
        {$modx->sendRedirect("/rabota/vacancy_{$vacancyid}", [
            'responseCode'  => 'HTTP/1.1 301 Moved Permanently'
        ])}
    {/if}

    
    {if $smarty.get.vacancies_submit}
        {$request = $smarty.get}
    {else}
        {$request = []}
    {/if}
    
    {$request = array_merge([
        "salary"    => 30000
    ], $request)}
    
    {$request.salary = str_replace(" ", "", $request.salary)}
     
    
    {$params = [ 
        "cache"     => 0,
        "page"      => (int)$smarty.get.page,
        "text"      => $request.text,
        "area"      => (int)$request.area|default:1,
        "specialization"      => (int)$request.specialization,
        "salary"      => (int)$request.salary
    ]}
    
    {$processor = "web/api/hh/vacancies/request"} 
    
    {$outer_tpl = "inc/rabota/list/outer.tpl"}
    {$inner_tpl = "inc/rabota/list/inner.tpl"}
    
    {*
    <pre>
        {print_r($params)}
    </pre>
    *}
    
{/block}


{block name=request append} 
    <noindex>
        <form action="" method="get">
            <div class="panel panel-default">
            
                <div class="panel-heading">
                    <img class="pull-right" src="{$template_url}img/logo/logo-hh-h30.png"/>
                    <h3 class="text-danger no-margin">Хорошая работа <small>для деловых людей</small></h3>
                </div>
                
                <div class="panel-body">
                    <div class="form-group">
                        <input type="text" class="form-control" name="text" value="{$request.text}" placeholder="Поиск вакансий">
                    </div>
                    
                    <div class="form-group">
                    
                        <div class="row">
                            <div class="col-md-4">
                                
                                {processor action="web/api/hh/areas/request" ns=modxsite assign=areas_result}
                                
                                {if $areas_result.success && $areas_result.object}
                                    <select name="area" class="form-control">
                                    
                                        <option value="{$areas_result.object.id}">{$areas_result.object.name}</option>
                                        
                                        {foreach $areas_result.object.areas as $area}
                                            <option value="{$area.id}" {if $request.area == $area.id}selected="selected"{/if}>{$area.name}</option>
                                        {/foreach}
                                        
                                    </select>
                                {/if}
                                
                            </div>
                            <div class="col-md-4">
                                
                                {processor action="web/api/hh/specializations/request" ns=modxsite assign=specializations_result}
                                
                                {if $specializations_result.success && $specializations_result.object}
                                    <select name="specialization" class="form-control"> 
                                        <option value="">Все профессиональные области</option>
                                    
                                        {foreach $specializations_result.object as $specialization}
                                            <option value="{$specialization.id}" {if $request.specialization == $specialization.id}selected="selected"{/if}>{$specialization.name}</option>
                                        {/foreach}
                                        
                                    </select>
                                {/if}
                                
                            </div>
                            <div class="col-md-4 form-horizontal">
                                <div class="form-group">
                                    <label for="salary" class="col-md-4 col-lg-3 control-label">Зарплата</label>
                                    <div class="col-md-8 col-lg-9">
                                        <input type="text" class="form-control" id="salary" name="salary" value="{((int)$request.salary)|number_format:0:".":" "}" placeholder="От">
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    
                </div>
                
                <div class="panel-footer">
                    <button class="btn btn-success" type="submit" name="vacancies_submit" value="1">Найти вакансии</button>
                </div>
                
            </div>
        </form>
    </noindex>
{/block}


{block fetch}

    {if $result.success && $result.object.items}
        <div class="alert alert-info">
            Найдено <span class="text-danger">{$result.found}</span> {$result.found|spell:"вакансия":"вакансии":"вакансий"}
        </div>
    {else}
        <div class="alert alert-danger">
            По вашему запросу ничего не найдено
        </div>
        
        {*
        <pre>
            {print_r($result)}
        </pre>
        *}
    {/if}

    {$smarty.block.parent}
    
{/block}

