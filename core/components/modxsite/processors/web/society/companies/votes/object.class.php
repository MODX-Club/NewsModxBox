<?php

/*
    Голосовние за определенную компанию (заведение).
*/

require_once __DIR__ . '/../../votes/object.class.php';


class modWebSocietyCompaniesVotesObjectProcessor extends modWebSocietyVotesObjectProcessor{
    
    
    public function initialize(){
        
        $vote_value = ceil($this->getProperty('vote_value', 0));
        
        if(
            $vote_value < 1
            OR $vote_value > 5
        ){
            return "Укажите значение от единицы до пяти";
        }
        
        //else
        
        $this->setProperties(array(
            "target_class"  => "modResource",
            "vote_value"    => $vote_value,
        ));
        
        /*
            Проверяем тип рейтинга
        */
        if(!$type = (int)$this->getProperty('type')){
            return "Укажите параметр рейтинга";
        }
        
        // else
        if(!$this->modx->getCount('modResource', array(
            "id"    => $type,
            "published" => 1,
            "deleted"   => 0,
            "hidemenu"  => 0,
            "parent"    => 1349,
        ))){
            return "Указан несуществующий тип рейтинга";
        }
        
        
        /*
            Пробуем получить ранее имеющийся отзыв от пользователя
        */
        $q = $this->modx->newQuery($this->classKey, array(
            "user_id"       => $this->modx->user->id,
            'type'          => $this->getTypeFromRequest(),   // Тип голоса
            "target_class"  => $this->getProperty('target_class'),
            "target_id"  => $this->getProperty('target_id'),
        )); 
        
        $q->limit(1);
        
        $q->select(array(
            "id",
        ));
        
        if($id = (int)$this->modx->getValue($q->prepare())){
            $this->setProperty('id', $id);
        }
        
        return parent::initialize();
    }
    
    
    protected function getTypeFromRequest(){
        return (int)$this->getProperty('type');
    }
    
    
    public function beforeSave(){
        
        $vote = & $this->object;
          
        if(!$this->modx->getCount('modResource', array(
            "id"    => $vote->target_id,
            "published" => 1,
            "deleted"   => 0,
            "template"  => 27,
        ))){
            return "Такие рейтинги принимаются только на заведения";
        }
        
        
        # $ok = parent::beforeSave();
        # if($ok !== true){
        #     return $ok;
        # }
        # 
        # print_r($vote->toArray());
        # print_r($vote->Thread->toArray());
        # 
        # return 'Debug';
        
        return parent::beforeSave();
    }
}


return "modWebSocietyCompaniesVotesObjectProcessor";
