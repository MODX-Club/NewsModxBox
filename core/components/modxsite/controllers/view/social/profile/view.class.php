<?php

/*
    Вывод профиля пользователя.
    Используется фейковая страница
*/

require_once dirname(dirname(__FILE__)) . '/view.class.php';

class SocialProfileView extends SocialView{
    
    public function renderTemplate(){
        
        
        // Получаем данные пользователя
        if(
            !$username = $this->modx->getOption('RouteUsername')
            OR !$user = $this->modx->getObject('modUser', array(
                "username"  => $username,
                "active"    => 1,
            ))
        ){
            return $this->modx->sendErrorPage();
        }
        
        $this->setSearchable(true);
        $this->setCanonical($this->makeCanonical($user->username));
        
        $this->meta['meta_title'] = $user->Profile->fullname ? $user->Profile->fullname : $user->username;
        
        return parent::renderTemplate();
    }
    
    protected function makeCanonical($username){
        return $this->modx->makeUrl($this->modx->resource->parent, '','', 'full') . $username . '/';
    }
}
