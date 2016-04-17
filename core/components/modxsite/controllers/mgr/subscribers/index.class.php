<?php

/*
    Управление подписками
*/

require_once dirname(dirname(__FILE__)) . '/index.class.php';

class ModxsiteControllersMgrSubscribersIndexManagerController extends ControllersMgrManagerController{
    
    
    
    public function getPageTitle() {
        # return $this->modx->lexicon('articleseditor');
        return "Управление подписками";
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        $assets_url = $this->getOption('assets_url');
        # $this->addJavascript( $assets_url . 'js/widgets/subscribers/grid.js');
        $this->addLastJavascript( $assets_url . 'js/widgets/subscribers/grid.js');
         
        
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function(){
                MODx.add("modxsite-grid-subscribersgrid");
            });
        </script>');
                # MODx.load("articleseditor-panel-panel");
        
        return;
    }
    
}



