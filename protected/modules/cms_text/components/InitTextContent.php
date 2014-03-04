<?php
class InitTextContent extends CApplicationComponent
{ 
  function config()
  {
    return array(
      'text'=> array 
      (
        'name'=>Yii::t('backend','STR_PAGE'),
        'sortOrder'=>1,
        'icon'=>'page_white.png',
      ),          
    );
  }
  
  function initTextContentData($content)
  {
    CmsModule::loadModule('cms_text');
    if($content && $content->external_id>=0)
    {
      $text=TypeText::model()->findByPk($content->external_id);
      if($text)
        return array('data'=>$text);
    }
    return false;
  }
  
  function previewTextContentData($content,$data)
  {
    CmsModule::loadModule('cms_text');
    $text= new TypeText;
    $text->attributes=$data['TypeText'];  
      
    return array('data'=>$text);
  }  
  
  function deleteTextData($content)
  {
    CmsModule::loadModule('cms_text');
    TypeText::model()->deleteByPk($content->external_id);
    return true;
  }
}