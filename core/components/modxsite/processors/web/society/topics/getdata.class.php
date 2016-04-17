<?php

# require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/topics/getdata.class.php';
# class modWebSocietyTopicsGetdataProcessor extends modSocietyWebTopicsGetdataProcessor{
    
    
require_once dirname(dirname(dirname(__FILE__))) . '/resources/articles/getdata.class.php';

class modWebSocietyTopicsGetdataProcessor extends modWebResourcesArticlesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "showunpublished"   => $this->modx->hasPermission('view_unpublished_topics'),
            "approved"          => false,       // Показывать только все подряд
        ));
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $q){
        $q = parent::prepareQueryBeforeCount($q);
        
        # $q->innerJoin('modResource', 'Parent');
        
        # $q->innerJoin('SocietyBlogTopic', 'bt', "bt.topicid = {$this->classKey}.id");
         
        // По типу заведения
        # if($facility_type = (int)$this->getProperty('facility_type')){
        #     $q->innerJoin("modTemplateVarResource", "facility_type", "facility_type.contentid = Parent.id AND facility_type.tmplvarid = 25 AND facility_type.value = {$facility_type}");
        # }
        
        return $q;
    }
    
    
    // public function prepareCountQuery(xPDOQuery & $query){
    //     $query = parent::prepareCountQuery($query);
        
    //     $where = array(
    //         // "template"  => 2,  // Получаем только статьи
    //     );
        
        // Получаем все блоги, к которым есть доступ на чтение
        /*$q = $this->modx->newQuery('SocietyBlog');
        $q->innerJoin('SocietyBlogTopic', 'BlogTopics');
        $q->where(array(
            "BlogTopics.topicid"    => $this->modx->resource->id,
        ));*/
        
        
        // Только одобренные
        # if($this->getProperty('approved')){
        #     $query->innerJoin('modTemplateVarResource', "tv_approved", "tv_approved.tmplvarid = 24 AND tv_approved.contentid = {$this->classKey}.id AND tv_approved.value = '1'");
        # }
        
        # $can_view_ids = array();
        # 
        # foreach($this->modx->getCollection('SocietyBlog', array(
        #     'class_key' => 'SocietyBlog',
        # )) as $blog){
        #     // print "<br />$blog->id";
        #     if($blog->checkPolicy('view')){
        #         $can_view_ids[] = $blog->id;
        #     }
        # }
        
        # $where['bt.blogid:in'] = $can_view_ids;
        
        // Поиск по тегу
        # if($tag = trim($this->getProperty('tag'))){
        #     $query->innerJoin('SocietyTopicTag', "Tags");
        #     $where['Tags.tag'] = $tag;
        # }
        
    //     if($where){
    //         $query->where($where);
    //     }
        
    //     return $query;
    // }
    
    
    public function setSelection(xPDOQuery $q){
        $q = parent::setSelection($q);
        
        $q->innerJoin('modUser', 'CreatedBy');
        $q->innerJoin('modUserProfile', 'CreatedByProfile', "CreatedBy.id = CreatedByProfile.internalKey");
        # $q->innerJoin('SocietyTopicAttributes', 'Attributes');
        # $q->innerJoin('SocietyBlogTopic', 'bt', "bt.topicid = {$this->classKey}.id");
        # $q->innerJoin('modResource', 'blog', "blog.id = bt.blogid");
        
        /*
            Получаем данные диалоговой ветви
        */
        $q->leftJoin('SocietyThread', 'thread', "thread.target_class='modResource' AND thread.target_id={$this->classKey}.id");      
        
        $q->select(array(
            "CreatedBy.username as author",
            "CreatedByProfile.fullname as author_fullname",
            "CreatedByProfile.photo as author_avatar",
            # "Attributes.short_text",
            # "Attributes.topic_tags",
            # "blog.id as blog_id",
            # "blog.pagetitle as blog_pagetitle",
            # "blog.uri as blog_uri",
            "CreatedBy.username as author_username",
            "thread.id as thread_id",
            "thread.positive_votes",
            "thread.negative_votes",
            "thread.comments_count",
        ));
        
        
        
        
        /*
            Проверяем, есть ли голос пользователя здесь
        */
        # $c->leftJoin('SocietyVote', 'vote', "vote.target_class='modResource' AND vote.target_id={$this->classKey}.id AND vote.user_id = ". $this->modx->user->id);
        
        
        # $c->select(array(
        #     "vote.id as vote_id",
        #     "vote.vote_direction",
        #     "vote.vote_value",
        # ));
        
        /*$s = $q->prepare();
        
        $s->execute();
        
        print_r($s->errorInfo());
        
        exit;*/
        
        return $q;
    }
    
    
    # public function afterIteration(array $list){
    #     $list = parent::afterIteration($list);
        
        # $url = $this->getSourcePath($this->modx->getOption('modavatar.default_media_source', null, 15));
        
        # switch($this->getProperty('image_url_schema')){
        #     case 'base':
        #         $images_base_url = $this->modx->runSnippet('getSourcePath');
        #         break;
        #         
        #     case 'full':
        #         $images_base_url = $this->modx->getOption('site_url');
        #         $images_base_url .= preg_replace("/^\//", "", $this->modx->runSnippet('getSourcePath'));
        #         break;
        #         
        #     default: $images_base_url = '';
        # }
        
        # foreach($list as & $l){
        #     if($l['author_avatar']){
        #         $l['author_avatar'] = $url . $l['author_avatar'];
        #     }
        #      
        #     $l['gallery'] = array();
        #     if(
        #         !empty($l['tvs']['gallery']['value'])
        #         AND $gallery = json_decode($l['tvs']['gallery']['value'], 1)
        #         AND is_array($gallery)
        #     ){
        #         foreach($gallery as $image){ 
        #             $image['image'] = $images_base_url . $image['image'];
        #             # $image['image'] = $image['image'];
        #             $l['gallery'][] = $image;
        #             
        #             if(!$l['image']){
        #                 $l['image'] = $image['image'];
        #             }
        #         }
        #         
        #     } 
        # }
        
    #     return $list;
    # }
    
    
}


// return 'modWebSocietyTopicsGetdataProcessor';
