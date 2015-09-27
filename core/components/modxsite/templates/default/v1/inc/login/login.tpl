{$assets_url = $modx->getOption('assets_url')}

<noindex>
        
    <form id="loginLoginForm" class="form" action="[[~[[*id]]]]" method="post">
        <input type="hidden" name="pub_action" value="login" />
        <input type="hidden" name="returnUrl" value="{$request_uri}" />
        
        <div class="row">
            
            <div class="col-md-6">
                <div class="loginLogin">
                            <fieldset class="loginLoginFieldset">
                                <div class="control-group">
                                    <label class="loginUsernameLabel" for="loginUsername" >Имя пользователя</label> 
                                    <div class="controls">
                                        <input id="loginUsername" class="form-control loginUsername" type="text" name="username" />    
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="loginPasswordLabel" for="loginPassword">Пароль</label> 
                                    <div class="controls">
                                        <input id="loginPassword" class="form-control loginPassword" type="password" name="password" />
                                    </div>
                                </div>
            
                 
                                
                                
                                
                            </fieldset>       
                    </div>
            </div>
             
            <div class="col-md-6">
                
                <label >Вход через социальные сети</label> 
                <div> 
                    {include file="inc/login/social_auth_button.tpl"}
                </div>  
                
            </div> 
            
            
            <div class="col-md-12">  
                <br /> 
                <span class="loginLoginButton">
                    <button type="submit" name="Login" class="btn btn-success">Вход</button>
                    <a href="{$modx->makeUrl(104)}" class="btn btn-primary">Регистрация</a>
                    <a href="{$modx->makeUrl(108)}" class="btn btn-info">Забыли пароль?</a> 
                </span>
            </div>
            
        </div>
    
    </form> 
     
</noindex>