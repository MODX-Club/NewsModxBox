<?php

abstract class modWebValidator{
    
    protected $processor;
    protected $modx;
    protected $object;
    
    public function __construct($processor){
        
        $this->processor = & $processor;
        $this->modx = & $processor->modx;
        $this->object = & $processor->object;
        
    }
    
    
    public function validate(){
        return !$this->processor->hasErrors();
    }
    
    
    protected function getProperty($k,$default = null){
        return $this->processor->getProperty($k,$default);
    }
    
    protected function setProperty($k,$v) {
        return $this->processor->setProperty($k,$v);
    }
    
    protected function addFieldError($field, $message){
        return $this->processor->addFieldError($field, $message);
    }
    
    protected function hasErrors(){
        return $this->processor->hasErrors();
    }
    
}

return 'modWebValidator';
