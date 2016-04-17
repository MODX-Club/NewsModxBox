<?php

/*
    Создание пресс-релизов
*/

require_once dirname(dirname(__FILE__)) . '/create.class.php';

class modWebSocietyTopicsArticlesReleasesCreateProcessor extends modWebSocietyTopicsArticlesCreateProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "article_status"    => 1,
            "tags"              => '',
        ));
        
        $extended = $this->modx->user->Profile->get('extended');
        
        if(empty($extended['company'])){
            return "Не указана компания. Пожалуйста, обновите свой профиль";
        }
        
        // else
        
        $this->setDefaultProperties(array(
            "tv14"          => $extended['company'],       //pseudonym
        ));
        
        return parent::initialize();
    }
    
    
    
    protected function getSuccessMessage(){
        return "Публикация успешно создана. В случае успешного прохождения проверки она появится на сайте.";
    }
    
}

return 'modWebSocietyTopicsArticlesReleasesCreateProcessor';



