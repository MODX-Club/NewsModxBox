<?php


class modSearch{
    
    public $modx;
    
    function __construct(MODx $modx, array $options = array()){
        $this->modx = $modx;
    }
    
    
    /*
        Создаем индексы для документов
    */
    public function createIndex(modResource $doc){
         
        
        // All words in dictionary in UPPER CASE, so don`t forget set proper locale via setlocale(...) call
        // $morphy->getEncoding() returns dictionary encoding
        
        $words = array();
        
        $this->parseWords($doc->pagetitle, $words);
        $this->parseWords($doc->longtitle, $words);
        $this->parseWords($doc->content, $words);
        
        # $this->parseWords('<a href="">Тестовая ссылка</a>.
        # Текст с точками... и переносами.
        # 
        # <script type="text/javascript">
        #     alert("скрипт");
        # </script>
        # ', $words);
        # 
        # $this->parseWords('<a href="">ссылкочка</a>.
        # Текст с точками... и переносами второй раз.
        # 
        # <script type="text/javascript">
        #     alert("скрипт");
        # </script>
        # ', $words);
        
        
        $lemmas = $this->getLemmas($words);
        
        # print_r($lemmas);
        
        // Удаляем все старые индексы
        # $q = $this->modx->newQuery();
        $this->modx->removeCollection('modSearchIndex', array(
            "resource_id"   => $doc->id,
        ));
        
        // Записываем новые индексы
        $table = $this->modx->getTableName('modSearchIndex');
        
        # $count = count($lemmas);
        
        $rows = array();
        foreach($lemmas as $lemma){
            $rows[] =  "({$doc->id}, '{$lemma}')";
        }
        
        $sql = "REPLACE INTO {$table} (`resource_id`, `lemma`) VALUES ";
        $sql .= implode(",\n", $rows);
        
        $sql .= "\n;";
        
        # print $sql;
        
        if($s = $this->modx->prepare($sql)){
            $s->execute();
        }
        
        # foreach($lemmas as $lemma){
        #     $sql .= "({$doc->id}, '{$lemma}')";
        #     if()
        # }
        
        return true;
    }
    
    protected function parseWords($text, array & $words){
        # $input = array('sdf' => $text);
        # print "\nЧистый текст: ". $text;
        # $output = modX :: sanitize($input);
        # print_r($input);
        # print_r($output);
        # $text = reset($input);
        $text = strip_tags($text);
        $text = preg_replace("/[^a-zа-я0-9]+/usim", " ", $text);
        
        
        $words = array_merge($words, explode(" ", $text));
        
        
        /*
            Приводим все слова в верхний регистр
        */
        $func = function($value){
            return mb_strtoupper(trim($value), 'UTF-8');
        };
        
        $words = array_map($func, $words);
        
        
        
        // Создаем уникальный массив
        $words = array_unique($words);
        
        
        $words = $this->sanitizeWordsArray($words);
        
        # print "\nОбработанный текст: ". $text;
        return;
    }
    
    
    protected function getLemmas(array $words){
        
        require_once(MODX_CORE_PATH . 'components/modsearch/external/phpmorphy/src/common.php');
        
        // set some options
        $opts = array(
            // storage type, follow types supported
            // PHPMORPHY_STORAGE_FILE - use file operations(fread, fseek) for dictionary access, this is very slow...
            // PHPMORPHY_STORAGE_SHM - load dictionary in shared memory(using shmop php extension), this is preferred mode
            // PHPMORPHY_STORAGE_MEM - load dict to memory each time when phpMorphy intialized, this useful when shmop ext. not activated. Speed same as for PHPMORPHY_STORAGE_SHM type
            'storage' => PHPMORPHY_STORAGE_FILE,
            // Enable prediction by suffix
            'predict_by_suffix' => true,
            // Enable prediction by prefix
            'predict_by_db' => true,
            // TODO: comment this
            'graminfo_as_text' => true,
        );
        
        // Path to directory where dictionaries located
        $dir = MODX_CORE_PATH . 'components/modsearch/external/phpmorphy/dicts';
        $lang = 'ru_RU';
        
        
        // Create phpMorphy instance
        # try {
        #     $morphy = new phpMorphy($dir, $lang, $opts);
        #     // print $morphy->getEncoding();
        #     // exit;
        # } catch(phpMorphy_Exception $e) {
        #     die('Error occured while creating phpMorphy instance: ' . PHP_EOL . $e);
        # }
        if(!$morphy = new phpMorphy($dir, $lang, $opts)){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, "[". __CLASS__ ."] Не был получен объект phpMorphy");
            return false;
        }
        
        $lemmas = array();
        
        # print_r($words);
        # 
        # exit;
        // print $morphy->getEncoding();
        
        // if(function_exists('iconv')) {
        //     foreach($words as &$word) {
        //         $word = iconv('windows-1251', $morphy->getEncoding(), $word);
        //     }
        //     unset($word);
        // }
        
        # try {
            foreach($words as $word) {
                if(!$word) continue;
                # $word = mb_strtoupper($word, 'UTF-8');
                // by default, phpMorphy finds $word in dictionary and when nothig found, try to predict them
                // you can change this behaviour, via second argument to getXXX or findWord methods
                $base = $morphy->getBaseForm($word);
                $all = $morphy->getAllForms($word);
                $part_of_speech = $morphy->getPartOfSpeech($word);
                
                // echo $morphy->getLocale();
                
                // var_dump($morphy->getShmCache()->getFilesList());
                
                // print_r($base);
                // exit;
                // $base = $morphy->getBaseForm($word, phpMorphy::NORMAL); // normal behaviour
                // $base = $morphy->getBaseForm($word, phpMorphy::IGNORE_PREDICT); // don`t use prediction
                // $base = $morphy->getBaseForm($word, phpMorphy::ONLY_PREDICT); // always predict word
        
                $is_predicted = $morphy->isLastPredicted(); // or $morphy->getLastPredictionType() == phpMorphy::PREDICT_BY_NONE
                $is_predicted_by_db = $morphy->getLastPredictionType() == phpMorphy::PREDICT_BY_DB;
                $is_predicted_by_suffix = $morphy->getLastPredictionType() == phpMorphy::PREDICT_BY_SUFFIX;
        
                // this used for deep analysis
                $collection = $morphy->findWord($word);
                // or var_dump($morphy->getAllFormsWithGramInfo($word)); for debug
        
                if(false === $collection) {
                    # echo $word, " NOT FOUND\n";
                    
                    // Если слово не найдено, добавляем его в массив как есть
                    $lemmas[] = $word;
                    
                    continue;
                } else {
                    # print "\n<br />Найдено слово: ". $word;
                }
                    
                foreach($base as $lemma){
                    $lemmas[] = $lemma;
                }
        #         echo $is_predicted ? '-' : '+', $word, "\n";
        #         echo 'lemmas: ', implode(', ', $base), "\n";
        #         echo 'all: ', implode(', ', $all), "\n";
        #         echo 'poses: ', implode(', ', $part_of_speech), "\n";
        # 
        #         echo "\n";
                // $collection collection of paradigm for given word
        
                // TODO: $collection->getByPartOfSpeech(...);
                # foreach($collection as $paradigm) {
                    // TODO: $paradigm->getBaseForm();
                    // TODO: $paradigm->getAllForms();
                    // TODO: $paradigm->hasGrammems(array('', ''));
                    // TODO: $paradigm->getWordFormsByGrammems(array('', ''));
                    // TODO: $paradigm->hasPartOfSpeech('');
                    // TODO: $paradigm->getWordFormsByPartOfSpeech('');
        
        
                    # echo "lemma: ", $paradigm[0]->getWord(), "\n";
        #             foreach($paradigm->getFoundWordForm() as $found_word_form) {
        #                 echo
        #                     $found_word_form->getWord(), ' ',
        #                     $found_word_form->getPartOfSpeech(), ' ',
        #                     '(', implode(', ', $found_word_form->getGrammems()), ')',
        #                     "\n";
        #             }
        #             echo "\n";
        # 
        #             foreach($paradigm as $word_form) {
        #                 // TODO: $word_form->getWord();
        #                 // TODO: $word_form->getFormNo();
        #                 // TODO: $word_form->getGrammems();
        #                 // TODO: $word_form->getPartOfSpeech();
        #                 // TODO: $word_form->hasGrammems(array('', ''));
        #             }
                # }
        
                # echo "--\n";
            }
        # } catch(phpMorphy_Exception $e) {
        #     die('Error occured while text processing: ' . $e->getMessage());
        # }
        
        
        $lemmas = $this->sanitizeWordsArray($lemmas);
        
        
        return array_unique($lemmas);
    }
    
    
    protected function sanitizeWordsArray(array $words){
        /*
            Убираем все слова меньше трех букв
        */
        $func = function($value){
            return mb_strlen($value, 'UTF-8') > 2;
        };
        
        return array_filter($words, $func);
    }
    
    
    /*
        Переводим строку в леммы
    */
    public function textToLemmas($text){
        $lemmas = array();
        
        if($text){
            $words = array();
            
            $this->parseWords($text, $words);
            
            # print_r($words);
            $lemmas = $this->getLemmas($words);
        }
        
        return $lemmas;
    }
    
}

