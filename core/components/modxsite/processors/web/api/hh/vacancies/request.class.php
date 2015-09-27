<?php

require_once __DIR__ . '/../request.class.php';

class modWebApiHhVacanciesRequestProcessor extends modWebApiHhRequestProcessor{
     
    
    
    public function initialize(){
        
        $path = "/vacancies";
        
        if($vacancy_id = (int)$this->getProperty('vacancy_id')){
            $path .= "/{$vacancy_id}";
        }
        
        $this->setProperties(array(
            "path"  => $path,             // Раздел для запроса
        ));
        
        
        /*
            Если значение пустое, удаляем переменную, так как hh ругается на пустое значение
        */
        
        if(!$this->getProperty('specialization')){
            $this->unsetProperty('specialization');
        }
        
        if(!$this->getProperty('salary')){
            $this->unsetProperty('salary');
        }
        
        return parent::initialize();
    }
     
    
}

return 'modWebApiHhVacanciesRequestProcessor';
