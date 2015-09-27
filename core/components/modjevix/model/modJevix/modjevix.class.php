<?php

require_once dirname(__FILE__) . '/lib/Jevix/jevix.class.php';

class modJevix extends Jevix{
    
    public $modx;
    
    protected $view_noindex = true;
    
    function __construct(modX & $modx) {
        $this->modx = & $modx;
        
        $this->JevixConfig();
    }
    
    /**
     * Конфигурирует типограф
	 *
	 */
	protected function JevixConfig() {
		// загружаем конфиг
		$this->LoadJevixConfig();
	}
	/**
	 * Загружает конфиг Jevix'а
	 *
	 * @param string $sType Тип конфига
	 * @param bool $bClear	Очищать предыдущий конфиг или нет
	 */
	public function LoadJevixConfig($sType='default',$bClear=true) {
		if ($bClear) {
			$this->tagsRules=array();
		}
		$aConfig = $this->getConfig();
		if (is_array($aConfig)) {
			foreach ($aConfig[$sType] as $sMethod => $aExec) {
				foreach ($aExec as $aParams) {
					if (in_array(strtolower($sMethod),array_map("strtolower",array('cfgSetTagCallbackFull','cfgSetTagCallback')))) {
						if (isset($aParams[1][0]) and $aParams[1][0]=='_this_') {
							$aParams[1][0]=$this;
						}
					}
					call_user_func_array(array($this,$sMethod), $aParams);
				}
			}
		}
        
		/**
		 * Хардкодим некоторые параметры
		 */
		unset($this->entities1['&']); // разрешаем в параметрах символ &
		
        // Релы для ссылок
        if ($this->view_noindex and isset($this->tagsRules['a'])) {
			$this->cfgSetTagParamDefault('a','rel','follow',true);
		}
	}
    
    
    protected function getConfig(){
        
        $config = array(
            'default' => array( 
                
        		// Разрешённые теги
        		'cfgAllowTags' => array(
        			// вызов метода с параметрами
        			array(
        				array('p','ls','div','cut','a', 'img', 'i', 'b', 'u', 's', 'video', 'em',  'strong', 'nobr', 'li', 'ol', 'ul', 'sup', 'abbr', 'sub', 'acronym', 'h3', 'h4', 'h5', 'h6', 'br', 'hr', 'pre', 'code', 'object', 'param', 'embed', 'blockquote', 'iframe','table','th','tr','td'),
        			),			
        		),
        		// Коротие теги типа
        		'cfgSetTagShort' => array(
        			array(
        				array('br','img', 'hr', 'cut','ls')
        			),
        		),
        		// Преформатированные теги
        		'cfgSetTagPreformatted' => array(
        			array(
        				array('pre','code','video')
        			),
        		),
        		// Разрешённые параметры тегов
        		'cfgAllowTagParams' => array(
        			// вызов метода 
        			 
        			# array(
        			# 	'p',
        			# 	array('class')
        			# ),
        			# array(
        			# 	'div',
        			# 	array('class')
        			# ),
        			# array(
        			# 	'h3',
        			# 	array('class')
        			# ),
        			# array(
        			# 	'h4',
        			# 	array('class')
        			# ),
        			# array(
        			# 	'h5',
        			# 	array('class')
        			# ),
        			array(
        				'img',
        				array(
                            'src', 'alt' => '#text', 'title', 
                            'align' => array('right', 'left', 'center', 'middle'), 
                            'width' => '#int', 'height' => '#int', 'hspace' => '#int', 'vspace' => '#int', /*'class'*/
                            "style" => array(
                                "float",
                                "width",
                            ),
                        )
        			),
        			// следующий вызов метода
        			array(
        				'a',
        				array('title', 'href', 'rel' => '#text', 'name' => '#text', 'target' => array('_blank'))
        			),
        			// и т.д.
        			array(
        				'cut',
        				array('name')
        			),
        			array(
        				'object',
        				array('width' => '#int', 'height' => '#int', 'data' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')), 'type' => '#text')
        			),
        			array(
        				'param',
        				array('name' => '#text', 'value' => '#text')
        			),
        			array(
        				'embed',
        				array('src' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')), 'type' => '#text','allowscriptaccess' => '#text', 'allowfullscreen' => '#text','width' => '#int', 'height' => '#int', 'flashvars'=> '#text', 'wmode'=> '#text')
        			),
        			array(
        				'acronym',
        				array('title')
        			),
        			array(
        				'abbr',
        				array('title')
        			),
        			array(
        				'iframe',
        				array('width' => '#int', 'height' => '#int', 'src' => array('#domain'=>array('youtube.com','rutube.ru','vimeo.com')))
        			),
        			array(
        				'ls',
        				array('user'=>'#text')
        			),
        			array(
        				'td',
        				array('colspan'=>'#int','rowspan'=>'#int','align'=>array('right', 'left', 'center', 'justify'),'height'=>'#int','width'=>'#int'/*, 'class'*/)
        			),
        			array(
        				'th',
        				array('colspan'=>'#int','rowspan'=>'#int','align'=>array('right', 'left', 'center', 'justify'),'height'=>'#int','width'=>'#int'/*, 'class'*/)
        			),
        			array(
        				'table',
        				array('border'=>'#int','cellpadding'=>'#int','cellspacing'=>'#int','align'=>array('right', 'left', 'center'),'height'=>'#int','width'=>'#int'/*, 'class'*/)
        			),
        		),
        		// Параметры тегов являющиеся обязательными
        		'cfgSetTagParamsRequired' => array(
        			array(
        				'img',
        				'src'
        			),			
        		),
        		// Теги которые необходимо вырезать из текста вместе с контентом
        		'cfgSetTagCutWithContent' => array(
        			array(
        				array('script',  'style')
        			),
        		),
        		// Вложенные теги
        		'cfgSetTagChilds' => array(
        			array(
        				'ul',
        				array('li'),
        				false,
        				true
        			),
        			array(
        				'ol',
        				array('li'),
        				false,
        				true
        			),
        			array(
        				'object',
        				'param',
        				false,
        				true
        			),
        			array(
        				'object',
        				'embed',
        				false,
        				false
        			),
        			array(
        				'table',
        				array('tr'),
        				false,
        				true
        			),
        			array(
        				'tr',
        				array('td','th'),
        				false,
        				true
        			),
        		),
        		// Если нужно оставлять пустые не короткие теги
        		'cfgSetTagIsEmpty' => array(
        			array(
        				array('param','embed','a','iframe')
        			),
        		),
        		// Не нужна авто-расстановка <br>
        		'cfgSetTagNoAutoBr' => array(
        			array(
        				array('ul','ol','object','table','tr')
        			)
        		),
        		// Теги с обязательными параметрами
        		'cfgSetTagParamDefault' => array(
        			array(
        				'embed',
        				'wmode',
        				'opaque',
        				true
        			),
        		),
        		// Отключение авто-добавления <br>
        		'cfgSetAutoBrMode' => array(
        			array(
        				$this->modx->getOption('modjevix.AutoBrMode', false),
        			)
        		),
        		// Автозамена
        		'cfgSetAutoReplace' => array(
        			array(
        				array('+/-', '(c)', '(с)', '(r)', '(C)', '(С)', '(R)'),
        				array('±', '©', '©', '®', '©', '©', '®')
        			)
        		),
        		'cfgSetTagNoTypography' => array(			
        			array(
        				array('code','video','object')
        			),
        		),
        		// Теги, после которых необходимо пропускать одну пробельную строку
        		'cfgSetTagBlockType' => array(
        			array(
        				array('h4','h5','h6','ol','ul','blockquote','pre')
        			)
        		),
        		'cfgSetTagCallbackFull' => array(
        			array(
        				'ls',
        				array('_this_','CallbackTagLs'),
        			),
        		),
        	),
        	
        	// настройки для обработки текста в результатах поиска
        	'search' => array(
        		// Разрешённые теги
        		'cfgAllowTags' => array(
        			// вызов метода с параметрами
        			array(
        				array('span'),
        			),			
        		),
        		// Разрешённые параметры тегов
        		'cfgAllowTagParams' => array(
        			array(
        				'span',
        				array('class' => '#text')
        			),			
        		),
        	),
        );
        
        return $config;
    }
    

    public function parse($sText,&$aError=null) {
		$sText = $this->FlashParamParser($sText);
		$sText = parent::parse($sText,$aError);
		$sText = $this->VideoParser($sText);
		$sText = $this->CodeSourceParser($sText);
		return $sText;
	} 
    
    
    # protected function makeTag__($tag, $params, $content, $short, $parentTag = null){
    #     
    #     switch($tag){
    #         
    #         case 'a': 
    #             
    #             /*
    #                 Фиксим формирование относительных ссылок в абсолютные,
    #                 если не указан домен и слеш в корне
    #             */ 
    #             if(!empty($params['href'])){
    #                 $href = $params['href'];
    # 				if(!preg_match('/^(http|https|ftp):\/\//ui', $href) && !preg_match('/^(\/|\#)/ui', $href) && !preg_match('/^(mailto):/ui', $href) ){
    #     			    $params['href'] = '/'.$href;   
    # 				}
    #             } 
    #             break;
    #             
    #         case 'img': 
    #             
    #             /*
    #                 Фиксим формирование относительных ссылок в абсолютные,
    #                 если не указан домен и слеш в корне
    #             */ 
    #             if(!empty($params['src'])){
    #                 $src = $params['src'];
    # 				if(!preg_match('/^(http|https|ftp):\/\//ui', $src) && !preg_match('/^(\/|\#)/ui', $src)){
    #     			    $params['src'] = '/'.$src;   
    # 				}
    #             } 
    #             break;
    #         
    #         default:;
    #     }
    #     
    #     return parent::makeTag($tag, $params, $content, $short, $parentTag);
    # }
    
    /**
	 * Заменяет все вхождения короткого тега <param/> на длиную версию <param></param>
	 * Заменяет все вхождения короткого тега <embed/> на длиную версию <embed></embed>
	 *
	 * @param string $sText Исходный текст
	 * @return string
	 */
	protected function FlashParamParser($sText) {
		if (preg_match_all("@(<\s*param\s*name\s*=\s*(?:\"|').*(?:\"|')\s*value\s*=\s*(?:\"|').*(?:\"|'))\s*/?\s*>(?!</param>)@Ui",$sText,$aMatch)) {
			foreach ($aMatch[1] as $key => $str) {
				$str_new=$str.'></param>';
				$sText=str_replace($aMatch[0][$key],$str_new,$sText);
			}
		}
		if (preg_match_all("@(<\s*embed\s*.*)\s*/?\s*>(?!</embed>)@Ui",$sText,$aMatch)) {
			foreach ($aMatch[1] as $key => $str) {
				$str_new=$str.'></embed>';
				$sText=str_replace($aMatch[0][$key],$str_new,$sText);
			}
		}
		/**
		 * Удаляем все <param name="wmode" value="*"></param>
		 */
		if (preg_match_all("@(<param\s.*name=(?:\"|')wmode(?:\"|').*>\s*</param>)@Ui",$sText,$aMatch)) {
			foreach ($aMatch[1] as $key => $str) {
				$sText=str_replace($aMatch[0][$key],'',$sText);
			}
		}
		/**
		 * А теперь после <object> добавляем <param name="wmode" value="opaque"></param>
		 * Решение не фантан, но главное работает :)
		 */
		if (preg_match_all("@(<object\s.*>)@Ui",$sText,$aMatch)) {
			foreach ($aMatch[1] as $key => $str) {
				$sText=str_replace($aMatch[0][$key],$aMatch[0][$key].'<param name="wmode" value="opaque"></param>',$sText);
			}
		}
		return $sText;
	}
     
    
    /**
	 * Парсинг текста на предмет видео
	 * Находит теги <pre><video></video></pre> и реобразовываетих в видео
	 *
	 * @param string $sText	Исходный текст
	 * @return string
	 */
	public function VideoParser($sText) {
        /*
            Мое
        */ 
 
	    $sText = preg_replace('/<video>((https?:)?\/\/(?:www\.|)youtube\.com\/.*)<\/video>/Ui', '<iframe width="560" height="315" src="$1" frameborder="0" allowfullscreen></iframe>', $sText);
		
        /**
		 * youtube.com
		 */
		$sText = preg_replace('/<video>http:\/\/(?:www\.|)youtube\.com\/watch\?v=([a-zA-Z0-9_\-]+)(&.+)?<\/video>/Ui', '<iframe width="560" height="315" src="http://www.youtube.com/embed/$1" frameborder="0" allowfullscreen></iframe>', $sText);
		
     
        /**
		 * vimeo.com
		 */
		$sText = preg_replace('/<video>http:\/\/(?:www\.|)vimeo\.com\/(\d+).*<\/video>/i', '<iframe src="http://player.vimeo.com/video/$1" width="500" height="281" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>', $sText);
		/**
		 * rutube.ru
		 */
		$sText = preg_replace('/<video>http:\/\/(?:www\.|)rutube\.ru\/tracks\/(\d+)\.html.*<\/video>/Ui', '<OBJECT width="470" height="353"><PARAM name="movie" value="http://video.rutube.ru/$1"></PARAM><PARAM name="wmode" value="window"></PARAM><PARAM name="allowFullScreen" value="true"></PARAM><EMBED src="http://video.rutube.ru/$1" type="application/x-shockwave-flash" wmode="window" width="470" height="353" allowFullScreen="true" ></EMBED></OBJECT>', $sText);
		/**
		 * video.yandex.ru
		 */
		$sText = preg_replace('/<video>http:\/\/video\.yandex\.ru\/users\/([a-zA-Z0-9_\-]+)\/view\/(\d+).*<\/video>/i', '<object width="467" height="345"><param name="video" value="http://video.yandex.ru/users/$1/view/$2/get-object-by-url/redirect"></param><param name="allowFullScreen" value="true"></param><param name="scale" value="noscale"></param><embed src="http://video.yandex.ru/users/$1/view/$2/get-object-by-url/redirect" type="application/x-shockwave-flash" width="467" height="345" allowFullScreen="true" scale="noscale" ></embed></object>', $sText);
		return $sText;
	}
    
    /**
	 * Подсветка исходного кода
	 *
	 * @param string $sText Исходный текст
	 * @return mixed
	 */
	public function CodeSourceParser($sText) {
		$sText=str_replace("<code>",'<pre class="prettyprint"><code>',$sText);
		$sText=str_replace("</code>",'</code></pre>',$sText);
		return $sText;
	}
    
    
    protected function makeTag($tag, $params, $content, $short, $parentTag = null){
        
        
        
        switch($tag){
            
            case 'a': 
                
                /*
                    Фиксим формирование относительных ссылок в абсолютные,
                    если не указан домен и слеш в корне
                */ 
                if(!empty($params['href'])){
                    $href = $params['href'];
        			if(!preg_match('/^(http|https|ftp):\/\//ui', $href) && !preg_match('/^(\/|\#)/ui', $href) && !preg_match('/^(mailto):/ui', $href) ){
        			    $params['href'] = '/'.$href;   
    				}
                } 
                break;
                
            case 'img': 
                
                /*
                    Фиксим формирование относительных ссылок в абсолютные,
                    если не указан домен и слеш в корне
                */ 
                if(!empty($params['src'])){
                    $src = $params['src'];
    				if(!preg_match('/^(http|https|ftp):\/\//ui', $src) && !preg_match('/^(\/|\#)/ui', $src)){
        			    $params['src'] = '/'.$src;   
    				}
                } 
                break;
            
            default:;
        }
        
        
        
    	$this->curParentTag=$parentTag;
		$tag = mb_strtolower($tag, 'UTF-8');


        # print "\n$tag";

		// Получаем правила фильтрации тега
		$tagRules = isset($this->tagsRules[$tag]) ? $this->tagsRules[$tag] : null;

        # if($tag == "img"){
        # }
            # print "\ntagRules:";
            # print_r($tagRules);
            # 
            # print "\nparams:";
            # print_r($params);

		// Проверка - родительский тег - контейнер, содержащий только другие теги (ul, table, etc)
		$parentTagIsContainer = $parentTag && isset($this->tagsRules[$parentTag][self::TR_TAG_CONTAINER]);

		// Вырезать тег вместе с содержанием
		if($tagRules && isset($this->tagsRules[$tag][self::TR_TAG_CUT])) return '';

		// Позволен ли тег
		if(!$tagRules || empty($tagRules[self::TR_TAG_ALLOWED])) return $parentTagIsContainer ? '' : $content;

		// Если тег находится внутри другого - может ли он там находится?
		if($parentTagIsContainer){
			if(!isset($this->tagsRules[$parentTag][self::TR_TAG_CHILD_TAGS][$tag])) return '';
		}

		// Тег может находится только внтури другого тега
		if(isset($tagRules[self::TR_TAG_CHILD])){
			if(!isset($tagRules[self::TR_TAG_PARENT][$parentTag])) return $content;
		}


		$resParams = array();
		foreach($params as $param=>$value){
			$param = mb_strtolower($param, 'UTF-8');
			$value = trim($value);
			if($value == '') continue;
            
            
			// Атрибут тега разрешён? Какие возможны значения? Получаем список правил
			$paramAllowedValues = isset($tagRules[self::TR_PARAM_ALLOWED][$param]) ? $tagRules[self::TR_PARAM_ALLOWED][$param] : false;
			if(empty($paramAllowedValues)) continue;

			// Если есть список разрешённых параметров тега
			if (is_array($paramAllowedValues)) {
				// проверка на список доменов
				if (isset($paramAllowedValues['#domain']) and is_array($paramAllowedValues['#domain'])) {
					if(preg_match('/javascript:/ui', $value)) {
						$this->eror('Попытка вставить JavaScript в URI');
						continue;
					}
					$bOK=false;
					foreach ($paramAllowedValues['#domain'] as $sDomain) {
						$sDomain=preg_quote($sDomain);						
						if (preg_match("@^(http|https|ftp)://([\w\d]+\.)?{$sDomain}/@ui",$value)) {							
							$bOK=true;
							break;
						}
					}
					if (!$bOK) {
						$this->eror("Недопустимое значение для атрибута тега $tag $param=$value");
						continue;
					}
				} 
                elseif ($param == 'style' && is_array($paramAllowedValues)) {
                    # print "\nParams: {$param}";
                    # print "\nValue: {$value}";
                    # print "\nAllowed:";
                    # print_r($paramAllowedValues);
                    
                    $new_value = '';
                    $new_value_array = array();
                    
                    /*
                        Разбиваем значение на составные части
                    */
                    $style_params = explode(";", $value);
                    
                    foreach($style_params as $style_param){
                        if(
                            $style_param_array = explode(":", $style_param)
                            AND count($style_param_array == 2)
                            AND $style_param = trim(mb_strtolower($style_param_array[0], "UTF-8"))
                            AND $style_value = trim($style_param_array[1])
                            AND in_array($style_param, $paramAllowedValues)
                        ){
                            # $new_value .= implode(":", $style_param_array). ";";
                            # $new_value .= "{$style_param}:{$style_value};";
                            
                            // Если выравнивание и тег - img,
                            // добавляем маржин
                            if($tag == 'img' AND $style_param == "float"){
                                switch($style_value){
                                    case 'left':
                                        $new_value_array['margin-right'] = "5px";
                                        break;
                                    case 'right':
                                        $new_value_array['margin-left'] = "5px";
                                        break;
                                }
                            }
                            
                            if(!array_key_exists($style_param, $new_value_array)){
                                $new_value_array[$style_param] = $style_value;
                            }
                        }
                    }
                    
                    foreach($new_value_array as $f => $v){
                        $new_value .= "{$f}:{$v};";
                    }
                    
                    # print $new_value;
                    # 
                    # exit;
                    
                    $value = $new_value;
                    
					# $this->eror("Недопустимое значение для атрибута тега $tag $param=$value");
					# continue;
				}
                elseif (!in_array($value, $paramAllowedValues)) {
                    # print "\nParams: {$param}";
                    # print "\nValue: {$value}";
					$this->eror("Недопустимое значение для атрибута тега $tag $param=$value");
					continue;
				}
                
                
                
			// Если атрибут тега помечен как разрешённый, но правила не указаны - смотрим в массив стандартных правил для атрибутов
			} elseif($paramAllowedValues === true && !empty($this->defaultTagParamRules[$param])){
				$paramAllowedValues = $this->defaultTagParamRules[$param];
			}

			if(is_string($paramAllowedValues)){
				switch($paramAllowedValues){
					case '#int':
						if(!is_numeric($value)) {
							$this->eror("Недопустимое значение для атрибута тега $tag $param=$value. Ожидалось число");
							continue(2);
						}
						break;

					case '#text':
						$value = htmlspecialchars($value);
						break;

					case '#link':
						// Ява-скрипт в ссылке
						if(preg_match('/javascript:/ui', $value)) {
							$this->eror('Попытка вставить JavaScript в URI');
							continue(2);
						}
						// Первый символ должен быть a-z0-9 или #!
						if(!preg_match('/^[a-z0-9\/\#]/ui', $value)) {
							$this->eror('URI: Первый символ адреса должен быть буквой или цифрой');
							continue(2);
						}
						// HTTP в начале если нет
						if(!preg_match('/^(http|https|ftp):\/\//ui', $value) && !preg_match('/^(\/|\#)/ui', $value) && !preg_match('/^(mailto):/ui', $value) ) $value = 'http://'.$value;
                        
                        # print $value;
                        # exit;
						
                        break;

					case '#image':
						// Ява-скрипт в пути к картинке
						if(preg_match('/javascript:/ui', $value)) {
							$this->eror('Попытка вставить JavaScript в пути к изображению');
							continue(2);
						}
						// HTTP в начале если нет
						if(!preg_match('/^(http|https):\/\//ui', $value) && !preg_match('/^\//ui', $value)) $value = 'http://'.$value;
						break;

					default:
						$this->eror("Неверное описание атрибута тега в настройке Jevix: $param => $paramAllowedValues");
						continue(2);
						break;
				}
			}

			$resParams[$param] = $value;
		}

		// Проверка обязятельных параметров тега
		// Если нет обязательных параметров возвращаем только контент
		$requiredParams = isset($tagRules[self::TR_PARAM_REQUIRED]) ? array_keys($tagRules[self::TR_PARAM_REQUIRED]) : array();
		if($requiredParams){
			foreach($requiredParams as $requiredParam){
				if(!isset($resParams[$requiredParam])) return $content;
			}
		}

		// Автодобавляемые параметры
		if(!empty($tagRules[self::TR_PARAM_AUTO_ADD])){
		  foreach($tagRules[self::TR_PARAM_AUTO_ADD] as $name => $aValue) {
		      // If there isn't such attribute - setup it
		      if(!array_key_exists($name, $resParams) or ($aValue['rewrite'] and $resParams[$name] != $aValue['value'])) {
			  $resParams[$name] = $aValue['value'];
		      }
		  }
		}
		
		// Пустой некороткий тег удаляем кроме исключений
		if (!isset($tagRules[self::TR_TAG_IS_EMPTY]) or !$tagRules[self::TR_TAG_IS_EMPTY]) {
			if(!$short && $content == '') return '';
		}
		
		// Если тег обрабатывает "полным" колбеком
		if (isset($tagRules[self::TR_TAG_CALLBACK_FULL])) {
			$text = call_user_func($tagRules[self::TR_TAG_CALLBACK_FULL], $tag, $resParams);
		} else {
			// Собираем тег
			$text='<'.$tag;

			// Параметры
			foreach($resParams as $param => $value) {
				if ($value != '') {
					$text.=' '.$param.'="'.$value.'"';
				}
			}

			// Закрытие тега (если короткий то без контента)
			$text.= $short && $this->isXHTMLMode ? '/>' : '>';
			if(isset($tagRules[self::TR_TAG_CONTAINER])) $text .= "\r\n";
			if(!$short) $text.= $content.'</'.$tag.'>';
			if($parentTagIsContainer) $text .= "\r\n";
			if($tag == 'br') $text.="\r\n";
		}
		return $text;
	}
    
}

