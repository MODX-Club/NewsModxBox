<?php

ini_set('display_errors', 1); 
$modx->resource->set('cacheable', false);
if (!($contentType = $modx->resource->getOne('ContentType'))) {
    if ($modx->getDebug() === true) {
        $modx->log(modX::LOG_LEVEL_DEBUG, "No valid content type for RESOURCE: " . print_r($modx->resource->toArray(), true));
    }
    $modx->log(modX::LOG_LEVEL_FATAL, "The requested resource has no valid content type specified.");
}

$file = $resource->getTVValue(7);
$name = preg_replace('/.*\//','',$file);

// print $file;

$base_path = $modx->runSnippet('getSourcePath', [
    "id"    => 17,
    'callback'  => 'getBasePath'
]);

$file = $base_path . $file;

# print $file;


/*print $base_path;

exit;

if(strpos( $file,'/') !== 0){
    $file = '/'.$file;
}

$file = preg_replace('/^\//',$modx->getOption('base_path') ,$file);*/
$filesize = filesize($file);

# print $filesize; 

# $modx->user->username;
# exit;

$modx->setOption('set_header', 0);

$type= $contentType->get('mime_type') ? $contentType->get('mime_type') : 'text/html';

$header= 'Content-Type: ' . $type;
header($header);


$dispositionSet= false;
if ($customHeaders= $contentType->get('headers')) {
    foreach ($customHeaders as $headerKey => $headerString) {
        header($headerString);
        if (strpos($headerString, 'Content-Disposition:') !== false) $dispositionSet= true;
    }
}

$header= 'Cache-Control: public';
header($header);
// $header= 'Content-Disposition: attachment; filename=' . $name;
$header= 'filename=' . $name;
header($header);
$header= 'Vary: User-Agent';
header($header);

header("Content-Length: " . $filesize);
ob_clean();
flush();
readfile($file);
exit; 