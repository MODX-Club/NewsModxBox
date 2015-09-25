<?php

/*
    Получаем список уникальных тегов
*/

class modWebSocietyTopicsTopictagsGetlastmodifiedProcessor extends modProcessor{
    
    public function process(){
        $tags = array();
        
        $q = $this->modx->newQuery('SocietyTopicTag');
        $q->innerJoin('modResource', 'Topic');
        
        $q->where(array(
            "Topic.published"   => 1,
            "Topic.deleted"   => 0,
        ));
        
        $q->select(array(
            "tag",
            "max(Topic.publishedon) as max_publishedon",
            "max(Topic.createdon) as max_createdon",
        ));
        $q->sortby('max_publishedon', "desc");
        $q->groupby('tag');
        
        $s = $q->prepare();
        $s->execute();
        
        while($row = $s->fetch(PDO::FETCH_ASSOC)){
            $tags[] = $row;
        }
        
        return $this->success('', $tags);
    }
    
}

return 'modWebSocietyTopicsTopictagsGetlastmodifiedProcessor';
