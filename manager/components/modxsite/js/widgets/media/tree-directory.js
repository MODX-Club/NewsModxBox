
ModxSite.tree.Directory = function(config) {
    config = config || {};
    
    Ext.applyIf(config,{
        // hideSourceCombo: true
        tbar: [{
            cls: 'x-btn-icon icon-file_upload'
            ,tooltip: {text: _('upload_files')}
            ,handler: this.uploadFiles
            ,scope: this
            ,hidden: MODx.perm.file_upload ? false : true
        }]
    });
    
    ModxSite.tree.Directory.superclass.constructor.call(this, config);
};

Ext.extend(ModxSite.tree.Directory, MODx.tree.Directory,{
    

    uploadFiles: function(btn,e) {
        // console.log(this);
        if (!this.uploader) {
            this.uploader = new MODx.util.MultiUploadDialog.Dialog({
                url: this.config.url
                ,modal: true
                ,base_params: {
                    action: 'browser/file/upload'
                    ,wctx: MODx.ctx || ''
                    ,source: this.getSource()
                }
                ,cls: 'ext-ux-uploaddialog-dialog modx-upload-window'
            });
            this.uploader.on('show',this.beforeUpload,this);
            this.uploader.on('uploadsuccess',this.uploadSuccess,this);
            this.uploader.on('uploaderror',this.uploadError,this);
            this.uploader.on('uploadfailed',this.uploadFailed,this);
        }
        this.uploader.base_params.source = this.getSource();
        this.uploader.show(btn);
    }
    
    ,getRootMenu: function() {
        var menu = [];
          

        if (MODx.perm.file_upload) {
            menu.push({
                text: _('upload_files')
                ,handler: this.uploadFiles
                ,scope: this
            });
        }

//        if (MODx.perm.file_manager) {
//            menu.push({
//                text: _('modx_browser')
//                ,handler: this.loadFileManager
//                ,scope: this
//            });
//        }

        return menu;
    }
    
});

Ext.reg('modxsite-tree-directory', ModxSite.tree.Directory);