<?php


require_once dirname(__FILE__) . '/update.class.php';


class modWebSocietyCommentsStatusUnpublishProcessor extends modWebSocietyCommentsStatusUpdateProcessor{
    
    
    public function checkPermissions(){
        
        return parent::checkPermissions() && $this->modx->hasPermission('publish_comments');
    }
    
    
    public function beforeSet(){
        $ok = parent::beforeSet();
        
        if($ok !== true){
            return $ok;
        }
        
        $this->setProperties(array(
            'published' => 0,
        ));
        
        return true;
    }
    
}


return 'modWebSocietyCommentsStatusUnpublishProcessor';
