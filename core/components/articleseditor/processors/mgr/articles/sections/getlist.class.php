<?php

# ini_set('display_errors', 1);

require_once MODX_CORE_PATH . 'components/modxsite/processors/web/resources/getdata.class.php';

class modMgrArticlesSectionsGetlistProcessor extends modWebResourcesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"  => "pagetitle",
            "dir"   => "ASC",
            "limit" => 0,
            "includeTVs"    => false,
            "showhidden"    => true,
        ));
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $c->where(array(
            "parent:in" => array(
                80,
            ),
        ));
        
        return $c;
    }
     
    
    public function outputArray(array $array, $count = false){
        
        $result = parent::outputArray($array, $count);
        
        $objects = array(array(
            "id"    => 0,
            "pagetitle"  => "Выберите из списка",
        )
        # ,array(
        #     "id"    => 185,
        #     "pagetitle" => "Все новости",
        # )
        );
        
        foreach((array)$result['object'] as $object){
            $objects[] = $object;
        } 
        
        return json_encode(array(
            "success"   => $result['success'],
            "total"     => count($objects),
            "results"   => $objects,
        ));
    }
    
}

return 'modMgrArticlesSectionsGetlistProcessor';