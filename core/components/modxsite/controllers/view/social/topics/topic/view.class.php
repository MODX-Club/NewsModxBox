<?php

/*
    Вывод профиля пользователя.
    Используется фейковая страница
*/

require_once dirname(dirname(dirname(__FILE__))) . '/view.class.php';

class SocialTopicsTopicView extends SocialView{
    
    public function renderTemplate(){
        
        /*
            Получаем все блоги топика, и если нет прав на чтение ни в одном блоге,
            то перекрываем доступ к топику
        */
        
        $q = $this->modx->newQuery('SocietyBlog');
        $q->innerJoin('SocietyBlogTopic', 'BlogTopics');
        $q->where(array(
            "BlogTopics.topicid"    => $this->modx->resource->id,
        ));
        
        $can_view = false;
        
        foreach($this->modx->getCollection('SocietyBlog', $q) as $blog){
            if($blog->checkPolicy('view')){
                $can_view = true;
                break;
            }
        }
        
        if(!$can_view){
            return $this->modx->sendUnauthorizedPage();
        }
        
        /*
        $this->setSearchable(true);
        $this->setCanonical($this->modx->makeUrl($this->modx->resource->id, '','', 'full') . $username . '/');*/
        
        return parent::renderTemplate();
    }
}
