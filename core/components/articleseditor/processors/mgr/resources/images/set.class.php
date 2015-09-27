<?php

/*
    Устанавливаем картинку документу
*/

require_once MODX_PROCESSORS_PATH . 'resource/update.class.php';

class modMgrResourcesImagesSetProcessor extends modResourceUpdateProcessor{
    
    
    public function initialize(){
        
        if(!$id = (int)$this->getProperty('resource_id')){
            return "Не указан ID документа";
        }
        // else
        $this->setProperty('id', $id);
        
        
        if(!$src = $this->getProperty('src')){
            return "Не указана картинка";
        }
        // else
        $this->setProperty('image', $src);
        
        return parent::initialize();
    }
    
    public function beforeSet(){
        
        # $this->setDefaultProperties(array(
        #     "context_key"   => $this->object->context_key,
        #     "pagetitle"   => $this->object->pagetitle,
        #     "alias"   => $this->object->alias,
        # ));
        
        $this->setDefaultProperties($this->object->toArray());
        
        return parent::beforeSet();
    }
    
    # public function process(){
    #     
    #     $id = (int)$this->getProperty('resource_id');
    #     
    #     if(!$resource = $this->modx->getObject('modResource', $id)){
    #         return "Не был получен объект документа";
    #     }
    #     
    #     $resource->setTVValue(2, $this->getProperty('src'));
    #     
    #     return $this->success('Картинка успешно обновлена');
    # }
    
    
    public function clearCache(){
        # $this->modx->cacheManager->refresh();
        # $this->modx->log(1, "Кеш сбрасывается");
        $response = $this->modx->runProcessor('system/clearcache');
        # $this->modx->log(1, print_r($response->getResponse(), 1));
    }
}

return 'modMgrResourcesImagesSetProcessor';

# 
# class modMgrResourcesImagesSetProcessor extends modProcessor{
#     
#     
#     public function initialize(){
#         
#         if(!(int)$this->getProperty('resource_id')){
#             return "Не указан ID документа";
#         }
#         
#         if(!$this->getProperty('src')){
#             return "Не указана картинка";
#         }
#         
#         return parent::initialize();
#     }
#     
#     public function process(){
#         
#         $id = (int)$this->getProperty('resource_id');
#         
#         if(!$resource = $this->modx->getObject('modResource', $id)){
#             return "Не был получен объект документа";
#         }
#         
#         $resource->setTVValue(2, $this->getProperty('src'));
#         
#         return $this->success('Картинка успешно обновлена');
#     }
#     
# }
# 
# return 'modMgrResourcesImagesSetProcessor';

