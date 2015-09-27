/*
    Грид группового редактора
*/
ArticlesEditor.grid.GroupEdit = function(config){
    config = config || {};
    
    this._tbar = {
        types: {}
    };
    
    
    Ext.applyIf(config, {
        url: ArticlesEditor.config.connectors_url + 'groupedit.php'
        // ,title: 'Список'
        // ,listType: 'documents'
        ,listType: 'articles'
        ,paging: true
        ,pageSize: 10
        ,fields: [
            'id'
            ,'type'
            ,'menu'
            ,'pagetitle'
            ,'longtitle'
            ,'description'
            ,'menuindex'
            ,'parent'
            ,'class_key'
            ,'context_key'
            ,'isfolder'
            ,'alias'
            ,'uri'
            ,'published'
            ,'publishedon'
            ,'publishedon_date'
            ,'deleted'
            ,'hidemenu'
            ,'image'
            ,'imageDefault'
            ,'article_status'
            // ,'createdon'
            ,'createdon_date'
            ,'createdby'
            ,'createdby_fullname'
            ,'pseudonym'
            
            // Тип объекта
            ,'object_type'
            ,'article_type'
            
            // Parent
            ,'uplevel_id'
            ,'parent_id'
            ,'parent_title'
            ,{
                name: 'sm_price'
                /*,type: 'float'
                ,allowBlank: true
                ,defaultValue: null*/
            }
            ,'sm_currency'
            ,'currency_title'
            ,'views'
            ,{
                name: 'sm_trade_price'
            }
            ,'rss'
            ,'news_list'
            
        ]
        ,remoteSort: true
        ,autosave: true
        ,columns: this.getColumnModel()
        ,context_key: 'web'
    });
    
    
    this._tbar.context_key = new MODx.combo.Context({
        text: 'Контекст'
        ,hidden_name: 'context_key'
        ,value: config.context_key
        ,listeners:{
            select: {
                fn: function(combo){
                    var context_key = combo.getValue();
                    var store = this.getStore();
                    store.setBaseParam('context_key', context_key);
                    this.getBottomToolbar().changePage(0);
                }
                ,scope: this
            }
        }
    });
    
    this._tbar.products_only = new Ext.form.Checkbox({
        label: 'Только товары'
        ,hidden_name: 'products_only'
        ,value: 1
        // ,listeners:{
        //     select: {
        //         fn: function(combo){
        //             var context_key = combo.getValue();
        //             var store = this.getStore();
        //             store.setBaseParam('context_key', context_key);
        //             this.getBottomToolbar().changePage(0);
        //         }
        //         ,scope: this
        //     }
        // }
    });
    
    this._tbar.types.documents = new Ext.Toolbar.Button({
        text: 'Все документы'
        ,value: 'documents'
        ,disabled: config.listType == 'documents' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    // this._tbar.types.products = new Ext.Toolbar.Button({
    //     text: 'Товары'
    //     ,value: 'products'
    //     ,disabled: config.listType == 'products' ? true : false
    //     ,handler: this.changeListType
    //     ,scope: this
    // });
    
    this._tbar.types.articles = new Ext.Toolbar.Button({
        text: 'Статьи'
        ,value: 'articles'
        ,disabled: config.listType == 'articles' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    this._tbar.search = new Ext.form.TextField({
        fieldLabel: 'Поиск'
        ,name: 'query_string'
        // ,disabled: config.listType == 'products' ? true : false
        // ,handler: this.Search
        ,listeners: {
            specialkey: {
                fn: this.Search
                ,scope: this
            }
        }
        ,width: 237
    });
    
    this._tbar.types.models = new Ext.Toolbar.Button({
        text: 'Модели'
        ,value: 'models'
        ,disabled: config.listType == 'models' ? true : false
        ,handler: this.changeListType
        ,scope: this
    });
    
    this._tbar.upLevelButton = new Ext.Toolbar.Button({
        text: 'На уровень выше'
        ,handler: this.toUpLevel
        ,iconCls: 'group_edit_toplevel'
        ,scope: this
        ,hidden: true
    });
    
    
    this._tbar.createArticle = new Ext.Toolbar.Button({
        text: 'Создать статью'
        ,handler: this.createArticle
        // ,hidden: true
        ,scope: this
    });
    
    this._tbar.createArticleOld = new Ext.Toolbar.Button({
        text: 'Создать статью (в новом окне)'
        ,handler: this.createArticleOld
        // ,hidden: true
        ,scope: this
    });
    
    
    this._tbar.refreshButton = new Ext.Toolbar.Button({
        text: 'Обновить'
        ,cls: 'primary-button'
        ,handler: function(){
            this.store.reload();
        }
        // ,hidden: true
        ,scope: this
    });
    
    
    Ext.applyIf(config, {
        // tbar:[
        //     // {
        //     //     xtype:'label'
        //     //     ,text: 'Контекст'
        //     // }
        //     // ,'-'
        //     // ,this._tbar.context_key
        //     // ,this._tbar.products_only
        //     this._tbar.types.documents
        //     ,this._tbar.types.articles
        //     // ,this._tbar.types.products
        //     //,this._tbar.types.models
        //     ,this._tbar.upLevelButton
        //     ,'-'
        //     ,this._tbar.createArticle
        //     ,{
        //         xtype:'label'
        //         ,text: 'Поиск'
        //     }
        //     ,this._tbar.search
        // ]
        tbar:{
            // layout: {
            //     type: 'vbox',
            //     align: 'stretch'  // Child items are stretched to full width
            // }
            // // ,defaultType: 'panel'
            // // ,bodyStyle: "padding: 15px;"
            // // ,defaults:{
            // //     // layout: 'toolbar'
            // //     bodyStyle: "padding: 20px; margin: 20px;"
            // //     ,border: false
            // // }
            // // ,items: [{ 
            // //     columnWidth: .25
            //     ,items: [
            //         this._tbar.types.documents
            //         ,this._tbar.types.articles
            //     ]
            // // },{ 
            // //     columnWidth: .6
            // // },{ 
            // //     columnWidth: .15
            // // }]
            
            // items: [
            //     this._tbar.types.documents
            //     ,this._tbar.types.articles
            //     ,this._tbar.upLevelButton
            //     ,'-'
            //     ,this._tbar.createArticle
            //     ,{
            //         xtype:'label'
            //         ,text: 'Поиск'
            //     }
            //     ,this._tbar.search
            // ]  
            
            layout:'column',
            xtype: 'panel',
            defaults:{
                bodyCfg: {
                    cls: ''  // Default class not applied if Custom element specified
                }
            }
            ,items: [{
                defaults:{
                    style: "padding: 0 0 5px;"
                }
                ,items: [
                    /*{
                        xtype: 'panel'
                        ,layout: 'auto'
                        ,defaults:{
                            style: "margin: 2px 5px 2px 2px;"
                            
                        }
                        ,items:[
                            this._tbar.types.articles
                            ,this._tbar.types.documents
                        ]
                    },*/
                    /*{
                        xtype: 'panel'
                        ,layout: 'auto'
                        ,defaults:{
                            style: "margin: 2px 5px 2px 2px;"
                            
                        }
                        ,items:[
                            this._tbar.upLevelButton
                        ]
                    }
                    ,
                    */
                    {
                        xtype: 'panel'
                        ,layout: 'auto'
                        ,defaults:{
                            style: "margin: 2px 5px 2px 2px;"
                            
                        }
                        ,items:[
                            {
                                xtype: 'panel'
                                ,defaults:{
                                    style: "margin: 2px 5px 2px 2px;"
                                    
                                }
                                ,items: [
                                    this._tbar.refreshButton  
                                ]
                            }
                            ,{
                                xtype: 'panel'
                                ,defaults:{
                                    style: "margin: 2px 5px 2px 2px;"
                                    
                                }
                                ,items: [
                                    this._tbar.createArticle      
                                ]
                            }
                            ,{
                                xtype: 'panel'
                                ,defaults:{
                                    style: "margin: 2px 5px 2px 2px;"
                                    
                                }
                                ,items: [
                                    this._tbar.createArticleOld    
                                ]
                            }
                        ]
                    }
                ]
        },{
                defaults:{
                    xtype: 'panel'
                    ,layout: 'table'
                    ,style:'padding: 5px'
                    // ,defaults:{
                    //     // style: "padding: 0 5px;"
                    // }
                }
                ,items: [{
                    xtype: 'panel'
                    ,layout: 'form' 
                    ,items:[this._tbar.search]
                },{
                    items: [
                        {
                            xtype: 'panel'
                            ,layout: 'table'
                            ,defaults:{
                                // style: "margin: 0 5px;"
                                // ,labelStyle: "margin-right: 5px;"
                            }
                            ,items:[{
                                xtype: 'label'
                                ,text: 'Дата от '
                                ,style: "margin: 0 5px 0 0;"
                            },{
                                xtype: 'datefield'
                                ,name: 'date_from'
                                ,format: 'Y-m-d'
                                ,listeners:{
                                    change: {
                                        scope: this
                                        ,fn: this.onChangeField
                                    }
                                    // ,select: {
                                    //     scope: this
                                    //     ,fn: this.onChangeField
                                    // }
                                }
                            },{
                                xtype: 'label'
                                ,text: 'до'
                                ,style: "margin: 0 5px 0 10px;"
                            },{
                                xtype: 'datefield'
                                ,name: 'date_till'
                                ,format: 'Y-m-d'
                                ,listeners:{
                                    change: {
                                        scope: this
                                        ,fn: this.onChangeField
                                    }
                                    // ,select: {
                                    //     scope: this
                                    //     ,fn: this.onChangeField
                                    // }
                                }
                            }]
                        }
                    ]
                }/*,{
                    xtype: 'panel' 
                    ,items:[{
                        xtype: 'checkbox'
                        ,fieldLabel: "Поиск"
                    },{
                        xtype: 'label'
                        ,text: "Фильтровать содержимое по накрутке"
                    }]
                }*/
                ]
            },{
                defaults:{
                    style: "padding: 5px;"
                }
                ,items: [
                    {
                        xtype: 'panel'
                        ,layout: 'form' 
                        ,items:[{
                            xtype: 'articleseditor-combo-authors'
                            ,fieldLabel: "Автор"
                            ,width: 200
                            ,listeners:{
                                select: {
                                    scope: this
                                    ,fn: this.onChangeField
                                }
                            }
                        },{
                            fieldLabel: "Статус"
                            ,xtype: 'modx-combo'
                            ,name:'article_status'
                            ,mode: 'local'
                            ,displayField: 'd'
                            ,valueField: 'v'
                            ,store: new Ext.data.SimpleStore({
                                fields: ['d','v']
                                ,data: [
                                    ['Выберите из списка', null]
                                    ,['Пишется', 0]
                                    ,['На редакции', 1]
                                    ,['Опубликована', 2]
                                    ,['Скрыта', 3]
                                    ,['Просрочена', 4]
                                ]
                                /*
                                    Выберите из списка==||Пишется==0||На редакции==1||Опубликована==2||Скрыта==3||Просрочена==4
                                */
                            })
                            ,listeners:{
                                select: {
                                    scope: this
                                    ,fn: this.onChangeField
                                }
                            }
                            ,width: 200
                        }]
                    }
                ]
            },{
                defaults:{
                    style: "padding: 5px;"
                }
                ,items: [
                    {
                        xtype: 'panel'
                        ,layout: 'form' 
                        ,items:[{
                            xtype: 'articleseditor-combo-sections'
                            ,fieldLabel: "Рубрика"
                            ,listeners:{
                                select: {
                                    scope: this
                                    ,fn: function(field){
                                        // console.log(field);
                                        // console.log(this);
                                        var store = this.store;
                                        store.setBaseParam('parent', field.getValue());
                                        store.load();
                                    }
                                }
                            }
                            // ,width: 200
                        },{
                            // ,width: 200
                            xtype: 'checkbox'
                            ,fieldLabel: "Мои статьи"
                            ,name: 'own_articles'
                            ,listeners:{
                                check: {
                                    scope: this
                                    ,fn: function(field, newValue){
                                        // console.log(newValue);
                                        // console.log(field);
                                        // console.log(field.getValue());
                                        // console.log(field.getRawValue());
                                         
                                        // console.log(this);
                                        var store = this.store;
                                        store.setBaseParam(field.name, newValue == true ? 1 : 0);
                                        store.load();
                                    }
                                }
                            }
                        }
                        /*,{
                            xtype: 'checkbox'
                            ,fieldLabel: "Статистика"
                            // ,width: 200
                        }*/
                        ]
                    }
                ]
            }]
            
            
        }
        ,baseParams:{
            action: 'getList'
            ,listType: config.listType
            ,parent: 0
            ,context_key: config.context_key
        }
    });
     
    
    ArticlesEditor.grid.GroupEdit.superclass.constructor.call(this, config);
    
    this.getBottomToolbar().on('change', this.onChangeBottomToolbar , this);
    
    // this.getBottomToolbar().on('celldblclick', this.onCellDblClick , this);
    
    /*
        Навешиваем события на строку, чтобы после обновления подгружать опять стор
    */
    this.getView().on('rowupdated', function( view, firstRow, record ){
        // console.log(this);
        // console.log(record.dirty);
        // console.log(record.getChanges( ));
        
        if(!record.dirty){
            this.store.reload();
        }
    }, this); 
    
};

Ext.extend(ArticlesEditor.grid.GroupEdit, MODx.grid.Grid, {
    
    
    onChangeBottomToolbar: function(PagingToolbar, pageData){
        var record = PagingToolbar.store.getAt(0);
        if(!record){
            return;
        }
        
        /*console.log(this);
        console.log(record);*/
        
        var uplevel_id = record.get('uplevel_id');
        var parent = record.get('parent');
        var parent_title = record.get('parent_title');
        
        // Если родитель - Статьи, то активируем кнопку создания статей
        // if(parent == 148){
        //     this._tbar.createArticle.show();
        // }
        // else{
        //     this._tbar.createArticle.hide();
        // }
        
        
        // Кнопка Вверх
        if(!uplevel_id){
            this._tbar.upLevelButton.hide();
            return;
        }
        
        
        this.showUpLevelButton(uplevel_id, parent_title);
        
    }
    
    
    ,getColumnModel: function(){
        
        return new Ext.grid.ColumnModel({
            grid: this
            ,defaults:{
                sortable: true
            }
            ,columns: [
                {
                    header: "ID"
                    ,dataIndex: 'id'
                    ,width: 50
                    ,hidden: true
                },{
                    header: "Картинка"
                    ,dataIndex: 'image'
                    ,width: 80
                    // ,fixed: true
                    ,css: 'cursor: pointer;'
                    ,renderer: function(value, column, record){
                        var tag = '';
                        if(value != '' && value != null){
                            // tag = '<img src="./uploads/'+ value +'" width="100" height="80" />';
                            tag = '<img src="'+ MODx.config.connectors_url +'system/phpthumb.php?src=/uploads/'+ value +'&w=100&h=80&f=png&q=90&far=C"/>';
                        }
                        return tag;
                    }
                    ,listeners: {
                        dblclick: {
                            scope: this
                            ,fn: function( cell, grid, rowIndex, e ){
                                // console.log(this);
                                // console.log(grid);
                                // console.log(grid.store);
                                // console.log(grid.store.getAt(rowIndex));
                                // console.log();
                                
                                var record = grid.getStore().getAt(rowIndex);
                                // 
                                // var image = grid.store.getAt(rowIndex).get('image');
                                // 
                                // // console.log(record);
                                // 
                                // var window = new ArticlesEditor.window.ImageManager({ 
                                //     value: image
                                //     ,resource_id: record.get('id')
                                //     ,scope: grid
                                // });
                                
                                
                                this.ShowChangeImageWindow(record);
                                
                                // window.show();
                            }
                        }
                    }
                },{
                    header: 'Заглавие'
                    ,dataIndex: 'pagetitle'
                    ,width: 150
                    ,scope: this
                    ,renderer: function(value, column, record){
                        
                        var output;
                        var classes = '';
                        
                        if(record.get('deleted') == '1'){
                            classes += ' deleted';
                        }
                        
                        if(record.get('published')  == '0'){
                            classes += ' unpublished';
                        } 
                        
                        if(record.get('hidemenu') == '1'){
                            classes += ' hidemenu';
                        }
                        
                        var text = '<span>'+ value +'</span>';
                        
                        if(record.get('isfolder') == '1'){
                            output = '<a href="javascript:;" onclick="Ext.getCmp(\'' + this.id+ '\').loadNodes('+ record.get('id') +', '+ record.get('parent') +');">'+text+'</a>';
                        }
                        else output = text;
                        
                        return '<div class="'+ classes +'">'+ output +'</div>';
                    }
                    ,editable: true
                },{
                    header: 'Рубрика'
                    ,dataIndex: 'parent_id'
                    ,scope: this
                    ,renderer: function(value, column, record){
                        var parent_title = record.get('parent_title');
                        if(parent_title){
                            value = parent_title;
                        }
                        return value;
                    }
                    
                },{
                    header: 'Тип статьи'
                    ,dataIndex: 'article_type'
                    ,scope: this
                    ,hidden: true
                    ,renderer: function(value, column, record){
                        
                        switch(value){
                            case '1':
                                value = 'Статья для сайта';
                                break;
                            case '2':
                                value = 'Зарубежные новости';
                                break;
                            case '3':
                                value = 'Биржевые новости';
                                break;
                            case '4':
                                value = 'Экономический результат';
                                break;
                            case '5':
                                value = 'Бонус статья';
                                break;
                            case '6':
                                value = 'Пресс релиз';
                                break;
                            case '8':
                                value = 'Газета ДП';
                                break;
                            case '11':
                                value = 'Свежий номер (бух. вести)';
                                break;
                                
                            default:;
                        }
                        
                        return value;
                    }
                    ,editable: false
                },{
                    header: 'Расширенный заголовок'
                    ,dataIndex: 'longtitle'
                    ,editable: true
                    ,hidden: true
                },{
                    header: 'Описание'
                    ,dataIndex: 'description'
                    ,editable: false
                    ,hidden: true
                },{
                    header: 'Алиас'
                    ,dataIndex: 'alias'
                    ,editable: true
                    ,hidden: true
                },{
                    header: 'Тип'
                    ,width: 50
                    ,hidden: true
                    ,dataIndex: 'object_type'
                    ,renderer: function(value){
                        switch(value){
                            case 'document':
                                value = 'Документ';
                                break;
                            case 'model':
                                value = 'Модель товара';
                                break;
                            case 'product':
                                value = 'Товар';
                                break;
                            default:;
                        }
                        return value;
                    }
                    ,sortable: false
                },{
                    header: 'Опубликовано'
                    ,dataIndex: 'published'
                    ,editable: true
                    ,width:50
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:green;">Да</span>';
                        }
                        else{
                            value = '<span style="color:red;">Нет</span>';
                        }
                        return value;
                    }
                },{
                    header: 'Скрыто'
                    ,width:50
                    ,dataIndex: 'hidemenu'
                    ,editable: false
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:red;">Да</span>';
                        }
                        else{
                            value = '<span style="color:green;">Нет</span>';
                        }
                        return value;
                    }
                },{
                    header: 'RSS'
                    ,dataIndex: 'rss'
                    ,editable: false
                    ,width:50
                    ,hidden: true
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:green;">Да</span>';
                        }
                        else{
                            value = '<span style="color:red;">Нет</span>';
                        }
                        return value;
                    }
                },{
                    header: 'Лента новостей'
                    ,dataIndex: 'news_list'
                    ,editable: false
                    ,hidden: true
                    ,width:50
                    ,renderer: function(value, column, record){
                        if(value == 'true' || value == '1'){
                            value = '<span style="color:green;">Да</span>';
                        }
                        else{
                            value = '<span style="color:red;">Нет</span>';
                        }
                        return value;
                    }
                },{
                    header: 'Дата публикации'
                    ,dataIndex: 'publishedon'
                    ,editable: false
                    ,renderer: function(value, column, record){
                        return record.get('publishedon_date');
                    }
                },{
                    header: 'Дата создания'
                    ,dataIndex: 'createdon_date'
                    ,editable: false
                    ,renderer: function(value, column, record){
                        return record.get('createdon_date');
                    }
                },{
                    header: 'Статус'
                    ,width: 80
                    // ,hidden: true
                    ,dataIndex: 'article_status'
                    ,sortable: true
                    ,hidden: true
                    ,renderer: function(value, column, record){
                        // console.log(value);
                        // console.log(record);
                        switch(value){
                            case '0':
                                value = 'Пишется';
                                break;
                            case '1':
                                value = '<span style="color: #e0a60b;">На редакции</span>';
                                break;
                            case '4':
                                value = 'Просрочена';
                                break;
                                
                            case '':
                            default:
                                if(record.get('hidemenu') == '1'){
                                    value = 'Скрыто';
                                }
                                else if(record.get('published') == '1'){
                                    value = '<span style="color: green;">Опубликовано</span>';
                                }
                                else if(record.get('published') != '1'){
                                    value = '<span style="color: red;">Не опубликовано</span>';
                                }
                            ;
                        }
                        return value;
                    }
                },{
                    header: 'Автор' 
                    // ,hidden: true
                    ,dataIndex: 'createdby'
                    ,sortable: true
                    ,renderer: function(value, column, record){
                        return record.get('createdby_fullname') || value;
                    }
                },{
                    header: 'Псевдоним' 
                    ,hidden: true
                    ,dataIndex: 'pseudonym'
                    ,sortable: true
                    ,editable: true
                    // ,renderer: function(value, column, record){
                    //     return record.get('createdby_fullname') || value;
                    // }
                },{
                    header: 'Статистика' 
                    // ,hidden: true
                    ,dataIndex: 'views'
                    ,sortable: true
                    ,editable: false
                    // ,renderer: function(value, column, record){
                    //     return record.get('createdby_fullname') || value;
                    // }
                }
                // ,{
                //     header: 'Цена'
                //     ,dataIndex: 'sm_price'
                //     ,renderer: function(value, cell, record){
                //         return value;
                //     }
                //     ,editable: true
                //     ,xtype: 'numbercolumn'
                // }
                // ,{
                //     header: 'Валюта'
                //     ,dataIndex: 'sm_currency'
                //     ,editable: true
                //     ,renderer: function(value, cell, record){
                //         /*
                //             Надо изменить механизм получения названия валют
                //             на выборку из готового массива всех валют.
                //         */
                //         var currency;
                //         if(currency = record.get('currency_title')){
                //             value = currency;
                //         }
                //         return value;
                //     }
                //     // ,editor: {
                //     //     xtype: 'shopmodx-combo-currencies'
                //     // }
                // }
                /*,{
                    header: 'Оптовая цена'
                    ,dataIndex: 'sm_trade_price'
                    ,renderer: function(value, cell, record){
                        return value;
                    }
                    ,editable: true
                    ,xtype: 'numbercolumn'
                }*/
            ]
            ,getCellEditor: this.getCellEditor
        });
    }
    
    ,getCellEditor: function(colIndex, rowIndex) {
        var record = this.grid.store.getAt(rowIndex);
        //console.log(record);
        //console.log(this);
        
        
        
        
        var fieldName = this.getDataIndex(colIndex);
        
        //console.log(fieldName);
        
        switch(fieldName){
            // Редактор цены
            case 'sm_price':
            case 'sm_trade_price':
                return this.grid.getPriceCellEditor(record); 
                
            case 'published':
            case 'hidemenu':
                var editor = this.grid.getBooleanCellEditor(record); 
                
                // editor.on('complete', function( editor, value, startValue ){
                //     
                //     console.log(this.grid.store);
                //     console.log(editor);
                //     console.log(value);
                //     console.log(startValue);
                //     this.grid.store.reload();
                // }, this);
                
                return editor;
                
            case 'sm_currency':
                return this.grid.getPriceCurrencyCellEditor(record);
                
                return new Ext.grid.GridEditor(o);
                // break;
        }
        
        var o = MODx.load({
            xtype: 'textfield'
            /*,listeners:{
                change: {
                    fn:function(field){
                        console.log(field);
                    }
                }
            }*/
        });
        
        return new Ext.grid.GridEditor(o);
    }
    
    /*
        Редактор колонки цены.
        Только если объект - товар, тогда только редактируем цену
    */
    ,getPriceCellEditor: function(record){
        var object_type = record.get('object_type');
        //console.log(object_type);
        
        
        if(object_type == 'product'){
            var o = MODx.load({
                xtype: 'numberfield'
                ,align: 'left'
            });
            
            return new Ext.grid.GridEditor(o);
        }
    }
    
    ,getBooleanCellEditor: function(record){
        var object_type = record.get('object_type');
        //console.log(object_type);
        
        var o = MODx.load({
            xtype: 'combo-boolean'
        });
        
        return new Ext.grid.GridEditor(o);
    }
    
    ,getPriceCurrencyCellEditor: function(record){
        var object_type = record.get('object_type');  
        
        if(object_type == 'product'){
            var o = MODx.load({
                xtype: 'shopmodx-combo-currencies'
                ,align: 'left'
                ,value: record.get('sm_currency')
            });
            
            return new Ext.grid.GridEditor(o);
        }
    }
    
    ,Search: function(field, e){
        
        if (e.getKey() != e.ENTER) {
            return true;
        }
        
        var store = this.getStore();
        var query = field.getValue();
        
        /*
            Надо будет продумать с базовыми параметрами для различных типов вывода
        */
        //this._tbar[type].baseParams = store.baseParams;
        
        // console.log(field);
        
        store.setBaseParam(field.name, query);
        store.setBaseParam('parent', null);
        
        
        for(var i in this._tbar.types){
            if(this._tbar.types[i].value == field.value){
                this._tbar.types[i].setDisabled(true);
            }
            else{
                this._tbar.types[i].setDisabled(false);
            }
        }
        
        this.getBottomToolbar().changePage(0);
        //console.log(this);
    }
    
    ,changeListType: function(button, e){
        var store = this.getStore();
        var type = button.value;
        
        /*
            Надо будет продумать с базовыми параметрами для различных типов вывода
        */
        //this._tbar[type].baseParams = store.baseParams;
        
        store.setBaseParam('listType', button.value);
        store.setBaseParam('parent', 0);
        
        
        for(var i in this._tbar.types){
            if(this._tbar.types[i].value == button.value){
                this._tbar.types[i].setDisabled(true);
            }
            else{
                this._tbar.types[i].setDisabled(false);
            }
        }
        
        this.getBottomToolbar().changePage(0);
        //console.log(this);
    }
    
    ,loadNodes: function(id, parent){
        this.showUpLevelButton(parent, "Вверх");
        
        this.getStore().setBaseParam('parent', id);
        this.getBottomToolbar().changePage(0);
    }
    
    ,showUpLevelButton: function(parent, title){
        this._tbar.upLevelButton.parent_id = parent;
        this._tbar.upLevelButton.setText(title);
        this._tbar.upLevelButton.show();
    }
    
    ,changeModelPrices: function(item, e){
        var record = this.menu.record;
        console.log(record);
        var win = new MODx.Window({
            width: 800
            ,buttons: [{
                    text: _('cancel')
                    // ,scope: this
                    ,handler: function() { win.close(); }
            }] 
            ,items: new MODx.grid.Grid({
                url: ArticlesEditor.config.connectors_url + 'model.php'
                ,height: 400
                ,autoHeight: false
                ,border: false
                ,autosave: true
                ,save_action: 'grouped/prices/update'
                ,fields: ['id', 'pagetitle', 'sm_price', 'sm_trade_price', 'parent' ]
                ,columns: new Ext.grid.ColumnModel({
                    defaults:{
                        sortable: true
                    }
                    ,columns: [
                    {
                        header: 'ID'
                        ,dataIndex: 'id'
                        ,width: 20
                    }
                    ,{
                        header: 'Название'
                        ,dataIndex: 'pagetitle'
                    }
                    ,{
                        header: 'Цена'
                        ,width: 30
                        ,dataIndex: 'sm_price'
                        ,editor: {
                            xtype: 'textfield'
                            /*,listeners:{
                                change: function( field, newValue, oldValue){
                                    //field.gridEditor.record.set('old_price', oldValue);
                                    console.log(this)
                                    console.log(field.gridEditor.record)
                                }
                            }*/
                        }
                    }
                    ,{
                        header: 'Оптовая цена'
                        ,width: 30
                        ,dataIndex: 'sm_trade_price'
                        ,editor: {
                            xtype: 'textfield'
                        }
                    }
                ]})
                ,paging: false
                ,pageSize: 0
                ,baseParams:{
                    action: 'grouped/prices/getlist'
                    ,model_id: record.id
                }
            })
        });
        
        win.show();
    }
    
    ,changeModelImage: function(item, e){
        var record = this.menu.record;
        // console.log(record);
        
        var config = {
            source: MODx.config.default_media_source
        }
        
        this.EditorGrid = new MODx.grid.Grid({
                url: ArticlesEditor.config.connectors_url + 'model.php'
                ,height: 500
                ,autoHeight: false
                ,border: false
                // ,autosave: true
                ,save_action: 'grouped/images/update'
                ,fields: [ 'parent', 'pagetitle', 'color', 'design', 'image', 'relativeUrl', 'dirname', 'source_path' , 'fullRelativeUrl']
                ,columns: new Ext.grid.ColumnModel({
                    defaults:{
                        sortable: true
                    }
                    ,columns: [
                        {
                            header: 'Картинка'
                            ,dataIndex: 'relativeUrl' 
                            ,renderer: function(value, cell, record){ 
                                if(!value){
                                    value = 'нет фото';
                                }
                                else{
                                    value = '<img width="70" src="'+ record.get('source_path') + value+'" />';
                                    //value =  record.get('source_path') + value ;
                                }
                                return value;
                            }
                            ,width: 70
                            ,editor: {
                                    xtype: 'modx-combo-browser'
                                    // ,rootId: 'products/'
                                    ,anchor: '100%'
                                    ,rootVisible: true
                                    ,browserEl: 'tvbrowser'+config.tv
                                    ,name: 'tvbrowser'+config.tv
                                    ,id: 'tvbrowser'+config.tv
                                    ,value: config.relativeValue
                                    ,hideFiles: false
                                    ,source: config.source || 1
                                    ,allowedFileTypes: config.allowedFileTypes || ''
                                    ,openTo: config.openTo || ''
                                    ,hideSourceCombo: true
                                    ,listeners: {
                                        beforerender : {
                                            fn: function( Editor, boundEl, value ){
                                                console.log(Editor);
                                                console.log('Editor');
                                                //Editor.config.rootId = this.EditorGrid.getSelectionModel().getSelected().get('dirname') || '/';
                                                Editor.config.rootId = Editor.gridEditor.record.get('dirname') || '/';
                                                console.log(Editor.config.rootId);
                                            }
                                            ,scope: this
                                        }
                                        ,'select': {fn:function(data ) {
                                            this.fireEvent('change', this, data.relativeUrl);
                                        }}
                                        ,'change': {fn:function(combobox, newValue) {
                                            var record = combobox.gridEditor.record;
                                            record.set('relativeUrl',  newValue);
                                            this.EditorGrid.saveRecord({
                                                record: record
                                            });
                                            return;
                                        },scope:this}
                                    }
                                }
                        }
                        ,{
                            header: 'Название'
                            ,dataIndex: 'pagetitle'
                            ,width: 40
                        }
                        ,{
                            header: 'Цвет'
                            ,dataIndex: 'color' 
                            ,width: 20
                        }
                        ,{
                            header: 'Исполнение'
                            ,dataIndex: 'design' 
                            ,width: 30
                        }
                    ]
                })
                ,paging: false
                ,pageSize: 0
                ,baseParams:{
                    action: 'grouped/images/getlist'
                    ,model_id: record.id
                }
            });
        
        var win = new MODx.Window({
            width: 1000
            ,buttons: [{
                    text: _('cancel')
                    // ,scope: this
                    ,handler: function() { win.close(); }
            }] 
            ,items: [this.EditorGrid]
        });
        
        
        win.show();
    }
    
                            
    ,ShowChangeImageWindow: function(record){
                            
        var image = record.get('image');
        
        // console.log(record);
        
        var window = new ArticlesEditor.window.ImageManager({ 
            value: image
            ,resource_id: record.get('id')
            ,scope: this
        });
        
        window.show();
        
        return;
    }                  
    
    // Редактируем документ
    ,editResource: function(item, e){
        var record = this.menu.record;
        
        window.open(MODx.config.manager_url + '?a='+ MODx.action['resource/update'] +'&id='+ record.id, '_blank');
    }
    
    // Открываем документ для просмотра
    ,showResource: function(item, e){
        var record = this.menu.record;
        
        window.open(MODx.config.site_url + record.uri, '_blank');
    }
    
    // Публикация ресурса
    ,publicateResource: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'published', 1);
    }
    
    
    // Снятие с публикации ресурса
    ,unpublicateResource: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        
        Ext.Msg.confirm('Подтверждение', 'Вы уверены, что хотите снять с публикации этот ресурс?<br />\
<strong>Внимание!</strong> После снятия с публикации ресурс не будет доступен никому, включая поисковые роботы! Это не хорошо с точки зрения продвижения.<br />\
Если вы просто хотите, чтобы ресурс не выводился в ленте, выполните пункт "Скрыть из ленты".', function (result){
            if(result != 'yes'){
                return;
            }
            
            this.updateRecord(record, 'published', 0);
            
            return;
        }, this);
        
        return;
    }
    
    
    
    // Выгружать в RSS
    ,setRSS: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'rss', 1);
    }
    
    // Исключить RSS
    ,unsetRSS: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'rss', 0);
    }
    
    
    // Выводить в новостную ленту
    ,setNewsList: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'news_list', 1);
    }
    
    // Исключить из новостной ленты
    ,unsetNewsList: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'news_list', 0);
    }
    
    // Скрыть ресурс
    ,hideResource: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'hidemenu', 1);
    }
    
    // Показать ресурс
    ,unhideResource: function(item, e){
        var record = this.getStore().getById(this.menu.record.id);
        return this.updateRecord(record, 'hidemenu', 0);
    }
    
    // Обновление записи
    ,updateRecord: function(record, field, value){
        if(!record){
            MODx.msg.alert("Ошибка", "Не была получена запись");
            return;
        }
        
        record.set(field, value);
        this.saveRecord({
            record: record
        });
    }
    
    // Удаление ресурса
    ,deleteResource: function(item, e){
        
        // console.log(this);
        // console.log(item);
        // console.log(e);
        // 
        // return;
        var record = this.menu.record;
        
        // var grid = this;
        
        if(!record.id){
            MODx.msg.alert("Ошибка", "Не был получен ID записи");
            return;
        }
        
        Ext.Msg.confirm('Подтверждение', 'Вы уверены, что хотите удалить этот ресурс?<br />\
<strong>Внимание!</strong> Все дочерние ресурсы также будут удалены!', function (result){
            if(result != 'yes'){
                return;
            }
            
            MODx.Ajax.request({
                // "url": ArticlesEditor.config.connector_url
                "url": MODx.config.connector_url
                ,params: {
                    action:    'resource/delete'
                    ,id: record.id
                }
                ,listeners:{
                    'success': {
                        fn: function(){
                            // console.log('success');
                            // console.log(this);
                            // console.log(this);
                            // console.log(this.store);
                            // console.log(this.store.reload());
                            this.store.reload()
                        }
                        ,scope: this
                    }
                    ,'failure': {
                        fn: function(){
                            // console.log('error');
                        }
                    }
                }
            });
            
            return;
        }, this);
        
    }
    
    
    // Сменить изображение
    ,changeImage: function(item, e){
        
        // console.log(this);
        // console.log(item);
        // console.log(e);
        // 
        // return;
        var record_data = this.menu.record;
        var record = this.getStore().getById(record_data.id);
        // console.log(record);
        
        
        // var grid = this;
        
        if(!record.id){
            MODx.msg.alert("Ошибка", "Не был получен ID записи");
            return;
        }
        
        this.ShowChangeImageWindow(record);
        
        return;
    }
    
    // Поднимаемся на уровень выше
    ,toUpLevel: function(button, e){
        this.loadNodes(button.parent_id);
    }
    
    // Создание статьи
    ,createArticle: function(a,b,c){ 
        
        var parent = this.baseParams.parent;
        var context_key = this._tbar.context_key.getValue();  
        
        var sectionsCombo = new ArticlesEditor.combo.Sections({
            width: 280  
        });
        var win = {};
        
        win = new ArticlesEditor.Window({
          'title': "Выберите рубрику"
          ,modal: true
          ,width: 300
          // ,autoWidth: true
          ,fields:[
                sectionsCombo  
            ]
          ,action: 'articles/setSection'
          ,buttons: [{
                text:   _('cancel')
                ,scope: this
                ,handler: function() { 
                    console.log(this);
                    win.close(); 
                }
            },{
                text: 'Продолжить'
                ,cls: 'primary-button'
                ,scope: this
                ,handler: function(){ 
                    var section = sectionsCombo.getValue();
                    
                    
                    
                    if(!section){
                        MODx.msg.alert("Ошибка!", "Выберите рубрику, в которую публикуется статья.");
                        return;
                    }
                    
                    // else 
                    var grid = this;
                    
                    
                    // window.open(MODx.config.manager_url + '?a='+ MODx.action['resource/create'] + '&context_key='+ context_key +'&template=2&parent='+ section, '_blank');
                    
                    this.w = {};
                    
                    window.w = this.w;
                    
                    Ext.Ajax.request({
                        url: MODx.config.manager_url + "?a=controllers/mgr/resource/create&namespace=articleseditor&template=2&parent=" + section
                        ,scope: this
                        ,success: function(r,o) {
                          // console.log('success'); 
                          w = new MODx.Window({ 
                            maximized: true
                            ,modal: true
                            ,resizable: false
                            ,closable: false
                            ,minimizable: false
                            // html: r.responseText
                            ,buttons: [{
                              text: 'Сохранить'
                              ,handler: function(){
                                p.submit();
                              }
                            },{
                              text: 'Отмена'
                              ,handler: function(){
                                w.hide();
                              }
                            }]
                            });
                            w.on('afterRender', function(){
                                this.update(r.responseText, true, function(){
                                    // console.log(this);
                                    // console.log('resource_editor');
                                    // console.log(resource_editor);
                                    
                                    /*
                                        Это чтобы в момент сохранения в объекте эдитора была
                                        возможность обратиться к гриду
                                    */
                                    resource_editor.grid = grid;
                                    
                                    // // grid
                                    // try{
                                    //     console.log(p);
                                    // }
                                    // catch(e){}
                                });
                            }, w);
                            w.on('hide', function(){
                                //MODx.unloadRTE('ta');
                                
                                // Удаляем активный редактор TinyMCE
                                var editor = tinyMCE.getInstanceById('ta');
                                if(editor){
                                    editor.remove();
                                }
                                
                                // console.log(p.destroy());
                                // p.rteLoaded = false;
                                this.destroy();
                                this.close();
                            }, w);
                          
                          
                          w.show();
                           
                          win.close();
                          
                          return true;
                        }
                        ,failure: function(r,o) {
                          MODx.msg.alert('Ошибка!', 'Ошибка выполнения запроса');
                          return false;
                        }
                      });
                    
                    
                    return;
                }
            }]
        }).show();
    }
    
    // Создание статьи
    ,createArticleOld: function(a,b,c){ 
        
        var parent = this.baseParams.parent;
        var context_key = this._tbar.context_key.getValue();  
        
        var sectionsCombo = new ArticlesEditor.combo.Sections({
            width: 280  
        });
        var win = {};
        
        win = new ArticlesEditor.Window({
          'title': "Выберите рубрику"
          ,modal: true
          ,width: 300
          // ,autoWidth: true
          ,fields:[
                sectionsCombo  
            ]
          ,action: 'articles/setSection'
          ,buttons: [{
                text:   _('cancel')
                ,scope: this
                ,handler: function() { 
                    console.log(this);
                    win.close(); 
                }
            },{
                text: 'Продолжить'
                ,cls: 'primary-button'
                ,scope: this
                ,handler: function(){ 
                    var section = sectionsCombo.getValue();
                    
                    if(!section){
                        MODx.msg.alert("Ошибка!", "Выберите рубрику, в которую публикуется статья.");
                        return;
                    }
                    
                    // else 
                    
                    win.close();
                    window.open(MODx.config.manager_url + '?a='+ MODx.action['resource/create'] + '&context_key='+ context_key +'&template=2&parent='+ section, '_blank');
                    
                    return;
                }
            }]
        }).show();
    }
    
    ,onChangeField: function(field){ 
        var store = this.store;
        store.setBaseParam(field.name, field.getValue());
        store.load();
    }
    
    
    /*
        Отправляем в редактуру
    */
    ,sendForRedacting: function(item, e){
        
        // console.log(this);
        // console.log(item);
        // console.log(e);
        // 
        // return;
        var record = this.menu.record;
        
        // var grid = this;
        
        if(!record.id){
            MODx.msg.alert("Ошибка", "Не был получен ID записи");
            return;
        }
        
        Ext.Msg.confirm('Подтверждение', 'Отправить статью в редактуру?', function (result){
            if(result != 'yes'){
                return;
            }
            
            MODx.Ajax.request({
                "url": ArticlesEditor.config.connector_url
                // "url": MODx.config.connector_url
                ,params: {
                    action:    'articles/update/status/redacting'
                    ,id: record.id
                }
                ,listeners:{
                    'success': {
                        fn: function(){
                            // console.log('success');
                            // console.log(this);
                            // console.log(this);
                            // console.log(this.store);
                            // console.log(this.store.reload());
                            this.store.reload()
                        }
                        ,scope: this
                    }
                    ,'failure': {
                        fn: function(){
                            // console.log('error');
                        }
                    }
                }
            });
            
            return;
        }, this);
        
    }
    
    /*
        Смена раздела
    */
    ,changeSection: function(item, e){
        
        // console.log(this);
        // console.log(item);
        // console.log(e);
        // 
        // return;
        var record = this.menu.record;
        
        // var grid = this;
        
        if(!record.id){
            MODx.msg.alert("Ошибка", "Не был получен ID записи");
            return;
        }
        
        var SectionsCombo = MODx.load({
            xtype: 'articleseditor-combo-sections'
            // ,fieldLabel: "Рубрика"
            ,width: 350
            ,value: record.parent
            // ,listeners:{
            //     select: {
            //         scope: this
            //         ,fn: function(field){
            //             console.log(field);
            //             console.log(this);
            //             // var store = this.store;
            //             // store.setBaseParam('parent', field.getValue());
            //             // store.load();
            //         }
            //     }
            // }
            // ,width: 200
        });
        
        var win = new MODx.Window({
            modal: true
            ,items:[
                SectionsCombo
            ]
            ,buttons: [{
                text: _('cancel')
                // ,scope: this
                ,handler: function() {
                    win.close();
                }
            },{
                text:   _('save')
                ,cls: 'primary-button'
                ,scope: this
                ,handler: function(){
                    var section_id = SectionsCombo.getValue();
                    if(!section_id){
                        MODx.msg.alert('Ошибка', "Необходимо выбрать раздел");
                        return false;
                    }
                    
                    var r = this.getStore().getById(record.id);
                    
                    // console.log(SectionsCombo);
                    // console.log(SectionsCombo.getValue());
                    // console.log(record);
                    // console.log(this);
                    // console.log(this.getStore());
                    // console.log(this.getStore().getById(record.id));
                    
                    // window.r = r;
                    
                    
                    
                    r.set('parent', section_id);
                    this.saveRecord({
                        record: r
                    });
                    
                    // console.log('sdfsdf');
                    // alert(this.saveRecord);
                    
                    win.close();
                }
            }]
        });
        
        win.on('hide', win, function(){
            this.close();
        });
        
        win.show();
        
        // Ext.Msg.confirm('Подтверждение', 'Отправить статью в редактуру?', function (result){
        //     if(result != 'yes'){
        //         return;
        //     }
        //     
        //     MODx.Ajax.request({
        //         "url": ArticlesEditor.config.connector_url
        //         // "url": MODx.config.connector_url
        //         ,params: {
        //             action:    'articles/update/status/redacting'
        //             ,id: record.id
        //         }
        //         ,listeners:{
        //             'success': {
        //                 fn: function(){
        //                     // console.log('success');
        //                     // console.log(this);
        //                     // console.log(this);
        //                     // console.log(this.store);
        //                     // console.log(this.store.reload());
        //                     this.store.reload()
        //                 }
        //                 ,scope: this
        //             }
        //             ,'failure': {
        //                 fn: function(){
        //                     // console.log('error');
        //                 }
        //             }
        //         }
        //     });
        //     
        //     return;
        // }, this);
        
    }
    
    // ,onCellDblClick: function( grid, rowIndex, columnIndex, e ){
    //     
    //     console.log(this);
    //     console.log(rowIndex);
    //     console.log(columnIndex);
    //     
    //     var view = grid.getView();
    //     var cm = grid.getColumnModel();
    //     var cell = view.getCell(rowIndex, columnIndex);
    //     
    //     console.log(cm.getColumnHeader( columnIndex ));
    //     console.log(cm.getDataIndex( columnIndex ));
    //     // console.log(view.findCellIndex(cell));
    //     // console.log(cell);
    // }
    
});
Ext.reg('articleseditor-grid-groupedit', ArticlesEditor.grid.GroupEdit);
