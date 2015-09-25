<?php

/*
    Получаем список файлов
*/

class modMgrMediaBrowserDirectoryGetlistProcessor extends modObjectGetlistProcessor{
    
    public $classKey = 'modMediaFile';
    
    protected $source = null;
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"  => "name",
            "output_format" => "tree",          // По умолчанию вывод для дерева
            "limit"         => 10,
        ));
        
        $this->setProperties(array(
            
            // Меняем параметр сортировки, так как в медиабраузере параметр dir передает запрашиваемую директорию
            'dir'   => $this->getProperty('sort_dir', "ASC"),
        ));
        
        switch($this->getProperty('sort')){
            
            case 'size':
            case 'lastmod':
                $this->setProperty('dir', 'DESC');
                break;
            
            # case 'lastmod':
            #     $this->setProperty('sort', 'createdon');
            #     break;
                
            default: ;    
        }
        
        # $this->setProperty('sort', 'rand()');
        
        
        if(!(int)$this->getProperty('source')){
            return "Не указан источник файлов";
        }
        
        return parent::initialize();
    }
    

    public function process() {
        if (!$this->getSource()) {
            return $this->modx->toJSON(array());
        }
        if (!$this->source->checkPolicy('list')) {
            return $this->modx->toJSON(array());
        }
        # $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize(); 

        # print_r($this->source->toArray());

        # $list = $this->source->getContainerList($this->getProperty('dir'));
        return parent::process();
    }

    /**
     * Get the active Source
     * @return modMediaSource|boolean
     */
    public function getSource() {
        $this->modx->loadClass('sources.modMediaSource');
        $this->source = modMediaSource::getDefaultSource($this->modx,$this->getProperty('source'));
        if (empty($this->source) || !$this->source->getWorkingContext()) {
            return false;
        }
        return $this->source;
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c){
        $c = parent::prepareQueryBeforeCount($c);
        
        $alias = $c->getAlias();
        
        $c->select(array(
            "{$alias}.*",
            "{$alias}.pathRelative as relativeUrl",
            # "{$alias}.basename as name",
        ));
        
        $where = array(
            # "source"    => (int)$this->getProperty('source'),
            "source"    => $this->source->id,
        );
        
        // Скрытие файлов
        if($this->getProperty('hideFiles')){
            $where['type!:='] = 'file';
        }
        
        // Фильтр файлов по названию
        if($query = trim($this->getProperty('query'))){
            $c->where(array(
                'name:like'     => "%{$query}%",
                'OR:tags:like'  => "%{$query}%",
            ));
        }
        
        /*
            Только свои
        */
        if($this->getProperty('own_only')){
            $c->where(array(
                'createdby'     => $this->modx->user->id,
                'OR:modifiedby:='  => $this->modx->user->id,
            ));
        }
        
        
        $c->where($where);
        
        # $c->prepare();
        # print $c->toSQL();
        # exit;
            
        # if($where = $this->getProperty('where')){
        #     $c->where($where);
        # }
        
        return $c;   
    }
    
    
    public function afterIteration(array $list) {
        $list = parent::afterIteration($list);
        
        $bases = $this->source->getBases();
        
        $path = $bases['path'];
        $pathAbsolute = $bases['pathAbsolute'];
        
        # print_r($bases);
        
        foreach($list as & $l){
            
            $l['path'] = $pathAbsolute . $l['pathRelative'];
            
            if($l['type'] == "file"){
                $l['leaf'] = true;
            }
        }
        
        return $list;
    }
    
    
    public function outputArray(array $array,$count = false) {
        if($this->getProperty('output_format') == 'tree'){
            return $this->modx->toJSON($array);
        }
        else if($this->getProperty('output_format') == 'json'){
            return parent::outputArray($array, $count);
        }
        
        // else 
        return array(
            "success"   => 1,
            "message"   => "",
            "total"     => $count,
            "count"     => count($array),
            "object"    => $array,
        );
    }
    
}

return 'modMgrMediaBrowserDirectoryGetlistProcessor';
