// Ext.namespace('ModxSite.grid');


ModxSite.grid.SubscribersGrid = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title: "Управление подписками"
        ,header: true
        ,url: ModxSite.config.connector_url
        ,action: 'users/subscribers/getlist'
        ,save_action: 'users/subscribers/updatefromgrid'
        ,paging: true
        ,pageSize: 10
        ,fields: [
            'id'
            ,'username'
            ,'email'
            ,'fullname'
            ,{
                name: 'subscribe_till_str',
                type: 'date'
            }
            ,'active'
            ,'blocked'
            
        ]
        ,remoteSort: true
        ,autosave: true
        ,columns: this.getColumnModel()
        ,tbar:[{
            xtype: 'label'
            ,text: 'Поиск'
        },{
            xtype: 'textfield'
            ,name: 'query'
            ,width: 250
            ,listeners: {
                specialkey: {
                    fn: this.Search
                    ,scope: this
                }
            }
        },{
            xtype: 'label'
            ,text: 'Только активные'
        },{
            xtype: 'checkbox'
            ,name: 'active_only'
            ,listeners: {
                check: {
                    fn: function(field, checked){
                        var store = this.getStore();
                        store.setBaseParam(field.name, checked == true ? 1 : 0);
                        this.getBottomToolbar().changePage(0); 
                    }
                    ,scope: this
                }
            }
        }]
    });
     
    ModxSite.grid.SubscribersGrid.superclass.constructor.call(this, config); 
    
}

Ext.extend(ModxSite.grid.SubscribersGrid, MODx.grid.Grid,{
    
    getColumnModel: function(){
        
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
                    header: "Логин"
                    ,dataIndex: "username"
                },{
                    header: "Емейл"
                    ,dataIndex: "email"
                },{
                    header: "ФИО"
                    ,dataIndex: "fullname"
                },{
                    header: "Подписка До"
                    ,dataIndex: "subscribe_till_str"
                    ,xtype: 'datecolumn'
                    ,format: 'Y-m-d'
                    // ,renderer: function(){
                    //     return new Date();
                    // }
                    ,editable: true
                },{
                    header: "Активный"
                    ,dataIndex: "active"
                    ,renderer: function(value){
                        if(value == true){
                            value = '<span style="color:green;">Да</span>';
                        }
                        else{
                            value = '<span style="color:red;">Нет</span>';
                        }
                        return value;
                    }
                },{
                    header: "Блокирован"
                    ,dataIndex: "blocked"
                    ,renderer: function(value){
                        if(value == true){
                            value = '<span style="color:red;">Да</span>';
                        }
                        else{
                            value = '<span style="color:green;">Нет</span>';
                        }
                        return value;
                    }
                }
            ]
            ,getCellEditor: this.getCellEditor
        });
    }
    
    
    ,getCellEditor: function(colIndex, rowIndex) {
        var record = this.grid.store.getAt(rowIndex); 
         
        var fieldName = this.getDataIndex(colIndex);
        var value = record.get(fieldName);
        
        // console.log(record.get(fieldName));
        
        switch(fieldName){
            
            case 'subscribe_till_str':
                var o = MODx.load({
                    xtype: 'datefield'
                    ,format: 'Y-m-d'
                    ,minValue: new Date()
                    // ,value: value != false && typeof value != 'object' ? new Date(value * 1000) : value
                    // ,value: new Date(1429473600*1000)
                    // ,value: 'sdfsdf'
                    /*,listeners:{
                        change: {
                            fn:function(field){
                                console.log(field);
                            }
                        }
                    }*/
                });
                o.setValue('sdfsdfsdf');
                break;
            
            default:
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
            
        }
        
        
        return new Ext.grid.GridEditor(o);
    }
    
    
    ,Search: function(field, e){
        
        if (e.getKey() != e.ENTER) {
            return true;
        }
        
        var store = this.getStore();
        var query = field.getValue();
         
        
        store.setBaseParam(field.name, query); 
        
        this.getBottomToolbar().changePage(0); 
    }
});

Ext.reg('modxsite-grid-subscribersgrid', ModxSite.grid.SubscribersGrid);
 
