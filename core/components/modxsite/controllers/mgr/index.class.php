<?php
 
class ControllersMgrManagerController extends modExtraManagerController{
    
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        $namespace = "modxsite";
        
        $manager_url = $modx->getOption('manager_url');
        
        $path = "{$manager_url}components/{$namespace}/";
        
        # $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
        $this->config['manager_url'] = 
        $this->config['assets'] = 
        $this->config['assets_url'] = 
        $modx->getOption("{$namespace}.manager_url", null, $path);
        $this->config['connectors_url'] = $this->config['manager_url'].'connectors/';
        $this->config['connector_url'] = $this->config['connectors_url'].'connector.php';
        
    }
    
    # public static function getInstance(modX &$modx, $className, array $config = array()) {
    #     $className = __CLASS__;
    #     return new $className($modx, $config);
    # }
    
    public function getOption($key, $options = null, $default = null, $skipEmpty = false){
        $options = array_merge($this->config, (array)$options);
        return $this->modx->getOption($key, $options, $default, $skipEmpty);
    }

    public function getLanguageTopics() {
        return array('basket:default');
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        $assets_url = $this->getOption('assets_url');
        # $this->addJavascript( $assets_url . 'js/modxsite.js');
        # 
        # $this->addHtml('<script type="text/javascript">
        #     ModxSite.config = '. $this->modx->toJSON($this->config).';
        # </script>');
        
        
        # $attrs = $this->modx->user->getAttributes(array(),'', true);
        # $policies = array();
        # if(!empty($attrs['modAccessContext']['mgr'])){
        #     foreach($attrs['modAccessContext']['mgr'] as $attr){
        #         foreach($attr['policy'] as $policy => $value){
        #             if(empty($policies[$policy])){
        #                 $policies[$policy] = $value;
        #             }
        #         }
        #     }
        # }
        
        # $this->modx->regClientStartupScript('<script type="text/javascript">
        #     Shop.policies = '. $this->modx->toJSON($policies).';
        # </script>', true);
        
        /*$this->addJavascript($this->getOption('assets_url').'js/shop.js'); 
        
        
        
        */
        
        
        return;
    }
    
    # public function getTemplatesPaths($coreOnly = false) {
    #     $paths = parent::getTemplatesPaths($coreOnly);
    #     $paths[] = $this->config['namespace_path']."templates/default/";
    #     return $paths;
    # } 
} 



?>
