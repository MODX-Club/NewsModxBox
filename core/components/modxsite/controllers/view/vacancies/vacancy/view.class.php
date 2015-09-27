<?php

/*
    Вывод вакансии
*/

require_once __DIR__ . '/../../view.class.php';

class VacanciesVacancyView extends View{
    
    public function renderTemplate(){
        $vacancy = null;
        
        // Получаем данные пользователя
        if($RouteVacancyId = (int)$this->modx->getOption('RouteVacancyId')){
            
            # $q = $this->modx->newQuery('SocietyTopicTag', array(
            #     "tag"  => $RouteTag,
            #     "active"    => 1,
            # ));
            # 
            # $q->limit(1);
            
            # $vacancy = $this->modx->getObject('SocietyTopicTag', $q);
             
            
            $namespace = 'modxsite';
            $response = $this->modx->runProcessor('web/api/hh/vacancies/request',
            array(
                "vacancy_id"    => $RouteVacancyId
            ), array(
                'processors_path' => $this->modx->getObject('modNamespace', $namespace)->getCorePath().'processors/',
            ));
            
            if(
                !$response->isError()
                AND $result = $response->getObject()
                AND empty($result['errors'])
            ){
                # print '<pre>';
                $vacancy = $result;
            }
                # print_r($vacancy);
                # exit;
        }
        
        
        # exit;
        
        if(!$vacancy){
            return $this->modx->sendErrorPage();
        }
        
        $this->setSearchable(true);
        $this->setCanonical($this->makeCanonical($RouteVacancyId));
        
        $this->modx->smarty->assign('vacancy', $vacancy);
        
        $this->meta['meta_title'] = "Вакансия {$vacancy['name']}";
        
        return parent::renderTemplate();
    }
    
    protected function makeCanonical($vacancy_id){
        return $this->modx->makeUrl($this->modx->resource->parent, '','', 'full') . "vacancy_{$vacancy_id}" . '.html';
    }
}
