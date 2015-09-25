<?php

// ini_set('display_errors', 1);

/**
 * Read from the registry to console
 *
 * @param string $register The register to read from
 * @param string $topic The topic in the register to read from
 * @param string $format (optional) The format to output as. Defaults to json.
 * @param string $register_class (optional) If set, will load a custom registry
 * class.
 * @param integer $poll_limit (optional) The number of polls to limit to.
 * Defaults to 1.
 * @param integer $poll_interval (optional) The interval of polls to grab from.
 * Defaults to 1.
 * @param integer $time_limit (optional) The time limit to sort by. Defaults to
 * 10.
 * @param integer $message_limit (optional) The max amount of messages to grab.
 * Defaults to 200.
 * @param boolean $remove_read (optional) If false, will not remove the message
 * when read. Defaults to true.
 * @param boolean $show_filename (optional) If true, will show the filename in
 * the message. Defaults to false.
 *
 * 
 * 
 * @level
 * debug
 * info
 * warn
 * error
 * 
 * 
 * @package modx
 * @subpackage processors.system.registry.register
 */
abstract class modBasketMgrImportConsoleProcessor extends modProcessor {
    
    protected $catalogParentID = 124;    // ID раздела шин
    protected $importTmpClass     = 'ShopmodxImportTmp';    // Временная таблица
    
    protected $productClassKey  = 'ShopmodxResourceProduct';
    
    // Префикс для артиклов, чтобы не перемешивались с товарами разных каталогов (шин и дисков)
    protected $articlePrefix    = '';  
    
    public function initialize() {
        /*$register = $this->getProperty('register');
        if (empty($register) || !preg_match('/^[a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff]*$/',$register)) {
            return $this->modx->lexicon('error');
        }

        $topic = $this->getProperty('topic');
        if (empty($topic)) {
            return $this->modx->lexicon('error');
        }*/
        
        if(!$this->getProperty('file')){
            return 'Не указан файл';
        }
        
        if(!$this->getProperty('source')){
            return 'Не был получен ID источника файлов';
        }
        
        
        $this->setDefaultProperties(array(
            'step'    => 'start',
        ));
        
        return true;
    }
    
    public function process() {
        
        // sleep(1);
        
        // return $this->failure('sdgsdgsdg');
        
        /*$options = array(
            'poll_limit' => $this->getProperty('poll_limit',1),
            'poll_interval' => $this->getProperty('poll_interval',1),
            'time_limit' => $this->getProperty('time_limit',10),
            'msg_limit' => $this->getProperty('message_limit',200),
            'show_filename' => $this->getProperty('show_filename',true),
            'remove_read' => true,
        );*/
         
        
        return $this->processRequest();
        
        
        // $response = array();
        
        
        
        /*if (!empty($messages)) {
            $response = array(
                'type' => 'event',
                'name' => 'message',
                'data' => '',
                'complete' => false,
            );
            foreach ($messages as $messageKey => $message) {
                if ($message['msg'] == 'COMPLETED') {
                    $response['complete'] = true;
                    continue;
                }
                
                $response['data'] .= '<span class="' . strtolower($message['level']) . '">';
                
                if ($options['title']) {
                    $response['data'] .= '<small>(' . trim($message['title']) . ')</small>';
                }
                
                $response['data'] .= $message['msg']."</span><br />\n";
            }
        }*/
        // return $this->modx->toJSON($response);
        
    }
    
    protected abstract function processRequest();
    
    
    protected function getFilePath(){
        if(!$id = $this->getProperty('source') OR !$source = $this->modx->getObject('sources.modMediaSource', $id)){return '';};
        // Инициализируем 
        if(!$source->initialize()){
            return false;
        }
        $bases = $source->getBases($this->getProperty('file'));
        
        if(!file_exists($bases['pathAbsoluteWithPath']) OR !is_readable($bases['pathAbsoluteWithPath'])){
            return false;
        }
        
        return $bases['pathAbsoluteWithPath'];
    }
    
    
    protected function prepareResponse(array $message, array $params = array()){
        $response = array(
            'data'  => '',   
        );
        
            
        if ($message['msg'] == 'COMPLETED') {
            $response['complete'] = true;
        }
        else{
            $response['data'] .= '<span class="' . strtolower($message['level']) . '">';
            if ($message['title']) {
                $response['data'] .= '<small>(' . trim($message['title']) . ')</small>';
            }
            $response['data'] .= $message['msg']."</span><br />\n";
            
            $response['params'] = $params;  // Параметры. Будут установлены в качестве передаваемых в запросе параметров
        }
        return $this->success('', $response);
    }
    
    // Очищаем кеш
    protected function clearCache(){
        $this->modx->runProcessor('system/clearcache');
        return;
    }
}
return 'modBasketMgrImportConsoleProcessor';


