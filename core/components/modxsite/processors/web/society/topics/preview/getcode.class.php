<?php


class modWebSocietyTopicsPreviewGetcodeProcessor extends modProcessor{
    
    
    public function checkPermissions(){
        
        return $this->modx->user->id && parent::checkPermissions();
    }
     
    
    public function initialize(){
        
        /*
            Устанавливаем автоподстановку Тегов <br /> для Джевикса
        */
        $this->modx->setOption('modjevix.AutoBrMode', true);
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        # $resource = $this->modx->newObject('modResource', array(
        #     "template"  => 2,
        #     "content"   => $this->getProperty('code'),
        # ));
        # 
        # $properties = array(
        #     "mode"      => 
        #     "resource"  => & $resource,
        # );
        # 
        # $this->modx->invokeEvent('OnBeforeDocFormSave', $properties);
        
        $jevix = $this->modx->getService('modJevix','modJevix', MODX_CORE_PATH . 'components/modjevix/model/modJevix/');
          
        $content = $this->getProperty('code'); 
        
        $content = $jevix->parse($content);
        
        return $this->success('', array(
            "code"  => $content,
        ));
    }
    
}

return 'modWebSocietyTopicsPreviewGetcodeProcessor';

