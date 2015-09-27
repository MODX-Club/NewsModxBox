<?php

require_once dirname(dirname(dirname(__FILE__))) . '/_validator.class.php';

class modWebSocietyTopicsValidator extends modWebValidator{
    
    
    public function validate(){
        
        $topic = & $this->object;
        # $attributes = & $topic->Attributes;
        
        /*foreach( $this->object->TopicBlogs as $o){
            print_r($o->Blog->toArray());
        }
        
        exit;*/
        
        $topic->pagetitle = strip_tags($topic->pagetitle);
        $topic->longtitle = strip_tags($topic->longtitle);
        
        
        if(!$topic->CreatedBy){
            return "Не был получен объект пользователя";
        }
         
        // Проверяем блоги топика
        # if(!$this->object->TopicBlogs){
        #     return "Не был указан ни один блог";
        # }
        
        // Иначе проверяем права на блог
        # foreach($this->object->TopicBlogs as $TopicBlog){
        #     
        #     $blog = $TopicBlog->Blog;
        #     
        #     //print_r($TopicBlog->toArray());
        #     
        #     if(!$blog instanceof SocietyBlog){
        #         return "Публиковать топики можно только в блоги";
        #     }
        #     
        #     $ok = $this->checkBlogAccess($blog);
        #     
        #     if($ok !== true){
        #         return $ok;
        #     }
        # }
        
         
        // Проверяем теги
        // Если есть, проверяем на наличие хотя бы одного активного
        # $topic_tags = array();
        # if($this->object->Tags){
        #     foreach($this->object->Tags as $tag){
        #         # print_r($tag->toArray());
        #         if($tag->active){
        #             $topic_tags[] = $tag->tag;
        #         }
        #     }
        # }
        # if(!$topic_tags){
        #     # $error = "Не указан ни один тег";
        #     # $error = $this->modx->lexicon('topic_post.error.type_topic_tags');
        #     # $this->addFieldError('topic_tags', $error);
        #     # return $error;
        # }
        # else{
        #     // Иначе сохраняем активные теги в топик
        #     $attributes->topic_tags = implode(",", $topic_tags);
        #     // print $this->object->topic_tags;
        # }
        
        
        // Режем контент
        # $content = $topic->content;
        
        # $attributes->raw_content = $content;
        
        # $content = str_replace(array(
        #     "<?"
        # ), array(
        #     "&lt;"
        # ), $content);
        # 
        # $content = strip_tags($content, '<strong><composite><composite><model><object><field><code><pre><cut><p><a><h4><h5><h6><img><b><em><i><s><u><hr><blockquote><table><tr><th><td><ul><li><ol>');
        # 
        # 
        # // Реплейсим переносы
        # $content = preg_replace("/[\r\n]{3,}/", "<br /><br />", $content);
        # $content = preg_replace("/\r/", "<br />", $content);
        # 
        # $content = preg_replace('/<code>(.+?)<\/code>/sim', "<pre class=\"prettyprint\"><code>$1</code></pre>", $content);
        
        
        # $jevix = $this->modx->getService('modJevix','modJevix', MODX_CORE_PATH . 'components/modjevix/model/modJevix/');
        # 
        # if(
        #     $this->modx->hasPermission('modxclub.post_indexed_links')
        #     AND $this->getProperty('links_follow')
        # ){
        #     $rel = "follow";
        # }
        # else{
        #     $rel = "nofollow";
        # } 
        # 
        # $jevix->cfgSetTagParamDefault('a','rel',$rel,true);
        # 
        # $errors = '';
        # $content = $jevix->parse($content, $errors);
         
         
        if(!$topic->content){
            return "Не заполнено содержимое публикации";
        }
        
        
        # $content_cut = explode("<cut>", $content, 2);
        # $short_text = $content_cut[0];
        # $attributes->short_text = $short_text;
        
        
        /**
		 * Получаемый и устанавливаем разрезанный текст по тегу <cut>
		 */
		# list($sTextShort,$sTextNew,$sTextCut) = $this->Cut($content);

        
		# $oTopic->setCutText($sTextCut);
		# $oTopic->setText($this->Text_Parser($sTextNew));
		# $oTopic->setTextShort($this->Text_Parser($sTextShort));
        
        
        # exit;
        
        # $topic->content = $sTextNew;
        # $attributes->short_text = $sTextShort;
        
        # print $sTextNew;
        # 
        # exit;
        
        return parent::validate();
    }
    
    /**
     * Производить резрезание текста по тегу cut.
	 * Возвращаем массив вида:
	 * <pre>
	 * array(
	 * 		$sTextShort - текст до тега <cut>
	 * 		$sTextNew   - весь текст за исключением удаленного тега
	 * 		$sTextCut   - именованное значение <cut>
	 * )
	 * </pre>
	 *
	 * @param  string $sText Исходный текст
	 * @return array
	 */
	public function Cut($sText) {
		$sTextShort = $sText;
		$sTextNew   = $sText;
		$sTextCut   = null;

		$sTextTemp=str_replace("\r\n",'[<rn>]',$sText);
		$sTextTemp=str_replace("\n",'[<n>]',$sTextTemp);

		if (preg_match("/^(.*)<cut(.*)>(.*)$/Ui",$sTextTemp,$aMatch)) {
			$aMatch[1]=str_replace('[<rn>]',"\r\n",$aMatch[1]);
			$aMatch[1]=str_replace('[<n>]',"\r\n",$aMatch[1]);
			$aMatch[3]=str_replace('[<rn>]',"\r\n",$aMatch[3]);
			$aMatch[3]=str_replace('[<n>]',"\r\n",$aMatch[3]);
			$sTextShort=$aMatch[1];
			$sTextNew=$aMatch[1].' <a name="cut"></a> '.$aMatch[3];
			if (preg_match('/^\s*name\s*=\s*"(.+)"\s*\/?$/Ui',$aMatch[2],$aMatchCut)) {
				$sTextCut=trim($aMatchCut[1]);
			}
		}

		return array($sTextShort,$sTextNew,$sTextCut ? htmlspecialchars($sTextCut) : null);
	}
    
    public function checkBlogAccess($blog){
        
        if(!$blog->checkPolicy('society_topic_resource_create')){
            return "У вас нет прав писать в блог {$blog->pagetitle}";
        }
        
        return true;
    }
    
}

return 'modWebSocietyTopicsValidator';