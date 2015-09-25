<?php

require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/blogs/getdata.class.php';

class modWebSocietyBlogsGetdataProcessor extends modSocietyWebBlogsGetdataProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "blogs"     => array(),         // Массив дополнительных 
            "check_for_post"   => 0,       // Проверка блогов, в которые можно публиковать топики
            "sort"      => "pagetitle",
        ));
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $q){
        $q = parent::prepareQueryBeforeCount($q);
        
        $alias = $q->getAlias();
        
        $q->innerJoin('modUser', 'CreatedBy');
        $q->innerJoin('modUserProfile', 'CreatedByProfile', "CreatedBy.id = CreatedByProfile.internalKey");
        
        /*
            Получаем данные диалоговой ветви
        */
        $q->leftJoin('SocietyThread', 'thread', "thread.target_class='modResource' AND thread.target_id={$alias}.id");    
        
        return $q;
    }
    
    
    public function prepareCountQuery(xPDOQuery & $query){
        $query = parent::prepareCountQuery($query);
        
        /*if(!$this->modx->hasPermission('view_all_blogs')){
            
        }*/
        
        $where = array(
            "template:in"  => array(14, 27),  // Получаем только публичные блоги и заведения
        );
        
        $can_view_ids = array();
        
        $check_for_post = $this->getProperty('check_for_post', 0);
        
        foreach($this->modx->getCollection('SocietyBlog', array(
            'class_key' => 'SocietyBlog',
        )) as $blog){
            if(!$blog->checkPolicy('view')){
                continue;
            }
            
            if($can_view_ids && !$blog->checkPolicy('society_topic_resource_create')){
                continue;
            }
            
            
            $can_view_ids[] = $blog->id;
        }
            # print '<pre>';
            # 
            # print_r($_SESSION);
        
        if($can_view_ids){
            
            $where['id:in'] = $can_view_ids;
            
            $where[] = array(
                "OR:createdby:="   => $this->modx->user->id,
            );
            
            if($blogs = $this->getProperty('blogs')){
                $where[] = array(
                    "OR:id:in"   => (array)$blogs,
                );
                 
                $this->modx->log(1,  print_r((array)$blogs, 1));
            }  
            
        }
        else{
            $query->where(array(
                "1 = 2",
            ));
        } 
        
        if($where){
            // $this->modx->log(1,  print_r((array)$where, 1));
            $query->where($where);
        }
        
        
        /*
            if($blogs){
                $query->prepare();
                $this->modx->log(1, $query->toSQL());
            }
        */
        
        return $query;
    }
    
    
    public function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        
        /*
            Проверяем, есть ли голос пользователя здесь
        */
        $c->leftJoin('SocietyVote', 'vote', "vote.target_class='modResource' AND vote.target_id={$this->classKey}.id AND vote.user_id = ". $this->modx->user->id);
        
        
        $c->select(array(
            "CreatedBy.username as author",
            "CreatedByProfile.photo as author_avatar",
            "CreatedBy.username as author_username",
            "thread.id as thread_id",
            "thread.positive_votes",
            "thread.negative_votes",
            "thread.comments_count",
            "vote.id as vote_id",
            "vote.vote_direction",
            "vote.vote_value",
        ));
        
        return $c;
    }
    
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        $url = $this->getSourcePath($this->modx->getOption('modavatar.default_media_source', null, 15));
        
        switch($this->getProperty('image_url_schema')){
            case 'base':
                $images_base_url = $this->modx->runSnippet('getSourcePath');
                break;
                
            case 'full':
                $images_base_url = $this->modx->getOption('site_url');
                $images_base_url .= preg_replace("/^\//", "", $this->modx->runSnippet('getSourcePath'));
                break;
                
            default: $images_base_url = '';
        }
        
        foreach($list as & $l){
            if($l['author_avatar']){
                $l['author_avatar'] = $url . $l['author_avatar'];
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
        }
        
        
        return $list;
    }
    
}


return 'modWebSocietyBlogsGetdataProcessor';