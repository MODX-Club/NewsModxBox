<?php

// require_once MODX_CORE_PATH.'model/modx/modresource.class.php';

class SocietyTopic extends modResource {
    
    public $showInContextMenu = true;
    public $allowChildrenResources = false; 
    
    public $TopicAttributes;

    function __construct(xPDO & $xpdo) {
        parent :: __construct($xpdo);  
    }
    
    
    public static function getControllerPath(xPDO &$modx) {
        $path = $modx->getOption('modsociety.controller_path',null);
        if(empty($path)){
            $path = $modx->getOption('modsociety.core_path',null, 
                $modx->getOption('core_path', null ). 'components/modsociety/')
                    .'controllers/mgr/';
        }
        $path .= "societytopic/";
        return $path;
    }

    public function getContextMenuText() {
        $this->xpdo->lexicon->load('modsociety:resources');
        return array(
            'text_create' => $this->xpdo->lexicon('modsociety_topic_resource_create'),
            'text_create_here' => $this->xpdo->lexicon('modsociety_topic_resource_create_here'),
        );
    }
    
    public function getResourceTypeName() {
        $this->xpdo->lexicon->load('modsociety:resources');
        return $this->xpdo->lexicon('modsociety_topic_resource');
    }
    
    function get($k, $format = NULL, $formatTemplate = NULL){
        switch($k){
            case 'url':
                return $this->makeUrl();
                break;
        }
        return parent::get($k, $format, $formatTemplate);
    }
    
    function makeUrl(){
        if(!$id = $this->get('id')){
            return false;
        }
        return $this->xpdo->getOption('base_url')."topics/{$id}/";
    }
    
    
    /*
        Проверяем права к топику.
        Но для начала надо проверить права к блогу, в котором топик находится.
    */
    public function checkPolicy($criteria, $targets = null, modUser $user = null) {
        if(!$user){
            $user = & $this->xpdo->user;
        }
        
        // Проверяем права на блог (хотя бы один)
        if($TopicBlogs = $this->TopicBlogs){
            $hasBlogAccess = false;
            foreach($TopicBlogs as $topicblog){
                // print_r($topicblog->Blog->toArray());
                if(
                    $blog = $topicblog->Blog
                    AND $blog->checkPolicy($criteria, $targets, $user)
                ){
                    $hasBlogAccess = true;
                    break;
                }
            }
            if(!$hasBlogAccess){
                return false;
            }
        }
        
        if ($criteria && $this->xpdo instanceof modX && $this->xpdo->getSessionState() == modX::SESSION_STATE_INITIALIZED) {
            if ($user->get('sudo')) return true;
            if (!is_array($criteria) && is_scalar($criteria)) {
                $criteria = array("{$criteria}" => true);
            }
            $policy = $this->findPolicy();
            if (!empty($policy)) {
                // print "sdfdfd";
                $principal = $user->getAttributes($targets);
                if (!empty($principal)) {
                    foreach ($policy as $policyAccess => $access) {
                        foreach ($access as $targetId => $targetPolicy) {
                            foreach ($targetPolicy as $policyIndex => $applicablePolicy) {
                                if ($this->xpdo->getDebug() === true)
                                    $this->xpdo->log(xPDO::LOG_LEVEL_DEBUG, 'target pk='. $this->getPrimaryKey() .'; evaluating policy: ' . print_r($applicablePolicy, 1) . ' against principal for user id=' . $user->id .': ' . print_r($principal[$policyAccess], 1));
                                $principalPolicyData = array();
                                $principalAuthority = 9999;
                                if (isset($principal[$policyAccess][$targetId]) && is_array($principal[$policyAccess][$targetId])) {
                                    foreach ($principal[$policyAccess][$targetId] as $acl) {
                                        $principalAuthority = intval($acl['authority']);
                                        $principalPolicyData = $acl['policy'];
                                        $principalId = $acl['principal'];
                                        if ($applicablePolicy['principal'] == $principalId) {
                                            if ($principalAuthority <= $applicablePolicy['authority']) {
                                                if (!$applicablePolicy['policy']) {
                                                    return true;
                                                }
                                                if (empty($principalPolicyData)) $principalPolicyData = array();
                                                $matches = array_intersect_assoc($principalPolicyData, $applicablePolicy['policy']);
                                                if ($matches) {
                                                    if ($this->xpdo->getDebug() === true)
                                                        $this->xpdo->log(modX::LOG_LEVEL_DEBUG, 'Evaluating policy matches: ' . print_r($matches, 1));
                                                    $matched = array_diff_assoc($criteria, $matches);
                                                     if (empty($matched)) {
                                                        return true;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                return false;
            }
        }
        return true;
    }
    
    
}