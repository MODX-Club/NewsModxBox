<?php

/*
    Создаем новый топик-вопрос
*/

require_once dirname(dirname(__FILE__)) . '/create.class.php';

class modWebSocietyTopicsQuestionsCreateProcessor extends modWebSocietyTopicsCreateProcessor{
    
    
    public function checkPermissions(){
        
        /*
            Процессор этот публичный, так как топики будут с премодерацией
        */
        
        return true;
    }
    
    public function initialize(){
        
        # $this->setDefaultProperties(array(
        #     'no_send_emails'    => 0,       // Не отсылать емейл-рассылку пользователям о новом топике  
        #     'tv26'              => strip_tags($this->getProperty('original_source')),   // Ссылка на источник
        # ));
        
        $this->setProperties(array(
            "published" => 0,
            # "parent" => 358,
        ));
        
        # if(
        #     $notices = $this->getProperty('notices')
        #     AND !is_array($notices)
        # ){
        #     $notices = array_map('trim', explode(",", $notices));
        #     $this->setProperty('notices', $notices);
        # }
        
        return parent::initialize();
    }
    
    
    public function beforeSave(){
        $topic = & $this->object;
        
        
        /*
            Если не был указан пользователь, то создаем нового
        */
        if(!$topic->CreatedBy){
            
            if(!$fullname = $this->getProperty('fullname')){
                return "Не было указано имя";
            }
            
            if(!$email = $this->getProperty('email')){
                return "Не был указан емейл";
            }
            
            $username = preg_replace("/\@.*/", "", $email) . '_' . rand(100, 999);
            
            // Проверяем наличие пользователя по юзернейму
            if($this->modx->getCount('modUser', array(
                "username" => $username,
            ))){
                return "Ошибка. попробуйте повторить попытку еще раз.";
            }
            
            // Проверяем наличие пользователя по емейлу
            if($this->modx->getCount('modUserProfile', array(
                "email" => $email,
            ))){
                return "Этот емейл уже занят. Авторизуйтесь или укажите другой емейл.";
            }
            
            $password = substr(md5(time()), 0, 6);
            
            $user = $this->modx->newObject('modUser', array(
                "username"  => $username,
                "password"  => $password,
            ));
            
            $user->Profile = $this->modx->newObject("modUserProfile", array(
                "email" => $email,
                "fullname"  => $fullname,
            ));
            
            $user->UserGroupMembers = $this->modx->newObject('modUserGroupMember', array(
                "user_group"    => 46,
            ));
            
            $topic->CreatedBy = $user;
        }
         
        
        return parent::beforeSave();
        
        # return !$this->hasErrors();
    }
    
}

return 'modWebSocietyTopicsQuestionsCreateProcessor';

