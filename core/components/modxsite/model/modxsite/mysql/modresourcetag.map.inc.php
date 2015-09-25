<?php
$xpdo_meta_map['modResourceTag']= array (
  'package' => '_modxsite',
  'version' => '1.1',
  'table' => 'modxsite_resources_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource_id' => NULL,
    'tag_id' => NULL,
    'active' => '1',
  ),
  'fieldMeta' => 
  array (
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
      'index' => 'index',
    ),
    'tag_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => true,
    ),
    'active' => 
    array (
      'dbtype' => 'enum',
      'precision' => '\'0\',\'1\'',
      'phptype' => 'string',
      'null' => false,
      'default' => '1',
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'resource_id' => 
    array (
      'alias' => 'resource_id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resource_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'tag_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'active' => 
    array (
      'alias' => 'active',
      'primary' => false,
      'unique' => false,
      'type' => 'BTREE',
      'columns' => 
      array (
        'active' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
    'aggregates'  => array(
        'Resource'  => array(
            'class_key' => 'modResource',
            "owner"     => "foreign",
            "local"     => "resource_id",
            "foreign"   => "id",
            "cardinality"   => "one",
        ),
        'Tag'  => array(
            'class_key' => 'modResource',
            "owner"     => "foreign",
            "local"     => "tag_id",
            "foreign"   => "id",
            "cardinality"   => "one",
        ),
    ),
);



