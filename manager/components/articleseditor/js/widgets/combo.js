/*
    Базовый комбо-бокс
*/

ArticlesEditor.combo.Combo = function(config){
    config = config || {};
      
    Ext.applyIf(config, {
        url: ArticlesEditor.config.connector_url
        ,baseParams: {
            action: 'getList'
        }
    });
    
    ArticlesEditor.combo.Combo.superclass.constructor.call(this, config);
}

Ext.extend(ArticlesEditor.combo.Combo, MODx.combo.ComboBox, {});
Ext.reg('articleseditor-combo', ArticlesEditor.combo.Combo);


/*
    Получаем разделы для новых статей
*/
ArticlesEditor.combo.Sections = function(config){
    config = config || {};
      
    Ext.applyIf(config, {
        displayField: 'pagetitle'
        ,fields: ['id','pagetitle']
        ,name: 'section'
        ,hiddenName: 'section'
        ,emptyText: 'Выберите из списка'
        ,typeAhead: true
        ,baseParams: {
            action: 'articles/sections/getList'
        }
    });
    
    ArticlesEditor.combo.Sections.superclass.constructor.call(this, config);
}

Ext.extend(ArticlesEditor.combo.Sections, ArticlesEditor.combo.Combo, {});
Ext.reg('articleseditor-combo-sections', ArticlesEditor.combo.Sections);

/*
    Получаем авторов статей
*/
ArticlesEditor.combo.Authors = function(config){
    config = config || {};
      
    Ext.applyIf(config, {
        displayField: 'fullname'
        ,fields: ['id','fullname']
        ,name: 'author'
        ,hiddenName: 'author'
        ,typeAhead: true
        ,baseParams: {
            action: 'articles/authors/getList'
        }
    });
    
    ArticlesEditor.combo.Authors.superclass.constructor.call(this, config);
}

Ext.extend(ArticlesEditor.combo.Authors, ArticlesEditor.combo.Combo, {});
Ext.reg('articleseditor-combo-authors', ArticlesEditor.combo.Authors);

