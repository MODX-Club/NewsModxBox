{$site_host = $modx->getOption('site_host')}
{$site_url = $modx->getOption('site_url')}
{$site_name = $modx->getOption('site_name')}

{*Создаем цифровую подпись*}
{$auth_link_salt = $modx->getOption('modsociety.auth_link_salt')}
{$key = md5("{$user->id}{$user->email}{$auth_link_salt}")}

{$unsubscribe_url = $modx->makeUrl($modx->getOption('modxsite.unsubscribe_doc_id'), '', [
    "key"   => $key,
    "uid"   => $user->id
]
, 'full')}

<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tbody>
        <tr bgcolor="#D71A21">
            <td>
                &nbsp;&nbsp;<font size="5" face="Verdana,Helvetica,sans-serif" color="#ffffff"><b>
                    <a href="{$site_url}" target="_blank"><img src="{$site_url}{$template_url}img/logo_new.png" alt="{$site_name|escape}" title="{$site_name|escape}"/></a>
                </b></font>
            </td>
        </tr>
        <tr height="20" bgcolor="#EFD7CD">
            <td>&nbsp;
                <font size="2"><a href="{$site_url}" target="_blank">все новости</a></font> 
                <font size="2"><a href="{$modx->makeUrl(84112, '', '', 'full')}" target="_blank">контакты</a></font> 
                <font size="2"><a href="{$modx->makeUrl(43, '', '', 'full')}" target="_blank">RSS</a></font> 
                <font size="2"><a href="{$modx->makeUrl(83981, '', '', 'full')}" target="_blank">подписка</a></font> 
                <font size="2"><a href="{$modx->makeUrl(83980, '', '', 'full')}" target="_blank">реклама</a></font>
            </td>
        </tr>
        
        {*
        <tr>
            <td>
                <a href="https://subscribe.dp.ru/newspaper/" style="display:block;margin-top:20px;margin-bottom:20px" target="_blank">
                    <font size="3" face="Verdana,Helvetica,sans-serif" color="#D71A21">
                        <b>
    				    	Читайте свежий номер газеты “Деловой Петербург”
					    </b>
    				</font>
        		</a>
            </td>
        </tr>
        *}
        {*
        <tr>
            <td>
                <font size="2" face="Verdana,Helvetica,sans-serif" color="#D71A21"><b>
                    ТОП 5 новостей <a href="http://dp.ru" target="_blank">dp.ru</a> за 24.04.2015</b>
                </font>
            </td>
        </tr>
        *}
        
        {*
            <tr>
            <td><ul><li><a style="color:#211919" href="http://www.dp.ru/a/2015/04/23/Nesportivnij_nastroj" target="_blank">Спрос на фитнес-клубы в Петербурге упал на 25-50%</a></li><
            li><a style="color:#211919" href="http://www.dp.ru/a/2015/04/22/Kuda_edut_i_kto_povezet" target="_blank">Место разорившихся петербургских турфирм начали занимать другие игроки</a></li>
            <li><a style="color:#211919" href="http://www.dp.ru/a/2015/04/22/Oboronnaja_skidka" target="_blank">Петербургский ремонтный завод рискует лишиться земли рыночной стоимостью 3 млрд рублей</a></li>
            <li><a style="color:#211919" href="http://www.dp.ru/a/2015/04/23/Soveti_deputatov" target="_blank">"ДП" выяснил, какие депутаты ЗакСа защищают интересы девелоперов </a></li>
            <li><a style="color:#211919" href="http://www.dp.ru/a/2015/04/22/Negativnij_multiplikator" target="_blank">Что поможет России отбиться от санкций Запада</a></li></ul></td></tr>
            
             
        *}
        {*
        <tr>
        <td><a href="http://ads.adfox.ru/3276/goLink?p1=bsse&amp;p2=bhgb&amp;pj=b&amp;pe=b&amp;pr=ecyonfe" target="_blank"><img src="https://ci4.googleusercontent.com/proxy/WqVDKvL13VWM5ITuvqhiQb1M1Ju872gZPpmSu_DJGZWpM66xB51IpLDegRMIkhFtGSXt8vpgZcjoPKyVxcpeqI0Nk5a9bwLHnq86r1icvR2i9YpNJ5OTsbwThg9STA2floImDTccwA=s0-d-e1-ft#http://ads.adfox.ru/3276/getCode?p1=bsse&amp;p2=bhgb&amp;pe=b&amp;py=a&amp;pfc=a&amp;pfb=a&amp;pr=ecyonfe" class="CToWUd"></a></td></tr>
        *}
        
        {foreach $articles as $object}
            <tr>
                <td>
                    <br />
                    <font size="2" face="Verdana,Helvetica,sans-serif" color="#D71A21">
                        <b>{$object.longtitle|default:$object.pagetitle}</b>
                    </font>
                    <p>
                        <a style="color:#211919" href="{$site_url}{$object.uri}" target="_blank">{$object.summary}</a>
                    </p>
                    <br />
                </td>
            </tr>
        {/foreach}
        
        
        <tr bgcolor="#EFD7CD">
            <td>
                <font size="2">&nbsp;<a href="{$unsubscribe_url}" target="_blank">отписаться</a></font> <font size="2">© {date('Y')}</font>
            </td>
        </tr>
    </tbody>
</table>
            