<?php

/*
    Управление медиафайлами
*/


require_once dirname(dirname(__FILE__)) . '/index.class.php';

class ModxsiteMgrMediaBrowserManagerController extends ControllersMgrManagerController{
        /**
     * @inherit
     */
    public function checkPermissions()
    {
        return $this->modx->hasPermission('file_manager');
    }

    /**
     * Register custom CSS/JS for the page
     *
     * @return void
     */
    public function loadCustomCssJs()
    {
        parent::loadCustomCssJs();
        
        # $assets_url = $this->getOption('assets_url');
        # $mgrUrl = $this->modx->getOption('manager_url', null, MODX_MANAGER_URL);
        # $this->addJavascript($mgrUrl . 'assets/modext/widgets/media/browser.js');
        # $this->addJavascript($assets_url . 'js/widgets/media/tree-directory.js');
        # $this->addJavascript($assets_url . 'js/widgets/media/view.js');
        # $this->addJavascript( $assets_url . 'js/widgets/media/browser.js');
        
        if($html = $this->initBrowser()){
            $this->addHtml($html);
        }
        
    }
    
    
    protected function initBrowser(){
        $html = <<<HTML
<script type="text/javascript">
// <![CDATA[
    Ext.onReady(function() {
        MODx.add('modxsite-media-view');
        Ext.getCmp('modx-layout').hideLeftbar(true, false);
    });
// ]]>
</script>
HTML;
        return $html;
    }

    /**
     * @inherit
     */
    public function process(array $scriptProperties = array())
    {
        return array();
    }

    /**
     * @inherit
     */
    public function getPageTitle()
    {
        return $this->modx->lexicon('modx_browser');
    }

    /**
     * @inherit
     */
    public function getTemplateFile()
    {
        return '';
    }

    /**
     * @inherit
     */
    public function getLanguageTopics()
    {
        return array('file');
    }
}