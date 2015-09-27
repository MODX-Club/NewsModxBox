<?php

class modxSiteRequest extends modRequest{
    
    public function getResource($method, $identifier, array $options = array()) {
        $resource = parent::getResource($method, $identifier, $options);
        
        if(!$resource){
            // Доступ к собственным ресурсам, даже если они не опубликованы
            if(
                $this->modx->user->id
                AND is_numeric($this->modx->resourceIdentifier)
            ){
                $resource = $this->modx->getObject('modResource', array(
                    "id"    => $this->modx->resourceIdentifier,
                    "createdby" => $this->modx->user->id,
                ));
            }
        }
        
        return $resource;
    }
}

