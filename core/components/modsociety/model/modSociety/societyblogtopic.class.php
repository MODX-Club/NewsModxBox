<?php
class SocietyBlogTopic extends xPDOSimpleObject {
    
    public function save($cacheFlag = NULL){
        if((!$this->get('topicid') && empty($this->_relatedObjects['Topic'])) OR 
                (!$this->get('blogid') && empty($this->_relatedObjects['Blog']))){
            return false;
        }
        return  parent::save($cacheFlag);
    }
}