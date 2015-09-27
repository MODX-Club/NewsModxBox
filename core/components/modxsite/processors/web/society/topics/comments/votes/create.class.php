<?php

/*
    Рейтинг на комментарий
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/votes/create.class.php';

class modWebSocietyTopicsCommentsVotesCreateProcessor extends modWebSocietyVotesCreateProcessor{
    
    
    public function initialize(){
        
        if($this->getProperty('vote_direction') == 'up'){
            $vote_value = 1;
        }
        else if($this->getProperty('vote_direction') == 'down'){
            $vote_value = -1;
        }
        else{
            $vote_value = 0;
        }
        
        $this->setProperties(array(
            "target_class"  => "SocietyComment",
            "vote_value"  => $vote_value,
        ));
        
        $this->unsetProperty('thread_id');
        
        return parent::initialize();
    }
    
    
    public function beforeSave(){
        
        $ok = parent::beforeSave();
        if($ok !== true){
            return $ok;
        }
        
        $vote =& $this->object;
        
        /*
            Получаем коммент, его топик и проверяем права на топик.
            Если прав на топик нет, то и рейтинг давать на комментарий тоже нельзя.
        */
        if(!$comment = $this->modx->getObject($vote->target_class, $vote->target_id)){
            return "Не был получен объект комментария";
        }
        
        // else
        if(
            !$topic = $this->modx->getObject($comment->Thread->target_class, $comment->Thread->target_id)
            OR !$topic->checkPolicy('view', null, $this->modx->user)
        ){
            return "Нет доступа к топику комментария";
        }
        
        // else
        if($comment->createdby == $vote->user_id){
            return "Нельзя голосовать за свои комментарии";
        }
        
        return !$this->hasErrors();
    }
    
}


return 'modWebSocietyTopicsCommentsVotesCreateProcessor';

