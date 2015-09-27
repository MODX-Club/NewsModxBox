<?php
$xpdo_meta_map['modResourceSection']= array (
  'package' => '_modxsite',
  'version' => '1.1',
  'table' => 'modxsite_resources_sections',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resource_id' => NULL,
    'section_id' => NULL,
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
      'null' => false,
      'index' => 'index',
    ),
    'section_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
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
        'section_id' => 
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
        'Section'  => array(
            'class_key' => 'modResource',
            "owner"     => "foreign",
            "local"     => "section_id",
            "foreign"   => "id",
            "cardinality"   => "one",
        ),
    ),
);
