<?php

$xpdo_meta_map = array (
  'modResource' => 
  array (
    0 => 'SocietyTopic',
    1 => 'SocietyBlog',
  ),
  'xPDOSimpleObject' => 
  array (
    0 => 'SocietyComment',
  ),
  'xPDOObject' => 
  array (
    0 => 'SocietySubscribers',
  ),
);

$this->map['modUser']['composites']['SocietyProfile'] = array(
    'class' => 'SocietyUserProfile',
    'local' => 'id',
    'foreign' => 'internalKey',
    'cardinality' => 'one',
    'owner' => 'local',
);

$this->map['modUser']['composites']['Notices'] = array(
    'class' => 'SocietyNoticeUser',
    'local' => 'id',
    'foreign' => 'user_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

$this->map['modUser']['composites']['Comments'] = array(
    'class' => 'SocietyComment',
    'local' => 'id',
    'foreign' => 'createdby',
    'cardinality' => 'many',
    'owner' => 'local',
); 

$this->map['modUser']['composites']['Votes'] = array(
    'class' => 'SocietyVote',
    'local' => 'id',
    'foreign' => 'user_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

$this->map['modUser']['composites']['EmailMessages'] = array(
    'class' => 'SocietyEmailMessage',
    'local' => 'id',
    'foreign' => 'user_id',
    'cardinality' => 'many',
    'owner' => 'local',
);

# $this->map['modResource']['composites']['Tags'] = array(
#     'class' => 'SocietyTopicTag',
#     'local' => 'id',
#     'foreign' => 'topic_id',
#     'cardinality' => 'many',
#     'owner' => 'local',
# );

// Голоса по типу рейтинга
$this->map['modResource']['composites']['TypeVotes'] = array(
    'class' => 'SocietyVote',
    'local' => 'id',
    'foreign' => 'type',
    'cardinality' => 'many',
    'owner' => 'local',
);

