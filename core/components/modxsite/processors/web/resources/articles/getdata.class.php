<?php


/*
    Получаем только статьи
*/

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';


class modWebResourcesArticlesGetdataProcessor extends modWebResourcesGetdataProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "cache"      => 1,
            "sort"      => "publishedon",
            "dir"       => "DESC",
            "JoinParents"   => true,        // Джоинить родителей
        )); 
        
        return parent::initialize();
    }
    
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        # if($this->getProperty('parent_joining_type') == 'inner'){
        #     $c->innerJoin('modResource', "Parent");
        # }
        # else{
        #     $c->leftJoin('modResource', "Parent");
        # }
        
        $c->innerJoin('modUser', 'Author', "Author.id = {$alias}.createdby");
        
        $where = array(
            "template"  => 2,
        );
        
         
        $c->where($where); 
        
        # $c->select(array(
        #     "{$alias}.id",
        #     "{$alias}.pagetitle",
        # ));
        # $c->prepare();
        # print $c->toSQL();
        # exit;
        
        return $c;
    }
    
    
    
    protected function setSelection(xPDOQuery $c){
        $c = parent::setSelection($c);
        
        $alias = $c->getAlias();
        
        $c->innerJoin('modUserProfile', "AuthorProfile", "AuthorProfile.internalKey = Author.id");
        
        $c->select(array(
            "Parent.pagetitle as section_title",
            "AuthorProfile.photo as author_avatar",
        ));
        
        # $c->prepare();
        # print $c->toSQL();
        # 
        # print '<pre>';
        # 
        # 
        # exit;
        
        return $c;
    }
    
    
    
    public function afterIteration(array $list){
        $list = parent::afterIteration($list);
          
         
        
        switch($this->getProperty('image_url_schema')){
            case 'base':
                $images_base_url = $this->getSourcePath(19);
                break;
                
            case 'full':
                $images_base_url = $this->modx->getOption('site_url');
                $images_base_url .= preg_replace("/^\/*/", "", $this->getSourcePath());
                break;
                
            default: $images_base_url = '';
        }
        
        foreach($list as & $l){  
            
            // Картинка
            #  = '';
            if(
                !empty($l['author_avatar'])
            ){
                $l['author_avatar'] = $images_base_url . $l['author_avatar'];
            }
            else{
                # $l['imageDefault'] = $images_base_url . 'public/images/No-Photo.jpg';
                $l['author_avatar_default'] = $images_base_url . 'avatarDefault.png';
            }
        }   
                
        return $list;
    }
    
    
    
    # public function beforeIteration(array $list){
    #     $list = parent::beforeIteration($list);
    #     
    #     /*
    #         Ищем все TV-поля
    #     */
    #     foreach($list as & $l){
    #         foreach($l as $f => $v){
    #             if(preg_match("/^tv\_([0-9]+)\_(.+)/", $f, $match)){
    #                 $tv_id = $match[1];
    #                 $tv_name = $match[2];
    #                 $l['tvs'][$tv_name] = array(
    #                     'tv_id'    => $tv_id,
    #                     'value'    => $v,
    #                 );
    #             }
    #         }
    #         // Устанавливаем картинку
    #         $l['tvs']['image']['value'] = $l['image'];
    #         
    #     } 
    #     
    #     return $list;
    # }
}


return 'modWebResourcesArticlesGetdataProcessor';

