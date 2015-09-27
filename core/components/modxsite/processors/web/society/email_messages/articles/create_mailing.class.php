<?php

/*
    Создаем почтовую рассылку новостей.
    Получаем максимум 24 новости за последние сутки.
    Так же учитываем рабочий день или не рабочий
*/

require_once __DIR__. '/../../../resources/articles/getdata.class.php';

class modWebSocietyEmailmessagesArticlesCreatemailingProcessor extends modWebResourcesArticlesGetdataProcessor{
    
    
    public function checkPermissions(){
        global $site_id;
        
        /*
            Антиспам
        */
        
        $key = md5($site_id);
        
        if($this->getProperty('key') != $key){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[".__CLASS__."] Неверный ключ");
            # $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[".__CLASS__."] Должен быть {$key}");
            return false;
        }
        
        return parent::checkPermissions();
    }
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "sort"      => "publishedon",
            "dir"       => "DESC",
            "in_rss_only"   => true,       // Только лоя RSS
            "in_news_list_only"     => true,       // Только те, что выводить в новостную ленту
            "summary"     => true,
            "limit"     => 24,
            "cache"     => 0,
        ));
        
        if(empty($this->modx->smarty)){
            $this->modx->switchContext('web');
            $this->modx->invokeEvent('OnHandleRequest');
        }
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        /*
            Если это не рабочий день, то не рассылаем
        */
        if(!$this->isWorking()){
            return $this->success('Это не рабочий день');
        }
        
        # return $this->success('Рабочий день');
        
        return parent::process();
    }
    
    
    protected function isWorking(){
        
        /*
        Предполагается, что дни недели с понедельника по пятницу включительно являются рабочими, а суббота и воскресение — выходными. Данное API возвращает все исключения из этого правила. Возможны следующие значения поля isWorking:
    
        0 — рабочий день;
        2 — праздничный/нерабочий день;
        3 — сокращенный на 1 час рабочий день.

        */
        
        
        // Проверяем рабочий ли день календаря или нет
        
        $day = date('N');
        if($day >= 1 AND $day <= 5){
            $isWorking = true;
        }
        else{
            $isWorking = false;
        }
        
        
        if(
            $data = file_get_contents('http://basicdata.ru/api/json/calend/')
            AND $data = json_decode($data, 1)
            # AND !empty($data['data'][date('Y')])
            AND @$data = $data['data'][date('Y')][(int)date('m')][(int)date('d')]['isWorking']
        ){
            switch($data){
                case '0':
                case '3':
                    $isWorking = true;
                    break;
                    
                case '2':
                    $isWorking = false;
                    break;
            }
        }
         
        return $isWorking;
    }
    
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
        
        // Получаем всех пользователей, кому отправлять рассылку
        $users_query = $this->modx->newQuery('modUser');
        $alias = $users_query->getAlias();
        
        $users_query->distinct();
        
        $users_query->innerJoin('modUserProfile', 'Profile');
        $users_query->leftJoin('SocietyNoticeUser', 'Notices');
        $users_query->leftJoin('SocietyNoticeType', 'NoticeType', "NoticeType.target = 'modResource' AND NoticeType.id = Notices.notice_id");
        $users_query->innerJoin('modUserGroupMember', 'UserGroupMembers');
        
        $users_query->select(array(
            "{$alias}.*",
            "Profile.email",
        ));
        
        $users_query->where(array(
            "active"    => 1,
            "Profile.blocked"   => 0,
            "NoticeType.id"   => 3,
            # "id:not in" => $sended_to,
        ));
        
        # $users_query->where(array(
        #     "UserGroupMembers.user_group"    => '1',
        #     # "id" => 422,
        # ));
        
        $users_query->where(array(
            "Profile.blockeduntil"    => 0,
            "OR:Profile.blockeduntil:<" => time(),
        ));
        
        # $users_query->prepare();
        # print $users_query->toSQL();
        
        $date = date('d-m-Y');
        $site_name = $this->modx->getOption('site_name');
        
        $this->modx->smarty->assign('articles', $list);
        
        $auth_link_salt = $this->modx->getOption('modsociety.auth_link_salt');
        
        foreach($this->modx->getIterator('modUser', $users_query) as $user){
            # print_r($user->toArray());
            
            $this->modx->smarty->assign('user', $user);
            
            $message = $this->modx->smarty->fetch('messages/society/articles/mailling/letter.tpl');
            
            # print $message;
            # exit;
            
            $subject = "Рассылка {$site_name} {$date}";
            
            if($emailmessage = $this->modx->newObject('SocietyEmailMessage', array(
                "user_id"   => $user->id,
                "subject"   => $subject,
                "message"   => $message,
            ))){
                # print_r($emailmessage->toArray());
                # $manager = $modx->getManager();
                // $manager->createObjectContainer('SocietyEmailMessage');
                $emailmessage->save();
            }
        }
        
        # print_r($users_query->stmt->errorInfo());
        
        
        
        
        # print $message;
        
        return $list;
    }
    
}



return 'modWebSocietyEmailmessagesArticlesCreatemailingProcessor';
