<?php
class SocietyNoticeUser extends xPDOSimpleObject {
    
    public function save($cacheFlag= null) {
        
        if(!$this->User){
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "[- ". __CLASS__ ." -]. User required");
            return false;
        }
        
        if(!$this->NoticeType){
            $this->xpdo->log(xPDO::LOG_LEVEL_WARN, "[- ". __CLASS__ ." -]. NoticeType required");
            return false;
        }
        
        return parent::save($cacheFlag);
    }  
}