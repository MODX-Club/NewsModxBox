<?php
/*
    $input - путь входящей картинки
    $aol - игнорировать размеры исходного изображения (новое изображение может превышать исходное).
    https://gist.github.com/Fi1osof/2f90bec4b7b6a89b114b
*/
if(empty($input)){
    return '';
}
if(!empty($options)){
    parse_str($options);
}
$w = isset($w) ? $w : null;
$h = isset($h) ? $h : null;
$cache = isset($cache) ? $cache : true;
$bestfit = isset($bestfit) ? $bestfit : false;
$fill = isset($fill) ? $fill : false;
$pi = pathinfo($input);
// print_r($pi);
$basename = $pi['basename'];
$extension = $pi['extension'];
$md5 = md5(json_encode($scriptProperties));
$src = "assets/components/imagick/cache/{$md5}.{$extension}";
$output_file = MODX_BASE_PATH . $src;
// print $output_file;
// exit;
// return;
if(!file_exists($output_file) OR !$cache){
    
    $file = MODX_BASE_PATH . $input;
    $image = new Imagick($file);
    
    $image->setBackgroundColor(new ImagickPixel('transparent')); 
    // $image->setbackgroundcolor('rgb(64, 64, 64)'); 
    
    // $image->setImageFormat("png32"); 
    // $image->setImageFormat("png64"); 
    // $image->setImageFormat("jpg"); 
    
    // die('sdfsd');
    // Превышение размеров
    if(empty($aol)){
        
        if($w){
            
            $width = $image->getImageWidth();
            if ($w > $width){
                $w = $width;
            }
        }
        
         
        if($h){
            
            $height = $image->getImageHeight();
            if ($h > $height){
                $h = $height;
            }
        }
    }
    
    // $fill = 0;
    $image->thumbnailImage($w, $h, $bestfit, 0);
    // $image->resizeImage($w, $h, Imagick::FILTER_LANCZOS, 1, $bestfit);
    
    if($fill){
        
        $original_width = $image->getImageWidth();
        $original_height = $image->getImageHeight();
        
        // die('sdfs');
        /* Создаём пустой холст */
        // $canvas = new Imagick();
        
        /* Холст должен быть достаточно большой, чтобы вместить оба изображения */
        $width = $original_width >= $w ? $original_width : $w;
        $height = $original_height >= $h ?$original_height : $h;
        
        
        // $canvas->newImage($width, $height, new ImagickPixel('transparent'));
        // $canvas->newImage($width, $height, new ImagickPixel('blue'));
        // $canvas->setImageFormat($extension);
        
        // if()
        
        // $canvas->setImageFormat('png');
        // $canvas->setImageFormat('jpeg');
        
        /* Наложение оригинального изображения и отражения на холст */
        
        if($width > $original_width){
            $x = ($width - $original_width) / 2;
        }
        else{
            $x = 0;
        }
        
        if($height > $original_height){
            $y = ($height - $original_height) / 2;
        }
        else{
            $y = 0;
        }
        
        switch(strtolower($extension)){
            
            case 'png':
            case 'gif':
                // // $image->setImageFormat('png64');
                
                // // print $width; 
                // // print $height;
                
                // // die('sdgsdfds');
                
                // /* Создаём пустой холст */
                // $canvas = new Imagick();
                // $canvas->newImage($width, $height, new ImagickPixel('transparent'));
                // $image->setImageFormat('png64');
                
                // // $canvas->setImageFormat($extension);
                // // $canvas->newImage($width, $height, new ImagickPixel('transparent'));
                
                // $canvas->compositeImage($image, imagick::COMPOSITE_OVER, $x, $y);
                
                // file_put_contents($output_file, $canvas);
                
                
                /* Создаём пустой холст */
                $canvas = new Imagick();
                
                /* Холст должен быть достаточно большой, чтобы вместить оба изображения */
                // $width = $original_width >= $w ? $original_width : $w;
                // $height = $original_height >= $h ?$original_height : $h;
                $canvas->newImage($width, $height, new ImagickPixel('transparent'));
                // $canvas->setImageFormat($extension);
                $canvas->setImageFormat($extension == 'png' ? 'png64' : $extension);
                
                /* Наложение оригинального изображения и отражения на холст */
                
                // if($width > $original_width){
                //     $x = ($width - $original_width) / 2;
                // }
                // else{
                //     $x = 0;
                // }
                
                // if($height > $original_height){
                //     $y = ($height - $original_height) / 2;
                // }
                // else{
                //     $y = 0;
                // }
                
                $canvas->compositeImage($image, imagick::COMPOSITE_OVER, $x, $y);
                
                file_put_contents($output_file, $canvas);
                
                break;
                
            default:
                
                $image->extentImage($width, $height, $x * -1, $y * -1);
                file_put_contents($output_file, $image);
                
        }
        
        // $image->setImageFormat($extension);
        
        // // $canvas->compositeImage($image, imagick::COMPOSITE_OVER, $x, $y);
        // $canvas->compositeImage($image, imagick::COMPOSITE_DEFAULT , $x, $y);
        // // $image->compositeImage($canvas, imagick::COMPOSITE_ATOP , $x, $y);
        
        // $canvas->flattenImages(); 
        
        
        // file_put_contents($output_file, $canvas);
    }
    else{
        
        file_put_contents($output_file, $image);
    }
    
}
// echo $image;
// $rand = rand(1,1000000);
// return "<img src=\"/{$src}?r={$rand}\" style='border: 1px solid red;'>";
return $src;