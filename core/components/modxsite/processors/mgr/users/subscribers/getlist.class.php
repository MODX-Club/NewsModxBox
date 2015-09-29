<?php

/*
    Подписчики
*/

require_once dirname(dirname(__FILE__)) . '/getlist.class.php';

class modMgrUsersSubscribersGetlistProcessor extends modMgrUsersGetlistProcessor{
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        
        $c->select(array(
            "{$alias}.*",
            "date_format(from_unixtime({$alias}.subscribe_till), '%Y/%m/%d') as subscribe_till_str",
        ));
        
        $where = array();
        
        if($this->getProperty('active_only')){
            $where['subscribe_till:>'] = time();
        }
        
        if($where){
            $c->where($where);
        }
        
        return $c;
    }
    
    # public function outputArray($array){
    #     
    #     print_r($array);
    #     exit;
    # }
    
}

return 'modMgrUsersSubscribersGetlistProcessor';
