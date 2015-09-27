{*
<div class="rating layer3">
</div>
*}

{$rating_field = $rating_field|default:'avg_rating'}
{$rating = $object[$rating_field]}
<div class="rating-stars {$wrapper_class|default:'layer3 row'}">
    <div class="col-sm-6 text-left rating-icons">
        <i class="glyphicon glyphicon-user" title="Всего пользователей проголосовало за заведение">{(int)$object.total_voters}</i>
        <i class="glyphicon glyphicon-thumbs-up" title="Всего голосов по отдельным параметрам рейтинга заведения">{(int)$object.total_votes}</i> 
    </div>
    <div class="col-sm-6">
        {include "inc/blocks/rating_stars/widget.tpl" wraper_attributes="data-target=\"#VotesModal_{$object.id}\" data-toggle=\"modal\" role=\"button\"" rating=$rating}
    </div>
</div>

{include "society/ratings/modals.tpl"}

