/*
    Общая панель-заготовка для панелей с заголовками
*/
ArticlesEditor.panel.Panel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        border: false
        ,baseCls: 'modx-formpanel container'
        ,items: []
    });
    
    if(config.paneltitle){
        config.items.splice(0,0,{
            html: '<h2>'+config.paneltitle+'</h2>'
            ,border: false
            ,cls: 'modx-page-header'
        });
    }
    
    ArticlesEditor.panel.Panel.superclass.constructor.call(this, config);
};

Ext.extend(ArticlesEditor.panel.Panel, MODx.Panel, {});
Ext.reg('articleseditor-panel-panel', ArticlesEditor.panel.Panel);


/*
    Главная панель для группового редактора
*/
ArticlesEditor.panel.MainPanel = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        paneltitle: 'Редактор статей'
        ,items:[
            {
                xtype: 'articleseditor-tabs-groupedit'
            }
        ]
    });
    
    ArticlesEditor.panel.MainPanel.superclass.constructor.call(this, config);
};

Ext.extend(ArticlesEditor.panel.MainPanel, ArticlesEditor.panel.Panel, {});
Ext.reg('articleseditor-panel-mainpanel', ArticlesEditor.panel.MainPanel);