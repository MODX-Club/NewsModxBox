{*
    Свежий номер газеты
*}

{if !$modx->user->id}
    <div class="panel panel-warning">
        <div class="panel-heading">
            Ошибка доступа
        </div>
        
        <div class="panel-body">
            <h4>
                <a href="[[~[[*parent]] ]]" class="link">Здесь</a> Вы можете ознакомиться с условиями подписки на газету "Деловая газета.ЮГ ".<br />
            Если Вы являетесь подписчиком электронной версии газеты " Деловая газета.ЮГ ", пожалуйста, <a href="javascript:;" data-target="#LoginModal" data-toggle="modal" >авторизуйтесь</a>.
            </h4>
        </div>
        
        <div class="panel-footer">
            <a href="javascript:;" data-target="#LoginModal" data-toggle="modal" class="btn btn-success">Авторизоваться</a>
            <a href="[[~104]]" class="btn btn-primary">Зарегистрироваться</a>
        </div>
    </div>

{else}
    
    {*
        Получаем последний журнал
    *}
    
    
    {*
        {$q = $modx->newQuery('modResource', [
            "parent"    => $modx->resource->id
        ])}
        
        {$ok = $q->sortby('publishedon', 'DESC')}
        
        {$doc = $modx->getObject('modResource', $q)}
    *}
    
    {$subscribe_till = $modx->user->subscribe_till}
    
    {if $subscribe_till > time() OR $modx->hasPermission('frames')}
        {*
            Выводим последний номер
            {$image = "uploads/{$doc->get('image')}"}
            
            <div class="row">
                <div class="col-md-6">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <a href="{$doc->uri}" target="_blank"><h4>{$doc->pagetitle}</h4></a>
                        </div>
                        
                        <div class="panel-body">
                            <a href="{$doc->uri}" target="_blank"><img src="{$image}" class="img img-responsive"/></a>
                        </div>
                        
                        <div class="panel-footer">
                            <a href="{$doc->uri}" class="btn btn-primary" target="_blank">Скачать номер</a>
                        </div>
                    </div>
                </div>
            </div> 
        *} 
        
        {*
            Получаем все номера
        *}
        
        {$params = [
            "parent"    => $modx->resource->id,
            "limit" =>  6,
            "page"  => $smarty.get.page,
            "sort"  => "publishedon",
            "dir"   => "DESC"
        ]} 
        
        {processor action="web/resources/getdata" ns=modxsite params=$params assign=result}
         
        {if $result.success && $result.object}
        
            <div class="row">
            
                {foreach $result.object as $object}
                    {$image = $object.image|default:$object.imageDefault}
                    
                    {$thumb = $modx->runSnippet('phpThumbOf', [
                        "input" => "uploads/{$image}",
                        "options"   => "&q=99&iar=1&w=590&h=840"
                    ])}
                    
                    <div class="col-md-6 col-lg-4">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="{$object.uri}" target="_blank"><h4>{$object.pagetitle}</h4></a>
                            </div>
                            
                            <div class="panel-body">
                                <a href="{$object.uri}" target="_blank"><img src="{$thumb}" class="img img-responsive"/></a>
                            </div>
                            
                            <div class="panel-footer">
                                <div class="pull-right text-icon text-primary">
                                    <i class="glyphicon glyphicon-eye-open"></i><i>{$object.views}</i>
                                </div>
                                <a href="{$object.uri}" class="btn btn-primary" target="_blank">Скачать номер</a>
                            </div>
                        </div>
                    </div>
                    
                {/foreach} 
                
            </div>
            
            {include "common/pagination/pagination.tpl"}
            
        {else}
            <div class="alert alert-danger">
                <h4>{$result.message|default:"Не были получены данные журналов"}</h4>
            </div>
        {/if}
        
    {else}
        <div class="alert alert-danger">
            <h4>У вас нет активной подписки для просмотра этого раздела.</h4>
            <p>По вопросам подписки обращайтесь в редакцию по телефону (861) 217 18 50 или напишите <a href="mailto:siv@dgazeta.ru">siv@dgazeta.ru</a>.</p>
        </div>
    {/if}
    
    
    
{/if}