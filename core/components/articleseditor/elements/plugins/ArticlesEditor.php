<?php
/*
    OnBeforeDocFormSave
    OnDocFormSave 
*/
 

switch($modx->event->name){
    
      
    
    // Перед сохранением документа
    case 'OnBeforeDocFormSave':
        $resource = null;
        
        foreach($scriptProperties as & $property){
            if($property instanceof modResource){
                $resource = & $property;
                break;
            }
        }
        
        if(!$resource){
            $modx->log(xPDO::LOG_LEVEL_ERROR, "Не был получен документ");
            return;
        } 
        
        /*
            Меняем статусы
        */
        // if($resource->isNew()){
        //     if(!$resource->article_status){
        //         $resource->article_status = '0';
        //     }
        // }
        // else{
        //     if(
        //         $resource->isDirty('published')
        //         AND $resource->published
        //     ){
                
        //         if($resource->article_status == '1'){
        //             $resource->article_status = '2';
        //         }
        //     }
        // }
        
        
        if(
            $resource->published
            AND !$resource->publishedon
        ){
            $resource->publishedon = time();
        }
        
        
        /*
            Если изменен статус публикации на опубликован, 
            то меняем тип статьи на Опубликована
        */
        if(
            $resource->isDirty('published')
            AND $resource->published
        ){
            if($resource->article_status != '2'){
                $resource->article_status = '2';
            }
        }
        
        // else{
        //     // if(!$resource->isDirty('article_status')){
        //     //     if(!$resource->article_status){
        //     //         $resource->article_status = 2;
        //     //     }
        //     // }
            
        //     if(!$resource->article_status){
        //         $resource->article_status = 2;
        //     }
        // }
        
        // $resource->article_status = 4; 
        
        
        /*
            Чистка содержимого документа
        */
        if($resource->template == 2){
            // $resource->pagetitle ="Тест. Не удалять";
            // $resource->content ="ddddddddddddddddd";
            
            $jevix = $modx->getService('modJevix','modJevix', MODX_CORE_PATH . 'components/modjevix/model/modJevix/');
        
            if(
                $modx->hasPermission('modxclub.post_indexed_links')
                AND $resource->getTVValue('links_follow')
            ){
                $rel = "follow";
            }
            else{
                $rel = "nofollow";
            } 
            
            // $rel = "nofollow";
            
            
            $jevix->cfgSetTagParamDefault('a','rel',$rel,true);
            
            $content = $resource->content;
            // $modx->log(1, "Чистый контент: " . $content);
            
            // print $content;
            /*
                $preg = "/(<p ?>([ \n\r\t\<\>\/p]+))/usim";
                $preg = "/(<p ?>([ \s\n\r\t\<\>\/p]+))/usim";
                
                preg_match_all($preg, $content, $match);
                
                print_r($match);
                // foreach($match as $i => $m){
                //     var_dump($m[$i][2]);
                // }
                
                print $content;
                exit;
            */
            
            // Очищаем лишние переносы
            /*
            */
            $preg = "/(<p ?>[ \s\n\r\t<>\/p]+<\/p>)/usim";
            $content = preg_replace($preg, "", $content);
            
            // print "Очищаем контент\n";
            
            // print $content;
            // exit;
            
            // $modx->log(1, "Очистили от переносов: " . $content);
            
            $content = $jevix->parse($content);
            
            /*
                $preg = "/(<br\/?>[ \n\r\t<>\/br]+<br\/?>)/sim";
                $content = preg_replace($preg, "<br />", $content);
            */
            
            // $modx->log(1, "После Jevix-а: " . $content);
            
            // print $content;
            
            // exit;
            
            /*
            // Очищаем лишние переносы
            $preg = "/(<p>[ \n\r\t<>\/p]+<\/p>)/sim";
            $content = preg_replace($preg, "<br />", $content);
            
            $preg = "/(<br\/?>[ \n\r\t<>\/br]+<br\/?>)/sim";
            $content = preg_replace($preg, "<br />", $content);
            */
            
            $resource->content = $content;
        }
        
        // Генерация PDF-картинки
        else if(
            $resource->template == 24
            AND $pdf = $resource->tv7
        ){
            // unset($scriptProperties['resource']);
            // $modx->log(1, print_r($scriptProperties, 1));
            // $modx->log(1, print_r($resource->tv7, 1));
            $file = MODX_CORE_PATH . "components/modxsite/files/subscribe/pdf/{$pdf}";
            
            if(file_exists($file)){
                $path = 'images/pdf_cover/';
                $new_file = md5($pdf).'.jpg';
                $fullpath = MODX_BASE_PATH . 'uploads/' . $path . $new_file;
                
                if(!file_exists($fullpath)){
                    if(
                        $source = $modx->getObject('source.modMediaSource', 12)
                        AND $source->initialize()
                        AND $im = new imagick(MODX_CORE_PATH . "components/modxsite/files/subscribe/pdf/{$pdf}[0]")
                    ){
                        $im->setImageFormat('jpg');
                        // header('Content-Type: image/jpeg');
                        $source->createObject("/".$path, $new_file, $im);
                        // $resource->image = $path . $new_file;
                    }
                }
                
                $resource->set('image', $path . $new_file);
                // $resource->set('tv2', $path . $new_file);
            }
            
        }
        
        break;
    
    
    /*
        Сохранение документа
    */
    case 'OnDocFormSave':
        $resource = null;
        
        foreach($scriptProperties as & $property){
            if($property instanceof modResource){
                $resource = & $property;
                break;
            }
        }
        
        if(!$resource){
            $modx->log(xPDO::LOG_LEVEL_ERROR, "Не был получен документ");
            return;
        } 
        
        // Если это был создан новый документ,
        // добавляет id к его алиасу
        if($mode == modSystemEvent::MODE_NEW){
            // print "Sdfsdf";
            switch($resource->template){
                case '2':
                    // print "Sdfsdf";
                    $id = $resource->id;
                    $alias = mb_substr($resource->alias, 0, 50, 'utf-8');
                    $resource->set('alias', "{$alias}-{$id}");
                    $resource->save();
                    // print_r($resource->toArray());
                    break;
            }
            
        }
        
        // print "sdfsdfsdfs 2";
        
        // exit;
        
        /*
            Пересоздаем поисковые индексы
        */
        if($searcher = $modx->getService('modSearch', 'modSearch')){
            $searcher->createIndex($resource);
        }
        
        break;
    
    
    // Инициализация MODX-а
    case 'OnMODXInit': 
        // $id = $modx->user->id;
        // if($id == 425){
        //     $modx->setOption('tree_root_id', 85716);
            
            
        //     // print '<pre>';
        //     // print_r($_SESSION);
        //     // exit;
            
        //     foreach($_SESSION["modx.user.{$id}.attributes"]['mgr']['modAccessContext']['web'] as & $policy){
        //         $policy['policy']['resource_tree'] = 1;
        //     }
        // }
        
        if(
            $modx->hasPermission('resource_tree')
            AND !$modx->hasPermission('view_all_documents')
        ){
            $modx->setOption('tree_root_id', 85716);
        }
        break;
     
     
    // case 'OnDocFormRender':
    //     $resource = & $scriptProperties['resource'];
        
    //     if(
    //         $resource->isNew()
    //         AND !$resource->template
    //     )
    //     {
    //         $resource->template = 24;
    //     }
        
    //     break;
     
     
    case 'OnManagerPageBeforeRender':
        // $resource = & $scriptProperties['resource'];
        
        // if(
        //     $resource->isNew()
        //     AND !$resource->template
        // )
        // {
        //     $resource->template = 24;
        // }
        
        $properties = & $controller->scriptProperties;
        
        if($properties['parent'] == 85716){
            $properties['template'] = 24;
            $properties['searchable'] = false;
            // $properties['content_type'] = 8;
            $modx->setOption('default_content_type', 8);
        }
        
        // $controller->scriptProperties['template'] = 24;
        // $modx->log(1, 'Controller');
        // $modx->log(1, print_r($controller->scriptProperties, 1));
        
        //     break;
            
            
        // case 'OnManagerPageBeforeRender':
        // case 'OnManagerPageAfterRender':
        $controller = & $scriptProperties['controller'];
        
        // print "SdfS";
        // exit;
        // if($controller->loadHeader){ 
            $manager_url = $modx->getOption('manager_url');
            $assets_url = $manager_url. 'components/modxsite/'; 
            
            $namespace = "modxsite";
            $manager_url = $modx->getOption('manager_url');
            $path = "{$manager_url}components/{$namespace}/";
            
            # $this->config['namespace_assets_path'] = $modx->call('modNamespace','translatePath',array(&$modx, $this->config['namespace_assets_path']));
            $config = array();
            $config['manager_url'] = 
            $config['assets'] = 
            $config['assets_url'] = 
            $modx->getOption("{$namespace}.manager_url", null, $path);
            $config['connectors_url'] = $config['manager_url'].'connectors/';
            $config['connector_url'] = $config['connectors_url'].'connector.php';
             
            $config = $modx->toJSON($config);
            
            $html = <<<HTML
            <script src="{$manager_url}assets/modext/widgets/media/browser.js"></script>
            <script src="{$manager_url}assets/modext/widgets/core/modx.rte.browser.js"></script>
            
            <script src="{$assets_url}js/modxsite.js"></script>
            <script type="text/javascript">
                ModxSite.config = {$config}; 
            </script>
            
            <script src="{$assets_url}js/widgets/media/tree-directory.js"></script>
            <script src="{$assets_url}js/widgets/media/view.js"></script>
            <script src="{$assets_url}js/widgets/media/browser.js"></script>
HTML;
            $controller->addHtml($html);
            // $modx->regClientStartupScript($html, true);
            // $controller->content = preg_replace('/<\/head>/', "{$html}\n</head>", $controller->content );
        // }
        
        break;
}