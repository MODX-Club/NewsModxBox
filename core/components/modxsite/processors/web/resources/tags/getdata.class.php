<?php

/*
    Получаем теги
*/


class modWebResourcesTagsGetdataProcessor extends modProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"      => "pagetitle",
            "dir"      => "ASC",
            "limit"         => 0,
            "steps"     => 5,      // Количество шагов рейтинга
        ));
        
        return parent::initialize();
    }
    
    public function process(){ 
        
        /*
            Создаем общий объект запроса
        */
        # $c = $this->modx->newQuery('modResource', array(
        #     "published" => 1,
        #     "hidemenu" => 0,
        #     "deleted" => 0,
        # ));
        # $alias = $c->getAlias();
        # 
        # $where = array(
        #     "parent"    => 68,
        # );
        
        # if($this->getProperty('rating')){
        # $c->query->select
        
        # $c->innerJoin('modResourceTag', "TagResources");
        # 
        # $c->where($where); 
        # 
        # $c->sortby("1", "DESC");
        
        /*
            1. Подсчитываем общее количество тегов
        */
         
        $c = $this->modx->newQuery('modResourceTag');
        $alias = $c->getAlias();
        
        $c->select(array(
            "count(*) as count",
            "{$alias}.tag_id",
        ));
        
        $c->groupby("2");
        $c->sortby("1", "DESC");
        
        $c->limit($this->getProperty('limit', 0));
        
        $c->prepare();
        
        $sql = $c->toSQL();
        
        # print $sql;
        # 
        # exit;
        
        /*
            Подсчитываем min и max
            min - будет 1 по 10-тибальной шкале.
            max - 10
        */
        $min_max_sql = "select 
            max(count) as max
            ,min(count) as min
            from ({$sql}) as t";
            
        
        $min_max_s = $this->modx->prepare($min_max_sql);
        $min_max_s->execute();
        
        extract($min_max_s->fetch(PDO::FETCH_ASSOC));
        
        # if($this->modx->hasPermission('sdfsdf')){
        #     print "\nMin: $min";
        #     print "\nMax: $max";
        #     exit;
        #     
        # }
        
         
        $steps = $this->getProperty('steps');
        $step = ($max - $min) / $steps; 
        $step = str_replace(",", ".", $step);
        
        
        # print "\nStep: $step";
        # exit;
        
        $sort = $this->getProperty("sort");
        $dir = $this->getProperty("dir");
        
        $content_table = $this->modx->getTableName('modResource');
        
            # ,{$max} as max
            
        $end_sql = "SELECT 
            if(count > {$min} * 1.5, round((count - {$min}) / {$step}), 1) as rating
            ,count
            ,tag_id as id
            ,c.pagetitle
            ,c.uri
            FROM ({$sql}) as t
            INNER JOIN {$content_table} as c on c.id = t.tag_id
            WHERE 
                c.published = 1
                AND c.deleted = 0
                AND c.hidemenu = 0
            ORDER BY {$sort} {$dir}
        "; 
            # ,id
            # ,pagetitle
            # ,uri
        
        $s = $this->modx->prepare($end_sql);
        
        # 
        $s->execute();
        
        # print $end_sql;
        # 
        # print_r($s->errorInfo());
        
        # if($this->modx->hasPermission('sdfsdf')){
        #     print $end_sql;
        #     print_r($s->errorInfo());
        #     exit;
        # }
        
        return $this->success('', $s->fetchAll(PDO::FETCH_ASSOC));
    } 
    
}

return 'modWebResourcesTagsGetdataProcessor';
