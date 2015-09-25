<?php

require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/topics/update.class.php';

require_once dirname(__FILE__) . '/_validator.class.php';

class modWebSocietyTopicsUpdateProcessor extends modSocietyWebTopicsUpdateProcessor{
    
    
    public function checkPermissions(){
        return $this->modx->user->id && parent::checkPermissions();
    }
    
    public function initialize(){
        
        if(!$id = (int)$this->getProperty('topic_id')){
            return "Не был указан ID топика";
        }
        else{
            $this->setProperty('id', $id);
        }
        
        $this->setDefaultProperties(array(
            "links_follow"  => 0,       // Индексируемые ссылки
        ));
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        //print '<pre>';
        $topic = & $this->object;
        
        
        if(
            !$this->modx->hasPermission('moderate_topics')
            && $topic->createdby != $this->modx->user->id
        ){
            return "Нельзя редактировать чужой топик";
        }
        
        //print_r($topic->toArray());
        
        //print (int)$topic->getMany('BlogTopics');
        
        // Проходимся по каждому указанному блогу
        if($blogs = (int)$this->getProperty('blogs')){
            if($TopicBlogs = & $topic->TopicBlogs){
                if(count($TopicBlogs) != '1'){
                    return "Получено больше одной связки Блог-Топик";
                }
                
                // else
                foreach($TopicBlogs as & $TopicBlog){
                    $blog = $this->modx->getObject('SocietyBlog', $blogs);
                    
                    $TopicBlog->Blog = $blog;
                }
            }
            else{
                $TopicBlog = $this->modx->newObject('SocietyBlogTopic', array(
                    "blogid" => $blogs,
                ));
                $topic->TopicBlogs = $TopicBlog;
            }
        }
        
        // return "Debug";
        
        return parent::beforeSet();
    }
    
    public function beforeSave(){
        
        # print_r($this->properties);
        
        // Устанавливаем теги
        $tags = (array)$this->object->Tags;
        
        if($topic_tags = $this->getProperty('topic_tags', array())){
            if(!is_array($topic_tags)){
                $topic_tags = array_map('trim', explode(',', $topic_tags));
            }
        }
        
        
        # print_r($topic_tags);
        
        // Преобразуем все теги к нижнему регистру
        $words = array();
        foreach($topic_tags as $topic_tag){
            $topic_tag = strip_tags($topic_tag);
            $words[mb_convert_case($topic_tag, MB_CASE_LOWER, 'utf-8')] = $topic_tag;
        }
        
        // Перебираем уже имеющиеся теги.
        // Отсутствующие снимаем с активности.
        // Имеющиеся активируем.
        foreach($tags as & $tag){
            $lower_word = mb_convert_case($tag->tag, MB_CASE_LOWER, 'utf-8');
            if(!array_key_exists($lower_word, $words)){
                $tag->active = 0;
            }
            else{
                $tag->active = 1;
                unset($words[$lower_word]);
            }
        }
        
        // Оставшиеся слова добавляем в виде новых тегов
        foreach($words as $word){
            $tags[] = $this->modx->newObject('SocietyTopicTag', array(
                "tag"       => $word,
                "active"    => 1,
            ));
        }
        
        $this->object->Tags = $tags;
        
        
        $validator = new modWebSocietyTopicsValidator($this);
        $ok = $validator->validate();
        if($ok !== true){
            return $ok;
        }
        
        // return "Debug";
        
        return parent::beforeSave();
    }
    
    public function afterSave(){
        $topic = & $this->object;
        
        // ссылка на источник
        $original_source = $this->getProperty('original_source', null);
        if(isset($original_source)){
            $original_source = strip_tags($original_source);
            $topic->setTVValue(26, $original_source);
        }
        
        // 1. Администрации
        $q = $this->modx->newQuery('modUser');
        $q->innerJoin('modUserGroupMember', 'UserGroupMembers');
        $q->where(array(
            "id:!=" => $this->modx->user->id,
            "UserGroupMembers.user_group"    => 20,
        ));
        
        if($users = $this->modx->getCollection('modUser', $q)){
            $url = $this->modx->makeUrl($topic->id, '', '', 'full');
            $message = "Отредактирован Топик<br />\n
<a href=\"{$url}\">{$topic->pagetitle}</a>
";
             
            foreach($users as $user){
                $user->sendEmail($message, array(
                    "subject"   => "Отредактирован топик на сайте {$site_name}",
                ));
                $this->modx->mail->reset();
            }
        }
        
        // Удаляем неактивные теги
        $this->modx->removeCollection('SocietyTopicTag', array(
            "active"    => 0,
        ));
        
        
        $this->modx->cacheManager->refresh();
        
        return parent::afterSave();
    }
    
    public function cleanup(){
        
        return $this->success('Топик успешно отредактирован', array(
            "id"    => $this->object->id,
        ));
    }
}

return 'modWebSocietyTopicsUpdateProcessor';
