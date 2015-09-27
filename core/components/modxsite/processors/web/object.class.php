<?php

abstract class modXObjectProcessor extends modObjectUpdateProcessor{
    
    public $logSaveAction = false;
    
    public function initialize() {
        
        
        if(!$this->classKey){
            $error = "classKey does not set";
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
            return $error;
        }
        
        /*
            If got PK, get and update object
        */
        if($primaryKey = $this->getProperty($this->primaryKeyField,false)){
            $this->object = $this->modx->getObject($this->classKey,$primaryKey);
            if (empty($this->object)){
                return $this->modx->lexicon($this->objectType.'_err_nfs',array($this->primaryKeyField => $primaryKey));
            }
        }
        // else create object
        else{
            $this->object = $this->modx->newObject($this->classKey);
        }
        
        if ($this->checkSavePermission && $this->object instanceof modAccessibleObject && !$this->object->checkPolicy('save')) {
            return $this->modx->lexicon('access_denied');
        }
        
        return !$this->hasErrors();
    }
    
    
    public function fireBeforeSaveEvent() {
        $preventSave = false;
        if (!empty($this->beforeSaveEvent)) {
            /** @var boolean|array $OnBeforeFormSave */
            $OnBeforeFormSave = $this->modx->invokeEvent($this->beforeSaveEvent,array(
                'mode'  => $this->object->isNew() ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                'data' => $this->object->toArray(),
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->object => &$this->object,
                $this->objectType => &$this->object,
            ));
            if (is_array($OnBeforeFormSave)) {
                $preventSave = false;
                foreach ($OnBeforeFormSave as $msg) {
                    if (!empty($msg)) {
                        $preventSave .= $msg."\n";
                    }
                }
            } else {
                $preventSave = $OnBeforeFormSave;
            }
        }
        return $preventSave;
    }
    
    
    # public function beforeSave() { 
    #     print_r($this->object->toArray());
    #     return 'Debug';
    # }
    
    
    public function fireAfterSaveEvent() {
        if (!empty($this->afterSaveEvent)) {
            $this->modx->invokeEvent($this->afterSaveEvent,array(
                'mode' => $this->object->isNew() ? modSystemEvent::MODE_NEW : modSystemEvent::MODE_UPD,
                $this->primaryKeyField => $this->object->get($this->primaryKeyField),
                $this->object => &$this->object,
                $this->objectType => &$this->object,
            ));
        }
    }
    
    
    public function logManagerAction() {
        if($this->logSaveAction){
            return parent::logManagerAction();
        }
        return;
    } 
    
}
