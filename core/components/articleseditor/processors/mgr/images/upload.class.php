<?php

/*
    Загружаем файл
*/

require_once MODX_CORE_PATH . 'components/modxsite/processors/mgr/media/browser/file/upload.class.php';

class modMgrImagesUploadProcessor extends modMgrMediaBrowserFileUploadProcessor{
    
    # public $classKey = 'docGallery';
    
    public function initialize(){
        
        if(!$this->getProperty('name')){
            return "Не было получено имя файла";
        }
        
        if(!$this->getProperty('image')){
            return "Не было получено содержимое файла";
        }
        
        $this->setDefaultProperties(array(
            "source"    => 12,
        ));
        
        # if(!(int)$this->getProperty('resource_id')){
        #     return "Не был получен ID документа";
        # }
        
        return parent::initialize();
    }
    
    
    /*
        [file] => Array
        (
            [name] => adver-botton_240x120_f72.png
            [type] => image/png
            [tmp_name] => /var/www/dgazeta.ru/public_html/tmp/php6NQZxt
            [error] => 0
            [size] => 21097
        )
    */
    
    
    public function process(){
        
        $tmp_dir = ini_get('upload_tmp_dir') ? ini_get('upload_tmp_dir') : sys_get_temp_dir();
        
        
        $data = $this->getProperty('image');
        $this->unsetProperty('image');
        
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $data = base64_decode($data);
        
        // $data = base64_decode($data);
        
        $this->content = $data;
        
        $original_name = $name = $this->getProperty('name'); 
        $pi = pathinfo($name); 
        $ext =strtolower( $pi['extension']);
         
        
        $extensions = $this->modx->getOption('upload_images');
        if(!is_array($extensions)){
            $extensions = array_map('trim', explode(",", $extensions));
        }
        
        if(!in_array($ext, $extensions)){
            return $this->failure("Файлы данного типа не разрешены к загрузке. Разрешены только: " . implode(', ', $extensions));
        }
        
        $name = md5(time()).uniqid().".".$ext;
        
        $path = "lazy/images/";
        
        $url = "{$path}{$name}";
        
        $this->setProperties(array(
            "src"  => $url,
        ));
        
        # $file = MODX_BASE_PATH . "/uploads/{$url}"; 
        # $file = $tmp_dir . '/' . md5(microtime()); 
        # 
        # if(!file_put_contents($file, $data)){
        #     return $this->failure("Не удалось сохранить файл");
        # }
        
        # if(!$meta = getimagesize($file)){
        #     return $this->failure("Не были получены метаданные файла");
        # }
        # # print_r($data);
        # $mediafile->fromArray(array(
        #     "image_width"  => $meta[0],    
        #     "image_height"  => $meta[1],
        #     "meta"  => $meta['mime'],    
        # ));
        
        # print_r($this->getProperties());
        
        $this->setProperties(array(
            "file"  => array(
                "name"  => $original_name,
                "type"  => $this->getProperty('type'),
                "size"  => $this->getProperty('size'),
                "tmp_name"  => $file,
                "error"     => 0,
            ),
        ));
        
        # return $this->cleanup();
        return parent::process();
    } 
    
    
    
    protected function saveFile($path, $name, $files){
        
        $success = $this->source->createObject($path, $name, $this->content);

        if (empty($success)) {
            $msg = '';
            $errors = $this->source->getErrors();
            foreach ($errors as $k => $msg) {
                $this->modx->error->addField($k,$msg);
            }
            return $this->failure($msg);
        }
        
        return true;
    }
    
    # 
    # public function process(){
    #     
    #     $data = $this->getProperty('image');
    #     $this->unsetProperty('image');
    #     
    #     list($type, $data) = explode(';', $data);
    #     list(, $data)      = explode(',', $data);
    #     $data = base64_decode($data);
    #     
    #     // $data = base64_decode($data);
    #     
    #     $name = $this->getProperty('name'); 
    #     $pi = pathinfo($name); 
    #     $ext =strtolower( $pi['extension']);
    #      
    #     
    #     $extensions = $this->modx->getOption('upload_images');
    #     if(!is_array($extensions)){
    #         $extensions = array_map('trim', explode(",", $extensions));
    #     }
    #     
    #     if(!in_array($ext, $extensions)){
    #         return $this->failure("Файлы данного типа не разрешены к загрузке. Разрешены только: " . implode(', ', $extensions));
    #     }
    #     
    #     $name = md5(time()).uniqid().".".$ext;
    #     
    #     $url = "lazy/images/{$name}";
    #     
    #     $this->setProperties(array(
    #         "src"  => $url,
    #     ));
    #     
    #     $file = MODX_BASE_PATH . "/uploads/{$url}"; 
    #     
    #     if(!file_put_contents($file, $data)){
    #         return $this->failure("Не удалось сохранить файл");
    #     }
    #     
    #     return $this->cleanup();
    # } 
    # 
    
    
    public function cleanup(){
        
        # return $this->success("Файл успешно загружен", $this->object->toArray());
        return $this->success("Файл успешно загружен", $this->getProperties());
    }
    
}


return 'modMgrImagesUploadProcessor';
