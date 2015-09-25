<?php

require_once dirname(dirname(__FILE__)). '/index.class.php';

class ModxsiteControllersMgrImportIndexManagerController extends ControllersMgrManagerController{
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        $className = __CLASS__;
        return new $className($modx, $config);
    }

    function loadCustomCssJs(){ 
        
        parent::loadCustomCssJs();
        $assets_url = $this->getOption('assets_url');
        $this->modx->regClientStartupScript($assets_url.'js/widgets/import/import.js'); 
        
        $this->modx->regClientStartupScript('<script>
            Ext.onReady(function(){
                MODx.add("shop-panel-import");
            });
        </script>', true); 
        
        
        return;
    }
    
    # public function getTemplateFile() {
    #     return 'import/layout.tpl';
    # }
}