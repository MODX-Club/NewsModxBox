<?php

require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/threads/comments/create.class.php';

class modWebSocietyTopicsCommentsCreateProcessor extends modSocietyWebThreadsCommentsCreateProcessor{
    
    
    public function checkPermissions(){
        
        if(!$this->modx->user->id){
            return false;
        }
        
        return parent::checkPermissions();
    }
    
    
    public function getLanguageTopics() {
        return array_merge((array)parent::getLanguageTopics(), array(
            "modsociety:default",
        ));
    }
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "createdby" => $this->modx->user->id,
            "createdon" => time(),
            "published" => $this->modx->hasPermission("modsociety.publish_comment"),
        ));
        
        $this->unsetProperty('thread_id');
        $this->unsetProperty('ip');
        $this->unsetProperty('editedon');
        $this->unsetProperty('editedby');
        $this->unsetProperty('deleted');
        $this->unsetProperty('deletedon');
        $this->unsetProperty('deletedby');
        $this->unsetProperty('comments_count');
        $this->unsetProperty('properties');
         
        return parent::initialize();
    }
    
    
    public function beforeSet(){
        
        // Режем контент
        $content = $this->getProperty('text');
        
        $this->setProperty('raw_text', $content);
        
        # $content = str_replace(array(
        #     "<?"
        # ), array(
        #     "&lt;"
        # ), $content);
        # 
        # $content = strip_tags($content, '<strong><composite><composite><model><object><field><code><pre><cut><p><a><h4><h5><h6><img><b><em><i><s><u><hr><blockquote><table><tr><th><td><ul><li><ol>');
        # 
        # // Реплейсим переносы
        # $content = preg_replace("/[\r\n]{3,}/", "<br /><br />", $content);
        # $content = preg_replace("/\r/", "<br />", $content);
        # 
        # $content = preg_replace('/<code>(.+?)<\/code>/sim', "<pre class=\"prettyprint\"><code>$1</code></pre>", $content);
        # 
        # $this->setProperty('text', $content);
        
        $jevix = $this->modx->getService('modJevix','modJevix', MODX_CORE_PATH . 'components/modjevix/model/modJevix/');
        
        if($this->modx->hasPermission('modxclub.post_indexed_links')){
            $rel = "follow";
        }
        else{
            $rel = "nofollow";
        } 
        
        $jevix->cfgSetTagParamDefault('a','rel',$rel,true);
        
        $errors = '';
        $content = $jevix->parse($content, $errors);
        
        $this->setProperty('text', $content);
        
        return parent::beforeSet();
    }
    
     
    
    public function afterSave(){
        $use_delayed_emails = $this->modx->getOption('modsociety.use_delayed_emails', null, false);
        
        
        # ini_set('display_errors', 1);
        $comment = $this->modx->getObject('SocietyComment', $this->object->comment_id);
        $topic = $this->modx->getObject($this->object->target_class, $this->object->target_id);
        
        if(empty($this->modx->smarty)){
            $this->modx->invokeEvent('OnHandleRequest');
        }
        $this->modx->smarty->assign('topic', $topic->toArray());
        $this->modx->smarty->assign('comment', $comment->toArray());
        
        if($author = $this->modx->user){
            $this->modx->smarty->assign('author', $author->toArray());
            
            if($author_profile = $author->Profile){
                $this->modx->smarty->assign('author_profile', $author_profile->toArray());
            }
            
        }
        
        # $this->modx->smarty->assign('topic_url', $this->modx->makeUrl($topic->id, '', '', 'full'));
        /*
            Рассылаем емейл-уведомления.
            Схема: https://www.lucidchart.com/documents/view/4f91f952-779b-4c62-beeb-ee6210a22ad6
        */
        
        // Кому уже отправлялось, чтобы не отправлять повторно
        // Сразу добавляем и того, кто пишет
        $sended_to = array(
            $this->modx->user->id,
        );       
        
        // Не используем этот объект запроса, а только его клоны
        $users_query = $this->modx->newQuery('modUser');
        $users_query->innerJoin('modUserProfile', 'Profile');
        $users_query->where(array(
            "active"    => 1,
            "Profile.blocked"   => 0,
            "id:not in" => $sended_to,
        ));
        $users_query->leftJoin('SocietyNoticeUser', 'Notices');
        $users_query->leftJoin('SocietyNoticeType', 'NoticeType', "NoticeType.id = Notices.notice_id");
        $users_query->where(array(
            "Profile.blockeduntil"    => 0,
            "OR:Profile.blockeduntil:<" => time(),
        ));
        
        // Комментарий в ответ на другой комментарий?
        if($parent = $comment->Parent){
            
            // Автор комментария - автор родительского комментария?
            if($parent->createdby != $this->modx->user->id){
                
                // Если текущий пользователь не автор родительского комментария,
                // то отслыаем уведомление об ответе на комментарий
                $sended_to = array_unique($sended_to);
                $q = clone($users_query);
                if($sended_to){
                    $q->where(array(
                        "id:not in"    => $sended_to,
                    ));
                }
                $q->where(array(
                    "id"    => $parent->createdby,
                    "NoticeType.type"   => "new_reply",
                ));
                $q->limit(1);
                
                if(
                    $user = $this->modx->getObject('modUser', $q)
                    AND $topic->checkPolicy('view', null, $user)
                ){
                    // Проверяем настройку уведомлений о новых комментариях
                    $this->modx->smarty->assign('auth_user_id', $user->id);
                    $message = $this->modx->smarty->fetch('messages/society/new_comment/comment_reply.tpl');
                    $this->modx->smarty->assign('auth_user_id', false);
                    
                    # $this->modx->log(1, $message);
                    
                    # print $message;
                    # 
                    # exit;
                    
                    $user->sendEmail($message, array(
                        "subject"   => "Новый ответ в топике «{$topic->pagetitle}»",
                    ));
                    $this->modx->mail->reset();
                    $sended_to[] = $parent->createdby; 
                    
                }
                
                
                // Если текущий пользователь не автор родительского комментария,
                // то отслыаем уведомление об ответе на комментарий
                # if(
                #     $ParentAuthor = $parent->Author
                #     AND $ParentAuthor->active
                #     AND !$ParentAuthor->Profile->blocked
                # ){
                #     // Проверяем настройку уведомлений о новых комментариях
                #     
                #     $message = $this->modx->smarty->fetch('messages/society/new_comment/comment_reply.tpl');
                #     $ParentAuthor->sendEmail($message, array(
                #         "subject"   => "Новый ответ на ваш комментарий",
                #     ));
                #     $sended_to[] = $ParentAuthor->id;
                #     $this->modx->mail->reset();
                # }
            }
            
        }
        # else{
        #     
        # }
        
        
        // Отправляем уведомление автору топика о новом комментарии
        $sended_to = array_unique($sended_to);
        $q = clone($users_query);
        $q->where(array(
            "id"    => $topic->createdby,
            "NoticeType.type"   => "new_comments_in_my_topics",
        ));
        
        if($sended_to){
            $q->where(array(
                "id:not in"    => $sended_to,
            ));
        }
        
        if(
            $user = $this->modx->getObject('modUser', $q)
            AND $topic->checkPolicy('view', null, $user)
        ){
            $this->modx->smarty->assign('auth_user_id', $user->id);
            $message = $this->modx->smarty->fetch('messages/society/new_comment/topic_author.tpl');
            $this->modx->smarty->assign('auth_user_id', false);
            $user->sendEmail($message, array(
                "subject"   => "Новый комментарий в вашем топике «{$topic->pagetitle}»",
            ));
            $sended_to[] = $user->id;
            $this->modx->mail->reset();
        }
        
        
        // Отсылаем комментарии всем, кто в этом топике писал
        // Получаем всех пользователей всех комментариев данной ветки
        $sended_to = array_unique($sended_to);
        $q = clone($users_query);
        $q->innerJoin('SocietyComment', "Comments", "Comments.thread_id = {$comment->thread_id} AND Comments.id != {$comment->id} AND Comments.createdby = modUser.id");
        
        if($sended_to){
            $q->where(array(
                "id:not in"    => $sended_to,
            ));
        }
        $q->where(array(
            "NoticeType.type"   => "new_comment",
        ));
        
        if($users = $this->modx->getCollection('modUser', $q)){
            foreach($users as $user){
                if($topic->checkPolicy('view', null, $user)){
                    $this->modx->smarty->assign('auth_user_id', $user->id);
                    $message = $this->modx->smarty->fetch('messages/society/new_comment/participants.tpl');
                    # $subject = "В топике добавлен новый комментарий";
                    $subject = "Новый комментарий в топике «{$topic->pagetitle}»";
                    /*
                        Пытаемся записать в отложенную рассылку
                    */
                    if(
                        !$use_delayed_emails
                        OR !$emailmessage = $this->modx->newObject('SocietyEmailMessage', array(
                            "user_id"   => $user->id,
                            "subject"   => $subject,
                            "message"   => $message,
                        ))
                        OR !$emailmessage->save()
                    ){
                        $user->sendEmail($message, array(
                            "subject"   => $subject,
                        ));
                        $this->modx->mail->reset();
                    }
                    
                    $sended_to[] = $user->id;
                    
                }
                $this->modx->smarty->assign('auth_user_id', false);
                # else{
                #     // Если нет права на топик, сразу добавляем в исключения
                #     $sended_to[] = $user->id;
                # }
            }
        }
        
        
        // Отсылаем уведомление администрации
        $sended_to = array_unique($sended_to);
        $q = clone($users_query);
        $q->innerJoin('modUserGroupMember', 'UserGroupMembers');
        $q->where(array(
            "UserGroupMembers.user_group"    => 20,
        ));
        
        if($sended_to){
            $q->where(array(
                "id:not in"    => $sended_to,
            ));
        }
        
        if($users = $this->modx->getCollection('modUser', $q)){
            foreach($users as $user){
                $this->modx->smarty->assign('auth_user_id', $user->id);
                $message = $this->modx->smarty->fetch('messages/society/new_comment/administration.tpl');
                $user->sendEmail($message, array(
                    "subject"   => "Новый комментарий в топике «{$topic->pagetitle}»",
                ));
                $sended_to[] = $user->id;
                $this->modx->mail->reset();
            }
            $this->modx->smarty->assign('auth_user_id', false);
        } 
        
        
        $this->modx->runProcessor('system/clearcache');
        
        return parent::afterSave();
    }
    
    
    public function cleanup(){
        
        $comment_id = $this->object->comment_id;
        
        $comment_html = '';
        
        // Пытаемся получить данные комментария в шаблоне
        // if(!$response = $modx->runProcessor('web/society/topics/comments/create',
        if($response = $this->modx->runProcessor('web/society/threads/comments/getdata',
        array(
            "comment_id"    => $comment_id,
            "listType"      => false,
        ), array(
            'processors_path' => MODX_CORE_PATH .'components/modxsite/processors/',
        ))){ 
            
            if(
                !$response->isError() 
                AND $object = $response->getObject()
            ){
                $comment = current($object);
                if(empty($this->modx->smarty)){
                    $this->modx->invokeEvent('OnHandleRequest');
                }
                $this->modx->smarty->assign('comment', $comment);
                $comment_html = $this->modx->smarty->fetch('society/threads/comments/inner.tpl');
            }
            
        }
        
        
        # return $this->success('Комментарий успешно опубликован', array(
        return $this->success($this->modx->lexicon('comment_post.success'), array(
            "comment_id"    => $comment_id,
            "comment_html"  => $comment_html,
        ));
    }
    
}

return 'modWebSocietyTopicsCommentsCreateProcessor';