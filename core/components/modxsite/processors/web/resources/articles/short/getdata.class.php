<?php

require_once dirname(dirname(__FILE__)) . '/getdata.class.php';

class modWebResourcesArticlesShortGetdataProcessor extends modWebResourcesArticlesGetdataProcessor{
    
    
    # public function initialize(){
        
        # $this->setDefaultProperties(array( 
        #     # "includeTVs"    => false,
        #     # "sort"          => "",
        #     # "parent_joining_type"   => "left",      // Тип джоининга таблицы родителей [left|inner]
        #     # "process_tags"  => false,       // Получить информацию о тэгах
        #     # "JoinParents"   => false,        // Джоинить родителей
        #     "in_rss_only"   => false,       // Только лоя RSS
        #     "in_news_list_only"     => false,       // Только те, что выводить в новостную ленту
        #     "show_hidden_on_mainpage"  => true,     // Некоторые статьи вручную скрывают с главной
        #     "top_news_only"     => false,     // Выводим только ТОП новости. Это которые выводятся в главном блоке
        #     "main_news_only"     => false,     // Выводим только главные новости. Это не самый главный блок, а просто главные новости по рубрикам
        # ));
        
        # switch($this->getProperty('sort')){
        #     case 'image':
        #         $this->setProperty("sort", "tv_2_image.value");
        #         break;
        # }
        
        # print_r($this->properties);
        # 
        # exit;
        
    #     return parent::initialize();
    # }
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        # if($this->getProperty('parent_joining_type') == 'inner'){
        #     $c->innerJoin('modResource', "Parent");
        # }
        # else{
        #     $c->leftJoin('modResource', "Parent");
        # }
        
        # $c->innerJoin('modUser', 'Author', "Author.id = {$alias}.createdby");
        # 
        $where = array(
            "parent"  => 86855,
            "OR:rss:="  => 1,
        );
        # 
        #  
        $c->where($where); 
        
        # print '<pre>';
        # print_r($this->properties);
        # print '</pre>';
        
        # $c->select(array(
        #     "{$alias}.id",
        #     "{$alias}.pagetitle",
        # ));
        # $c->prepare();
        # print $c->toSQL();
        # exit;
        
        return $c;
    }
    
}

return 'modWebResourcesArticlesShortGetdataProcessor';

