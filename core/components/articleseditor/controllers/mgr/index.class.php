<?php
 
require_once MODX_CORE_PATH . 'components/modxsite/controllers/mgr/media/browser.class.php';

class ArticleEditorControllersMgrManagerController extends ModxsiteMgrMediaBrowserManagerController{
    
    function __construct(modX &$modx, $config = array()) {
        parent::__construct($modx, $config);
        
        
        # $manager_url = $modx->getOption('manager_url');
        # 
        # $namespace = "articleseditor";
        # 
        # $path = "{$manager_url}components/{$namespace}/";
        # 
        # $this->config['assets'] = $modx->getOption("{$namespace}.manager_url", null, $path);
        # $this->config['connectors_url'] = $this->config['assets'].'connectors/';
        # $this->config['connector_url'] = $this->config['connectors_url'].'connector.php';
         
        # print '<pre>';
        # 
        # print_r($this->config);
        # exit;
    }
    
    # public function getOption($key, $options = null, $default = null, $skipEmpty = false){
    #     $options = array_merge($this->config, (array)$options);
    #     return $this->modx->getOption($key, $options, $default, $skipEmpty);
    # }

    public function getLanguageTopics() {
        return array('articleseditor:default');
    }

    function loadCustomCssJs(){
        parent::loadCustomCssJs();
        
        
        $manager_url = $this->modx->getOption('manager_url');
        
        $this->loadCreateResourceCssJs();
        
        
        $namespace = "articleseditor";
        
        $path = "{$manager_url}components/{$namespace}/";
        
        $config = array();
        
        $config['assets'] = $this->modx->getOption("{$namespace}.manager_url", null, $path);
        $config['connectors_url'] = $config['assets'].'connectors/';
        $config['connector_url'] = $config['connectors_url'].'connector.php';
        
        
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->modx->regClientStartupScript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        
        # $assets_url = $this->getOption('assets');
        $assets_url = $config['assets'];
        
        $this->addJavascript($assets_url.'js/core/articleseditor.js'); 
        
        $this->addHtml('<script type="text/javascript">
            ArticlesEditor.config = '. $this->modx->toJSON($config).';
        </script>');
         
        return;
    }
    
        /**
     * Register custom CSS/JS for the page
     * @return void
     */
    public function loadCreateResourceCssJs() {
        $mgrUrl = $this->modx->getOption('manager_url',null,MODX_MANAGER_URL);
        $this->addJavascript($mgrUrl.'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($mgrUrl.'assets/modext/widgets/resource/modx.panel.resource.js');
        $this->addJavascript($mgrUrl.'assets/modext/sections/resource/create.js');
        # $this->addHtml('
        # <script type="text/javascript">
        # // <![CDATA[
        # MODx.config.publish_document = "'.$this->canPublish.'";
        # MODx.onDocFormRender = "'.$this->onDocFormRender.'";
        # MODx.ctx = "'.$this->ctx.'";
        # Ext.onReady(function() {
        #     MODx.load({
        #         xtype: "modx-page-resource-create"
        #         ,record: '.$this->modx->toJSON($this->resourceArray).'
        #         ,publish_document: "'.$this->canPublish.'"
        #         ,canSave: "'.($this->modx->hasPermission('save_document') ? 1 : 0).'"
        #         ,show_tvs: '.(!empty($this->tvCounts) ? 1 : 0).'
        #         ,mode: "create"
        #     });
        # });
        # // ]]>
        # </script>');
        /* load RTE */
        # $this->loadRichTextEditor();
    }
    
    # public function getTemplatesPaths($coreOnly = false) {
    #     $paths = parent::getTemplatesPaths($coreOnly);
    #     $paths[] = $this->config['namespace_path']."templates/default/";
    #     return $paths;
    # }
    
    # public function getTemplateFile() {
    #     return 'index.tpl';
    # }
}


class ArticleseditorControllersMgrIndexManagerController extends ArticleEditorControllersMgrManagerController{
    
    public static function getInstance(modX &$modx, $className, array $config = array()) {
        # die(__CLASS__);
        $className = __CLASS__;
        return new $className($modx, $config);
    }
    
    public static function getInstanceDeprecated(modX &$modx, $className, array $config = array()) {
        return self::getInstance($modx, $className, $config);
    }
    
    public function getPageTitle() {
        # return $this->modx->lexicon('articleseditor');
        return "Редактор статей";
    }
    
    public function process(array $scriptProperties = array()) {
        
        $assets_url = $this->getOption('assets');
        
    }
    
    
    public function loadCustomCssJs() {
        
        parent::loadCustomCssJs();
        
        $namespace = "articleseditor";
        
        $manager_url = $this->modx->getOption('manager_url');
        
        $path = "{$manager_url}components/{$namespace}/";
        
        # $assets_url = $this->getOption('assets');
        $assets_url = $this->modx->getOption("{$namespace}.manager_url", null, $path);
         
        $this->addCss("{$assets_url}css/mgr.css");
        $this->addJavascript("{$assets_url}js/widgets/window.js");
        $this->addJavascript("{$assets_url}js/widgets/image_manager.js");
        $this->addJavascript("{$assets_url}js/widgets/panel.js");
        $this->addJavascript("{$assets_url}js/widgets/tabs.js");
        $this->addJavascript("{$assets_url}js/widgets/grid.js");
        $this->addJavascript("{$assets_url}js/widgets/combo.js");
        
        $this->addHtml('<script type="text/javascript">
            Ext.onReady(function(){MODx.add("articleseditor-panel-mainpanel")});
        </script>');
        
        //$this->addJavascript($this->config['jsUrl'].'group_edit.js');
        /*$this->addJavascript($this->modx->getOption('manager_url').'assets/modext/util/datetime.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/element/modx.panel.tv.renders.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/resource/modx.grid.resource.security.local.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/widgets/resource/modx.panel.resource.tv.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/resource/update.js');
        $this->addJavascript($this->modx->getOption('manager_url').'assets/modext/sections/resource/create.js');*/
        
        /*$this->addJavascript($this->config['jsUrl'].'widgets/resources.grid.js');
        $this->addJavascript($this->config['jsUrl'].'shop/widgets/resources.grid.js');
        $this->addJavascript($this->config['jsUrl'].'widgets/home.panel.js');
        $this->addJavascript($this->config['jsUrl'].'widgets/modx.panel.resource.js');
        $this->addJavascript($this->config['jsUrl'].'sections/index.js');*/
        
        
        
#         $html = <<<HTML
#         <script type="text/javascript" src="/public/components/tinymce/jscripts/tiny_mce/tiny_mce.js?v=233pl"></script>
#         <script type="text/javascript" src="/public/components/tinymce/xconfig.js?v=233pl"></script>
#         <script type="text/javascript" src="/public/components/tinymce/tiny.js?v=233pl"></script>
#         <script type="text/javascript">
#         //<![CDATA[
#         var inRevo20 = 0;MODx.source = '12';Tiny.lang = {"toggle_editor":"Toggle Editor: ","setting_base_url":"TinyMCE Document Base URL","setting_base_url_desc":"Allows the setting of a base URL property to override the document_base_url TinyMCE settings. Useful for rootrelative environments.","setting_convert_fonts_to_spans":"Convert Fonts to Spans","setting_convert_fonts_to_spans_desc":"If you set this option to true, TinyMCE will convert all font elements to span elements and generate span elements instead of font elements. This option should be used in order to get more W3C compatible code, since font elements are deprecated.","setting_convert_newlines_to_brs":"Convert Newlines to BRs","setting_convert_newlines_to_brs_desc":"If you set this option to true, newline characters codes get converted into br elements. This option is set to false by default.","setting_css_selectors":"CSS Selectors","setting_css_selectors_desc":"Here you can enter a list of selectors that should be available in the editor. Enter them as follows:<br \/>\"displayName=selectorName;displayName2=selectorName2\"<br \/>For instance, say you have <b>.mono<\/b> and <b>.smallText<\/b> selectors in your CSS file, you could add them here as:<br \/>\"Monospaced text=mono;Small text=smallText\"<br \/>Note that the last entry should not have a semi-colon after it.","setting_custom_buttons1":"Custom Buttons Row 1","setting_custom_buttons1_desc":"Enter the buttons to use as a comma separated list for the first row. Be sure that each button has the required plugin enabled in the \"Custom Plugins\" setting.","setting_custom_buttons2":"Custom Buttons Row 2","setting_custom_buttons2_desc":"Enter the buttons to use as a comma separated list for the second row. Be sure that each button has the required plugin enabled in the \"Custom Plugins\" setting.","setting_custom_buttons3":"Custom Buttons Row 3","setting_custom_buttons3_desc":"Enter the buttons to use as a comma separated list for the third row. Be sure that each button has the required plugin enabled in the \"Custom Plugins\" setting.","setting_custom_buttons4":"Custom Buttons Row 4","setting_custom_buttons4_desc":"Enter the buttons to use as a comma separated list for the fourth row. Be sure that each button has the required plugin enabled in the \"Custom Plugins\" setting.","setting_custom_buttons5":"Custom Buttons Row 5","setting_custom_buttons5_desc":"Enter the buttons to use as a comma separated list for the fifth row. Be sure that each button has the required plugin enabled in the \"Custom Plugins\" setting.","setting_custom_plugins":"Custom Plugins","setting_custom_plugins_desc":"A comma-separated list of TinyMCE plugins to use.","setting_editor_theme":"Editor Theme","setting_element_format":"Element Format","setting_element_format_desc":"This option enables control if elements should be in html or xhtml mode. xhtml is the default state for this option. This means that for example &lt;br \/&gt; will be &lt;br&gt; if you set this option to \"html\".","setting_entity_encoding":"Entity Encoding","setting_entity_encoding_desc":"This option controls how entities\/characters get processed by TinyMCE. Available values are \"named\", \"numeric\" and \"raw\".","setting_fix_nesting":"Auto-fix Nesting","setting_fix_nesting_desc":"This option controls if invalid contents should be corrected before insertion in IE. IE has a bug that produced an invalid DOM tree if the input contents are not correct so this option tries to fix this using preprocessing of the HTML string. This option is disabled by default since it might be a bit slow.","setting_fix_table_elements":"Auto-fix Table Elements","setting_fix_table_elements_desc":"This option enables you to specify that table elements should be moved outside paragraphs or other block elements. If you enable this option block elements will be split into two chunks when a table is found within it. This option is disabled by default.","setting_font_size_classes":"Font Size Classes","setting_font_size_classes_desc":"This option allows specification of a comma separated list of class names that is to be used when the user selects font sizes. This option is only used when the convert_fonts_to_spans option is enabled. This list of classes should be 7 items. This option is not used by default, but can be useful if you want to have custom classes for each font size for browser compatibility.","setting_font_size_style_values":"Font Size Style Values","setting_font_size_style_values_desc":"This option allows specification of a comma separated list of style values that is to be used when the user selects font sizes. This option is only used when the convert_fonts_to_spans option is enabled. This list of style values should be 7 items. This option is not used by default, but can be useful if you want to have custom CSS values for each font size for browser compatibility. Defaults to: xx-small,x-small,small,medium,large,x-large,xx-large.","setting_forced_root_block":"Forced Root Block","setting_forced_root_block_desc":"This option enables you to make sure that any non block elements or text nodes are wrapped in block elements. For example &lt;strong&gt;something&lt;\/strong&gt; will result in output like: &lt;p&gt;&lt;strong&gt;something&lt;\/strong&gt;&lt;\/p&gt;.","setting_indentation":"Indentation","setting_indentation_desc":"This option allows specification of the indentation level for indent\/outdent buttons in the UI. This defaults to 30px but can be any value.","setting_invalid_elements":"Invalid Elements","setting_invalid_elements_desc":"This option should contain a comma separated list of element names to exclude from the content. Elements in this list will be removed when TinyMCE executes a cleanup.","setting_nowrap":"Prevent Editor Wrap","setting_nowrap_desc":"This nowrap option enables you to control how whitespace is to be wordwrapped within the editor. This option is set to false by default, but if you enable it by setting it to true editor contents will never wrap.","setting_object_resizing":"Object Resizing","setting_object_resizing_desc":"This option gives you the ability to turn on\/off the inline resizing controls of tables and images in Firefox\/Mozilla. These are enabled by default.","setting_remove_linebreaks":"Remove Linebreaks","setting_remove_linebreaks_desc":"This option controls whether line break characters should be removed from output HTML.","setting_remove_redundant_brs":"Remove Redundant BRs","setting_remove_redundant_brs_desc":"This option is enabled by default and will control the output of trailing BR elements at the end of block elements. Since IE cannot render these properly we need to remove them by default to ensure proper output across all browsers. So for some browsers this BR at the end of the LI at the example below is redundant.","setting_removeformat_selector":"RemoveFormat Selector","setting_removeformat_selector_desc":"This option allows specification of which elements should be removed when you press the removeformat button. This is a CSS selector pattern.","setting_path_options":"Path Options","setting_path_options_desc":"Either \"rootrelative\", \"docrelative\", or \"fullpathurl\".","setting_table_inline_editing":"Table Inline Editing","setting_table_inline_editing_desc":"This option gives you the ability to turn on\/off the inline table editing controls in Firefox\/Mozilla. According to the TinyMCE documentation, these controls are somewhat buggy and not redesignable, so they are disabled by default.","setting_template_list":"Template List","setting_template_list_desc":"Specify a list of templates for the template plugin. They must be comma-separated, and then have the format name:URL:description. For example, MyTemp:assets\/templates\/mytemp.html:My very own template","setting_template_list_snippet":"Template List Snippet","setting_template_list_snippet_desc":"Enter the name of a snippet that will generate a list of templates compatible with the tiny.template_list format (name:URL:description). If a snippet is named, the results returned by the snippet call will be used rather than any value present in tiny.template_list","setting_template_selected_content_classes":"Template Selected Content Classes","setting_template_selected_content_classes_desc":"Specify a list of CSS class names for the template plugin. They must be separated by spaces. Any template element with one of the specified CSS classes will have its content replaced by the selected editor content when first inserted.","setting_theme_advanced_blockformats":"HTML Block Elements","setting_theme_advanced_blockformats_desc":"This option should contain a comma separated list of formats that will be available in the format drop down list. This option is only available if the advanced theme is used.","setting_theme_advanced_font_sizes":"Advanced Theme Font Sizes","setting_theme_advanced_font_sizes_desc":"This option should contain a comma separated list of font sizes to include. Each item in the list should be a valid value for the font-style CSS property (10px, 12pt, 1em, etc.). Example: Big text=30px,Small text=small,My Text Size=.mytextsize","setting_skin":"TinyMCE Skin","setting_skin_desc":"This option enables you to specify what skin you want to use with your theme. A skin is basically a CSS file that gets loaded from the skins directory inside the theme. The advanced theme that TinyMCE comes with has two skins, these are called \"default\" and \"o2k7\". We added another skin named \"cirkuit\" that is chosen by default.","setting_skin_variant":"TinyMCE Skin Variant","setting_skin_variant_desc":"This option enables you to specify a variant for the skin, for example \"silver\" or \"black\". \"default\" skin does not offer any variant, whereas \"o2k7\" default offers \"silver\" or \"black\" variants to the default one. For the \"cirkuit\" skin there's one variant named \"silver\". When creating a skin, additional variants may also be created, by adding ui_[variant_name].css files alongside the default ui.css."};
#         //]]>
#         </script>
#         <script type="text/javascript" src="/public/components/tinymce/tinymce.panel.js?v=233pl"></script>
#         <script type="text/javascript">
#         //<![CDATA[
#         Tiny.config = {"accessibility_warnings":true,"browserUrl":"\/manager\/index.php?a=browser","cleanup":true,"cleanup_on_startup":false,"compressor":"","content_css":"\/css\/style.css","element_list":"","entities":"","execcommand_callback":"Tiny.onExecCommand","file_browser_callback":"Tiny.loadBrowser","force_p_newlines":true,"force_br_newlines":false,"formats":{"alignleft":{"selector":"p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img","classes":"justifyleft"},"alignright":{"selector":"p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img","classes":"justifyright"},"alignfull":{"selector":"p,h1,h2,h3,h4,h5,h6,td,th,div,ul,ol,li,table,img","classes":"justifyfull"}},"frontend":false,"height":"400px","plugin_insertdate_dateFormat":"%Y-%m-%d","plugin_insertdate_timeFormat":"%H:%M:%S","preformatted":true,"resizable":true,"relative_urls":true,"remove_script_host":true,"resource_browser_path":"\/manager\/controllers\/browser\/index.php?","template_external_list_url":"\/public\/components\/tinymce\/template.list.php","theme_advanced_disable":"","theme_advanced_resizing":true,"theme_advanced_resize_horizontal":true,"theme_advanced_statusbar_location":"bottom","theme_advanced_toolbar_align":"left","theme_advanced_toolbar_location":"top","width":"95%","apply_source_formatting":true,"button_tile_map":false,"convert_fonts_to_spans":"1","convert_newlines_to_brs":"","convert_urls":true,"dialog_type":"window","directionality":"ltr","element_format":"xhtml","entity_encoding":"xhtml","force_hex_style_colors":true,"indentation":"30px","invalid_elements":"","nowrap":"","object_resizing":"1","path_options":"","remove_linebreaks":"","remove_trailing_nbsp":false,"skin":"cirkuit","skin_variant":"","table_inline_editing":"","template_selected_content_classes":"","theme_advanced_path":true,"editor":"TinyMCE","elements":["ta"],"id":0,"mode":"new","buttons1":"undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help","buttons2":"bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops","buttons3":"tablecontrols","buttons4":"","buttons5":"","css_path":"\/css\/style.css","fix_nesting":"","fix_table_elements":"","font_size_classes":"","font_size_style_values":"xx-small,x-small,small,medium,large,x-large,xx-large","forced_root_block":"p","language":"ru","plugins":"style,advimage,advlink,modxlink,searchreplace,print,contextmenu,paste,fullscreen,noneditable,nonbreaking,xhtmlxtras,visualchars,media, table","remove_redundant_brs":"1","removeformat_selector":"b,strong,em,i,span,ins","theme":"advanced","theme_advanced_blockformats":"p,h1,h2,h3,h4,h5,h6,div,blockquote,code,pre,address","theme_advanced_buttons1":"undo,redo,selectall,separator,pastetext,pasteword,separator,search,replace,separator,nonbreaking,hr,charmap,separator,image,modxlink,unlink,anchor,media,separator,cleanup,removeformat,separator,fullscreen,print,code,help","theme_advanced_buttons2":"bold,italic,underline,strikethrough,sub,sup,separator,bullist,numlist,outdent,indent,separator,justifyleft,justifycenter,justifyright,justifyfull,separator,styleselect,formatselect,separator,styleprops","theme_advanced_buttons3":"tablecontrols","theme_advanced_buttons4":"","theme_advanced_buttons5":"","theme_advanced_font_sizes":"80%,90%,100%,120%,140%,160%,180%,220%,260%,320%,400%,500%,700%","theme_advanced_styles":"","use_browser":"1","document_base_url":"\/"};
#         Tiny.config.setup = function(ed) {
#             ed.onInit.add(Tiny.onLoad);
#             ed.onKeyUp.add(Tiny.onChange);
#             ed.onChange.add(Tiny.onChange);
#         };
#         Tiny.templates = [];
#         
#         Ext.onReady(function() {
#         });
#         //]]>
#         </script>
# 
# HTML;
# 
#         
#         $this->addHtml($html);
            $scriptProperties = array();
            require_once $this->modx->getOption('tiny.core_path',null,$this->modx->getOption('core_path').'components/tinymce/').'tinymce.class.php';
            $tiny = new TinyMCE($this->modx,$scriptProperties);

            $def = $tiny->context->getOption('cultureKey',$tiny->context->getOption('manager_language','en'));
            $tiny->properties['language'] = $this->modx->getOption('fe_editor_lang',array(),$def);
            $tiny->properties['frontend'] = true;

            $tiny->setProperties($scriptProperties);
            $tiny->initialize();
        
    }
    
    
    
    
    /*
        Метод прописан в контроллере компонента ModxSite
    */
    protected function initBrowser(){
        # die(__FILE__);
        return '';
    }
}
?>
