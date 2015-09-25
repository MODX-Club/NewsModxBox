<?php

/*
    Специализации
*/ 

require_once __DIR__ . '/../request.class.php';

class modWebApiHhSpecializationsRequestProcessor extends modWebApiHhRequestProcessor{
     
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "path"  => "/specializations",             // Раздел для запроса
        ));
        
        return parent::initialize();
    }
     
    
}

return 'modWebApiHhSpecializationsRequestProcessor';