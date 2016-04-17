<?php

require_once dirname(__FILE__) . '/update.class.php';

class modMgrUsersSubscribersUpdatefromgridProcessor extends modMgrUsersSubscribersUpdateProcessor{
    
    public function initialize() {
        $data = $this->getProperty('data');
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $data = $this->modx->fromJSON($data);
        if (empty($data)) return $this->modx->lexicon('invalid_data');
        $this->setProperties($data);
        $this->unsetProperty('data');
        
        # print '<pre>';
        # 
        # print_r($data);
        # 
        # exit;
        
        return parent::initialize();
    }
}


return 'modMgrUsersSubscribersUpdatefromgridProcessor';

