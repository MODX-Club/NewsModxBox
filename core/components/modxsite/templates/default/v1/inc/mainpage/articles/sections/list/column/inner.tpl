<div class="article">

    <a href="{$object.uri}" class="title">
        {$object.pagetitle}
    </a>

    <p style="text-decoration: none;" class="text">
        {$object.summary}
    </p>
    
    <p class="text-right">
        <span class="text">{date('d.m.Y', $object.publishedon|default:$object.createdon)}</span>
    </p>
    
</div>