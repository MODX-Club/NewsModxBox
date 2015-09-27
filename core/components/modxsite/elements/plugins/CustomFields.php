<?php
/*
    OnBeforeDocFormSave
    OnDocFormSave
    OnResourceTVFormRender
*/ 

$tvs = array(
	1	=> "tags",
// 	2	=> "image",
	3	=> "main",
// 	12	=> "article_status",
	14	=> "pseudonym",
	15	=> "news_list",
	16	=> "rss",
	17	=> "top_news",
	18	=> "mailing",
	19	=> "article_genre",
	21	=> "",      // sections
	25  => "hide_on_mainpage",
	26  => "fake_hide_adverts",
);

switch($modx->event->name){
    
       
        
    /*
        Рендеринг ТВшек
    */
    case 'OnResourceTVFormRender':
        
		if(!$document = $modx->getObject('modResource', $resource)){
			$modx->log(xPDO::LOG_LEVEL_ERROR, "Не был получен документ");
			return;
		}
		
        $categories = & $scriptProperties['categories'];
        
        foreach($categories as $c_id => & $category){
            
            foreach($category['tvs'] as & $tv){
                
				if(array_key_exists($tv->id, $tvs)){
					$field = preg_replace("/^fake\_/", "", $tvs[$tv->id]);
					
					switch($tv->id){

						case '1':	// Тэги
							$q = $modx->newQuery('modResourceTag');
							$q->select(array(
								"GROUP_CONCAT(distinct tag_id) as tags",
							));
							$q->where(array(
								"resource_id" => $document->id,
							));
							$tags = $modx->getValue($q->prepare());
							$value = str_replace(",", "||", $tags);
							break; 

						case '21':	// Рубрики
							$q = $modx->newQuery('modResourceSection');
							$q->select(array(
								"GROUP_CONCAT(distinct section_id) as sections",
							));
							$q->where(array(
								"resource_id" => $document->id,
							));
							$sections = $modx->getValue($q->prepare());
							$value = str_replace(",", "||", $sections);
							break; 
							
						default: $value = $document->$field;
					}
					
					$tv->value = $value;
					$tv->relativeValue = $value;
					$inputForm = $tv->renderInput($document, array('value'=> $tv->value));
					$tv->set('formElement',$inputForm);
					 
				} 
            }
        }
        
        break;
        
    
    // Перед сохранением документа
    case 'OnBeforeDocFormSave':
        if(!$resource = & $scriptProperties['resource']){
            $modx->log(xPDO::LOG_LEVEL_ERROR, "Не был получен документ");
            return;
        }
        /*
            Тэги.
            Перед сохранением документа мы получим все старые 
            теги и установим им active = 0.
            Всем актуальным тегам будет установлено active = 1.
            После сохранения документа в событии OnDocFormSave мы удалим все не активные теги
        */ 
        
        
        if(
            $resource->tvs
            OR $mode == modSystemEvent::MODE_NEW
        ){
            // $modx->log(1, print_r($resource->toArray(), 1));
            
            foreach($tvs as $tv_id => $tv_name){
                $field = preg_replace("/^fake\_/", "", $tv_name);
                
                $var = "tv{$tv_id}";
                // if(isset($resource->$var)){
                    
                    switch($tv_id){
                        
                        case '1':   // Tags
                            $tags = array();
                            foreach((array)$resource->Tags as $tag){
                                $tag->active = 0;
                                $tags[$tag->tag_id] = $tag;
                            } 
                            
                            if(!empty($resource->$var)){
                                // print_r($resource->$var);
                                foreach((array)$resource->$var as $tv_value){
                                    if($tv_value){
                                        if(!empty($tags[$tv_value])){
                                            $tags[$tv_value]->active = 1;
                                        }
                                        else{
                                            $tags[$tv_value] = $modx->newObject('modResourceTag', array(
                                                "tag_id"    => $tv_value,
                                            ));
                                        }
                                    }
                                }
                            }
                            
                            // foreach($tags as $tag){
                            //     print_r($tag->toArray());
                            // }
                            
                            $resource->Tags = $tags;
                            
                            $tags_ids = array();
                            foreach($resource->Tags as $tag){
                                if($tag->active){
                                    $tags_ids[] = $tag->tag_id;
                                }
                            }
                            
                            $resource->set('tags', ($tags_ids ? implode(",", $tags_ids) : NULL));
                            break;
                        
                        case '21':   // Sections
                            $sections = array();
                            foreach((array)$resource->Sections as $section){
                                $section->active = 0;
                                $sections[$section->section_id] = $section;
                            } 
                            
                            if(!empty($resource->$var)){
                                foreach((array)$resource->$var as $tv_value){
                                    if($tv_value){
                                        if(!empty($sections[$tv_value])){
                                            $sections[$tv_value]->active = 1;
                                        }
                                        else{
                                            $sections[$tv_value] = $modx->newObject('modResourceSection', array(
                                                "section_id"    => $tv_value,
                                            ));
                                        }
                                    }
                                }
                            }
                            
                            $resource->Sections = $sections;
                            
                            // $sections_ids = array();
                            // foreach($resource->Sections as $section){
                            //     if($section->active){
                            //         $sections_ids[] = $section->section_id;
                            //     }
                            // }
                            
                            // $resource->set('sections', ($sections_ids ? implode(",", $sections_ids) : NULL));
                            break;
                            
                            /*
                                Флажки (чекбоксы)
                            */
                    		case 3:
                    		case 15:
    						case 16:
    						case 17:
    						case 18:
    						case 25:
    						case 26:
    						    $array = (array)$resource->$var;
    						    $resource->set($field, (int)reset($array));
    						    break;    
                            
                        default:
                            $resource->set($field, $resource->$var);
                    }
                    
                // }
            }
        }
        
        // die('plugin');
        
        break;
    
    
    /*
        Сохранение документа
    */
    case 'OnDocFormSave':
        // if(!$resource = & $scriptProperties['resource']){
        //     $modx->log(xPDO::LOG_LEVEL_ERROR, "Не был получен документ");
        //     return;
        // }
        
        /*
            Удаляем все не активные теги
        */
        $modx->removeCollection('modResourceTag',array(
            'active' => 0,
            // 'resource_id' => $resource->id,
        ));
        
        /*
            Удаляем все не активные связки с рубриками
        */
        $modx->removeCollection('modResourceSection',array(
            'active' => 0,
            // 'resource_id' => $resource->id,
        ));
        
        /*
            Удаляем TV, так как они сохраняются в системную таблицу
        */
        if($tvs){
            $modx->removeCollection('modTemplateVarResource',array(
                'tmplvarid:in' => array_keys($tvs),
                // 'contentid' => $resource->id,
            ));
            
            // Сбрасываем автоинкримент
            $sql = "ALTER TABLE {$tv_table} auto_increment = 1";
            $modx->prepare($sql)->execute();
        }
        
        
        break;
     
    
}