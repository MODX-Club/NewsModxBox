<?php
if($modx->context->key == 'mgr'){
    return;
}
 
switch($modx->event->name){
    
    case 'OnPageNotFound':
        if(!empty($modx->resource)){
            return;
        }
        /*print $modx->resourceIdentifier;
        exit;*/
         
        
        /*
            Роутим запрос
        */
        
        // Профиль пользователя
        if(preg_match('/^(people|profile)\/([^\/]+)\/?$/', $modx->resourceIdentifier, $match)){
            $username = $match[2];
            $modx->setOption('RouteUsername', $username);
            return $modx->sendForward(86896);
        }
        /****************************************************/
        
        // Активность пользователя
        if(preg_match('/^(people\/|)([^\/]+)\/stream\/?$/', $modx->resourceIdentifier, $match)){
            $username = $match[2];
            $modx->setOption('RouteUsername', $username);
            return $modx->sendForward(86899);
        }
        /****************************************************/
          
        // Просмотр комментария
        if(preg_match('/^comments\/comment-(\d+)\.html/', $modx->resourceIdentifier, $match)){
            $comment = $match[1];
            $modx->setOption('RouteCommentID', $comment);
            return $modx->sendForward(86894);
        }
        /****************************************************/
        
        // Теги
        if(preg_match('/^tag\/([^\/]+)\/?$/', $modx->resourceIdentifier, $match)){
            $tag = $match[1];
            $modx->setOption('RouteTag', $tag);
            return $modx->sendForward(1180);
        }
        /****************************************************/
        
        // Потеряные топики
        if(preg_match('/^topics\/.*\-([0-9]+)\.html\/?$/', $modx->resourceIdentifier, $match)){
            $params = (array)$_REQUEST;
            unset($params['q']);
            if($url = $modx->makeUrl($match[1], '', $params)){  
                return $modx->sendRedirect($url, array(
                    'responseCode' => 'HTTP/1.1 301 Moved Permanently',
                ));
            }
            
            // return $modx->sendForward(1180);
        }
        
        // Старая постраничность 
        if(preg_match('/(.*\/)page\/(\d+)/i', $modx->resourceIdentifier, $match)){ 
            $url = $match[1]; 
            if($match[2] > 1){ 
                $url .= "?page={$match[2]}";
            } 
            $modx->sendRedirect($url, array(
                'responseCode' => 'HTTP/1.1 301 Moved Permanently',
            ));
            return ;
        }
        
        
        // Trackback
        if(preg_match('/(.*(\/|\.html))trackback\/?/i', $modx->resourceIdentifier, $match)){ 
            $url = $match[1];  
            $modx->sendRedirect($url, array(
                'responseCode' => 'HTTP/1.1 301 Moved Permanently',
            ));
            return ;
        }
        
        // Перекидываем с пустого id
        if(isset($_GET['id']) && empty($_GET['id'])){
            $url = $modx->getOption('site_url');
            $modx->sendRedirect($url, array(
                'responseCode' => 'HTTP/1.1 301 Moved Permanently',
            ));
            return;
        }
             
        // if($modx->hasPermission('dsfsdfsdf')){
        //     print $modx->resourceIdentifier;
        //     exit;
        // }
        
        // Иначе логируем информацию о запросе
        // $modx->log(1, "Страница не найдена");
        // $modx->log(1, print_R($_SERVER, 1));
        // $modx->log(1, print_R($_REQUEST, 1));
        
        
        
        break;
    
    
    case 'OnHandleRequest':
        /*
        {$email = $modx->getObject('modUser', $user_id)->Profile->email}
        {$str = "{$user_id}{$modx->siteId}{$auth_link_salt}"}
        {$auth_link_salt = $modx->getOption('modsociety.auth_link_salt')}
        */
        
        // Авторизация по прямой ссылке
        if(
            !empty($_GET['u'])
            && !empty($_GET['e'])
            && !empty($_GET['auth_key'])
            && !$modx->user->id
            && empty($_GET['service'])
        ){ 
            
            $user_id = $_GET['u'];
            $email = $_GET['e'];
            $auth_key = $_GET['auth_key'];
            $key = md5($user_id . $modx->site_id . $modx->getOption('modsociety.auth_link_salt'));
            
            # print "<br />" . $user_id;
            # print "<br />" . $email;
            # print "<br />" . $modx->site_id;
            # print "<br />";
            # print $key;
            if(
                $user = $modx->getObject('modUser', $user_id)
                AND $email == $user->Profile->email
                AND $auth_key == $key
            ){
                # print "OK";
                $modx->user = &$user;
                $modx->setPlaceholder('modx.user.username', $user->username);
                $user->addSessionContext($modx->context->key);
            }
            
            # exit;
            break;
        }
        
        
        
    # case 'OnWebPageInit':
    #     /*$modx->resource = $modx->getObject('modResource', $modx->resourceIdentifier);
    #     var_dump($modx->resource->id);
    #     var_dump($modx->resource->searchable);
    #     $modx->resource->searchable = 0;*/
    #     
    #     // $modx->sendErrorPage();
    #     
    #     break;
    #     
    # case 'OnLoadWebDocument':
    #     
    #     break;
}