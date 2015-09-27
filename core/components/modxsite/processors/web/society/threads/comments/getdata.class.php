<?php

/*
    Получаем комментарии
*/

// require_once dirname(dirname(dirname(__FILE__))).'/getdata.class.php';


class modWebSocietyThreadsCommentsGetdataProcessor extends modObjectProcessor{
    
    public $classKey = "SocietyComment";
    
    
    public function initialize(){
        
        
        $this->setDefaultProperties(array(
            'includeTVs'    => false,
            "thread_id"     => null,
            "listType"      => "tree",  //  Тип вывода. tree - древовидный, plain - просто по порядку
            "showdeleted"    => false,
            "showunpublished"    => true,
        ));
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        $this->images_url = $this->modx->runSnippet('getSourcePath', array(
            "id"    => $this->modx->getOption('modavatar.default_media_source'),
        ));
        
        $parent = $this->getProperty('parent', null);
        $thread_id = $this->getProperty('thread_id', null);
        
        $comments = $this->getComments($parent, $thread_id);
        
        
        return $this->outputArray($comments);
    }
    
    
    protected function getComments($parent = null, $thread_id = null){
        $comments = array();
        
        $where = array();
        
        if(!$this->getProperty('showdeleted')){
            $where['deleted'] = 0;
        }
        
        if(!$this->getProperty('showunpublished')){
            $where['published'] = 1;
        }
        
        $listType = $this->getProperty('listType');
        
        $c = $this->modx->newQuery($this->classKey);
        
        
        $c->select(array(
            "{$this->classKey}.*",    
        ));
        
        
        if($listType == 'tree'){
            $where['parent'] = $parent;
        }
        
        
        if(!$parent && $thread_id){
            $where['thread_id'] = $thread_id;
        }
        
        
        if($comment_id = (int)$this->getProperty('comment_id')){
            $where['id'] = $comment_id;
        }
         
        
        if($where){
            $c->where($where);
        }
        
        //$c->leftJoin('SocietyThread', 'Thread');
        $c->leftJoin('modUser', 'Author');
        $c->leftJoin('modUserProfile', 'AuthorProfile', 'Author.id = AuthorProfile.internalKey');
        $c->leftJoin('SocietyThread', 'CommentThread', "CommentThread.target_id = {$this->classKey}.id AND CommentThread.target_class = 'SocietyComment'");
        
        $c->select(array(
            //"Thread.comments_count as thread_comments_count",    
            "Author.id as author_id",    
            "Author.username as author_username",    
            "AuthorProfile.fullname as author_fullname",    
            "AuthorProfile.photo as author_avatar", 
            "CommentThread.rating",
            "CommentThread.positive_votes",
            "CommentThread.negative_votes",
            "CommentThread.neutral_votes",   
        ));
        
        $c->sortby("{$this->classKey}.id", "ASC");
        
        
        if($s = $c->prepare() AND $s->execute()){
            /*print $c->toSQL();
            exit;*/
            while(
                $row = $s->fetch(PDO::FETCH_ASSOC)
                AND $id = $row['id']
            ){
                $comment = $this->prepareRow($row);
                 
                /*
                    Если вывод древовидный, получаем дочерние комментарии
                */
                if($listType == 'tree'){
                    $comment['children'] = $this->getComments($id);
                }
                
                if($comment['author_avatar']){
                    $comment['author_avatar'] = $this->images_url . $comment['author_avatar'];
                }
                
                $comments[] = $comment;
            }
        }
        
        
        return $comments;
    }
    
    
    protected function prepareRow(array $row){
        
        return $row;
    }
    
    // protected function prepareQuery(){}
    
    protected function getMessage(){return '';}



    public function outputArray(array $array, $count = false){
        
        return array(
            'success' => true,
            'message' => $this->getMessage(),
            'count'   => count($array),
            'total'   => $count,
            'object'  => $array,
        );
    }    
    
}


return 'modWebSocietyThreadsCommentsGetdataProcessor';

