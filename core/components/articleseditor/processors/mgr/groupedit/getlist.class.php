<?php

/*
    ArticlesEditorGetlistInitializerProcessor
    ArticlesEditorProductsGetlistProcessor
    ArticlesEditorProductsModelsGetdataProcessor  
    ArticlesEditorDocumentsGetdataProcessor
    parent_title
*/



require_once MODX_CORE_PATH . 'components/modxsite/processors/web/resources/articles/getdata.class.php';

/*
    Инициализация. Определяем, какой процессор использовать.
*/

class ArticlesEditorGetlistInitializerProcessor extends modProcessor{
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        /** @var modProcessor $processor */
        
        switch($properties['listType']){
                
            case 'models':
                $className = 'ArticlesEditorProductsModelsGetdataProcessor';
                break;
                
            case 'products':
                # $className = 'ArticlesEditorProductsGetlistProcessor';
                # break;
            case 'documents':
            case 'articles':
                $className = 'ArticlesEditorDocumentsGetdataProcessor';
                break;
                
            default: $className = __CLASS__;
        }
        
        $processor = new $className($modx,$properties);
        return $processor;
    }
    
    public function process(){
        return $this->failure('Недопустимый тип вывода');
    }
    
}



/*
    Получаем документы
*/
class ArticlesEditorDocumentsGetdataProcessor extends modWebResourcesArticlesGetdataProcessor{
 
    public function initialize(){
        /*$this->modx->setLogLevel(3);
        $this->modx->setLogTarget('HTML');*/
        
        $this->setDefaultProperties(array(
            'cache'        => 0,
            'limit'         => 20,
            'parent'        => 0,
            'context_key'   => 'web',
            'showhidden'    => 1,
            'showunpublished'    => 1,
            # 'includeTVs'    => 0,
            'sort'          => 'id',
            'dir'           => 'DESC',
            "query_string"  => '',      // Поисковая строка
            "JoinParents"   => true,
            "show_hidden_on_mainpage"  => true,
        ));
        
        
        /*
            Если нет прав видеть чужие статьи, указываем "Только свои"
        */
        
        if(!$this->modx->hasPermission('view_not_own_articles')){
            $this->setProperty('own_articles', 1);
        }
        
        # if($sort = $this->getProperty('sort')){
        #     $this->setProperty('sort', "{$this->classKey}.{$sort}");
        # }
        
        # print '<pre>';
        # var_dump($this->properties['parent']);
        return parent::initialize();
    }
    
    protected function getCount__(xPDOQuery & $c){
        
        $query = clone $c;
        $query = $this->prepareCountQuery($query);
        if(!$this->total = $this->countTotal($this->classKey,$query)){
            return false;
        }
        
        # $sortKey = 'pagetitle';
        if($sortKey = $this->getProperty('sort')){
            $dir = $this->getProperty('dir');
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
        }
        else{
            $sortKey = 'show_in_tree';
            $dir = 'DESC';
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
            
            $sortKey = 'isfolder';
            $dir = 'DESC';
            $c->sortby($sortKey,$dir);
            $query->sortby($sortKey,$dir);
        }
        
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit > 0) {
            $query->limit($limit,$start);
        }
        
        $query = $this->prepareUniqObjectsQuery($query);
        if($query->prepare() && $query->stmt->execute() && $rows = $row = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
            $IDs = array();
            foreach($rows as $row){
                $IDs[] = $row['id'];
            }
            if ($this->flushWhere && isset($c->query['where'])) $c->query['where'] = array();
            $c->where(array(
                "{$this->classKey}.id:IN" => $IDs,
            ));
        }
        else{
            return false;
        }     
        
        return $c;
    }    
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        # $c->leftJoin('ShopmodxProduct', 'Product');
        
        $where =  array();
        
        # if($this->getProperty('listType') == 'products'){
        #     $where["Product.id:!="] = null;
        # }
        # else{
        #     $where["{$this->classKey}.parent"] = $this->getProperty('parent');
        # }
        
        switch($this->getProperty('listType')){
            case 'articles':
                if(!$this->getProperty('parent')){
                    $this->setProperty('parent', null);
                }
                $where['template'] = 2;
                break;
                
            default:
                $parent = $this->getProperty('parent');
                if(is_numeric($parent)){
                    $where["{$alias}.parent"] = $parent;
                }
        }
        
        
        if($context_key = $this->getProperty('context_key')){
            $where["{$this->classKey}.context_key"] = $this->getProperty('context_key');
        }
        
        
        if($where){
            $c->where($where);
        }
        return $c;
    }
     

    protected function setSelection(xPDOQuery $c){
        # $c->leftJoin('modResource', 'Parent');
        # $c->leftJoin('modResource', "Currency", "Currency.id = Product.sm_currency");
        
        $c = parent::setSelection($c);
        
        $alias = $c->getAlias();
        
        $c->leftJoin('modUserProfile', "CreatedByProfile", "CreatedByProfile.internalKey = {$alias}.createdby");
        
        $c->select(array( 
            "IF({$alias}.createdon > 0 , from_unixtime({$alias}.createdon, '%Y-%m-%d %H:%i:%s' ), NULL) as createdon_date",
            "IF({$alias}.publishedon > 0 , from_unixtime({$alias}.publishedon, '%Y-%m-%d %H:%i:%s' ), NULL) as publishedon_date",
            # 'Currency.pagetitle as currency_title',  
            "CreatedByProfile.fullname as createdby_fullname",
            'Parent.pagetitle as parent_title',    
            'Parent.id as parent_id',    
        ));
        
        if($this->getProperty('parent')){
            $c->select(array( 
                'Parent.parent as uplevel_id',    
            ));
        }
        
        return $c;
    } 
 
    public function afterIteration(array $data){
        
        $data = parent::afterIteration($data);
        
        $can_edit_document = $this->modx->hasPermission('edit_document');
        $can_delete_document = $this->modx->hasPermission('delete_document');
        $can_delete_document = $this->modx->hasPermission('delete_document');
        $can_publish_document = $this->modx->hasPermission('publish_document');
        $can_unpublish_document = $this->modx->hasPermission('unpublish_document');
        $can_change_document_parent = $this->modx->hasPermission('change_document_parent');
        # $can_set_rss = $this->modx->hasPermission('can_set_rss');
        # $can_set_news_list = $this->modx->hasPermission('can_set_news_list');
        
        foreach($data as & $d){
            $menu = array();
            
            if($can_edit_document){
                $menu[] = array(
                    'text' => 'Редактировать',
                    'handler' => 'this.editResource',
                );
            }
            
            $menu[] = array(
                'text' => 'Просмотр',
                'handler' => 'this.showResource',
            );
            
            
            if($d['published']){
                
                if($can_unpublish_document){
                    $menu[] = array(
                        'text' => 'Снять с публикации',
                        'handler' => 'this.unpublicateResource',
                    );
                }
                
                if($d['hidemenu']){
                    $menu[] = array(
                        'text' => 'Вывести в ленту',
                        'handler' => 'this.unhideResource',
                    );
                }
                else{
                    $menu[] = array(
                        'text' => 'Скрыть из ленты',
                        'handler' => 'this.hideResource',
                    );
                }
                
            }
            else{
                if($can_publish_document){
                    $menu[] = array(
                        'text' => 'Опубликовать',
                        'handler' => 'this.publicateResource',
                    );
                }
            }
            
            
            
            $menu[] = array(
                'text' => 'Сменить изображение',
                'handler' => 'this.changeImage',
            );
            
            if($can_delete_document){
                $menu[] = array(
                    'text' => 'Удалить',
                    'handler' => 'this.deleteResource',
                );
            }
            
            
            
            // Смена раздела
            if(
                $can_change_document_parent
                OR $d['createdby'] == $this->modx->user->id 
            ){
                $menu[] = array(
                    'text' => 'Изменить раздел',
                    'handler' => 'this.changeSection',
                );
            }
            
            
            // Отправка на редактуру
            if(
                $d['article_status'] == '0'
                # !$d['published'] 
                # AND !$can_publish_document
                AND $d['createdby'] == $this->modx->user->id 
            ){
                $menu[] = array(
                    'text' => 'Отправить на редактуру',
                    'handler' => 'this.sendForRedacting',
                );
            }
            
            
            // Смена RSS
            # if(
            #     $can_set_rss
            #     OR $d['createdby'] == $this->modx->user->id 
            # ){
            #     if($d['rss']){
            #         $menu[] = array(
            #             'text' => 'Исключить из RSS',
            #             'handler' => 'this.unsetRSS',
            #         );
            #     }
            #     else{
            #         $menu[] = array(
            #             'text' => 'Выгружать в RSS',
            #             'handler' => 'this.setRSS',
            #         );
            #     }
            # }
            
            
            // Смена Новостной ленты
            # if(
            #     $can_set_news_list
            #     OR $d['createdby'] == $this->modx->user->id 
            # ){
            #     if($d['news_list']){
            #         $menu[] = array(
            #             'text' => 'Исключить из новостной ленты',
            #             'handler' => 'this.unsetNewsList',
            #         );
            #     }
            #     else{
            #         $menu[] = array(
            #             'text' => 'Выводить в новостную ленту',
            #             'handler' => 'this.setNewsList',
            #         );
            #     }
            # }
            
            # $menu[] = array(
            #     'text' => 'Просмотр',
            #     'handler' => 'this.showResource',
            # );
            
            // Формируем меню для определенных типов объектов
            if(!empty($d['object_type'])){
                
                // Для моделей товаров
                if($d['object_type'] == 'model'){
                    
                    $menu[] = array(
                        'text' => 'Изменить цены',
                        'handler' => 'this.changeModelPrices',
                    );
                    
                    $menu[] = array(
                        'text' => 'Изменить картинку',
                        'handler' => 'this.changeModelImage',
                    );
                }
                
                // Для товаров
                else if($d['object_type'] == 'product'){
                    
                }
            }
            
            
            $d['menu'] = $menu;
            
            # $d['content'] = '';
            unset($d['content']);
        }
        
        # return array();
        return $data;
    }
    
    public function prepareRow(xPDOObject $object) {
        
        /*
            Определяем тип объекта
        */
        if($object instanceOf ShopmodxResourceProductModel){
            $object->set('object_type', 'model');
        }
        else if($object instanceOf ShopmodxResourceProduct){
            $object->set('object_type', 'product');
        }
        else{
            $object->set('object_type', 'document');
        }
        return parent::prepareRow($object);
    }    
    
    public function outputArray(array $array,$count = false) {
        $results = array();
          
        foreach($array as $a){
            $results[] = $a;
        }
        
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            'count'   => count($array),
            'total'   => $count,
            'results'    => $results,
        ));
    }
}




/*
    Получаем товары
*/
/*class ArticlesEditorProductsGetlistProcessor extends modWebCatalogProductsGetdataProcessor{
 
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit' => 20,
        ));
        
        return parent::initialize();
    }
    
    
    public function outputArray(array $array,$count = false) {
        $results = array();
          
        foreach($array as $a){
            $results[] = $a;
        }
        
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            'count'   => count($array),
            'total'   => $count,
            'results'    => $results,
        ));
    }
}*/


/*
    Поулчаем модели товаров
*/
/*class ArticlesEditorProductsModelsGetdataProcessor extends modWebCatalogModelsGetdataProcessor{
 
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'limit' => 20,
        ));
        
        return parent::initialize();
    }
    
    public function outputArray(array $array,$count = false) {
        $results = array();
          
        foreach($array as $a){
            $results[] = $a;
        }
        
        return $this->modx->toJSON(array(
            'success'   => true,
            'message'   => '',
            'count'   => count($array),
            'total'   => $count,
            'results'    => $results,
        ));
    }
}*/




return 'ArticlesEditorGetlistInitializerProcessor';