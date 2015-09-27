<?php

/*
    Создание документа
*/
 
$theme = $this->modx->getOption('manager_theme',null,'default');
$path = MODX_MANAGER_PATH . "controllers/{$theme}/";

require_once $path . "resource/create.class.php";

class ArticleseditorControllersMgrResourceCreateManagerController extends ResourceCreateManagerController{
    
    /** @var bool Set to false to prevent loading of the header HTML. */
    public $loadHeader = false;
    /** @var bool Set to false to prevent loading of the footer HTML. */
    public $loadFooter = false;
    /** @var bool Set to false to prevent loading of the base MODExt JS classes. */
    # public $loadBaseJavascript = false;
    
    
    
    public function initialize(){
        # print '<pre>';
        $this->config['controller'] = 'resource/create';
        # print_r($this->config);
        $this->modx->request->action = $this->config['controller'];
        
        return parent::initialize();
    }
     
         
        
    public function render() {
        
        # $this->loadRichTextEditor();
        
        $render = parent::render();
        
        $record = $this->modx->toJSON($this->resourceArray); 
        
        $canPublish = (int)$this->canPublish;
        $canSave = (int)($this->modx->hasPermission('save_document') ? 1 : 0);
        $show_tvs = (int)(!empty($this->tvCounts) ? 1 : 0);
        
        $rules = '';
        
        foreach($this->ruleOutput as $rule){
            $rule = preg_replace("/<script.*\{(.+)\}.*/simu", "$1", $rule);
            $rules .= "\n" . $rule;
        }
        
        $output = <<<HTML
        <script type="text/javascript">
        // <![CDATA[
        MODx.config.publish_document = "'.1.'";
        MODx.onDocFormRender = "'..'";
        MODx.ctx = "'.web.'";
        // Ext.onReady(function() {
        
            /*console.log('this');
            console.log(this);
            console.log('grid');
            console.log(grid);*/
            
            var resource_editor = MODx.load({
                xtype: "modx-panel-resource"
                ,renderTo: null
                ,record: {$record}
                ,publish_document: {$canPublish}
                ,canSave: {$canSave}
                ,show_tvs: {$show_tvs}
                ,mode: "create"
                ,url: MODx.config.connector_url + '?action=resource/create'
                ,useLoadingMask: false
                ,listeners: { 
                    'success': {fn:function(r){
                        //console.log('dfsfdf');
                        //console.log(r);
                        //console.log(this);
                        /*try {
                            r = Ext.decode(r.responseText);
                        } catch (e) {
                            Ext.MessageBox.show({
                                title: _('error')
                                ,msg: e.message+': '+ r.responseText
                                ,buttons: Ext.MessageBox.OK
                                ,cls: 'modx-js-parse-error'
                                ,minWidth: 600
                                ,maxWidth: 750
                                ,modal: false
                                ,width: 600
                            });
                            return;
                        }*/
                        
                        
                        //console.log('r');
                        //console.log(r);
                        
                        w.close();
                        try{
                            //console.log(r);
                            window.open(MODx.config.manager_url + '?a='+ MODx.action['resource/update'] + '&id=' + r.result.object.id, '_blank');
                            // sdfsdf();
                            
                            /*
                                grid передается в основном табличном редакторе
                            */
                            this.grid.store.reload();
                        }
                        catch(e){
                            MODx.msg.alert('Статус', r.result.message || 'Документ успешно сохранен');
                            setTimeout( function() {
                                window.location.reload();
                            } , 1000);
                        }
                        
                        
                        
                    }
                    //, scope: this
                    
                    }
                }
            });
            
            w.add(resource_editor);
        // });
        // ]]>
        </script>
HTML;

        $output .= $render;
         
        $output .= <<<HTML
        <script type="text/javascript">
            var p = Ext.getCmp('modx-panel-resource');
            if (p) {
                p.add({
                    xtype: 'hidden'
                    ,listeners: {
                        afterrender: function(){
                            //     console.log('rendered');
                            setTimeout( function() {
                                // alert(p.setup);
                                p.setup();
                                
                                // window.p = p;
                                
                                // MODx.loadRTE('ta');
                                {$rules}
                            } , 1000)
                        }
                    }
                });
                p.doLayout();
            }
        </script>
HTML;
        
        
        
        return $output;
    }
    
}
 