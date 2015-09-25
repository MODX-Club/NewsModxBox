<?php
switch($modx->event->name){
    case 'OnHandleRequest':
        
        if($modx->context->key == 'mgr'){
            return;    
        } 
        
        /*
            Выход. Убивается сессия вообще, выйдет из всех контекстов, в том числе и mgr
        */
        if(isset($_GET['service'])){
            switch(strtolower($_GET['service'])){
                case 'logout':
                    $modx->user->endSession();
                    $modx->user = null;
                    $modx->getUser();
                    break;
            }
        } 
        
        break;
        
        
    // case 'OnWebLogin':
        
    //     if($user){
    //         /*
    //             Пытаемся получить анонимную корзину, если есть
    //         */ 
            
    //         if(
    //             !empty($_SESSION['order_id'])
    //             AND $anonim_order_id = (int)$_SESSION['order_id']
    //             AND $anonim_order_id == (int)$modx->basket->getActiveOrderId()
    //             AND $order = $modx->getObject('Order', $anonim_order_id)
    //             AND !$order->contractor
                
    //         ){
    //             $order->contractor = $user->id;
    //             if(!$order->createdby){
    //                 $order->createdby = $user->id;
    //             }
    //             if($order->save()){
    //                 unset($_SESSION['order_id']);
    //             }
    //         }
    //     }
        
    //     break;
    
    
    case 'OnUserNotFound':
        // Try to find by email
        $modx->event->_output = $modx->getObjectGraph('modUser', '{"Profile":{},"UserSettings":{}}', array ('Profile.email' => $scriptProperties['username']));
        break;
        
    
    case 'OnWebPageComplete':
        // Счетчик просмотров 
        // if($modx->resource->template == 2){
             
            $table = $modx->getTableName("modResource");
            $id = $modx->resource->id;
            $sql = "UPDATE {$table} set views = views + 1 WHERE id = {$id} LIMIT 1";
            $modx->prepare($sql)->execute();
            
            /*
                Так же сохраняем в отдельную таблицу просмотров
            */
            $view_table = $modx->getTableName('modResourceVisit');
            $sql = "INSERT INTO {$view_table} VALUES ({$id}, unix_timestamp(now()))";
            if($s = $modx->prepare($sql)){
                $s->execute();
            } 
        // }
         
        break;
        
    case 'OnSiteRefresh':
        
        /*
            Сбрасываем устаревшие данные счетчика просмотров
        */
        $view_table = $modx->getTableName('modResourceVisit');
        $sql = "DELETE FROM {$view_table} WHERE `time` < unix_timestamp(now() - INTERVAL 1 DAY)";
        if($s = $modx->prepare($sql)){
            $s->execute();
            // $modx->log(1, print_r($s->errorInfo()));
        }
        
        break;
    
    // case 'OnMODXInit':
        
    //     if($modx->context->key == 'mgr'){
    //         return;    
    //     } 
         
    //     if($_SERVER['HTTP_X_REAL_IP'] == '46.39.53.100'){
    //         $modx->setOption('site_status', 1);
    //     }
        
    //     break;
    
    default:;
}