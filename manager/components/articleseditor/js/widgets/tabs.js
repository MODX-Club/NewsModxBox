/*
    Таб-панель для группового редактора
*/
ArticlesEditor.tabs.GroupEdit = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        bodyStyle: 'padding: 10px'
        ,border: true
        ,items: [
            {
                xtype: 'articleseditor-grid-groupedit'
            }    
        ]
    });
    
    ArticlesEditor.tabs.GroupEdit.superclass.constructor.call(this, config);
};

Ext.extend(ArticlesEditor.tabs.GroupEdit, MODx.Tabs, {});
Ext.reg('articleseditor-tabs-groupedit', ArticlesEditor.tabs.GroupEdit);