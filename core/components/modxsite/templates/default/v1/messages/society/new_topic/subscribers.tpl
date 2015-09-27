{extends file="messages/society/layout.tpl"}

{block name=content}

    {$options = $auth_params|default:[]}  
    
    {$get_params = http_build_query($options)}
    
    {$topic_url =  "{$site_url}{$topic.uri}?$get_params"}
    
    На сайте опубликован новый топик <a href="{$topic_url}">{$topic.pagetitle}</a>. <br /><br />
    <blockquote>{$topic.content|strip_tags|truncate:500}</blockquote>
    <br /><br />
{/block}
  