<?php

/*
    Вывод тега
*/

require_once dirname(dirname(__FILE__)) . '/view.class.php';

class SocialTagView extends SocialView{
    
    public function renderTemplate(){
        $tag = null;
        
        // Получаем данные пользователя
        if($RouteTag = $this->modx->getOption('RouteTag')){
            
            $q = $this->modx->newQuery('SocietyTopicTag', array(
                "tag"  => $RouteTag,
                "active"    => 1,
            ));
            
            $q->limit(1);
            
            $tag = $this->modx->getObject('SocietyTopicTag', $q);
        }
        
        if(!$tag){
            return $this->modx->sendErrorPage();
        }
        
        $this->setSearchable(true);
        $this->setCanonical($this->makeCanonical($tag->tag));
        
        $this->modx->smarty->assign('tag', $tag->tag);
        
        $this->meta['meta_title'] = $tag->tag;
        
        return parent::renderTemplate();
    }
    
    protected function makeCanonical($username){
        return $this->modx->makeUrl($this->modx->resource->parent, '','', 'full') . $username . '/';
    }
}
