<div class="counters">

    {block yandex_metrika}
    
        {literal}
            <!-- Yandex.Metrika informer -->
                <a href="https://metrika.yandex.ru/stat/?id=32704175&amp;from=informer"
                target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/32704175/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
                style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" onclick="try{Ya.Metrika.informer({i:this,id:32704175,lang:'ru'});return false}catch(e){}" /></a>
                <!-- /Yandex.Metrika informer -->
                
                <!-- Yandex.Metrika counter -->
                <script type="text/javascript">
                    (function (d, w, c) {
                        (w[c] = w[c] || []).push(function() {
                            try {
                                w.yaCounter32704175 = new Ya.Metrika({
                                    id:32704175,
                                    clickmap:true,
                                    trackLinks:true,
                                    accurateTrackBounce:true,
                                    webvisor:true
                                });
                            } catch(e) { }
                        });
                
                        var n = d.getElementsByTagName("script")[0],
                            s = d.createElement("script"),
                            f = function () { n.parentNode.insertBefore(s, n); };
                        s.type = "text/javascript";
                        s.async = true;
                        s.src = "https://mc.yandex.ru/metrika/watch.js";
                
                        if (w.opera == "[object Opera]") {
                            d.addEventListener("DOMContentLoaded", f, false);
                        } else { f(); }
                    })(document, window, "yandex_metrika_callbacks");
                </script>
                <noscript><div><img src="https://mc.yandex.ru/watch/32704175" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
                <!-- /Yandex.Metrika counter --> 
        {/literal}
             
    {/block}
    
    {block dev_copyright}
        <a title="Разработка сайтов, новостных порталов и интернет-магазинов на MODX Revolution" target="_blank" href="https://modxclub.ru"><img src="{$assets_url}images/site/logos/modx_h30.jpg"></a>
        <a title="Бесплатный модуль для новостных порталов на базе MODX Revolution" target="_blank" href="http://modxnews.ru">Powered by NewsModxBox</a>
    {/block}

</div>