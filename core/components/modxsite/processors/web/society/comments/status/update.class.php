<?php

require_once dirname(dirname(__FILE__)) . '/update.class.php';

class modWebSocietyCommentsStatusUpdateProcessor extends modWebSocietyCommentsUpdateProcessor{
    
    
    public function beforeSet(){
        
        $this->setProperties($this->object->toArray());
        
        return parent::beforeSet();
    }
    
}


return 'modWebSocietyCommentsStatusUpdateProcessor';

