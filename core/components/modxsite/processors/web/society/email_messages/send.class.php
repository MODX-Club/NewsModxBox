<?php

/*
    Класс рассылки очереди писем (как правило по крону)
*/


class modWebSocietyEmailmessagesSendProcessor extends modProcessor{
    
    
    public function initialize(){
        
        $this->setProperties(array(
            "time_limit"    => 240,       // In seconds
        ));
        
        return parent::initialize();
    }
    
    
    public function process(){
        $sended = 0;
        $time_limit = $this->getProperty('time_limit', 30);
        $start_time = time();
        
        $q = $this->modx->newQuery('SocietyEmailMessage');
        $q->sortby("id");
        $q->limit(1);
        
        while(
            time() < $start_time + $time_limit
            AND $object = $this->modx->getObject('SocietyEmailMessage', $q)
        ){
            $object->remove();
            if($user = $object->User){
                $message = $object->message;
                $subject = $object->subject;
                $user->sendEmail($message, array(
                    "subject"   => $subject,
                ));
                $sended++;
                $this->modx->mail->reset();
            }
        }
        
        return $this->success("sendede {$sended} messages");
    }
    
}

return 'modWebSocietyEmailmessagesSendProcessor';
