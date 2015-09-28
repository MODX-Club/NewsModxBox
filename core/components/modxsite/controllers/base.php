<?php
// ini_set('display_errors', 1);
 

$properties = $modx->resource->getOne('Template')->getProperties();


if ($modx->resource->cacheable != '1') {
    $modx->smarty->caching = false;
}

if(!empty($properties['phptemplates.non-cached'])){
    $modx->smarty->compile_check = false;
    $modx->smarty->force_compile = true;
}

        
if(!empty($properties['view'])){
    $path = $properties['view'];
}
else{
    $path = 'view';
}

$class = '';
$arr = explode('/', $path);

foreach($arr as $a){
    $l = mb_convert_case(mb_substr($a, 0, 1, 'utf-8'), MB_CASE_UPPER, 'utf-8');
    $class .= $l;
    $class .= mb_substr($a, 1, mb_strlen($a, 'utf-8'), 'utf-8');
}

require_once dirname(__FILE__) . '/view/'.$path.'.class.php';
$view = new $class($modx, $properties);

return preg_replace("/[ \n\t\r]+$/sm", "", $view ->process());
