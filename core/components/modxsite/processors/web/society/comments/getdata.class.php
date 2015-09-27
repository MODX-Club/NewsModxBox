<?php

/*
    Получаем все комментарии, просто линейно
*/
require_once dirname(dirname(dirname(__FILE__))) . '/getdata.class.php';

class modWebSocietyCommentsGetdataProcessor extends modWebGetdataProcessor{
    
    public $classKey = 'SocietyComment';
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"  => "id",
            "dir"   => "DESC",
        ));
        
        return parent::initialize();
    }
    
    public function prepareQueryBeforeCount(xPDOQuery $q){
        $q = parent::prepareQueryBeforeCount($q);
        
        $q->innerJoin('SocietyThread', 'Thread');
        $q->innerJoin('modUser', 'CreatedBy', "CreatedBy.id = {$this->classKey}.createdby");
        
        return $q;
    }
    
    
    public function setSelection(xPDOQuery $q){
        $q = parent::setSelection($q);
        
        $q->innerJoin('modUserProfile', 'CreatedByProfile', "CreatedBy.id = CreatedByProfile.internalKey");
        
        $q->leftJoin('SocietyThread', 'CommentThread', "CommentThread.target_id = {$this->classKey}.id AND CommentThread.target_class = 'SocietyComment'");
        
        $q->select(array(
            "CreatedBy.username as author_username",
            "CreatedByProfile.fullname as author_fullname",
            "CreatedByProfile.photo as author_avatar",
            "CommentThread.rating",
            "CommentThread.positive_votes",
            "CommentThread.negative_votes",
            "CommentThread.neutral_votes",
        ));
        
        return $q;
    }
    
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        $url = $this->modx->runSnippet('getSourcePath', array(
            "id"    => $this->modx->getOption('modavatar.default_media_source', null, 15),
        ));
        
        foreach($list as & $l){
            if($l['author_avatar']){
                $l['author_avatar'] = $url . $l['author_avatar'];
            }
        }
        
        return $list;
    }
}


return 'modWebSocietyCommentsGetdataProcessor';
