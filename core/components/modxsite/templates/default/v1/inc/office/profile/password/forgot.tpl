{snippet name=ForgotPassword params=[
    resetResourceId => 109,
    tplType => 'embedded',
    sentTpl => 'Письмо с инструкциями для активации нового пароля отправлено на емейл [[+email]]',
    sentTplType => 'inline',
    emailTpl => '<p>Здравствуйте, [[+username]],</p>

<p>Для активации нового пароля, пожалуйста, пройдите по этой ссылке:</p>

<p><a href="[[+confirmUrl]]">[[+confirmUrl]]</a></p>

<p>Ваш новый пароль: [[+password]]</p>

<p>Обратите внимание, что пароль будет действителен только после перехода по ссылке <a href="[[+confirmUrl]]">[[+confirmUrl]]</a></p>

<p>С уважением,<br />
<em>Администрация сайта</em></p>',
    emailTplType => 'inline'
] assign=result} 

<form class="loginFPForm form-horizontal" action="" method="post">

    <div class="panel panel-default loginFP">
        <div class="panel-heading">
            <h4>{$modx->lexicon('login.forgot_password')}</h4>
        </div>
        
        
        <div class="panel-body">
            {if $errors = $modx->getPlaceholder("loginfp.errors")}
                <div class="alert alert-danger">{$errors}</div>
            {else if $result}
                <div class="alert alert-success">{$result}</div>
            {/if} 
            
                <div class="form-group">
                    <label for="username" class="col-md-4 control-label">{$modx->lexicon('login.username')}</label>
                        
                    <div class="col-md-8">
                        <input type="text" value="{$modx->getPlaceholder('loginfp.post.username')}" id="username" name="username" class="form-control ">
                        <span class="error text-danger"></span>
                    </div>
                </div>
            
            
                <div class="form-group">
                    <label for="username" class="col-md-4 control-label">Или адрес электронной почты</label>
                        
                    <div class="col-md-8">
                        <input type="text" value="{$modx->getPlaceholder('loginfp.post.email')}" id="email" name="email" class="form-control ">
                        <span class="error text-danger"></span>
                    </div>
                </div>
             
                <input class="returnUrl" type="hidden" name="returnUrl" value="{$modx->getPlaceholder('loginfp.request_uri')}" />
                
                <input class="loginFPService" type="hidden" name="login_fp_service" value="forgotpassword" />
                <span class="loginFPButton">
                
        </div>
        
        <div class="panel-footer">
            <input class="btn btn-primary" type="submit" name="login_fp" value="{$modx->lexicon('login.reset_password')}" />
        </div>
        
    </div>
    
</form>