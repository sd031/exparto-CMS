<?php
/**
* Yii extension implementing jquery uploadify-v2.1.0
* 
* Uploadify is a jQuery plugin that allows the easy integration of a multiple (or single) file uploads 
* on your website.  It requires Flash and any backend development language.  An array of options allow 
* for full customization for advanced users, but basic implementation is so easy that even coding novices 
* can do it.
* 
* See : http://www.uploadify.com
* 
* I freely stole code from the great JUI extension, all errors are mine though
* 
* This widget generates an <input type='file'> tag and the relevant uploadify.js code and includes
* 
* @package EuploadifyWidget 
* @author M. Betel
* @version 0.9
* @filesource EuploadifyWidget.php
* 
*/

class EuploadifyWidget extends CInputWidget 
{
	private $body = null;
    private $options = array();
    private $callbacks = array();
    private $baseUrl; 
	
    /**
    * Valid options for uploadify
    */
    protected $validOptions = array(
        'id'            => array('type'=>'string'),    // The relative path to the uploadify.swf file.    
        'swf'            => array('type'=>'string'),    // The relative path to the uploadify.swf file.
        'langFile'       => array('type'=>'string'),    // Change to your language file path, you can translate any language file and save with your own name, just remember to use UTF-8 in language files  
        'uploader'       => array('type'=>'string'),    // The path to the backend script that will be processing your uploaded files.
        'checkScript'    => array('type'=>'string'),    // The relative path to the backend script that will check if the file selected already resides on the server.   
        'postData'       => array('type'=>'array'),    // An object containing name/value pairs of additional information you would like sent to the upload script. {’name’: ‘value’} 
        'fileDataName'   => array('type'=>'string'),    // The name of your files array in the upload server script.  Default = ‘Filedata’
        'method'         => array('type'=>'string', 'possibleValues'=>array('POST', 'GET')),    // Set the method for sending scriptData to the backend script. 
        'scriptAccess'   => array('type'=>'string'),    // The access mode for scripts in the flash file. If you are testing locally, set to ‘always’.   
        'folder'         => array('type'=>'string'),    // The path to the folder you would like to save the files to. Do not end the path with a ‘/’.     
        'queueID'        => array('type'=>'string'),    // The ID of the element you want to use as your file queue. By default, one is created on the fly below the ‘Browse’ button.
        'queueSizeLimit' => array('type'=>'integer'),   // The limit of the number of items that can be in the queue at one time. Default = 999.                            
        'multi'          => array('type'=>'boolean'),   // Set to true if you want to allow multiple file uploads.   
        'auto'           => array('type'=>'boolean'),   // Set to true if you want to allow multiple file uploads.
        'fileTypeDesc'   => array('type'=>'string'),    // The text that will appear in the file type drop down at the bottom of the browse dialog box.
        'fileTypeExts'   => array('type'=>'string'),    // A list of file extensions you would like to allow for upload. Format like ‘*.ext1;*. ext2;*.ext3′. fileDesc is required when using this option.
        'fileSizeLimit'  => array('type'=>'integer'),   // A number representing the limit in bytes for each upload. 
        'uploadLimit' => array('type'=>'integer'),   // A limit to the number of simultaneous uploads you would like to allow. Default: 1  
        'buttonText'     => array('type'=>'string'),    // The text you would like to appear on the default button. Default = ‘BROWSE’
        'hideButton'     => array('type'=>'boolean'),   // Set to true if you want to hide the button image.
        'rollover'       => array('type'=>'boolean'),   // et to true if you would like to activate rollover states for your browse button. To prepare your browse button for rollover states, simple add the ‘over’ and ‘press’ states below the normal state in a single file.  
        'width'          => array('type'=>'integer'),   // The width of the button image / flash file. Default = 30   
        'height'         => array('type'=>'integer'),   // The height of the button image / flash file. If rollover is set to true, this should be 1/3 the height of the actual file. Default = 110
        'wmode'          => array('type'=>'string'),    // Set to transparent to make the background of the flash file transparent.     
        'cancelImage'    => array('type'=>'string'),    // The path to the default cancel image. Default = ‘cancel.png’
        'progressData'   => array('type'=>'string'),    // 'percentage', 'speed' or 'all'
        'removeCompleted'=> array('type'=>'string'),    // if set to false, completed files will not be removed from queue ultil all uploads finish
        'checkExisting'  => array('type'=>'string'),    // default '' - if not set, files will be uploaded and renamed, no file will be replaced
        'requeueErrors'  => array('type'=>'boolean'),  
        'buttonClass'  => array('type'=>'string'),                                                                
      );
    
    /**
    * Valid callbacks for uploadify
    */
    protected $validCallbacks = array(
        'onDialogClose',
        'onDialogOpen',
        'onSelect',
        'onSelectError',
        'onQueueRemove',
        'onQueueComplete',
        'onUploadComplete',
        'onUploadError',
        'onUploadProgress',
        'onUploadStart',
        'onUploadSuccess',
    );
    
    /**
    * Make the code to be inserted in the view
    */
	public function run() 
	{
		$clientScript = Yii::app()->getClientScript(); 
        $dir = dirname(__FILE__);
        $this->baseUrl = Yii::app()->getAssetManager()->publish($dir);
		
        $clientScript->registerScriptFile($this->baseUrl . '/uploadify/beta/jquery.uploadify.js', CClientScript::POS_HEAD);
		    //$clientScript->registerScriptFile($this->baseUrl . '/uploadify/swfobject.js', CClientScript::POS_HEAD); 
		    $clientScript->registerCssFile($this->baseUrl . '/uploadify/beta/uploadify.css');
        
		list($name, $id) = $this->resolveNameID();
        $options = $this->makeOptions();
        $js =<<<EOP
$("#{$id}").uploadify({$options});
EOP;
		$clientScript->registerScript('Yii.'.get_class($this).'#'.$id, $js, CClientScript::POS_READY);
		
    $this->htmlOptions['id'] = $id;
		$this->htmlOptions['name'] = $id;
		$this->htmlOptions['type'] = 'file'; 
		$html = CHtml::tag('input', $this->htmlOptions, $this->body);
		echo $html;    
	}
    
    /**
    * From JUI
    * Check callbacks against valid callbacks
    * @param array $value user's callbacks
    * @param array $validCallbacks valid callbacks
    */
    protected static function checkCallbacks($value, $validCallbacks)
    {
        if (!empty($validCallbacks)) {
            foreach ($value as $key=>$val) {
                if (!in_array($key, $validCallbacks)) {
                    throw new CException(Yii::t('EUploadify', '{k} must be one of: {c}', array('{k}'=>$key, '{c}'=>implode(', ', $validCallbacks))));
                }
            }
        }
    }
    
    /**
    * From JUI extension
    * Check the options against the valid ones
    *
    * @param array $value user's options
    * @param array $validOptions valid options
    */
    protected static function checkOptions($value, $validOptions)
    {
        if (!empty($validOptions)) { 
            foreach ($value as $key=>$val) {
                
                if (!array_key_exists($key, $validOptions)) {
                    throw new CException(Yii::t('EUploadify', '{k} is not a valid option', array('{k}'=>$key)));
                }
                $type = gettype($val);    
                if ((!is_array($validOptions[$key]['type']) && ($type != $validOptions[$key]['type'])) || (is_array($validOptions[$key]['type']) && !in_array($type, $validOptions[$key]['type']))) {
                        throw new CException(Yii::t('EUploadify', '{k} must be of type {t}', 
                        array('{k}'=>$key,'{t}'=>$validOptions[$key]['type'])));
                }
                if (array_key_exists('possibleValues', $validOptions[$key])) {
                   if (!in_array($val, $validOptions[$key]['possibleValues'])) {
                        throw new CException(Yii::t('EUploadify', '{k} must be one of: {v}', array('{k}'=>$key, '{v}'=>implode(', ', $validOptions[$key]['possibleValues']))));
                   }
                }
                if (($type == 'array') && array_key_exists('elements', $validOptions[$key])) {
                        self::checkOptions($val, $validOptions[$key]['elements']);
                }
             
            }
        }
    }
   
    /**
    * Getter
    * @return array
    */
    public function getCallbacks()
    {
        return $this->callbacks;
    }
   
   /**
    * Setter
    * @param array $value callbacks
    */
    public function setCallbacks($value)
    {
        if (!is_array($value))
            throw new CException(Yii::t('EUploadify', 'callbacks must be an associative array'));
        self::checkCallbacks($value, $this->validCallbacks);
        $this->callbacks = $value;
    }
   
    /**
    * Getter
    * @return array
    */
    public function getOptions()
    {
        return $this->options;
    }
    
    /**
    * Setter
    * @param mixed $value
    */
    public function setOptions($value)
    {
        if (!is_array($value))
            throw new CException(Yii::t('EUploadify', 'options must be an array'));
        self::checkOptions($value, $this->validOptions);
        $this->options = $value;
    }

    /**
    * encode Options & Callbacks
    * @return string
    */
    protected function makeOptions()
    {
        // Set defaults
        if(!array_key_exists('swf', $this->options))
            $this->options['swf'] = $this->baseUrl . '/uploadify/beta/uploadify.swf?PHPSESSID=' . session_id();
        //if(!array_key_exists('langFile', $this->options))
        //    $this->options['langFile'] = $this->baseUrl . '/uploadify/beta/uploadifyLang_en.js';            
        if(!array_key_exists('cancelImage', $this->options))
            $this->options['cancelImage'] = $this->baseUrl . '/uploadify/beta/uploadify-cancel.png';   
        if(!array_key_exists('buttonText', $this->options))
            $this->options['buttonText'] = 'Browse';
        if(!array_key_exists('checkExisting', $this->options))
            $this->options['checkExisting'] = '';          
        if(!array_key_exists('buttonImg', $this->options))
            $this->options['buttonImg'] = '';                              
        $this->options = array_merge($this->options, $this->callbacks);
        $encodedOptions = self::encode($this->options);
        return $encodedOptions;                         
    }    
	
    /**
    * Encode an array into a javascript array
    *
    * @param array $value
    * @return string
    */
    private static function encode($value) {
        return CJavaScript::encode($value);
    }
}