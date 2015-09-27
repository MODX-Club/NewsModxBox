<?php


class modWebSocietyUsersActivateProcessor extends modObjectUpdateProcessor{
    
    public $classKey = 'modUser';
    
    
    public function initialize(){
        
        if(!$user_id = $this->getProperty('user_id')){
            return "Не указан ID пользователя";
        }
        // else
        $this->setProperty('id', $user_id);
        
        if(!$key = $this->getProperty('key')){
            return "Не указан ключ";
        }
        
        $this->setProperties(array(
            "active"    => 1,
        ));
        
        $this->setDefaultProperties(array(
            "auto_auth" => false,   // Автоматическая авторизация пользователя
        ));
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        $user = & $this->object;
        $profile = & $user->Profile;
        
        // Проверяем ключ
        if($this->getProperty('key') != md5($user->id . $profile->email)){
            return "Неверная подпись";
        }
        
        // Проверяем активацию
        if($user->active){
            return "Пользователь был активирован ранее";
        }
        
        // Проверяем блокировку
        if($profile->blocked || ($profile->blockeduntil > time())){
            return "Пользователь заблокирован";
        }
        
        return parent::beforeSet();
    }
    
    
    public function cleanup(){ 
        // Автоматическая авторизация прописана в action-процессоре
        if($this->getProperty('auto_auth') && !$this->modx->user->id){
            $this->modx->user = & $this->object;
            $this->modx->user->addSessionContext($this->modx->context->key); 
        }
        
        return $this->success('Пользователь успешно активирован', array(
            "id"    => $this->object->id,
        ));
    }
}


return 'modWebSocietyUsersActivateProcessor';

