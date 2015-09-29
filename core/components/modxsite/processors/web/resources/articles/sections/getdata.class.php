<?php

/*
    Получаем рубрики новостей
*/

require_once dirname(dirname(dirname(__FILE__))) . '/getdata.class.php';


class modWebResourcesArticlesSectionsGetdataProcessor extends modWebResourcesGetdataProcessor{
    
     
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "limit"     => 0,
            "sort"      => "menuindex",
            "dir"       => "ASC",
            "pain_page_only"    => false,   // Только те, что выводить на главную
        )); 
        
        return parent::initialize();
    }
    
    
    public function prepareQueryBeforeCount(xPDOQuery $c) {
        $c = parent::prepareQueryBeforeCount($c);
        $alias = $c->getAlias();
        
        $where = array(
            "parent"  => 80,
            "template"  => 4,
        );
        
        // Только для главной
        if($this->getProperty('show_on_pain_page')){
            $c->innerJoin('modTemplateVarResource', "tv_main_only", "tv_main_only.contentid = {$alias}.id AND tv_main_only.tmplvarid = 22 AND tv_main_only.value = '1'");
        }
        
        $c->where($where);
        
        return $c;
    }
    
    /*
        Получаем ID-шники TV-шек, для которых нужно получить данные
    */
    protected function getTVs(){
        return array(
            20,      // Выводить баннер в правом блоке
            23,     // top_banner
            24,     // right_banner_code
        );
    }
    
    
}


return 'modWebResourcesArticlesSectionsGetdataProcessor';
