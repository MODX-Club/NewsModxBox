<?php

class View{
    
    public $modx;
    protected $properties = array();
    protected $meta = array();
    
    public function __construct(MODx & $modx, $properties = array()){
        $this->modx =  & $modx;
        $this->properties = $properties;
    }
    
    public function initialize(){
        $resource = & $this->modx->resource;
        
        $this->meta = array(
            "meta_robots"       => $resource->searchable ? 'index, follow' : 'noindex, nofollow',
            "meta_canonical"    => $this->modx->makeUrl($resource->id, '','','full'),
            "meta_title"        => !empty($this->modx->resource->longtitle) ? $this->modx->resource->longtitle : $this->modx->resource->pagetitle,
        );
    }
        
    public function process(){
        $this->initialize();
        
        return $this->renderTemplate();
    }
    
    protected function renderTemplate(){
        $tpl = $this->getTemplate();
        
        // Устанавливаем меты
        foreach($this->meta as $tag => $value){
            $this->modx->smarty->assign($tag, $value);
        }
        
        return $this->modx->smarty->fetch("tpl/{$tpl}");
    }
    
    protected function getTemplate(){
        if(!empty($this->properties['tpl'])){
            $tpl = $this->properties['tpl'];
        }
        else{
            $tpl = 'index.tpl';
        }
        return $tpl;
    }
    
    protected function setField($field, $value){
        return $this->modx->resource->set($field, $value);
    }
    
    protected function setSearchable($searchable){
        return $this->meta['meta_robots'] = $searchable ? 'index, follow' : 'noindex, nofollow';
    }
    
    protected function setCanonical($url){
        return $this->meta['meta_canonical'] = $url;
    }
    
    
    
}