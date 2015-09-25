/*
    
*/
ArticlesEditor.Window = function(config){
    config = config || {};
    
    Ext.applyIf(config, {
        url: ArticlesEditor.config.connector_url
    });
    
   ArticlesEditor.Window.superclass.constructor.call(this, config);
};

Ext.extend(ArticlesEditor.Window, MODx.Window, {});
Ext.reg('articleseditor-window', ArticlesEditor.Window);
 
