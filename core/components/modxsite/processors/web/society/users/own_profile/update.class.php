<?php

/*
    Обновление собственного профиля
*/

require_once dirname(dirname(__FILE__)) . '/update.class.php';


class modWebSocietyUsersOwnprofileUpdateProcessor extends modWebSocietyUsersUpdateProcessor{
    
    public function checkPermissions(){
        
        $properties = array(
            'user_id'   => $this->modx->user->id,
            "fullname"   => $this->getProperty('fullname'),
            "new_password"   => $this->getProperty('new_password'),
            "notices"   => $this->getProperty('notices', array()),
            "company"   => $this->getProperty('company', null),
        );
        
        $this->properties = array();
        
        $this->setProperties($properties);
        
        # print_r($this->getProperties());
        
        return parent::checkPermissions();
    }
    
}


return 'modWebSocietyUsersOwnprofileUpdateProcessor';


