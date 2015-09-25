var ArticlesEditor = function(config){
    config = config || {};
    ArticlesEditor.superclass.constructor.call(this,config);
};
Ext.extend(ArticlesEditor,Ext.Component,{
    config: {}
    ,tree: {}
    ,combo: {}
    ,tabs: {}
    ,panel: {}
    ,grid: {}
    ,page: {}
    ,'window': {}
});
Ext.reg('articleseditor',ArticlesEditor);

ArticlesEditor = new ArticlesEditor();