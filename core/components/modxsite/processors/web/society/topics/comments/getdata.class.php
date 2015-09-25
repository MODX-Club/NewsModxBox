<?php


require_once dirname(dirname(dirname(__FILE__))) . '/comments/getdata.class.php'; 

class modWebSocietyTopicsCommentsGetdataProcessor extends modWebSocietyCommentsGetdataProcessor{
    
    
    public function prepareQueryBeforeCount(xPDOQuery $q){
        $q = parent::prepareQueryBeforeCount($q);
        
        $where = array(
            "Thread.target_class" => 'modResource',
        );
        
        $q->where($where);
        
        return $q;
    }
    

    
    public function prepareCountQuery(xPDOQuery & $query){
        $query = parent::prepareCountQuery($query);
        
        $where = array(
        );
        
        
        // $query->innerJoin('modResource', 'resource', "resource.id = Thread.target_id");
        
        # $can_view_ids = array();
        # 
        # foreach($this->modx->getCollection('SocietyBlog', array(
        #     'class_key' => 'SocietyBlog',
        # )) as $blog){
        #     if($blog->checkPolicy('view')){
        #         $can_view_ids[] = $blog->id;
        #     }
        # }
        # 
        # if(!$can_view_ids){
        #     $query->where(array(
        #         "1 = 2",
        #     ));
        #     
        #     return $query;
        # }
        
        // else
        
        # if($this->modx->hasPermission('sdfsdf')){
            # $query->leftJoin('SocietyBlogTopic', 'bt', "bt.topicid = Thread.target_id");
            # $query->where(array(
            #     'bt.blogid:in' => $can_view_ids,
            #     "OR:Thread.target_id:in"    => $can_view_ids,
            # ));
        # }
        # else{
        #     $query->innerJoin('SocietyBlogTopic', 'bt', "bt.topicid = Thread.target_id");
        #     $where['bt.blogid:in'] = $can_view_ids;
        #     
        # }
        
        /*print_r($can_view_ids);
        print_r(count($can_view_ids));
        exit;*/
        
        
        if($where){
            $query->where($where);
        }
        
        // Получаем последние комментарии из этих топиков
        if($this->getProperty('one_comment_per_thread')){ 
            $ids = array();
            
            $sub_query = $this->modx->newQuery($this->classKey); 
            $sub_query->select(array(
                "max(id) as id",
                "thread_id",
            ));
            $sub_query->groupby('thread_id');
            
            $s = $sub_query->prepare();
            $s->execute();
            
            while($row = $s->fetch(PDO::FETCH_ASSOC)){
                $ids[] = $row['id'];
            }
            
            if($ids){
                $query->where(array(
                    "id:in" => $ids,
                ));
            }
        }
        
        /*$s = $query->prepare();
        $s->execute();
        print_r($s->errorInfo());
        exit;*/
        
        
        
        return $query;
    }
    
    
    
    public function setSelection(xPDOQuery $q){
        $q = parent::setSelection($q);
        
        //$q->innerJoin('SocietyThread', 'Thread');
        $q->innerJoin('modResource', 'resource', "resource.id = Thread.target_id");
        
        $q->select(array(
            "Thread.comments_count as thread_comments_count",  
            "resource.id as resource_id",
            "resource.pagetitle as resource_pagetitle",
            "resource.uri as resource_uri",
        ));
        
        return $q;
    }
    
}


return 'modWebSocietyTopicsCommentsGetdataProcessor';
