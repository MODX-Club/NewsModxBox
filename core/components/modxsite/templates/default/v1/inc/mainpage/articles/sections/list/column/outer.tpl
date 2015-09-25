  
{foreach $result.object as $object name=loop}
    {include file=$inner_tpl index=$smarty.foreach.loop.iteration}
{/foreach}

[[-- !pdoResources? &parents=`[[+id]]` &limit=`4`  &resources=`-[[*main-news-resource]]`  &tpl=`Articles_prelast_3` &tpl_4=`Articles_prelast_2`   &includeTVs=`image-main, main-news` &tvFilters=`main-news!=1`]]
     
