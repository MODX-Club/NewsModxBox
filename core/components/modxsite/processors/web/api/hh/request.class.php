<?php


abstract class modWebApiHhRequestProcessor extends modProcessor{
    
    protected $client = null;
    protected $host_url = 'https://api.hh.ru/';
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            'cache'             => 1,           // Use cache
            'cache_lifetime'    => 0,               // seconds
            'cache_prefix'      => 'getdata/',      
            "path"  => "/",             // Раздел для запроса
        ));
        
        return parent::initialize();
    }
     
    


    public function process(){
        
        // Use or not caching
        $cacheable = $this->getProperty('cache');
        
        if($cacheable){
            $key = $this->getProperty('cache_prefix') . md5( __CLASS__ . json_encode($this->getProperties()));
            if($cache = $this->modx->cacheManager->get($key)){
                return $cache;
            }
        }
        
        $params = $this->getProperties();
        
        /*
            Важно учитывать, что в hh пагинация начинается с 0. page => 1 - это уже вторая страница
        */
        if(
            isset($params['page']) 
            AND $params['page'] > 0)
        {
            $params['page'] -= 1;
        }
        
        # print_r($params);
        # exit;
        
        $result = $this->request($this->getProperty('path'), $params);
        
        if($result === false){
            return $this->failure("Ошибка выполнения запроса");
        }
        
        // else
        $result = $this->prepareResponse($result);
        
        $result = $this->outputArray($result);
        
        if($cacheable && !empty($result['success'])){
            $this->modx->cacheManager->set($key, $result, $this->getProperty('cache_lifetime', 0));
        }
        
        return $result;
    }  
    
    protected function request($path = '/', array $params = array()){
        $result = array();
        
        if(!$client = $this->getClient()){
            return false;
        }
        
        # print_r($params);
        # exit;
        
        $result = $client->request($this->host_url, $path, 'GET', $params);
        
        #  print_r($result);
        
        return $result;
    }
    
    protected function getClient(){
        
        if(!$this->client){
            $this->client = $this->modx->getService('rest.modRestCurlClient');
        }
        
        return $this->client;
    }
    
    
    protected function prepareResponse($result){
        $result = (array)json_decode($result, true);
        
        return $result;
    }
    
    
    public function outputArray(array $array,$count = false) {
        return array(
            "success"   => true,
            "message"   => '',
            "limit" => @$array['per_page'],
            "total" => @$array['per_page'] * @$array['pages'],
            "page" => @$array['page'] + 1,
            "found" => @$array['found'],
            "count" => count(@$array['items']),
            "object" => @$array,
        );
    }
    
}

return 'modWebApiHhRequestProcessor';
