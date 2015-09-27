/*
    Менеджер картинок
*/

ArticlesEditor.window.ImageManager = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        title: "Менеджер картинок"
        // ,html: image == '' ? '' : '<img src="./'+ image +'" style="max-height:300px;max-width:400px;"/>'
        // ,closeAction: 'close'
        ,action: 'resources/images/set'
        ,modal: true
        // ,minHeight: 300
        // ,minWidth: 400
        ,height: 450
        // ,autoHeight: true
        ,width: 500
        // ,bodyStyle:{
        //     width: 'auto'
        // }
        ,url: MODx.config.manager_url + "components/articleseditor/connectors/connector.php"
        ,fields: [
            this.uploadButton
            ,this.ImageField
            // ,this.ResourceField
            ,{
                xtype: "textfield"
                ,hidden: true
                ,name: "resource_id"
                ,value: config.resource_id || null
            }
        ]
        ,success: function(a,b,c){
            // console.log(this);
            // console.log(a);
            // console.log(b);
            // console.log(c);
            this.store.reload();
        }
        ,buttons: [{
            /*
                При клике имитируем клик по хидден-полю file
            */
            text: "Загрузить новое"
            ,scope: this
            ,handler: function(a, b, c) { 
                // window.v = this.uploadButton;
                this.uploadButton.getEl().dom.click();
                // console.log(this.uploadButton);
                // console.log(this);
                // console.log(a);
                // console.log(b);
                // console.log(c);
                // this.close(); 
            } 
        }
        ,{
            text: "Выбрать имеющееся"
            ,scope: this
            ,handler: function(a, b, c) {
                // console.log(this);
                // console.log(a);
                // console.log(b);
                // console.log(c);
                // this.close();
                
                // console.log(this);
                
                var panel = this;
                
                var win = new MODx.Window({
                    width: '50%',
                    height: window.innerHeight * 2/3,
                    modal: true,
                    buttons: false,
                    items: [{
                        xtype: 'modxsite-media-view'
                        ,onSelect: function(){
                            // console.log(this);
                            var selected = this.view.getSelectedRecords();
                            // console.log(selected);
                            
                            if(selected.length == '1'){
                                var record = selected[0];
                                var src = record.get('relativeUrl');
                                panel.renderImage(src);
                                panel.ImageField.setValue(src);
                                win.close();
                            }
                        }
                    }],
                    listeners: {
                        
                        /*
                            Прерываем отправку запроса на нажатию Enter
                        */
                        beforeSubmit: function(){
                            console.log('wefwefwefbgggg');
                            return false;
                        }
                    }
                });
                
                win.on('hide', function(){
                    win.close();
                });
                
                win.show();
                win.maximize();
            } 
        }
        ,{
            text: _('save')
            ,cls: 'primary-button'
            ,scope: this
            ,handler: this.submit
        }]
    });
    
    
    ArticlesEditor.window.ImageManager.superclass.constructor.call(this, config);
     
    if(config.value){
        // console.log(config.value);
        // console.log(this);
        // var image = config.value;
        // var html = image == '' ? '' : '<img src="./'+ image +'" style="max-height:300px;max-width:500px;"/>';
        // config.html = html;
        // this.renderImage(config.value);
        this.on('afterRender', function(){
            console.log(this);
            this.renderImage(config.value);
        }, this);
    }
    
   //  this.panel = new Ext.Panel({
   //      
   //  });
   // 
   //  this.add(this.panel);
   
    // if(config.value){
    //     this.renderImage(config.value)  ;
    // }
};

Ext.extend(ArticlesEditor.window.ImageManager, MODx.Window, {
    
    uploadButton: new Ext.form.TextField({
            // text: _('cancel')
            // ,scope: this
            // ,handler: function(a, b, c) { 
            //     console.log(this);
            //     console.log(a);
            //     console.log(b);
            //     console.log(c);
            //     this.close(); 
            // }
            
            text: "Загрузить новое"
            ,xtype:'textfield'
            ,hidden: true
            ,inputType:"file"
            // ,scope: this
            ,listeners: {
                change: function(){
                }
                ,afterRender: function(field){
                        // console.log(this);
                        // console.log(field);
                    this.getEl().dom.onchange = function(){
                         
                        // console.log(this);
                        // console.log(this.scope);
                        // console.log(field);
                        
                        var panel = Ext.getCmp(this.id).ownerCt.ownerCt;
                        
                        // console.log(panel);
                        // return;
                       
                        var file = this.files[0];
                        // console.log(file);

                        var reader  = new FileReader();
                        
                        reader.onloadend = function () {
                             // console.log(this.result);
                            
                            var formData = new FormData();
                            
                            // console.log(file);
                            
                            formData.append("name", file.name);
                            formData.append("lastmod", file.lastModified / 1000);
                            formData.append("size", file.size);
                            formData.append("type", file.type);
                            formData.append("image", this.result);
                            // formData.append("resource_id", Ext.getCmp('modx-panel-resource').resource);
                            formData.append("resource_id", 'need_value');
                            
                            formData.append("HTTP_MODAUTH", MODx.siteId);
                            // siteId "modx54c2bf0598d4b9.42093291_4225507ad03545ea6.13182118"

                            var xhr = new XMLHttpRequest();
                            var boundary = String(Math.random()).slice(2);
                            // xhr.setRequestHeader('Content-Type','multipart/form-data; boundary=' + boundary);
                            xhr.onreadystatechange = function(response) {
                                // console.log(this);
                                // console.log(response);
                                  if (this.readyState == 4 && this.status == 200) {
                                    var response = {};
                                    try{
                                        response = JSON.parse(this.responseText);
                                    }
                                    catch(error){
                                        alert('Ошибка разбора ответа');
                                        return;
                                    }
                                    // console.log(response);
                                    if(!response.success){
                                        alert(response.message || "Ошибка выполнения запроса");
                                        return;
                                    }
                                    
                                    // var store = panel.getStore();
                                    // var record = new Ext.data.Record(r.object);
                                    // console.log(record);
                                    // store.add(record);
                                    
                                    /*
                                        Запрос выполнен успешно.
                                        Сохраняем значение в поле и обновляем картинку
                                    */
                                    
                                    var src = response.object.src;
                                    
                                    panel.renderImage(src);
                                    
                                    // console.log(field);
                                    
                                    panel.ImageField.setValue(src);
                                    
                                    // Подгоняем размеры
                                    // panel.setWidth('auto');
                                    // panel.doLayout();
                                }
                            };
                            
                            xhr.open('post', MODx.config.manager_url + 'components/articleseditor/connectors/connector.php?action=images/upload');
                            xhr.send(formData);
                            
                        }
                        
                        if (file) {
                            reader.readAsDataURL(file);
                        }
                    }
                    /*console.log(this);
                    console.log(this.getEl());
                    console.log(this.getEl().dom);
                    console.log(this.getEl().onSelect);
                    console.log(this.getEl().dom.onchange);
                    console.log(Ext.getCmp(this.getEl().dom.id).fireEvent('change'));*/
                }
            }
            // ,handler: function(a, b, c) { 
            //     console.log(this);
            //     console.log(a);
            //     console.log(b);
            //     console.log(c);
            //     // this.close(); 
            // }
            
            
        })
    
    ,renderImage: function(image){
        var html = image == '' ? '' : '<img src="'+ MODx.config.base_url +'uploads/'+ image +'" style="max-height:300px;max-width:400px;"/>';
        this.update(html);
    }
    
    ,ImageField: new Ext.form.TextField({
        xtype: "textfield"
        ,hidden: true
        ,value: ""
        ,name: "src"
    })
    
    
    
    ,submit: function(close) {
        
        
        close = close === false ? false : true;
        var f = this.fp.getForm();
        if (f.isValid() && this.fireEvent('beforeSubmit',f.getValues())) {
            f.submit({
                waitMsg: _('saving')
                ,scope: this
                ,failure: function(frm,a) {
                    if (this.fireEvent('failure',{f:frm,a:a})) {
                        MODx.form.Handler.errorExt(a.result,frm);
                    }
                    this.doLayout();
                }
                ,success: function(frm,a) {
                    if (this.config.success) {
                        Ext.callback(this.config.success,this.config.scope || this,[frm,a]);
                    }
                    this.fireEvent('success',{f:frm,a:a});
                    if (close) { this.config.closeAction !== 'close' ? this.hide() : this.close(); }
                    this.doLayout();
                }
            });
        }
    }
    
    // ,ResourceField: new Ext.form.TextField({
    //     xtype: "textfield"
    //     ,hidden: true
    //     ,value: ''
    //     ,name: "resource_id"
    // })
});

Ext.reg('articleseditor-window-imagemanager', ArticlesEditor.window.ImageManager);

 