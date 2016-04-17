<?php

/*
    Получаем список файлов
*/

require_once dirname(__FILE__) . '/getlist.class.php';

class modMgrMediaBrowserDirectoryGetfilesProcessor extends modMgrMediaBrowserDirectoryGetlistProcessor{
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "output_format" => "json",
            "thumb_width"       => 100,
            "thumb_height"      => 80,
        ));
        
        return parent::initialize();
    }
    
    
    public function afterIteration(array $list) {
        $connectors_url = $this->modx->getOption('connectors_url');
        
        $thumb_width = $this->getProperty('thumb_width');
        $thumb_height = $this->getProperty('thumb_height');
        
        $list = parent::afterIteration($list);
        
        foreach($list as & $l){
            if($l['type'] == "file"){
                
                $image_url = "{$connectors_url}system/phpthumb.php?src=/uploads/{$l['pathRelative']}";
                
                $l['preview'] = 1;
                $l['thumb_width'] = $thumb_width;
                $l['thumb_height'] = $thumb_height;
                $l['image'] = "{$image_url}&w=500&h=300&q=90";
                $l['thumb'] = "{$image_url}&w={$thumb_width}&h={$thumb_height}&f=png&q=90&far=C";
                $l['url'] = $l['relativeUrl'];
                $l['fullRelativeUrl'] = "uploads/".$l['relativeUrl'];
            }
        }
        
        return $list;
    }
    
    
    # public function outputArray(array $array,$count = false) {
    #     return $this->modx->toJSON($array);
    # }
    
}

return 'modMgrMediaBrowserDirectoryGetfilesProcessor';
