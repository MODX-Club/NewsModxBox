<?php
$output = '';

if(!$input){
    return $output;
}

if($cache){
    $cache_key = "images/" . md5(json_encode($scriptProperties));
    if($cache_content = $modx->cacheManager->get($cache_key)){
        // print "from cache";
        return $cache_content;
    }
}

$h = isset($h) ? $h : 0;
$w = isset($w) ? $w : 0;

// header('Content-type: image/png');
// print '<pre>';

// $src = 'http://openweathermap.org/img/w/01d.png';
// $src = 'http://openweathermap.org/img/w/09d.png';

$image = new Imagick($input); 
$max = $image->getQuantumRange();
// print_r($max);

$max = $max["quantumRangeLong"];
$image->thresholdImage(0.7 * $max);
// echo "<img src='{$image}' />";

if($h && $w){
    $image->thumbnailImage($h, $w);
}

$output = $image->getImageBlob();

if($as_base64){
    $output = base64_encode($output);
}

if($cache){
    $modx->cacheManager->set($cache_key, $output, $cache_time);
}

return $output;