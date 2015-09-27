<?php

require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/threads/comments/update.class.php';

class modWebSocietyCommentsUpdateProcessor extends modSocietyWebThreadsCommentsUpdateProcessor{
    
    
    public function initialize(){

        // Sanitize post data
        $this->unsetProperty('published');
        
        return parent::initialize();
    }
}

return 'modWebSocietyCommentsUpdateProcessor';

