<?php

/*
    Получаем авторов статей.
    Это пользователи, которые хоть раз что-то написали
*/

require_once MODX_PROCESSORS_PATH . 'security/user/getlist.class.php';


class modMgrArticlesAuthorsGetlistProcessor extends modUserGetListProcessor{
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        
        
        /*
            Получаем только тех, кто хоть один документ создал
        */
        $content_table = $this->modx->getTableName("modResource");
        
        $where_sql = "EXISTS (SELECT NULL FROM {$content_table} c WHERE c.template = 2 AND c.createdby = modUser.id)"; 
        
        $c->query['where'][] = new xPDOQueryCondition(array('sql' => $where_sql, 'conjunction' => 'OR'));
        
        # $c->prepare();
        # 
        # print $c->toSQL();
        # 
        # exit;
        return $c;
    }
    
    
    public function beforeIteration(array $list) {
        $list[] = array(
            "id"    => 0,
            "fullname"  => "Выберите из списка",
        );
        return $list;
    }
    
}


return 'modMgrArticlesAuthorsGetlistProcessor';
