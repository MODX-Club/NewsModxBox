<?php

/*
Процессор, определяющий по запрошенному действию какой процессор выполнять
*/


class modWebPublicActionProcessor extends modProcessor{
    
    protected static $actualClassName;
    
    public static function getInstance(modX &$modx,$className,$properties = array()) {
        
        // Здесь мы имеем возможность переопределить реальный класс процессора
        if(!empty($properties['pub_action']) && !self::$actualClassName){
             
            switch($properties['pub_action']){
                
                case 'currencies/update_courses':
                    require_once dirname(dirname(__FILE__)) . '/currencies/update_courses.class.php';
                    self::$actualClassName = "modWebCurrenciesUpdatecoursesProcessor";
                    break;
                
                case 'weather/update':
                    require_once dirname(dirname(__FILE__)) . '/weather/update.class.php';
                    self::$actualClassName = "modWebWeatherUpdateProcessor";
                    break;
                
                case 'login':
                    require_once dirname(dirname(__FILE__)) . '/users/login.class.php';
                    self::$actualClassName = "modWebUsersLoginProcessor";
                    break;
                 
                
                case 'topics/preview/getcode':
                    require dirname(dirname(__FILE__)) . '/society/topics/preview/getcode.class.php';
                    self::$actualClassName = 'modWebSocietyTopicsPreviewGetcodeProcessor';
                    break; 
                
                case 'topic/save':
                    require dirname(dirname(__FILE__)) . '/society/topics/create.class.php';
                    self::$actualClassName = 'modWebSocietyTopicsCreateProcessor';
                    break; 
                
                case 'topics/comments/save':
                    require dirname(dirname(__FILE__)) . '/society/topics/comments/create.class.php';
                    self::$actualClassName = 'modWebSocietyTopicsCommentsCreateProcessor';
                    break; 
                
                
                case 'topics/votes/create':
                    require dirname(dirname(__FILE__)) . '/society/topics/votes/create.class.php';
                    self::$actualClassName = 'modWebSocietyTopicsVotesCreateProcessor';
                    break; 
                
                case 'topics/comments/votes/create':
                    require dirname(dirname(__FILE__)) . '/society/topics/comments/votes/create.class.php';
                    self::$actualClassName = 'modWebSocietyTopicsCommentsVotesCreateProcessor';
                    break; 
                    
                
                case 'comments/remove':
                    require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/threads/comments/remove.class.php';
                    self::$actualClassName = "modSocietyWebThreadsCommentsRemoveProcessor";
                    break;
    
                case 'comments/publish': 
                    require_once dirname(dirname(__FILE__)) . '/society/comments/status/publish.class.php';
                    self::$actualClassName = "modWebSocietyCommentsStatusPublishProcessor";
                    break;
    
                case 'comments/unpublish': 
                    require_once dirname(dirname(__FILE__)) . '/society/comments/status/unpublish.class.php';
                    self::$actualClassName = "modWebSocietyCommentsStatusUnpublishProcessor";
                    break;
                
                case 'email_messages/articles/create_mailing':
                    require dirname(dirname(__FILE__)) . '/society/email_messages/articles/create_mailing.class.php';
                    self::$actualClassName = 'modWebSocietyEmailmessagesArticlesCreatemailingProcessor';
                    break; 
                
                case 'email_messages/send':
                    require dirname(dirname(__FILE__)) . '/society/email_messages/send.class.php';
                    self::$actualClassName = 'modWebSocietyEmailmessagesSendProcessor';
                    break; 
                
                default:;
            } 
        }
        
        if(self::$actualClassName){
            $className = self::$actualClassName;
            return $className::getInstance($modx,$className,$properties);
        }

        return parent::getInstance($modx,$className,$properties);
    }
    
    
    public function process(){
        
        $error = 'Действие не существует или не может быть выполнено';
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, __CLASS__ . " - {$error}");
        $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($this->getProperties(), true));
        return $this->failure($error);
    }
    
}

return 'modWebPublicActionProcessor';