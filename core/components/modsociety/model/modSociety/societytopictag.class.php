<?php
class SocietyTopicTag extends modAccessibleSimpleObject {
    
    public function save($cacheFlag= null) {
        
        $this->tag = trim($this->tag);
        
        if(!$this->tag){
            $this->xpdo->log(xPDO::LOG_LEVEL_ERROR, "[- ". __CLASS__ ." -]. Column tag can not be empty");
            return false;
        }
        
        if(!$this->Topic){
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "[- ". __CLASS__ ." -]. Topic required");
            return false;
        }
        
        return parent::save($cacheFlag);
    }    
}