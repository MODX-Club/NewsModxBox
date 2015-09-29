<?php

/*
    Получаем самые популярные новости за последние сутки (по умолчанию)
*/

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';

class modWebResourcesArticlesPopularGetdataProcessor extends modWebResourcesArticlesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "sort"      => "visits.visits",
            "dir"       => "DESC",
        )); 
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        
        $c->innerJoin('view.modResourceVisitView', "visits", "visits.resource_id = {$alias}.id");
        
        $where = array(
        );
        
        if($where){
            $c->where($where);
        } 
        return $c;
    }
    
    
}


return 'modWebResourcesArticlesPopularGetdataProcessor';

