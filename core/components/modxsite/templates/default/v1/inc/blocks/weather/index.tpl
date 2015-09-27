{*
    Виджет погоды
*}

{if $weather = json_decode($modx->getOption('modxsite.weather', null), true)}

    {$src = "http://openweathermap.org/img/w/{$weather.icon}.png"} 
    
    <span class="weather">
        Погода {$weather.temp}
        {if $image = $modx->runSnippet('imagick', [
            "input" => $src,
            "h"     => 24,
            "w"     => 24,
            "as_base64" => true,
            "cache"     => true,
            "cache_time"    => 3600
        ])}
            <img class="weather-icon" src="data:image/png;base64,{$image}" title="{$weather.weather}"/>
        {/if}
    </span>
{/if}
