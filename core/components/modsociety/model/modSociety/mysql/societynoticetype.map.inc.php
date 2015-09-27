<?php
$xpdo_meta_map['SocietyNoticeType']= array (
  'package' => 'modSociety',
  'version' => '1.1',
  'table' => 'society_notice_types',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'type' => NULL,
    'comment' => '',
    'rank' => NULL,
  ),
  'fieldMeta' => 
  array (
    'type' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '50',
      'phptype' => 'string',
      'null' => false,
      'index' => 'unique',
    ),
    'comment' => 
    array (
      'dbtype' => 'varchar',
      'precision' => '256',
      'phptype' => 'string',
      'null' => false,
      'default' => '',
    ),
    'rank' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
  'indexes' => 
  array (
    'type' => 
    array (
      'alias' => 'type',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'type' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
        ),
      ),
    ),
  ),
  'composites' => 
  array (
    'NoticeUsers' => 
    array (
      'class' => 'SocietyNoticeUser',
      'local' => 'id',
      'foreign' => 'notice_id',
      'cardinality' => 'many',
      'owner' => 'local',
    ),
  ),
);
