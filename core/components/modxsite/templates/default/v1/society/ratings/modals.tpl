<noindex>
    
    <!-- Modal -->
    <div class="modal fade modal-ratings" id="VotesModal_{$object.id}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3>Рейтинг {$object.pagetitle}</h3>
                </div>
                <div class="modal-body">
                     
                    <div class="row"> 
                        <div class="col-xs-4">
                            <strong>Рейтинг</strong>
                        </div>
                        <div class="col-xs-4">
                            <strong>Средний балл</strong>
                        </div>
                        <div class="col-xs-4">
                            <strong>Ваша оценка</strong>
                        </div>
                        
                        <hr />
                    </div>
                                
                    {$ids = [1353, 1355, 1350, 1354, 1352, 1371, 1360, 1361, 1364, 1351, 1365]}
                    
                    {foreach $ids as $rating_id}
                        {$rating_field = "{$rating_id}_rating"}
                        {$rating = (float){$object[$rating_field]}}
                        
                        {$title_field = "{$rating_id}_rating_title"}
                        {$title = {$object[$title_field]}}
                        
                        {$url = $modx->makeUrl($rating_id)}
                        <div class="panel panel-default">
                            
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-sm-4 col-xs-12">
                                        <a href="{$url}">{$title}</a>
                                    </div>
                                    <div class="col-sm-4 col-xs-6">
                                        {include "inc/blocks/rating_stars/widget.tpl" rating=$rating show_meta=0}
                                    </div>
                                    <div class="col-sm-4 col-xs-6">
                                        {include "inc/blocks/rating_stars/widget.tpl" rating=0 voter=1 target_id=$object.id type=$rating_id show_meta=0}
                                    </div>
                                </div>
                            </div>
                        </div>
                    {/foreach}
                
                </div>
                <div class="modal-footer ">
                    <div class="text-left">
                        Просьба: указывайте вашу оценку только если вы реально посещали это заведение.<br />
                        Кликните по соответствующей звезде рейтинга, чтобы сохранить ваш голос.<br />
                        Не обязательно указывать все рейтинги.
                    </div>
                </div>
            </div>
        </div>
    </div>
        
</noindex>
        