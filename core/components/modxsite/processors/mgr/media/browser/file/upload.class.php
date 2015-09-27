<?php

/*
    Загружаем файл
*/

require_once MODX_PROCESSORS_PATH . 'browser/file/upload.class.php';


class modMgrMediaBrowserFileUploadProcessor extends modBrowserFileUploadProcessor{
    
    public function initialize(){
        
        $this->setProperties(array(
            "path"  => '/lazy/images/',
        ));
        
        if (!$this->getSource()) {
            return $this->modx->lexicon('permission_denied');
        }
        $this->source->setRequestProperties($this->getProperties());
        $this->source->initialize();
        if (!$this->source->checkPolicy('create')) {
            return $this->modx->lexicon('permission_denied');
        }
        
        return parent::initialize();
    }
    

    public function process() {
        
        
        # print_r($_FILES);
        # print_r($this->getProperties());
        
        // Проверяем наличие файла
        if(!$file = $this->getProperty('file')){
            return $this->failure("Не был получен файл");
        }
        
        // else
        
        $imagesExts = $this->source->getOption('imageExtensions', null,'jpg,jpeg,png,gif');
        $imagesExtsArray = explode(',',$imagesExts);
        $imagesExtsArray = array_map("trim", $imagesExtsArray);
        $imagesExtsArray = array_map("strtolower", $imagesExtsArray);
        
        // Получаем или создаем объект файла
        $path = $this->getProperty('path');
        
        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if(!in_array($ext, $imagesExtsArray)){
            return $this->failure("Расширение '{$ext}' не разрешено . Разрешенные: {$imagesExts}");
        }
        
        $size = $file['size'];
        
        $name = md5($file['name'] . $size)  . ".{$ext}";
        
        $original_name = $file['name'];
        $file['name'] = $name;
         
        
        $files = array($file);
        
        $pathRelative = trim($path.$name, '/');
        $data = array(
            "source"  => $this->source->id,
            "pathRelative"  => $pathRelative,
        );
        
        $abs_path = $this->source->getBasePath() . $pathRelative;
        $this->setProperty('abs_path', $abs_path);
        
        
        /*
            Сохраняем файл
        */
        $success = $this->saveFile($path, $name, $files);

        if ($success !== true) {
            return $this->failure($success);
        }
        
        // else
        
        
        if($mediafile = $this->modx->getObject("modMediaFile", $data)){
            $mediafile->fromArray(array(
                "modifiedby" => $this->modx->user->id,
                "modifiedon" => time(),
            ));
        }
        else{
            $mediafile = $this->modx->newObject("modMediaFile", $data);
            
            $mediafile->fromArray(array(
                "name"      => $original_name,
                "basename"  => $name,
                "type"      => 'file',
                "createdby" => $this->modx->user->id,
                "createdon" => time(),
            ));
        }
        
        /*
            Получаем миме-тайп
        */ 
        $mediafile->fromArray(array(
            # "size"  => filesize($abs_path),
            "size"  => $size,
        )); 
        
        /*
            Получаем мета-данные из самого файла
        */
        # print $abs_path;
        if(@$meta = getimagesize($abs_path)){
            # print_r($data);
            $mediafile->fromArray(array(
                "image_width"  => $meta[0],    
                "image_height"  => $meta[1],
                "meta"  => $meta['mime'],    
            ));
        }
        # print_r($data);
        
        if(@$filemtime = filemtime($abs_path)){
            $mediafile->lastmod = $filemtime;
        }
        
        
        $mediafile->save();
        
        return $this->success('', array(
            "url"   => $this->source->getBaseUrl() . $pathRelative,
            "src"   => $pathRelative,
            "name" => $name,
        ));
    }
    
    
    protected function saveFile($path, $name, $files){
        
        $success = $this->source->uploadObjectsToContainer($path, $files);

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
    
}

return 'modMgrMediaBrowserFileUploadProcessor';
