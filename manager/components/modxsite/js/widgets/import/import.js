// Консоль

if(typeof Shop == 'undefined'){
    Shop = {
        panel: {}
    };
}

if(typeof Shop.window == 'undefined'){
    Shop.window = {};
}

Shop.window.Console  = function(config) {
    config = config || {};
    
    Ext.applyIf(config,{
        'connector_url': 'components/modxsite/connectors/'
    });
        
    Ext.applyIf(config,{
        title: _('console')
        ,modal: Ext.isIE ? false : true
        ,closeAction: 'hide'
        ,shadow: true
        ,resizable: false
        ,collapsible: false
        ,closable: true
        ,maximizable: true
        ,autoScroll: true
        ,height: 400
        ,width: 650
        ,refreshRate: 2
        ,cls: 'modx-window modx-console'
        ,items: [{
            itemId: 'header'
            ,cls: 'modx-console-text'
            ,html: _('console_running')
            ,border: false
        },{
            xtype: 'panel'
            ,itemId: 'body'
            ,cls: 'x-form-text modx-console-text'
            ,border: false
        }]
        ,buttons: [
            /*{
                text: _('console_download_output')
                ,handler: this.download
                ,scope: this
            },*/{
                text: _('ok')
                ,itemId: 'okBtn'
                ,disabled: true
                ,scope: this
                ,handler: this.hide
            }
        ]
        ,keys: [{
            key: Ext.EventObject.S
            ,ctrl: true
            ,fn: this.download
            ,scope: this
        },{
            key: Ext.EventObject.ENTER
            ,fn: this.hide
            ,scope: this
        }]
        
        ,autoHeight: false
        
        ,url: config.connector_url + 'import.php'
        ,baseParams: {
            action: config.action || 'default/console'
            ,format: config.format || 'html_log'
        }
    });
    
    Shop.window.Console.superclass.constructor.call(this,config);
    
    this.on('show', this.StartImport);
    this.on('hide', this.close, this);
};

Ext.extend(Shop.window.Console, MODx.Window,{
    
    StartImport: function(){
        console.log(this.submit);
        this.submit();
    }
    
    ,submit: function(close) {
        close = close === false ? false : true;
        var f = this.fp.getForm();
        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            f.submit({
                //waitMsg: _('saving')
                
                scope: this
                ,failure: function(frm,a) {
                    if (this.fireEvent('failure',{f:frm,a:a})) {
                        MODx.form.Handler.errorExt(a.result,frm);
                    }
                }
                ,success: function(frm, response) {
                    //console.log(this);
                    // console.log(frm);
                    console.log(response);
                    
                    var object = response.result.object;
                    
                    
                    
                    if(!response.success || response.success == '0'){
                        MODx.msg.alert('Ошибка', response.message || 'Ошибка выполнения запроса');
                        return;
                    }
                    
                    // Получаем и устанавливаем параметры
                    var form = this.fp.getForm();
                    for(var i in object.params){
                        //console.log(i);
                        //console.log(object.params[i]);
                        form.baseParams[i] = object.params[i];
                    }
                    
                    
                    if (object.complete) {
                        this.fireEvent('complete');
                        this.fbar.setDisabled(false);
                        return;
                    }
                    
                    var out = this.getComponent('body');
                    //  console.log(out);
                    if (out) {
                        out.el.insertHtml('beforeEnd', object.data);
                        // e.data = '';
                        out.el.scroll('b', out.el.getHeight(), true);
                    }
                    
                    
                    
                    /*if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                    }
                    this.fireEvent('success',{f:frm,a:a});*/
                    
                    if(this.isVisible()){
                        //console.log(this);
                        //console.log(this.submit);
                        this.submit();
                    }
                    
                    // if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
                }
            });
        }
    }
});


// Импорт шин и дисков

Shop.panel.Import = function(config) {
    config = config || {};
    
    Ext.applyIf(config,{
        'connector_url': 'components/modxsite/connectors/'
    });
    
    Ext.applyIf(config,{
        url: config.connector_url + 'import.php'
        ,title: 'Импорт'
        ,bodyStyle: 'padding: 10px;'
        // ,width: 400
        ,layout: 'form'
        ,items: [
            {
                xtype: 'label'
            }
            ,this.getFileBrowser()
        ]
        ,bbar: [
            this.GetStartimportButton()
            
        ]
        ,listeners: {
            select: this.OnSelect
        }
    });
    Shop.panel.Import.superclass.constructor.call(this,config);
};

Ext.extend(Shop.panel.Import , MODx.Panel,{
    
    OnSelect: function(data){
        
        this.startimportButton.enable();
    }
    
    ,GetStartimportButton: function(){
        this.startimportButton = new Ext.Button({
            text: 'Запустить импорт'
            ,handler:  this.StartImport
            ,scope: this
            ,disabled: true
        });
        return this.startimportButton;
    }
    
    ,StartImport: function(){
        /*if(!this.pathname){
            MODx.msg.alert('Ошибка', "Необходимо выбрать файл для импорта");
            return;
        }*/
        /*console.log(this.FileBrowser);
        console.log(this.FileBrowser.getValue());
        console.log(this.FileBrowser.source);*/
        
        new Shop.window.Console({        
            'register'  : 'mgr'       
            ,'topic' : '/npgitporter/import/source{$type}/'
            ,url: this.url
            ,baseParams:{
                action: this.action || 'default/console'
                ,source: this.FileBrowser.source
                ,file: this.FileBrowser.getValue()
            }
        }).show();
    }
    
    ,getFileBrowser: function(){
        
        this.FileBrowser = new MODx.combo.Browser({
         
                fieldLabel: 'Файл для загрузки'
                // ,width: 200
                // ,hiddenName: 
                ,source: 14
                ,listeners: {
                    'select': {
                        fn:function(data) {
                            //console.log(data);
                            //Ext.getCmp('tv'+this.config.tv).setValue(data.relativeUrl);
                            //Ext.getCmp('tvbrowser'+this.config.tv).setValue(data.relativeUrl);
                            this.fireEvent('select',data);
                        },
                        scope:this
                    }
                    /*,'change': {
                        fn:function(cb,nv) {
                            Ext.getCmp('tv'+this.config.tv).setValue(nv);
                            this.fireEvent('select',{
                                relativeUrl: nv
                                ,url: nv
                            });
                        },
                        scope:this
                    }*/
                }
        });
        return this.FileBrowser;
    }
    
});

Ext.reg('shop-panel-import', Shop.panel.Import);

 