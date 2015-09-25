var ModxSite = function(config){
    config = config || {};
    ModxSite.superclass.constructor.call(this,config);
};
Ext.extend(ModxSite,Ext.Component,{
    config: {}
    ,tree: {}
    ,combo: {}
    ,tabs: {}
    ,panel: {}
    ,grid: {}
    ,policies:{}
    
    ,hasPermission: function(police){
        return this.policies[police] || false;
    }
});
Ext.reg('modxsite', ModxSite);

var ModxSite = new ModxSite();