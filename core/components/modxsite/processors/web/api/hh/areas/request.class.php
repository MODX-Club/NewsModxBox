<?php

/*
    Регионы
*/ 

require_once dirname(dirname(__FILE__)) . '/request.class.php';

class modWebApiHhAreasRequestProcessor extends modWebApiHhRequestProcessor{
     
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "path"  => "/areas/1",             // Раздел для запроса
        ));
        
        return parent::initialize();
    }
     
    
}

return 'modWebApiHhAreasRequestProcessor';