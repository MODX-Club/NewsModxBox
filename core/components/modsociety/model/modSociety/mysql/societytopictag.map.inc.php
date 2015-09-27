<?php
$xpdo_meta_map['SocietyTopicTag']= array (
  'package' => 'modSociety',
  'version' => '1.1',
  'table' => 'society_topic_tags',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'topic_id' => NULL,
    'tag' => NULL,
    'active' => 1,
  ),
  'fieldMeta' => 
  array (
    'topic_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
    'tag' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '100',
      'phptype' => 'string',
      'null' => false,
    ),
    'active' => 
    array (
      'dbtype' => 'tinyint',
      'precision' => '1',
      'phptype' => 'integer',
      'null' => false,
      'default' => 1,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'topic_id' => 
    array (
      'alias' => 'topic_id',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'topic_id' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
        'tag' => 
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
  'aggregates' => 
  array (
    'Topic' => 
    array (
      'class' => 'modResource',
      'local' => 'topic_id',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ), 
);
