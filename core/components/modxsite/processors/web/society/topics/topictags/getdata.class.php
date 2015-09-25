<?php

/*
    Получаем все теги для топиков
*/

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/getdata.class.php';

class modWebSocietyTopicsTopictagsGetdataProcessor extends modWebGetdataProcessor{
    
    public $classKey = 'SocietyTopicTag';
    
    /*public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->groupby("tag");
        
        return $c;
    }*/
}


return 'modWebSocietyTopicsTopictagsGetdataProcessor';
