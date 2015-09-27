Ext.namespace('ModxSite.browser');


ModxSite.browser.View = function(config) {
    config = config || {};
    
    Ext.applyIf(config, {
        // bbar: new Ext.PagingToolbar({
        //     pageSize: config.pageSize || (parseInt(MODx.config.default_per_page) || 20)
        //     // ,store: this.getStore()
        //     ,displayInfo: true
        //     // ,items: pgItms
        // })
        
        baseParams: {
            action: 'browser/directory/getfiles'
            ,prependPath: config.prependPath || null
            ,prependUrl: config.prependUrl || null
            ,source: config.source || 1
            // @todo: this overrides the media source configuration
            ,allowedFileTypes: config.allowedFileTypes || ''
            ,wctx: config.wctx || 'web'
            ,dir: config.openTo || ''
            ,limit: config.limit || 100
        }
    });
     
    ModxSite.browser.View.superclass.constructor.call(this, config); 
    
}

Ext.extend(ModxSite.browser.View, MODx.browser.View,{
    

    // _loadStore: function(config) {
    //     this.store = new Ext.data.JsonStore({
    //         url: config.url
    //         ,baseParams: config.baseParams || {
    //             action: 'browser/directory/getList'
    //             ,wctx: config.wctx || MODx.ctx
    //             ,dir: config.openTo || ''
    //             ,source: config.source || 0
    //         }
    //         ,root: config.root || 'results'
    //         ,fields: config.fields
    //         ,totalProperty: 'total'
    //         ,listeners: {
    //             'load': {fn:function(){ this.select(0); }, scope:this, single:true}
    //         }
    //         ,remoteSort: false
    //     });
    //     
    //     console.log(this.store);
    //     
    //     this.store.load();
    // },
    
    
    /*
        Отменяем сортировку на стороне браузера
    */
    sortStore : function() {
        // console.log(this);
        
        return;
        // var v = MODx.config.modx_browser_default_sort || 'name'
        // this.store.sort(v, v == 'name' ? 'ASC' : 'DESC');
        // this.select(0);
    }
    
    
});

Ext.reg('modxsite-browser-view', ModxSite.browser.View);
 
