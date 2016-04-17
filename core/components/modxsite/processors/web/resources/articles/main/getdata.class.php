<?php

/*
    Получаем только главные новости
*/


require_once dirname(dirname(__FILE__)) . '/getdata.class.php';


class modWebResourcesArticlesMainGetdataProcessor extends modWebResourcesArticlesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "main_news_only"     => true,     // Выводим только главные новости
        )); 
        
        return parent::initialize();
    }
    
    # public function prepareQueryBeforeCount(xPDOQuery $c) {
    #     
    #     $c = parent::prepareQueryBeforeCount($c);
    #     
    #     $c->where(array(
    #         "main"  => '1',
    #     ));
    #     
    #     return $c;
    # }
    
}


return 'modWebResourcesArticlesMainGetdataProcessor';
