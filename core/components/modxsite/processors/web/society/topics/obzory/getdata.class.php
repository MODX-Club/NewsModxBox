<?php

/*
    Получаем только обзоры заведений
*/

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';

class modWebSocietyTopicsObzoryGetdataProcessor extends modWebSocietyTopicsGetdataProcessor{
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $where = array(
            "template" => 28,
        );
        
        $c->where($where);
        
        return $c;
    }
    
}

return 'modWebSocietyTopicsObzoryGetdataProcessor';
