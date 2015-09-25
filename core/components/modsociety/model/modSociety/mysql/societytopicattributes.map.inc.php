<?php
$xpdo_meta_map['SocietyTopicAttributes']= array (
  'package' => 'modSociety',
  'version' => '1.1',
  'table' => 'society_topic_attributes',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'resourceid' => NULL,
    'content_hash' => NULL,
    'short_text' => NULL,
    'raw_content' => NULL,
    'topic_tags' => '',
  ),
  'fieldMeta' => 
  array (
    'resourceid' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'unique',
    ),
    'content_hash' => 
    array (
      'dbtype' => 'char',
      'precision' => '32',
      'phptype' => 'string',
      'null' => true,
      'index' => 'unique',
    ),
    'short_text' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'raw_content' => 
    array (
      'dbtype' => 'text',
      'phptype' => 'string',
      'null' => false,
    ),
    'topic_tags' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '1024',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
  ),
  'indexes' => 
  array (
    'resourceid' => 
    array (
      'alias' => 'resourceid',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'resourceid' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
    'content_hash' => 
    array (
      'alias' => 'content_hash',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'content_hash' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => true,
        ),
      ),
    ),
  ),
  'aggregates' => 
  array (
    'Topic' => 
    array (
      'class' => 'SocietyTopic',
      'local' => 'resourceid',
      'foreign' => 'id',
      'cardinality' => 'one',
      'owner' => 'foreign',
    ),
  ), 
);
