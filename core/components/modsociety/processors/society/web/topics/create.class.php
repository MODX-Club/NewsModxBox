<?php

require_once MODX_PROCESSORS_PATH . 'resource/create.class.php';

class SocietyTopicCreateProcessor extends modResourceCreateProcessor{
    
    public $classKey = "SocietyTopic";
    
    public $permission = '';
    
    
    public function initialize(){
        
        $this->setProperties(array(
            'class_key' => 'SocietyTopic',
        ));
        
        
        if(!$this->getProperty('pagetitle')){
            $this->addFieldError('pagetitle', "Не указано название топика");
        }
        
        if(!$this->getProperty('content')){
            $this->addFieldError('content', "Не заполнен текст топика");
        }
        
        /*if(!$blogs = $this->getProperty('blogs')){
            $this->addFieldError('blogs', "В какой блог публикуем топик?");
        }
        else */
        
        $blogs = $this->getProperty('blogs');
        
        if($blogs && !is_array($blogs)){
            $blogs = explode(',', $blogs);
            $blogs = array_map('trim', $blogs);
            $this->setProperty('blogs', $blogs);
        }
        
        if($this->hasErrors()){
            return 'Проверьте правильность заполнения данных';
        }
        
        return parent::initialize();
    }
    
    
    public function beforeSave(){
        $properties = $this->getProperties();
          
         
        // Проходимся по каждому указанному блогу
        if($blogs = $this->getProperty('blogs')){
             
            $BlogTopics = array(); 
              
            
            foreach((array)$blogs as $blog_id){
                // Проверяем получение блога
                if(
                    !$blog = $this->modx->getObject('SocietyBlog', (int)$blog_id)
                    OR ! $blog instanceof SocietyBlog
                ){
                    // print_r($blog->toArray());
                    return 'Не был получен указанный блог';
                }
                 
                // else
                // Устанавливаем связку Блог-Топик
                $BlogTopic = $this->modx->newObject('SocietyBlogTopic');
                $BlogTopic->addOne($blog);
                $BlogTopics[] = $BlogTopic;
            }
            
            $this->object->addMany($BlogTopics);
        }
        
        // Проверяем блоги топика
        if(!$TopicBlogs = $this->object->TopicBlogs){
            return "Не был указан ни один блог";
        }
        
        // Иначе проверяем права на блог
        foreach($this->object->TopicBlogs as $TopicBlog){
            
            $blog = $TopicBlog->Blog;
            
            if(!$blog instanceof SocietyBlog){
                return "Публиковать топики можно только в блоги";
            }
            
            $ok = $this->checkBlogAccess($blog);
            
            if($ok !== true){
                return $ok;
            }
        }
        
        // Добавляем атрибуты топика
        $attributes = $this->modx->newObject('SocietyTopicAttributes', $properties);
        
        $this->object->addOne($attributes);
         
        return parent::beforeSave();
    }
    
    public function checkBlogAccess($blog){
        
        if(!$blog->checkPolicy('society_topic_resource_create')){
            return "У вас нет прав писать в блог {$blog->pagetitle}";
        }
        
        return true;
    }
    
}

return 'SocietyTopicCreateProcessor';

