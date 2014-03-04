<?php
/**
* Cms Preload component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class PreloadCms extends CApplicationComponent 
{

    public $languages = array();
    
    
    public function init() 
    {
        //multilanguage init
    		Yii::app()->getModule('cms_content');
    		
        $def_lang=Language::getDefault(); 
        if($def_lang)
          Yii::app()->setLanguage($def_lang->lang_code);
        
        $langs=Language::getList();
    		foreach($langs as $lng)
    		  $this->languages[]=$lng['lang_code'];  
        array_push($this->languages, Yii::app()->getLanguage());
        $this->parseLanguage();

    }

    private function parseLanguage() 
    {
        Yii::app()->urlManager->parseUrl(Yii::app()->getRequest());
        if(!isset($_GET['lang'])) 
        {
            //$defaultLang = Yii::app()->getRequest()->getPreferredLanguage();
            //if (in_array($defaultLang, $this->languages))
            //{
            //    Yii::app()->setLanguage($defaultLang);
            //}else{
                Yii::app()->setLanguage($this->languages[0]);
            //}
        }
        elseif($_GET['lang']!=Yii::app()->getLanguage() && in_array($_GET['lang'],$this->languages))
        {
            Yii::app()->setLanguage($_GET['lang']);
        }
 
    }
}
?>