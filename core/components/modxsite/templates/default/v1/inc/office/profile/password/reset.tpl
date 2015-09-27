{*
    Сброс пароля
*}

{$params = [
    "expiredTpl"    => "lgnExpiredTplModified",
    "tpl"   => '<p class="loginResetPassHeader">[[+username]],</p>

<p class="loginResetPassText">Ваш пароль был успешно сброшен. Теперь вы можете <a href="javascript:;" data-toggle="modal" data-target="#LoginModal" class="link">авторизоваться</a>.</p>
<p><i>Напоминаем, что ваш новый пароль был указан в письме.</i></p>',
    "tplType"   => "inline"
]}

{snippet name=ResetPassword params=$params assign=result}

{if $message = $result|trim}
    <div class="alert alert-info">
        {$message}
    </div>
{/if}


