<?php

require_once dirname(dirname(__FILE__)) . '/update.class.php';


class modMgrUsersSubscribersUpdateProcessor extends modMgrUsersUpdateProcessor{
    
    public function initialize(){
        
        $id = (int)$this->getProperty('id');
        if(!$subscribe_till = $this->getProperty('subscribe_till_str')){
            $subscribe_till = null;
        }
        else if(!is_numeric($subscribe_till)){
            $subscribe_till = strtotime($subscribe_till);
        }
        
        $this->properties = array();
        
        $this->setProperties(array(
            "id"    => $id,
            "subscribe_till"    => $subscribe_till,
        ));
        
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        $this->setProperties(array(
            "username"  => $this->object->username,
            "email"  => $this->object->Profile->email,
        ));
        
        return parent::beforeSet();
    }
    
}


return 'modMgrUsersSubscribersUpdateProcessor';

