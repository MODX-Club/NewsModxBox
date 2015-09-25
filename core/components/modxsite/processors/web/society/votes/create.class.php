<?php

/*
    Create new vote
*/

/*
    Depricated. Пока еще используется, но надо будет перевести на object-процессор (лежит в этой директории)
*/

class modWebSocietyVotesCreateProcessor extends modObjectCreateProcessor{

    public $classKey = 'SocietyVote';
    
    
    public function initialize(){
         
        if(!$this->modx->user->id){
            return 'Необходимо авторизоваться';
        }
        
        $this->setDefaultProperties(array(
            'vote_direction'    => 0,
            'vote_value'        => 0.000,
            "voted_message"     => "Вы не можете голосовать дважды за один и тот же объект",    // Сообщение, если пользователь уже голосовал
        ));
        
        $vote_value = (float)$this->getProperty('vote_value');
        
        if($vote_value > 0){
            $this->setProperty('vote_direction', '1');
        }
        else if($vote_value < 0){
            $this->setProperty('vote_direction', '-1');
        }
        
        $this->setProperties(array(
            "user_id"       => $this->modx->user->id,
            'type'              => $this->getTypeFromRequest(),   // Тип голоса
            "vote_date"     => time(),
        )); 
        
        return parent::initialize();
    }
    
    
    /*
        Получаем указанный в запросе тип.
        По умолчанию указываем 0, чтобы не могли указывать типы там,
        где этого нельзя делать
    */
    protected function getTypeFromRequest(){
        return 0;
    }
    
    
    public function beforeSave(){
        
        $vote = & $this->object;
          
        /*
            Если указан ID диалоговой ветки, то пытаемся ее получить
        */
        
        
        
        if($vote->thread_id){
            
            // Если ветка не была получена, то ошибка
            if(!$vote->Thread){
                return 'Не была получена диалоговая ветка';
            }
            
            
            // else
            // Иначе устанавливаем голосу id и класс целевого объекта
            $vote->fromArray(array(
                "target_id"     => $vote->Thread->target_id,
                "target_class"  => $vote->Thread->target_class,
            ));
            
            /*
                Пометка. На этом этапе хорошо бы проверять права на голосование за этот объект
            */
        }
        
        else if(!$target_id = $vote->target_id){
            return 'Не был получен ID целевого  объекта';
        }
        
        else if(!$target_class = $vote->target_class){
            return 'Не был получен класс целевого объекта';
        }
        
        // Пытаемся получить ветку по классу и id цели.
        // Иначе создаем новую
        else if($thread = $this->modx->getObject('SocietyThread', array(
            "target_class" => $target_class,
            "target_id" => $target_id,
        ))){
            $vote->Thread = $thread;
        }
        else{
            $vote->Thread = $this->modx->newObject('SocietyThread', array(
                "target_class"  => $target_class,
                "target_id"     => $target_id,
                # "createdon"     => time(),
            ));
        }
        
        // Иначе пытаемся получить диалоговую ветку по этим id и классу объекта
        // Или целевой объект. Если не был получен, возвращаем ошибку
        # else if(
        #     !$thread = $this->object->getOne('Thread', array(
        #         "target_id"     => $target_id,
        #         "target_class"  => $target_class,
        #     ))
        #     AND !$this->modx->getCount($target_class, $target_id)
        # ){
        #     return "Не был получен целевой объект";
        # }
        
        // Проверка наличия самого целевого объекта
        if(!$this->modx->getCount($vote->target_class, $vote->target_id)){
            return 'Не был получен целевой объект';
        }
        
        if(!$thread =& $vote->Thread){
            return "Не была получена целевая ветка";
        }
        
        // Проверяем совпадение ключей цели в объекте и в диалоговой ветке
        if(
            $thread->target_class != $vote->target_class
            OR $thread->target_id != $vote->target_id
        ){
            return "Не совпадают цели в объекте голоса и диалоговой ветке";
        }
        
        /*
            Если есть объект диалоговой ветки,
            обновляем рейтинг, а так же счетчики общего числа голосов
        */
        $thread->rating += $vote->vote_value;
        
        if($vote->vote_direction > 0){
            $thread->positive_votes += 1;
        }
        else if($vote->vote_direction < 0){
            $thread->negative_votes += 1;
        }
        else{
            $thread->neutral_votes += 1;
        }
        
        // Проверяем, чтобы этот пользователь еще не голосовал
        $q = $this->modx->newQuery($this->classKey, array(
            "user_id"       => $vote->user_id,
            "target_class"  => $vote->target_class,
            "target_id"     => $vote->target_id,
            "type"          => $vote->type,
        ));
        $q->limit(1);
        
        if($this->modx->getCount($this->classKey, $q)){
            return $this->getProperty('voted_message');
        }
         
        
        # return "Debug";
        
        return parent::beforeSave();
    }
    
}

return 'modWebSocietyVotesCreateProcessor';

