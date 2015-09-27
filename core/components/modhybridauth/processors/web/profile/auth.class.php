<?php

class modHybridAuthWebProfileAuthProcessor extends modProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "failure_page" => (int)$this->modx->getOption('modhybridauth.failure_page'),        
            "registration_page" => (int)$this->modx->getOption('modhybridauth.registration_page_id'),        
        ));
        
        return parent::initialize();
    }    
    
    
    public function process(){
        
        $this->modx->setLogTarget('FILE');
        
        # $this->modx->log(1, 1);
        
        $user_profile = array();
        //if( !empty( $_GET["action"] ) && $_GET["action"] == 'auth' && !empty($_GET["service"]) ) {
        if($provider = $this->getProperty('provider', false)) {
            # $this->modx->log(1, 2);
            try  {
                $config = $this->modx->modHybridAuth->getProvidersConfig();
                
                $hybridauth = new Hybrid_Auth( $config );
                
                $adapter = $hybridauth->authenticate($provider);
                $user_profile = $adapter->getUserProfile();
            } catch( Exception $e )  {
                $error = "<b>got an error!</b> " . $e->getMessage();
                
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, '[modHybridAuth] ' . $error);
                $url = $this->modx->makeUrl($this->getProperty('failure_page'), null, null, 'full');
                $this->modx->sendRedirect($url);
            }
            
            // Check is loggedin
            if ($this->modx->user->hasSessionContext($this->modx->context->key)) {
                # $this->modx->log(1, 3);
                
                if(
                    !$redirect_id = (int)$this->getProperty('redirect_id')
                    OR !$redirectTo = $this->modx->makeUrl($redirect_id)
                ){
                    $redirectTo = $this->modx->getOption('site_url');
                }
                
                $this->modx->sendRedirect($redirectTo);
                return;
            }
            # $this->modx->log(1, 4);
            // else
            
            // Try to get user by social profile
            $q = $this->modx->newQuery('modUser');
            $q->innerJoin('modUserProfile', 'Profile');
            $q->innerJoin('modHybridAuthUserProfile', 'SocialProfiles');
            $q->innerJoin('modHybridAuthProvider', 'Provider', "Provider.id=SocialProfiles.provider");
            $q->where(array(
                "SocialProfiles.identifier"  => $user_profile->identifier,
                "Provider.name"     => $provider,
                "modUser.active"    => 1,
                "Profile.blocked"   => 0,
            ));
            $q->limit(1);
            
            //$q->prepare();
            //$this->modx->log(1, $q->toSQL());
            
            if($user = $this->modx->getObject('modUser', $q)){
                # $this->modx->log(1, 5);
                $user->addSessionContext($this->modx->context->key);
                
                if(
                    !$redirect_id = (int)$this->getProperty('redirect_id')
                    OR !$redirectTo = $this->modx->makeUrl($redirect_id)
                ){
                    $redirectTo = $this->modx->getOption('site_url');
                }
                
                $this->modx->sendRedirect($redirectTo);
                return;
            }
            
            // else send to registration
            if(
                $redirect_id = $this->getProperty('registration_page') 
                AND $redirect_url = $this->modx->makeUrl($redirect_id)
            ){
                # $this->modx->log(1, 6);
                $this->modx->sendRedirect($redirect_url);
                return;
            }
            else{
                $this->modx->log(1, "[- ". __CLASS__." -] registration_page is not setted");
                return "Cannot process registration";
            }
        }
        else{
            # $this->modx->log(1, 7);
            $response = $this->modx->runProcessor('web/endpoint', 
                $this->getProperties(), 
                array(
                    'processors_path'   => $this->modx->modHybridAuth->getOption('processorsPath'),        
                )
            );
            return $response->getResponse();
        }
        # $this->modx->log(1, 8);
        return '';
    }
}

return 'modHybridAuthWebProfileAuthProcessor';