<?php

require_once dirname(dirname(dirname(__FILE__))).'/site/web/resources/getdata.class.php';

class modWebResourcesGetdataProcessor extends modSiteWebResourcesGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array( 
            "includeTVs"    => false,
            "sort"          => "",
            "parent_joining_type"   => "left",      // Тип джоининга таблицы родителей [left|inner]
            "process_tags"  => false,       // Получить информацию о тэгах
            "JoinParents"   => false,        // Джоинить родителей
            "in_rss_only"   => false,       // Только лоя RSS
            "in_news_list_only"     => false,       // Только те, что выводить в новостную ленту
            "show_hidden_on_mainpage"  => true,     // Некоторые статьи вручную скрывают с главной
            "top_news_only"     => false,     // Выводим только ТОП новости. Это которые выводятся в главном блоке
            "main_news_only"     => false,     // Выводим только главные новости. Это не самый главный блок, а просто главные новости по рубрикам
        ));
        
        # switch($this->getProperty('sort')){
        #     case 'image':
        #         $this->setProperty("sort", "tv_2_image.value");
        #         break;
        # }
        
        # print_r($this->properties);
        # 
        # exit;
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        if($this->getProperty('JoinParents')){
            if($this->getProperty('parent_joining_type') == 'inner'){
                $c->innerJoin('modResource', "Parent");
            }
            else{
                $c->leftJoin('modResource', "Parent");
            }
        }
        
        
        $where = array();
        
        /*
            Поиск по тегам
        */
        
        if($tag = (int)$this->getProperty('tag')){
            # $c->query['where'][] = new xPDOQueryCondition(array('sql' => "find_in_set('{$tag}', {$alias}.tags)"));
            
            $c->innerJoin("modResourceTag", "Tags", "Tags.tag_id = {$tag} AND Tags.resource_id = {$alias}.id");
            # $where['Tags.tag_id'] = $tag;
        }
        
        // Поиск по автору
        if($author = (int)$this->getProperty('author')){
            $where["createdby"] = $author;
        }
        
        /*
            Поиск по датам
        */
        if($date_from = $this->getProperty('date_from')){
            $time = strtotime($date_from);
            $c->where(array(
                "createdon:>=" => $time,
                "OR:publishedon:>=" => $time,
            ));
        }
        
        if($date_till = $this->getProperty('date_till')){
            $time = strtotime($date_till);
            $c->where(array(
                "createdon:<=" => $time,
                "OR:publishedon:<=" => $time,
            ));
        }
        
        
        /*
            По типу статей
        */
        if($article_type = (int)$this->getProperty('article_type')){
            $where['article_type'] = $article_type;
        }
        
        
        /*
            Только топовые новости
        */
        if($this->getProperty('top_news_only')){
            $where['top_news'] = '1';
        }
        
        
        /*
            Только главные новости
        */
        if($this->getProperty('main_news_only')){
            $where['main'] = '1';
        }
        
        /*
            Только для новостной ленты
        */
        if($this->getProperty('in_news_list_only')){
            $where['news_list'] = '1';
        }
        
        /*
            Только для RSS
        */
        if($this->getProperty('in_rss_only')){
            $where['rss'] = '1';
        }
        
        /*
            По статусу
            Выберите из списка==||Пишется==0||На редакции==1||Опубликована==2||Скрыта==3||Просрочена==4
        */
        
        $article_status = $this->getProperty('article_status', null);
        if(is_numeric($article_status)){
            switch($article_status){
                
                case '2':
                    $where['published'] = 1;
                    break;
                
                case '3':
                    $where['hidemenu'] = 1;
                    break;
                    
                default: 
                    $where['article_status'] = $article_status;
            }
        }
        
        /*
            Только собственные статьи
        */
        if($this->getProperty('own_articles')){
            $where['createdby'] = $this->modx->user->id;
        }
        
        
        /*
            Поиск по рубрикам
        */
        if($section = (int)$this->getProperty('section')){
            $c->leftJoin("modResourceSection", "Section", "Section.resource_id = {$alias}.id AND Section.section_id = {$section}");
            $c->where(array(
                "parent"    => $section,
                "OR:Section.id:!=" => null,
            ));
        }
        
        
        // Поиск по запросу
        if(
            $query_string = $this->getProperty('query')
            OR $query_string = $this->getProperty('query_string')
        ){
            # $c->where(array(
            #     "id"    => $query_string,
            #     "OR:pagetitle:like" => "%{$query_string}%",
            #     "OR:longtitle:like" => "%{$query_string}%",
            #     # "OR:content:like" => "%{$query_string}%",
            # ));
            
            /*
                Обрабатываем поисковый запрос
            */
            
            $searcher = $this->modx->getService('modSearch', 'modSearch', MODX_CORE_PATH . 'components/modsearch/model/modsearch/');
            
            $lemmas = $searcher->textToLemmas($query_string);
            
            
            foreach($lemmas as & $lemma){
                $lemma = "{$lemma}";
            }
            # unset($lemma);
            # 
            # print_r($lemmas);
            # 
            # exit;
            
            $indexes_table = $this->modx->getTableName('modSearchIndex');
            
            
            /*
                Поиск по любому из слов
            */
            # $sql = "EXISTS (
            #     SELECT NULL FROM {$indexes_table} WHERE {$indexes_table}.resource_id = {$alias}.id AND {$indexes_table}.lemma IN (". implode(", ", $lemmas) .")
            # )";
            
            /*
                Поиск по всем словам
            */
            # $sql = '';
            $condition = array();
            
            foreach($lemmas as $lemma){
                
                $quoted = $this->modx->quote("%{$lemma}%");
                $condition[] = "{$indexes_table}.lemma LIKE {$quoted}";
            }
                
            $sql = "EXISTS (
                SELECT NULL FROM {$indexes_table} 
                    WHERE {$indexes_table}.resource_id = {$alias}.id 
                    AND (". implode(' OR ', $condition) .")
            )";
            
            $c->query['where'][] = new xPDOQueryCondition(array(
                'sql' => $sql,
            ));
            
            # $sql = "SELECT NULL FROM {$indexes_table} WHERE {$indexes_table}.resource_id = {$alias}.id";
            # 
            # foreach($lemmas as $lemma){
            #     $sql .= " AND {$indexes_table}.lemma = $lemma";
            # }
            # 
            # $sql = "EXISTS (
            #     {$sql}
            # )";
            # 
            
            # print_r($lemmas);
            # exit;
            
            # $c->select(array(
            #     "{$alias}.*", 
            # ));
            # 
            # $c->select(array(
            #     "{$alias}.id",
            #     "{$alias}.pagetitle",
            # ));
            # $c->prepare();
            # 
            # print $c->toSQL();
            
            # exit;
        }
        
        
        /*
            Скрытые с главной
        */
        if(!$this->getProperty('show_hidden_on_mainpage')){
            $where['hide_on_mainpage'] = '0';
        }
        
        
        # if($this->modx->hasPermission('debug')){
        #     print_r($where);
        # }
        
        if($where){
            $c->where($where);
        }
        
        return $c;
    }
    

    # protected function prepareUniqObjectsQuery(xPDOQuery & $query){
    #     
    #     $query = parent::prepareUniqObjectsQuery($query);
    #     
    #     $query->prepare();
    #     print $query->toSQL();
    #     
    #     exit;
    #     
    #     return $query;
    # }
    
    
    protected function getCount(xPDOQuery & $c){
        if(!$sortKey = $this->getProperty('sort')){
            $sortClassKey = $this->getSortClassKey();
            $sortKey = $this->modx->getSelectColumns($sortClassKey,$this->getProperty('sortAlias',$sortClassKey),'',array($this->getProperty('sort')));
        }
        
        $c = $this->prepareCountQuery($c);
        if(!$this->total = $this->countTotal($this->classKey,$c)){
            return false;
        }
        
        $c = $this->prepareQueryAfterCount($c);
        
        
        if($sortKey){
            $c->sortby($sortKey,$this->getProperty('dir'));
        }
        
        # $query = clone $c;
        
        $limit = intval($this->getProperty('limit'));
        $start = intval($this->getProperty('start'));
        
        if ($limit || $start) {
            $c->limit($limit,$start);
        }
        
        # if ($limit || $start) {
        #     $query->limit($limit,$start);
        # }
        # 
        # $query = $this->prepareUniqObjectsQuery($query);
        # if($query->prepare() && $query->stmt->execute() && $rows = $query->stmt->fetchAll(PDO::FETCH_ASSOC)){
        #     $IDs = array();
        #     foreach($rows as $row){
        #         $IDs[] = $row['id'];
        #     }
        #     
        #     if ($this->flushWhere && isset($c->query['where'])) $c->query['where'] = array();
        #     $c->where(array(
        #         "{$this->classKey}.id:IN" => $IDs,
        #     ));
        # }
        # else{
        #     
        #     if($query->stmt AND $query->stmt->errorCode() !== "00000"){
        #         $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__);
        #         $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($query->stmt->errorInfo(), true));
        #         $this->modx->log(xPDO::LOG_LEVEL_ERROR, $query->toSQL());
        #     }
        #     
        #     return false;
        # }     
        
        return $c;
    }
    

    # protected function setSelection(xPDOQuery $c){
    #     $c = parent::setSelection($c);
    #     
    #     $c->prepare();
    #     print $c->toSQL();
    #     
    #     exit;
    #     
    #     return $c;
    # }
    
    
    protected function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        
        $alias = $c->getAlias();
        
        if(!$this->getProperty('includeTVs')){
            
            $tvs = $this->getTVs();
            
            if($tvs){
                
                $q = $this->modx->newQuery('modTemplateVar', array(
                    "id:in" => $tvs,
                ));
                $q->select(array(
                    "modTemplateVar.id",
                    "modTemplateVar.name",
                ));
                
                
                # $tvs = array();
                $select = array();
    
                $s = $q->prepare();
                $s->execute();
    
                while($row = $s->fetch(PDO::FETCH_ASSOC)){
                    $tv_id = $row['id'];
                    $tv_name = "tv_{$tv_id}_{$row['name']}";
                    $c->leftJoin('modTemplateVarResource', $tv_name, "{$tv_name}.tmplvarid = {$tv_id} AND {$tv_name}.contentid = {$this->classKey}.id");
                    
                    $tv_select = "{$tv_name}.value as `{$tv_name}`";
                    
                    # switch($tv_id){
                    #     case '12':
                    #         $select[] = "{$tv_name}.value as `status`";
                    #         break;
                    # }
                    
    #                 switch($tv_name){
    # 
    #                     case 'text':
    #                     case 'analog':
    #                         $tv_select = "replace({$tv_name}.value, ' - снят с производства', '') as `{$tv_name}`";
    #                         break;
    # 
    #                     case 'vendor':
    #                         $tv_select = "replace({$tv_name}.value, 'Прочее', 'Разное') as `{$tv_name}`";
    #                         break;
    # 
    #                     case 'price':
    #                         continue;
    #                         # $tv_select = "replace({$tv_name}.value, ',', '.') as sm_price";
    #                         break;
    # 
    #                     # case 'image':
    #                     #     $tv_select = "{$tv_name}.value as `tv_{$tv_name}`";
    #                     #     break;
    # 
    #                     default:
    #                         $tv_select = "{$tv_name}.value as `{$tv_name}`";
    #                 }
                    
                    
    
                    $select[] = $tv_select;
                }
    
                $c->select($select);
            }
            


        }
        
        # $c->select(array(
        #     "{$alias}.image as tv_2_image",
        # ));
        
        return $c;
    }
    
    
    /*
        Получаем ID-шники TV-шек, для которых нужно получить данные
    */
    protected function getTVs(){
        return array(
            # 2,      // image
            # 12,     // status
            27,     // gallery
            30,     // sell_link
            31,     // original_source
            32,     // hide_image
        );
    }
    
    public function beforeIteration(array $list){
        $list = parent::beforeIteration($list);
        
        /*
            Ищем все TV-поля
        */
        foreach($list as & $l){
            foreach($l as $f => $v){
                if(
                    preg_match("/^tv\_([0-9]+)\_(.+)/", $f, $match)
                    AND $v
                ){
                    $tv_id = $match[1];
                    $tv_name = $match[2];
                    $l['tvs'][$tv_name] = array(
                        'tv_id'    => $tv_id,
                        'value'    => $v,
                    );
                }
            }
            # // Устанавливаем картинку
            # $l['tvs']['image']['value'] = $l['image'];
            
        }
        
        
        
        # print_r($list);
        # exit;
        return $list;
    }
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list); 
        
        $process_tags = $this->getProperty('process_tags');
        
        switch($this->getProperty('image_url_schema')){
            case 'base':
                $images_base_url = $this->getSourcePath();
                break;
                
            case 'full':
                $images_base_url = $this->modx->getOption('site_url');
                $images_base_url .= preg_replace("/^\/*/", "", $this->getSourcePath());
                break;
                
            default: $images_base_url = '';
        }
        
        foreach($list as & $l){  
            
            // Картинка
            #  = '';
            if(
                !empty($l['image'])
            ){
                $l['public_image'] = $images_base_url . $l['image'];
            }
            
            // Тэги
            if(
                $process_tags 
                AND $tags = $l['tags']
            ){
                $tags_ids = explode(",", $tags);
                $tags_q = $this->modx->newQuery("modResource", array(
                    "id:in" => $tags_ids,
                    "published" => 1,
                    "hidemenu"  => 0,
                    "deleted"  => 0,
                ));
                $tags_q->select(array(
                    "id as id",
                    "pagetitle as tag",
                    "uri as uri",
                ));
                
                $s = $tags_q->prepare();
                $s->execute();
                
                $l['tags_array'] = $s->fetchAll(PDO::FETCH_ASSOC);
            }
            
            
            
            $l['gallery'] = array();
            if(
                !empty($l['tvs']['gallery']['value'])
                AND $gallery = json_decode($l['tvs']['gallery']['value'], 1)
            ){
                foreach($gallery as $image){ 
                    $image['image'] = $images_base_url . $image['image'];
                    # $image['image'] = $image['image'];
                    $l['gallery'][] = $image;
                    
                    if(!$l['image']){
                        $l['image'] = $image['image'];
                    }
                }
                
            } 
            
            
            $l['sell_link'] = array();
            if(
                !empty($l['tvs']['sell_link']['value'])
                AND $sell_link = json_decode($l['tvs']['sell_link']['value'], 1)
            ){
                foreach($sell_link as $link){  
                    # $image['image'] = $image['image'];
                    $l['sell_link'][] = $link;
                }
                
            } 
        }   
                
        return $list;
    }
    
}

return 'modWebResourcesGetdataProcessor';