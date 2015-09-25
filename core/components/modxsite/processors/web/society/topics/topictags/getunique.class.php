<?php

/*
    Получаем список уникальных тегов
*/

class modWebSocietyTopicsTopictagsGetuniqueProcessor extends modProcessor{
    
    public function process(){
        $tags = array();
        
        $q = $this->modx->newQuery('SocietyTopicTag');
        $q->select(array(
            "tag",
        ));
        $q->sortby('tag');
        $q->groupby('tag');
        
        $s = $q->prepare();
        $s->execute();
        
        while($row = $s->fetch(PDO::FETCH_ASSOC)){
            $tags[] = $row['tag'];
        }
        
        return $this->success('', $tags);
    }
    
}

return 'modWebSocietyTopicsTopictagsGetuniqueProcessor';
