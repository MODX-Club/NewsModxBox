<?php

require_once dirname(dirname(dirname(__FILE__))) . '/_validator.class.php';

class modWebSocietyUsersValidator extends modWebValidator{
    
    
    public function validate(){
        
        $user = & $this->object;
        
        $profile = & $this->processor->profile;
        
        
        if(!$profile->fullname){
            $this->processor->addFieldError('fullname', "Пожалуйста, укажите ФИО");
        } 
        
        // Проверяем емейл
        if(
            !preg_match("/^[\/_a-z0-9-]+(\.[\/_a-z0-9-]+)*@[\/_a-z0-9-]+(\.[\/_a-z0-9-]+)*(\.[a-z]{2,4})$/i", $profile->email)
        ){
            $this->addFieldError('email', 'Укажите корректный email');
        }
         
        // Обновление компании
        $company = $this->getProperty('company', null);
        if(isset($company)){
             
            $extended = $this->processor->profile->get('extended');
            
            $extended['company'] = $company;
            
            $this->processor->profile->set('extended', $extended);
        }
        
        return parent::validate();
    }
    
}

return 'modWebSocietyUsersValidator';
