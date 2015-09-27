<?php

/*
    Получаем и обновляем данные погоды
*/

class modWebWeatherUpdateProcessor extends modProcessor{
    
    
    public function initialize(){
        
        $this->setDefaultProperties(array(
            "region"  => $this->modx->getOption('modxsite.weather_region', null, 'Moscow'),
        ));
        
        return parent::initialize();
    }
    
    
    public function process(){
        
        $region = $this->getProperty('region');
        
        $url = "http://api.openweathermap.org/data/2.5/weather?mode=json&lang=ru&units=metric&q={$region}";
        
        $json = file_get_contents($url);
        
        if(
            $weatherData = json_decode($json, true)
            AND $weatherData['cod'] == 200
            AND $weather = current($weatherData['weather'])
            AND $main = $weatherData['main']
        ){
            
            // Температура
            $temp = round($main['temp']);
            $icon = $weather['icon'];
            $weather_str = $weather['description'];
            
            # print "\n$temp";
            # print "\n$weather";
            # 
            # print_r($weatherData);
             
            
            if($setting = $this->modx->getObject('modSystemSetting', array(
                'key' => 'modxsite.weather'
            ))){
                
                $data = array(
                    "weather"   => $weather_str,
                    "temp"      => ( ($temp > 0) ? '+' : ($temp < 0 ? '-' : '') ) . $temp,
                    "icon"      => $icon,
                );
                
                # var_dump($temp);
                
                
                $setting->value = json_encode($data);
                
                # print '<pre>';
                # print_r($data);
                
                $setting->save();
                
                $this->modx->cacheManager->refresh();
            }
        }
        
        return $this->success();
    }
    
}

