<?php

/*
CREATE OR REPLACE VIEW modx_modxsite_resource_visits_view AS SELECT count( * ) AS visits, resource_id
FROM `modx_modxsite_resource_visits`
GROUP BY resource_id
*/

$xpdo_meta_map['modResourceVisitView']= array (
  'package' => 'modxsite',
  'version' => '1.1',
  'table' => 'modxsite_resource_visits_view',
  'extends' => 'xPDOObject',
  'fields' => 
  array (
    'visits' => 0,
    'resource_id' => NULL,
  ),
  'fieldMeta' => 
  array (
    'visits' => 
    array (
      'dbtype' => 'bigint',
      'precision' => '21',
      'phptype' => 'integer',
      'null' => false,
      'default' => 0,
    ),
    'resource_id' => 
    array (
      'dbtype' => 'int',
      'precision' => '10',
      'attributes' => 'unsigned',
      'phptype' => 'integer',
      'null' => false,
    ),
  ),
);
