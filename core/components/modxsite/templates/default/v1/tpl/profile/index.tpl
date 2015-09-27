{extends file="tpl/index.tpl"}

{block name=pagetitle}{/block}
{block name=Breadcrumbs}{/block}

{block name=content}
    [[!smarty?tpl=`society/profiles/index.tpl`]]
{/block}

{*block name=page}
    <script data-main="{$template_url}js/pages/profile" src="{$template_url}libs/require/require.js"></script>
{/block*}
