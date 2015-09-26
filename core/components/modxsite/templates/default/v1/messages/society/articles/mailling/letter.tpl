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
        <tr bgcolor="#ddd">
            <td>
                &nbsp;&nbsp;<font size="5" face="Verdana,Helvetica,sans-serif" color="#ffffff"><b>
                    <a href="{$site_url}" target="_blank"><img src="{$site_url}{$template_url}img/logo.png" alt="{$site_name|escape}" title="{$site_name|escape}"/></a>
                </b></font>
            </td>
        </tr>
        <tr height="20" bgcolor="#ddd">
            <td>&nbsp;
                <font size="2"><a href="{$site_url}" target="_blank">все новости</a></font> 
                <font size="2"><a href="{$modx->makeUrl(84112, '', '', 'full')}" target="_blank">контакты</a></font> 
                <font size="2"><a href="{$modx->makeUrl(43, '', '', 'full')}" target="_blank">RSS</a></font> 
                <font size="2"><a href="{$modx->makeUrl(83981, '', '', 'full')}" target="_blank">подписка</a></font> 
                <font size="2"><a href="{$modx->makeUrl(83980, '', '', 'full')}" target="_blank">реклама</a></font>
            </td>
        </tr>
          
          
        
        {foreach $articles as $object}
            <tr>
                <td>
                    <br />
                    <font size="2" face="Verdana,Helvetica,sans-serif" color="#333">
                        <b>{$object.longtitle|default:$object.pagetitle}</b>
                    </font>
                    <p>
                        <a style="color:#211919" href="{$site_url}{$object.uri}" target="_blank">{$object.summary}</a>
                    </p>
                    <br />
                </td>
            </tr>
        {/foreach}
        
        
        <tr bgcolor="#ddd">
            <td>
                <font size="2">&nbsp;<a href="{$unsubscribe_url}" target="_blank">отписаться</a></font> <font size="2">© {date('Y')}</font>
            </td>
        </tr>
    </tbody>
</table>
            