<?php

/*
    Отправляем статью на редактуру.
    Отправить на редактуру может только владелец статьи и только свою
*/


class modMgrArticlesUpdateStatusRedactingProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modResource';
    
    
    public function checkPermissions(){
        
        return $this->modx->user->id && parent::checkPermissions();
    }
    
    public function initialize(){
        
        if(!(int)$this->getProperty('id')){
            return "Не был получен id документа";
        }
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        $id = (int)$this->getProperty('id');
        
        if($id != $this->object->id){
            return "ID документа не совпадает";
        }
        
        // else
        $this->setProperties($this->object->toArray());
        
        return parent::beforeSet();
    }
    
    
    public function beforeSave(){
        $resource =& $this->object;
        
        if($resource->createdby != $this->modx->user->id){
            return "На редактуру можно отправить только свою статью";
        }
        
        // else
        
        // Проверяем статус документа
        if($resource->published){
            return "Нельзя отправить на редактуру опубликованную статью";
        } 
        
        if($resource->article_status){
            return "Нельзя отправить на редактуру не новую статью";
        }
        
        //else
        $resource->set('article_status', 1);
        
        
        # return "Debug";
        return parent::beforeSave();
    }
    
}

return 'modMgrArticlesUpdateStatusRedactingProcessor';


