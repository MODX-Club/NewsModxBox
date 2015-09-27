<?php
$xpdo_meta_map['SocietyUserProfile']= array (
  'package' => 'modsociety',
  'version' => '1.1',
  'table' => 'society_user_attributes',
  'extends' => 'xPDOSimpleObject',
  'fields' => 
  array (
    'internalKey' => NULL,
    'createdon' => NULL,
  ),
  'fieldMeta' => 
  array (
    'internalKey' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'unique',
    ),
    'createdon' => 
    array (
      'dbtype' => 'int',
      'precision' => '11',
      'phptype' => 'integer',
      'null' => false,
      'index' => 'index',
    ),
  ),
  'indexes' => 
  array (
    'internalKey' => 
    array (
      'alias' => 'internalKey',
      'primary' => false,
      'unique' => true,
      'type' => 'BTREE',
      'columns' => 
      array (
        'internalKey' => 
        array (
          'length' => '',
          'collation' => 'A',
          'null' => false,
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
          'null' => false,
        ),
      ),
    ),
  ),
    "aggregates" => array(
        "User"  => array(
            'class' => 'modUser',
            'local' => 'internalKey',
            'foreign' => 'id',
            'cardinality' => 'one',
            'owner' => 'foreign',
        )  
    ),
);
