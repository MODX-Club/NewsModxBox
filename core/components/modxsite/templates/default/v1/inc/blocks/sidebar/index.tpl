{include "inc/banners/article.tpl"}
                                            
                                            {if !$modx->resource->hide_adverts}
                                                {for $i=1; 2 >= $i; $i++}
                                                    {include "inc/banners/adfox/sidebar/banner{$i}.tpl"}
                                                {/for}
                                            {/if}
                                            
                                            {*
                                                {include "inc/banners/economic.tpl"}
                                            *}
                                            
                                            {*
                                                Вывод статьи-баннера
                                            *}
                                            {if $modx->resource->getTVValue('right_block_show_article_banner')}
                                                {include "inc/banners/article.tpl"}
                                                {$modx->resource->setOption('right_banner_article_included', true)}
                                            {/if}