<?php

require_once MODX_CORE_PATH . 'components/modsociety/processors/society/web/threads/vote.class.php';

class modWebSocietyTopicsVotesCreateProcessor extends modSocietyWebThreadsVoteProcessor{
    
    public function initialize(){
        
        
        $vote_direction = $this->getProperty('vote_direction');
        
        switch($vote_direction){
            case 'up':
                $vote_value = 1;
                break;
                
            case 'down':
                $vote_value = -1;
                break;
                
            default:
                $vote_value = 0;
        }
        
        $this->unsetProperty('vote_direction');
        
        $this->setProperties(array(
            "target_class"  => "modResource",
            "vote_value"    => $vote_value,
        ));
        
        return parent::initialize();
    }
    
    public function afterSave(){
        // Если есть права, аппрувим топик
        if(
            $this->modx->hasPermission('society.approve_topics')
            AND $this->object->target_class == 'modResource'
            AND $thread = $this->object->Thread
            AND $topic = $this->modx->getObject('modResource', $thread->target_id)
        ){
            if($thread->rating > 0){
                $approved = '1';
            }
            else{
                $approved = '';
            }
            $topic->setTVValue(24, $approved);
        }
        
        return parent::afterSave();
    }
    
}


return 'modWebSocietyTopicsVotesCreateProcessor';