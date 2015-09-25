<?php

require_once dirname(dirname(__FILE__)). '/console.class.php';


class modBasketMgrImportDefaultConsoleProcessor extends modBasketMgrImportConsoleProcessor { 
    
    protected $csv_delimiter = ",";
    protected $enclosure = '"';
    protected $csv_escape = '\\';
    
    protected function processRequest(){
        
        // $this->modx->setLogTarget('HTML');
        
        switch($this->getProperty('step')){
            
            
            case  'start':
                $message = array(
                    'level' => 'warn',
                    'title' => '',
                    'msg'   => 'Инициализация импорта',    
                );
                $response = $this->prepareResponse($message, array(
                    'step' => 'initialize',    
                    // 'step' => 'createBrends',    
                ));
                break;
            /*
                Инициализация.
                Проверяем наличие файла
                
            */
            case 'initialize':
                $response = $this->__initialize();
                
                break;
                
            // Очистка временной  таблицы
            case 'truncateTable':
                $response = $this->truncateTable();
                break;
                
            // Импорт данных из файла во  временную таблицу
            case 'parseFile':
                $response = $this->parseFile();
                break;
                
            // Сброс остатков товаров
            case 'takeoffNotExisting':
                $response = $this->takeoffNotExisting();
                break;
                
            // Создание категорий
            case 'createSections':
                $response = $this->createSections();
                break;
                
            // Создание коллекций
            case 'createCollections':
                $response = $this->createCollections();
                break;
            
                
            // Обновление товаров
            case 'updateGoods':
                $response = $this->updateGoods();
                break;
            
            // Завершение импорта
            case 'end':
                $message = array(
                    'level' => 'warn',
                    'title' => '',
                    'msg'   => 'COMPLETED',    
                );
                $response = $this->prepareResponse($message);
                
                // Очищаем кеш
                $this->clearCache();                
                break;
            
            default:
                return $this->failure('Неизвестное действие');
        }
        
        return $response;
    }
    
    protected function __initialize(){
        // Проверяем расширение
        $file = $this->getProperty('file');
        $pi = pathinfo($file);
        
        if(strtolower($pi['extension']) != 'csv'){
            return $this->failure("Для импорта можно использовать файлы только с расширением CSV");
        }
        
        // Получаем полный путь до файла
        if(!$file = $this->getFilePath()){
            return $this->failure("Не был получен файл");
        }
         
        $this->modx->logManagerAction('start_import', null, $pi['basename']);
        # exit;
        //Если все ОК, переходим к следующему шагу
        $message = array(
            'level' => 'warn',
            'title' => '',
            'msg'   => 'Инициализация прошла успешно. Стартуем парсинг файла', 
        );
        
        return $this->prepareResponse($message, array(
            'step' => 'truncateTable',    
            //'step' => 'createModels',    
        ));;
    }
    
    
    // Очищаем временную таблицу
    protected function truncateTable(){
        $table = $this->modx->getTableName($this->importTmpClass);
        
        $sql = "TRUNCATE TABLE {$table}";
         
        $s = $this->modx->prepare($sql);
        
        $result = $s->execute();
        
        if(!$result){
            return $this->failure('Не удалось очистить временную таблицу');
        }
        
        //Если все ОК, переходим к следующему шагу
        $message = array(
            'level' => 'warn',
            'title' => '',
            'msg'   => 'Временная таблица очищена. Стартуем парсинг файла', 
        );
        
        return $this->prepareResponse($message, array(
            'step' => 'parseFile',    
        ));;
    }
    
    
    // Парсим файл и заливаем в базу
    protected function parseFile(){
        // Проверяем расширение
        
        // Получаем полный путь до файла
        if(!$file = $this->getFilePath()){
            return $this->failure("Не был получен файл");
        }
        
        $linesPerStep = 500;
        $skip = 5;
        $i = -1;        // Стартуем с -1, так как  счетчик сразу плюсуется в начале итерации
        // $limit = 500;
        $total = 0;     // Всего записей обработано
        
        if(!$fo = fopen($file, 'r')){
            return $this->failure('Не удалось открыть файл для чтения');
        }
        
        
        
        $queries = array();
        
        # while($row = fgets($fo)){
        while($data = fgetcsv ( $fo, 0, $this->csv_delimiter, $this->enclosure, $this->csv_escape)){
            $i++;
            
            if($i<$skip){
                continue;
            }
            
            //print '<pre>';
            
            # $row = mb_convert_encoding($row, 'utf-8', 'windows-1251');
            
            # $data = explode($this->column_separator, $row);
            if(
                !$data
                || !$data[0]
                || !$data[1]
                || !$data[2]
            ){
                continue;
            }
            
            $sm_article     = str_replace(array(' ', '-'), '_', trim($this->articlePrefix.$data[0])); 
            $pagetitle      = str_replace('_', ' ', trim($data[0])); 
            $longtitle      = trim($data[3]); 
            $category       = trim($data[1]); 
            $collection     = trim($data[2]); 
            $description    = trim($data[4]); 
            $material       = trim($data[5]); 
            $size           = trim($data[6]); 
            $color          = trim($data[7]); 
            $features          = trim($data[8]);  // Особенности
            $material_price_category = trim($data[9]); // Ценовая категория материала
            $price          = str_replace(",", ".", trim($data[10])); 
            $in_stock          = mb_convert_case(trim($data[11]), MB_CASE_LOWER, 'utf-8') == 'да' ? 1 : 0; 
            $show_yam          = mb_convert_case(trim($data[12]), MB_CASE_LOWER, 'utf-8') == 'да' ? 1 : 0;       // Отображать в Я.Маркет
            $by_order          = mb_convert_case(trim($data[13]), MB_CASE_LOWER, 'utf-8') == 'да' ? 1 : 0;       // На заказ
            $yam_category        = trim($data[14]);  // Категория в Я.Маркете 
            
            if(!$sm_article){
                continue;
            }
            
            # print $in_stock;
            
            # print_r($data);
            # exit;
            # break;
            
            $queries[] = "('{$sm_article}', '{$pagetitle}', '{$longtitle}', '{$category}', '{$collection}', '{$description}', '{$material}', '{$size}', '{$color}', '{$features}', '{$material_price_category}', 
            '{$price}', '{$in_stock}', '{$show_yam}', '{$by_order}', '{$yam_category}')";
            
              
            if($total % $linesPerStep == 0){
                if(!$this->insertInDataBase($queries)){
                    return $this->failure("Не удалось выполнить запрос");
                }
                $queries = array();
            }
            
            /*if($i == $limit){
                break;
            }*/
            
            $total++;
        }
        
        if($queries){
            if(!$this->insertInDataBase($queries)){
                return $this->failure("Не удалось выполнить запрос");
            }
        }
        
        //Если все ОК, переходим к следующему шагу
        # $total = $i-$skip;
        $message = array(
            'level' => 'warn',
            'title' => '',
            # 'msg'   => "Файл успешно распарсили. Импортировано {$total} товаров.<br />
            # Снимаем с публикации все шины, которых нет в импорте.", 
            'msg'   => "Файл успешно распарсили. Импортировано {$total} товаров.<br />
            Снимаем с публикации отсутствующие товары.", 
        );
        
        return $this->prepareResponse($message, array(  
            'step' => 'takeoffNotExisting',     
            # 'step'  => 'end',
        ));;
    }
    
    
    protected function insertInDataBase(array $queries){
        $table = $this->modx->getTableName($this->importTmpClass);
        
        $sql = "INSERT INTO {$table} 
            (`sm_article`, `pagetitle`, `longtitle`, `category`, `collection`, `description`, `material`, `size`, `color`, `features`, `material_price_category`, 
            `price`, `in_stock`, `show_yam`, `by_order`, `yam_category`) 
            VALUES \n";
            
        $sql .= implode(",\n", $queries);
        /*print $sql;
        exit;*/
        $s = $this->modx->prepare($sql);
        
        $result = $s->execute();
        if(!$result){
            // $this->modx->setLogTarget('HTML');
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($s->errorInfo(), 1));
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, $sql);
            // print "<br />". $sql;
        }
        return $result;
    }
    
    
    /*
        Сбрасываем остатки товаров, которых нет в импорте.
        То есть делаем так, что в наличии нет товаров
    */
    protected function takeoffNotExisting(){ 
        
        $parent = $this->catalogParentID;
        
        $q = $this->modx->newQuery($this->productClassKey);
        $q->command('UPDATE'); 
        
        $q->innerJoin('ShopmodxProduct',  'Product');
        $q->leftJoin($this->importTmpClass, "Tmp", "Product.sm_article = Tmp.sm_article");
        
        $q->where(array(
            "Tmp.id" => null,
        )); 
        
        $q->set(array(
            "hidemenu"  => 1,    
            "searchable"  => 0,    
        ));
        
        $s = $q->prepare();
        $s->execute();
        
        # print $q->toSQL();
        # print_r($s->errorInfo());
        
        $message = array(
            'level' => 'warn',
            'title' => '',
            'msg'   => "Отсутствующие товары сняты с публикации. Стартуем создание разделов.", 
        );
        
        // exit;
        
        return $this->prepareResponse($message, array(
            'step' => 'createSections',    
            # 'step' => 'end',    
        ));;
    }
    
    
    // Создаем не существующие категории
    protected function createSections(){
        $parent = $this->catalogParentID;
        $new = 0;   
        
        $q = $this->modx->newQuery($this->importTmpClass);
        $q->leftJoin("modResource", "Category", "Category.parent = {$parent} AND Category.pagetitle = {$this->importTmpClass}.category");
        $q->where(array(
            "Category.id" => null,
        ));
        $q->select(array(
            "{$this->importTmpClass}.category",
        ));
        $q->distinct();
        
        if(!$s = $q->prepare()){
            return $this->failure('Не удалось подготовить запрос');
        }
        if(!$s->execute()){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($s->errorInfo(), true));
            return $this->failure('Не удалось выполнить запрос');
        }
        
         
        //print '<pre>';
        while($row =  $s->fetch(PDO::FETCH_ASSOC)){
            /*print "<br />";
            print_r($row);*/
            $pagetitle = trim($row['category']);
             
                
            // Создаем производителя
            $data = array(
                'pagetitle' => $pagetitle,
                'parent'    => $parent,
                'published' => true,
                'hidemenu'  => false,
                'template'  => 23,
                'isfolder'  => 1,
                # 'tv27'      => $row['brand_article'],
                'syncsite'  => false,
                'clearCache'=> false,
            );
            if(!$response = $this->modx->runProcessor('resource/create', $data)){
                $error = "Не удалось выполнить процессор создания категории";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
                return $this->failure($error);
            }
            if($response->isError()){
                $error = "Ошибка выполнения процессора создания категории";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), true));
                return $this->failure($error);
            }
            //break;
            
            $new++; 
            
            $this->modx->error->reset();
        }
        
        
        $message = array(
            'level' => 'warn',
            'title' => '',
            'msg'   => "Категории обновлены. Добавлено новых категорий: {$new}. Стартуем импорт новых коллекций.", 
        );
        
        return $this->prepareResponse($message, array(
            'step' => 'createCollections',     
        ));;
    }
    
    
    
    
    // Создаем не существующие коллекции
    protected function createCollections(){
        $parent = $this->catalogParentID;
        $new = 0;   
        
        $q = $this->modx->newQuery($this->importTmpClass);
        $q->innerJoin("modResource", "Category", "Category.parent = {$parent} AND Category.pagetitle = {$this->importTmpClass}.category");
        $q->leftJoin("modResource", "Collection", "Collection.parent = Category.id AND Collection.pagetitle = {$this->importTmpClass}.collection");
        $q->where(array(
            "Collection.id" => null,
        ));
        $q->select(array(
            "{$this->importTmpClass}.collection",
            "Category.id as category_id",
        ));
        $q->distinct();
        
        if(!$s = $q->prepare()){
            return $this->failure('Не удалось подготовить запрос');
        }
        if(!$s->execute()){
            $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($s->errorInfo(), true));
            return $this->failure('Не удалось выполнить запрос');
        }
        
         
        //print '<pre>';
        while($row =  $s->fetch(PDO::FETCH_ASSOC)){
            /*print "<br />";
            print_r($row);*/
            $pagetitle = trim($row['collection']);
             
                
            // Создаем производителя
            $data = array(
                'pagetitle' => $pagetitle,
                'parent'    => $row['category_id'],
                'published' => true,
                'hidemenu'  => false,
                'template'  => 24,
                'isfolder'  => 1,
                'syncsite'  => false,
                'clearCache'=> false,
            );
            # print_r($data);
            if(!$response = $this->modx->runProcessor('resource/create', $data)){
                $error = "Не удалось выполнить процессор создания коллекции";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
                return $this->failure($error);
            }
            if($response->isError()){
                $error = "Ошибка выполнения процессора создания коллекции";
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, $error);
                $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), true));
                return $this->failure($error);
            }
            //break;
            
            $new++; 
            
            $this->modx->error->reset();
        }
        
        
        $message = array(
            'level' => 'warn',
            'title' => '',
            'msg'   => "Коллекции обновлены. Добавлено новых коллекций: {$new}. Стартуем импорт товаров.", 
        );
        
        return $this->prepareResponse($message, array(
            'step' => 'updateGoods',     
        ));;
    }
    
     
    
    // Обновляем текущие и создаем новые товары
    protected function updateGoods(){
        
        $new = $this->getProperty('new_goods', 0);
        $updated = $this->getProperty('updated_goods', 0);
        
        $limit = 50;   // Сколько товаров за один раз обрабатывать
           
        $parent = $this->catalogParentID;
        
        $q = $this->modx->newQuery($this->importTmpClass);
        $q->innerJoin('modResource', "Category", "Category.parent = {$parent} AND Category.pagetitle = {$this->importTmpClass}.category");
        $q->innerJoin('modResource', "Collection", "Collection.pagetitle = {$this->importTmpClass}.collection AND Category.id = Collection.parent");
        $q->leftJoin('ShopmodxProduct',  'Product', "Product.sm_article = {$this->importTmpClass}.sm_article");
        
        
        # $s = $q->prepare();
        # $s->execute();
        # 
        # print_r($s->errorInfo());
        # print $q->toSQL();
        # exit;
        
        // Если записи не были найдены, прекращаем импорт
        if(!$this->modx->getCount($this->importTmpClass, $q)){
            $message = array(
                'level' => 'warn',
                'title' => "",
                'msg'   => "Импорт успешно выполнен. Обновлено товаров: {$updated}. Создано новых товаров: {$new}.", 
            );
            
            return $this->prepareResponse($message, array(
                'step'  => 'end',
            ));
        }
        
        // else
        $q->select(array(
            "{$this->importTmpClass}.*",
            "Collection.id as parent",
            "Product.resource_id",
            "Product.id as product_id",
        )); 
        $q->limit($limit);
        
        $currency = $this->modx->getOption('shopmodx.default_currency');
         
        $objects = $this->modx->getIterator($this->importTmpClass, $q);
        
        
        foreach($objects as $object){
            $resource = null;
             
            $object->remove();
            
            $article = $object->sm_article;
            
            // Если есть товар с таким артикулом, то обновлем ему цену и остаток
            if($object->resource_id){
                # print $object->resource_id;
                if(
                    $resource = $this->modx->getObject('modResource', $object->resource_id)
                    AND $product = & $resource->Product
                ){
                    $resource->setTVValue(11, $object->in_stock ? 1 : '');
                    $resource->setTVValue(16, $object->show_yam ? 1 : '');
                    $resource->setTVValue(22, $object->by_order ? 1 : '');
                    $resource->setTVValue(25, $object->yam_category);
                    $resource->setTVValue(27, $object->material);
                    $resource->setTVValue(28, $object->size);
                    $resource->setTVValue(30, $object->color);
                    $resource->setTVValue(29, $object->features);
                    $resource->setTVValue(31, $object->material_price_category);
                    
                    $product->sm_price = $object->price;
                    # $product->sm_article = $object->pagetitle;
                    
                    $resource->fromArray(array(
                        "pagetitle"         => $object->pagetitle,
                        "longtitle"         => $object->longtitle,
                        "description"       => $object->description,
                        "hidemenu"          => 0,    
                        "searchable"        => 1,  
                    ));
                    
                    $resource->save();
                    
                    $this->modx->logManagerAction("shopmodxresourceproduct_update", $this->productClassKey, $object->resource_id);
                    
                    $updated++;
                }
                # print_r($resource->toArray());
            }
            // Иначе создаем новый
            else{
                # print_r($object->toArray());
                # 
                # return;
                // Иначе создаем товар
                $data = array_merge(
                    $object->toArray(),
                    array(
                        'sm_price'  => $object->price, 
                        "sm_currency"    => $currency,
                        'class_key' => $this->productClassKey,  
                        'published' => 1,
                        'template'  => 21,
                        "isfolder"  => 0,
                        'tv11'       => $object->in_stock ? 1 : '',
                        'tv16'       => $object->show_yam ? 1 : '',
                        'tv22'       => $object->by_order ? 1 : '',
                        'tv25'       => $object->yam_category,
                        'tv27'       => $object->material,
                        'tv28'       => $object->size,
                        'tv30'       => $object->color,
                        'tv29'       => $object->features,
                        'tv31'       => $object->material_price_category,
                        'syncsite'  => false,
                        'clearCache'=> false,
                    )
                ); 
                 
                  
                // Иначе создаем новый 
                if(!$response = $this->modx->runProcessor('resource/create', $data)){
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Не удалось выполнить процессор");
                    continue;
                }
                else if($response->isError()){
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, "Не удалось выполнить процессор");
                    $this->modx->log(xPDO::LOG_LEVEL_ERROR, print_r($response->getResponse(), true));
                    continue;
                }
                
                
                if(
                    $resp_object = $response->getObject()
                    AND $id = (int)$resp_object['id']
                ){
                    $resource = $this->modx->getObject($this->productClassKey, $id);
                }
                else{
                    $this->modx->log(1, __CLASS__);
                    $this->modx->log(1, "Ошибка. Для импорт-объекта с артикулом {$object->sm_article} не был получен объект нового документа");
                }
                
                $new++; 
            }
            
            // Обновляем картинки
            if($resource){
                $tv_image_value = '';
                $tv_gallery_value = '';
                $images_array = array();
                
                // Выполняем поиск картинок
                $images = glob(MODX_ASSETS_PATH. "images/products/" . "{$article}[_.]*");
                
                if($images){
                    foreach($images as $image){
                        # print "\n";
                        # print_r($image);
                        
                        // На всякий случай проверяем регуляркой, так как в glob() условие ограниченное
                        $pi = pathinfo($image);
                        # print_r($pi);
                        
                        if(preg_match("/{$article}(\_[0-9]+|)$/", $pi['filename'])){
                            # print "\n";
                            $images_array[] = $pi['basename'];
                            # print $pi['filename'];
                        }
                    }
                    
                    if($images_array){
                        $tv_image_value = "products/" . current($images_array);
                        # print $tv_image_value;
                        
                        $migx = array();
                        
                        foreach($images_array as $i => $image){
                            # print "\n";
                            # print $i;
                            
                            $migx[] = array(
                                "MIGX_id" => $i + 1,
                                "image"     => "products/". $image,
                                "title"     => "",
                                "description"     => "",
                            );
                        }
                        
                        if($migx){
                            $tv_gallery_value = json_encode($migx);
                        }
                        
                    }
                }
                
                if(!$tv_image_value){
                    $this->modx->log(1, __CLASS__);
                    $this->modx->log(1, "Ошибка. Для товара {$resource->id} не найдено ни одной картинки");
                }
                
                $resource->setTVValue(18, $tv_image_value);
                $resource->setTVValue(26, $tv_gallery_value);
            }
            
            # exit;;
        } 
        
        $message = array(
            'level' => 'info',
            'title' => "Импорт товаров",
            'msg'   => "Обновлено товаров: {$updated}. Создано новых товаров: {$new}",
        );
        
        return $this->prepareResponse($message, array(
            'new_goods' => $new,
            'updated_goods' => $updated,
        ));
    }
    
}


return 'modBasketMgrImportDefaultConsoleProcessor';

