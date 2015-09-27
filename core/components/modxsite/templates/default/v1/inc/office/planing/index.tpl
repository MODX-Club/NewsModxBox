{*
    Финансовое планирование
*}


<div class="modEconomy">
    
    
    <div class="row" style="margin-bottom: 20px;">
        <div class="col-xs-12 col-md-6">
            {*
                Редактор
            *}
            {include "./editor.tpl"}
            
            
            
            
            
        </div>
        
        <div class="col-xs-12 col-md-6">
        
            <div class="row">
                <div class="col-xs-12 col-md-4 col-lg-3" style="margin-bottom: 20px;">
                    {*
                        График-сводка
                    *}
                    
                    {include "./total-graph.tpl"}
                </div>
                
                <div class="col-xs-12 col-md-8 col-lg-9" style="margin-bottom: 20px;">
                    
                    {*
                        График по дням
                    *}
                    
                    {include "./graph.tpl"}
                </div>
            </div>
            
        </div>
        
    </div>
    
    
    
</div>




