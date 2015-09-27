<?php

/*
    Вывод комментария.
    Используется фейковая страница
*/

require_once dirname(dirname(__FILE__)) . '/view.class.php';

class SocialCommentView extends SocialView{
    
    public function renderTemplate(){
        
        
        // Получаем данные пользователя
        if(
            !$comment_id = (int)$this->modx->getOption('RouteCommentID')
        ){
            return $this->modx->sendErrorPage();
        }
        
        // Пытаемся получить данные комментария
        $response = $this->modx->runProcessor('web/society/topics/comments/getdata',
        array( 
            "where" => array(
                "id"    => $comment_id
            ),
            "limit" => 1,
        ), array(
            'processors_path' => MODX_CORE_PATH . 'components/modxsite/processors/',
        ));
        
        if($response->isError() || !$comments = $response->getObject()){
            return $this->modx->sendErrorPage();
        }
        
        // else
        $this->modx->smarty->assign('comments', $comments);
        
        $this->setSearchable(true);
        $this->setCanonical($this->makeCanonical($comment_id));
        
        return parent::renderTemplate();
    }
    
    protected function makeCanonical($comment_id){
        return $this->modx->makeUrl($this->modx->resource->parent, '','', 'full') . "comment-{$comment_id}.html";
    }
}
