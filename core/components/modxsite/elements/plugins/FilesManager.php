<?php
// print $modx->event->name;

switch($modx->event->name){
    
    case 'OnFileManagerUpload':
        
        $source = & $scriptProperties['source'];
        
        if(!$source->hasErrors()){
            /*
                Запрещаем загрузку в корень, точнее удаляем новый файл,
                так как в MODX нет нормальной обработки событий на загрузку файлов, 
                чтобы запретить загрузку
            */
            if(in_array($source->id, array(
                17, // PDF-files
                19, // Avatars
            ))){
                return;
            }
            
            if($directory == '/' && $files){
                foreach($files as $file){
                    $path = $directory . $source->fileHandler->sanitizePath($file['name']);
                    $source->removeObject($path);
                }
                $source->addError('path', "Нельзя загружать файлы в корневой раздел. Выберите имеющуюся папку или создайте новую.");
            }
        }
        
        break;
        
    default:;
}