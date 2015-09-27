
    <div id="topic_list_{$object.id}" class="topic_list">
        
        <div class="clearfix"></div>
        <p class="date">
            <span class="login">
                <a title="" href="{$users_url}{$object.author_username}">
                    {$object.author_username}
                </a>
            </span>
            <span class="sep"> | </span>
            {prettydate date={$object.publishedon|default:$object.createdon}}
              
        </p>
        
       
        <p>
            <a href="{$object.blog_uri}" >{$object.blog_pagetitle}</a> - <a href="{$object.uri}">{$object.pagetitle}</a>
            <div class="comments">Комментарии: <a title="" href="{$object.uri}#comments"><span class="fui-chat"></span></a> {(int)$object.comments_count} </div>
        </p>
         
        
        
    </div>