<?php

$xpdo_meta_map = array (
  'xPDOSimpleObject' => 
  array (
    0 => 'modResourceTag',
  ),
);


$this->map['modResource']['composites']['Tags'] = array(
    'class' => 'modResourceTag',
    'local' => 'id',
    'foreign' => 'resource_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

$this->map['modResource']['composites']['TagResources'] = array(
    'class' => 'modResourceTag',
    'local' => 'id',
    'foreign' => 'tag_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

$this->map['modResource']['composites']['Sections'] = array(
    'class' => 'modResourceSection',
    'local' => 'id',
    'foreign' => 'resource_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

$this->map['modResource']['composites']['SectionResources'] = array(
    'class' => 'modResourceSection',
    'local' => 'id',
    'foreign' => 'section_id',
    'cardinality' => 'many',
    'owner' => 'local',
);


# $this->loadClass('modResourceTag');
# 
$custom_fields = array(
    "modResource"   => array(
        "fields"    => array(
            "createdby"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'int',
                  'precision' => '10',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "article_type"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                    'dbtype' => 'tinyint',
                    'precision' => '3',
                    'attributes' => 'unsigned',
                    'phptype' => 'integer',
                    'null' => true,
                    'index' => 'index',
                ),
            ),
            "image"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'varchar',
                  'precision' => '512',
                  'phptype' => 'string',
                  'null' => true,
                ),
            ),
            "article_status"  => array(
                "defaultValue"  => 0,
                "metaData"  => array (
                  'dbtype' => 'tinyint',
                  'precision' => '3',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => true,
                ),
            ),
            "mssql_id"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'char',
                  'precision' => '36',
                  'phptype' => 'string',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "tags"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'varchar',
                  'precision' => '256',
                  'phptype' => 'string',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "pseudonym"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'varchar',
                  'precision' => '256',
                  'phptype' => 'string',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "news_list"  => array(
                "defaultValue"  => '0',
                "metaData"  => array (
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "rss"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "top_news"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "mailing"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "article_genre"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'tinyint',
                  'precision' => '3',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "main"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "fasturl"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array ( 
                  'dbtype' => 'char',
                  'precision' => '6',
                  'phptype' => 'string',
                  'null' => true,
                  'index' => 'unique',
                ),
            ),
            "views"  => array(
                "defaultValue"  => 0,
                "metaData"  => array ( 
                  'dbtype' => 'int',
                  'precision' => '10',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => false,
                  'default' => 0,
                  'index' => 'index',
                ),
            ),
            "hide_on_mainpage"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
            "hide_adverts"  => array(
                "defaultValue"  => '0',
                "metaData"  => array ( 
                  'dbtype' => 'enum',
                  'precision' => '\'0\',\'1\'',
                  'phptype' => 'string',
                  'null' => false,
                  'default' => '0',
                  'index' => 'index',
                ),
            ),
        ),
        
        "indexes"   => array(
            'deleted' => 
            array (
              'alias' => 'deleted',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'deleted' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'createdby' => 
            array (
              'alias' => 'createdby',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'createdby' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'article_type' => 
            array (
              'alias' => 'article_type',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'article_type' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'mssql_id' => 
            array (
              'alias' => 'mssql_id',
              'primary' => false,
              'unique' => true,
              'type' => 'BTREE',
              'columns' => 
              array (
                'mssql_id' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'tags' => 
            array (
              'alias' => 'tags',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'tags' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'pseudonym' => 
            array (
              'alias' => 'pseudonym',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'pseudonym' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'news_list' => 
            array (
              'alias' => 'news_list',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'news_list' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'rss' => 
            array (
              'alias' => 'rss',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'rss' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'top_news' => 
            array (
              'alias' => 'top_news',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'top_news' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'mailing' => 
            array (
              'alias' => 'mailing',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'mailing' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'article_genre' => 
            array (
              'alias' => 'article_genre',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'article_genre' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'main' => 
            array (
              'alias' => 'main',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'main' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'fasturl' => 
            array (
              'alias' => 'fasturl',
              'primary' => false,
              'unique' => true,
              'type' => 'BTREE',
              'columns' => 
              array (
                'fasturl' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'views' => 
            array (
              'alias' => 'views',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'views' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'hide_on_mainpage' => 
            array (
              'alias' => 'hide_on_mainpage',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'hide_on_mainpage' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
            'hide_adverts' => 
            array (
              'alias' => 'hide_on_mainpage',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'hide_on_mainpage' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => false,
                ),
              ),
            ),
        ),
    ),
    
    'modUser'   => array(
        "fields"    => array(
            "subscribe_till"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'int',
                  'precision' => '10',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
            "createdon"  => array(
                "defaultValue"  => NULL,
                "metaData"  => array (
                  'dbtype' => 'int',
                  'precision' => '10',
                  'attributes' => 'unsigned',
                  'phptype' => 'integer',
                  'null' => true,
                  'index' => 'index',
                ),
            ),
        ),
        
        "indexes"   => array(
            'subscribe_till' => 
            array (
              'alias' => 'subscribe_till',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'subscribe_till' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
            'createdon' => 
            array (
              'alias' => 'createdon',
              'primary' => false,
              'unique' => false,
              'type' => 'BTREE',
              'columns' => 
              array (
                'createdon' => 
                array (
                  'length' => '',
                  'collation' => 'A',
                  'null' => true,
                ),
              ),
            ),
        ),
    ),
);
# 
foreach($custom_fields as $class => $class_data){
    foreach($class_data['fields'] as $field => $data){
        $this->map[$class]['fields'][$field] = $data['defaultValue'];
        $this->map[$class]['fieldMeta'][$field] = $data['metaData'];
    }
    
    if(!empty($class_data['indexes'])){
        foreach($class_data['indexes'] as $index => $data){
            $this->map[$class]['indexes'][$index] = $data;
        }
    }
}

// $this->map['modResourceTag']['indexes']['test'] = ; 

// $manager = $modx->getManager();

// $index = $manager->addField('modResourceTag', 'test' );
// $index = $manager->addIndex('modResourceTag', 'test');


