ModxSite.Media = function(config) {
    config = config || {};
     
    // 
    // ModxSite.Media.superclass.constructor.call(this,config);
    
    
    
    
    
    // console.log(this);
    // console.log(this.tree);
    // this.tree.baseParams.action = 'media/browser/directory/getlist';
    // this.tree.config.url = ModxSite.config.connector_url;
    // this.view.store.proxy.api.read.url = ModxSite.config.connector_url;
    
    
    config = config || {};
    
    Ext.applyIf(config, {
        url: ModxSite.config.connectors_url + 'media/connector.php'
        ,onSelect: MODx.onBrowserReturn || config.onSelect
    });
    
    this.ident = config.ident || Ext.id();

    MODx.browserOpen = true;
    
    // DataView
    this.view = MODx.load({
        xtype: 'modxsite-browser-view'
        ,source: config.source || MODx.config.default_media_source
        ,allowedFileTypes: config.allowedFileTypes || ''
        ,wctx: config.wctx || 'web'
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,id: this.ident+'-view'
        ,url: config.url
        ,remoteSort: true
        ,onSelect: {fn: this.onSelect, scope: this}
        // ,onSelect: MODx.onBrowserReturn || config.onSelect
    });

    // Hide the "new window" toolbar button
    MODx.browserOpen = true;
    // Tree navigation
    this.tree = MODx.load({
        xtype: 'modxsite-tree-directory'
        ,url: config.url
        ,onUpload: function() {
            this.view.run();
        }
        ,scope: this
        ,source: config.source || MODx.config.default_media_source
        ,hideFiles: config.hideFiles || true
        ,openTo: config.openTo || ''
        ,ident: this.ident
        ,rootId: config.rootId || '/'
        ,rootName: _('files')
        ,rootVisible: config.rootVisible == undefined || !Ext.isEmpty(config.rootId)
        ,id: this.ident+'-tree'
        ,hideSourceCombo: config.hideSourceCombo || true
        ,useDefaultToolbar: false
        ,listeners: {
            afterUpload: {
                fn: function() {
                    this.view.run();
                }
                ,scope: this
            }
            ,changeSource: {
                fn: function(s) {
                    this.config.source = s;
                    this.view.config.source = s;
                    this.view.baseParams.source = s;
                    this.view.dir = '/';
                    this.view.run();
                }
                ,scope: this
            }
            ,nodeclick: {
                fn: function(n, e) {
                    n.select();
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                ,scope: this
            }
            ,afterrender: {
                fn: function(tree) {
                    tree.root.expand();
                }
                ,scope: this
            }
        }
    });
    this.tree.on('click', function(node, e) {
        console.log(this);
        this.load(node.id);
    }, this);

    Ext.applyIf(config, {
        cls: 'modx-browser container'
        ,layout: 'border'
        ,width: '98%'
        ,height: '95%'
        ,items: [{
            region: 'west'
            ,width: 250
            ,items: this.tree
            ,id: this.ident+'-browser-tree'
            ,cls: 'modx-browser-tree shadowbox'
            ,autoScroll: true
            ,split: true
        },{
            region: 'center'
            ,layout: 'fit'
            // ,items: [{
            //     xtype: 'panel'
            //     ,items: this.view
            // }]
            ,items: this.view
            ,id: this.ident+'-browser-view'
            ,cls: 'modx-browser-view-ct'
            ,autoScroll: true
            ,border: false
            ,tbar: this.getToolbar()
            ,bbar: this.getBottombar()
        },{
            region: 'east'
            ,width: 250
            ,id: this.ident+'-img-detail-panel'
            ,cls: 'modx-browser-details-ct'
            ,split: true
            //,collapsed: true
        }]
    });
    // MODx.Media.superclass.constructor.call(this, config);
    MODx.Media.superclass.constructor.call(this, config);
    this.config = config;
    
}

Ext.extend(ModxSite.Media, MODx.Media,{
    
    
    
    onSelect: function(data) {
        console.log('onSelect');
        // console.log(this);
        var selNode = this.view.getSelectedNodes()[0];
        var callback = this.config.onSelect || this.onSelectHandler;
        var lookup = this.view.lookup;
        var scope = this.config.scope;
        
        
        // alert(selNode);
        // alert(callback);
        // alert(scope);
        // alert(data);
        
        // console.log(selNode);
        // console.log(callback);
        // console.log(lookup);
        // console.log(scope);
        // console.log(data);
        
        
        if(selNode && callback) {
            data = lookup[selNode.id];
            
            // console.log('data');
            // console.log(data);
            
            Ext.callback(callback,scope || this,[data]);
            this.fireEvent('select',data);
            if (window.top.opener) {
                window.top.close();
                window.top.opener.focus();
            }
        }
        
        // console.log('onSelect');
        // alert(this);
    }
    
    
    ,setReturn: function(el) {
        this.returnEl = el;
    }
    
    ,onSelectHandler: function(data) {
        console.log('onSelectHandler');
        Ext.get(this.returnEl).dom.value = unescape(data.url);
    }
    
    ,getToolbar: function() {
        return [{
            text: _('filter')+':'
            ,xtype: 'label'
        },{
            xtype: 'textfield'
            ,id: this.ident+'filter'
            ,selectOnFocus: true
            ,width: 200
            ,name: 'query'
            ,listeners: {
                render: {
                    fn: function(field){
                        Ext.getCmp(this.ident+'filter').getEl().on('keyup', function() {
                            this.filter(field);
                        }, this, {buffer: 500});
                    }
                    ,scope: this
                }
            }
        },{
            text: _('sort_by')+':'
            ,xtype: 'label'
        },{
            id: this.ident+'sortSelect'
            ,xtype: 'combo'
            ,typeAhead: true
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'name'
            ,name: 'sort'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_sort || 'name'
            ,store: new Ext.data.SimpleStore({
                fields: ['name', 'desc'],
                data : [
                    ['name', _('name')]
                    ,['size', _('file_size')]
                    ,['lastmod', _('last_modified')]
                ]
            })
            ,listeners: {
                select: {
                    fn: this.sortStore
                    ,scope: this
                }
            }
        }, '-', {
            text: _('files_viewmode')+':'
            ,xtype: 'label'
        }, '-', {
            id: this.ident+'viewSelect'
            ,xtype: 'combo'
            ,typeAhead: false
            ,triggerAction: 'all'
            ,width: 100
            ,editable: false
            ,mode: 'local'
            ,displayField: 'desc'
            ,valueField: 'type'
            ,lazyInit: false
            ,value: MODx.config.modx_browser_default_viewmode || 'grid'
            ,store: new Ext.data.SimpleStore({
                fields: ['type', 'desc'],
                data : [['grid', _('files_viewmode_grid')],['list', _('files_viewmode_list')]]
            })
            ,listeners: {
                'select': {fn:this.changeViewmode, scope:this}
            }
        }, '-', {
            text: 'Только мои:'
            ,xtype: 'label'
        }, '-', {
            xtype: 'checkbox'
            ,name: 'own_only'
            ,listeners:{
                check: {
                    scope: this
                    ,fn: function(field, checked){
                        // console.log(this);
                        // console.log(field);
                        // console.log(a);
                        // console.log(b);
                        var store = this.getStore();
                        store.setBaseParam(field.name, checked == true ? 1 : null);
                        store.load();
                    }
                }
            }
        }];
    }
    
    ,getBottombar: function(){
        var store = this.getStore();
        return new Ext.PagingToolbar({
            pageSize: store.baseParams.limit || 20
            ,store: store
            ,displayInfo: true
            // ,items: pgItms
        });
    }
    
    ,getStore: function(){
        return this.view.getStore();
    }
    
    ,filter : function(field) {
        
        this.view.store.setBaseParam(field.name, field.getValue());
        
        this.view.store.load();
        
        console.log(this);
        console.log(field);
        
        return;
        // var filter = Ext.getCmp(this.ident+'filter');
        // this.view.store.filter('name', filter.getValue(), true);
        // this.view.select(0);
    }
    
    ,sortStore: function(field){
        
        /*
            Оставлю, пока не разберусь где еще вызывается, где не передается элемент
        */
        console.log(field);
        
        
        if(field){
            this.view.store.setBaseParam(field.name, field.getValue());
            this.view.store.load();
        }
         
        
        console.log(this.view.store);
        
        return;

            var v = Ext.getCmp(this.ident+'sortSelect').getValue();
        // this.view.store.sort(v, v == 'name' ? 'asc' : 'desc');
        // this.view.select(0);
        this.view.store.load();
    }
    
});

Ext.reg('modxsite-media-view', ModxSite.Media);
