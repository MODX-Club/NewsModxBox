<?php

/*
    Вывод профиля пользователя.
    Используется фейковая страница
*/

require_once dirname(dirname(__FILE__)) . '/view.class.php';

class SocialProfileStreamView extends SocialProfileView{
    
    public function renderTemplate(){
        
        
        return parent::renderTemplate();
    }
    
    
    protected function makeCanonical($username){
        return $this->modx->makeUrl(86895, '','', 'full') . $username . '/stream/';
    }
}
