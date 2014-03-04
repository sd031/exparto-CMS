<?php
/**
* Cms Init Content component.
*
* @author Kestutis Gliebas <kgliebas@gmail.com>
* @copyright Copyright &copy; 2013 Kestutis Gliebas
*/

class InitContentContent extends CApplicationComponent
{
  function config()
  {
    return array(
      'structure'=> array 
      (
        'name'=>Yii::t('backend','STR_STRUCTURE'),
        'sortOrder'=>100,
        'icon'=>'sitemap_color.png',
        'validChildren'=>'all',
        'isRoot'=>true,
        //'showOption'=>false,
      ),    
      'link'=> array 
      (
        'name'=>Yii::t('backend','STR_LINK'),      
        'sortOrder'=>10,
        'icon'=>'link.png',       
        'valid_children'=>'all',
      ),
      'php'=> array 
      (
        'name'=>Yii::t('backend','STR_PHP'),      
        'sortOrder'=>40,
        'icon'=>'page_white_php.png',       
        'valid_children'=>'all',
        'showOption'=>false,
      ),      
      'phptemplate'=> array 
      (
        'name'=>Yii::t('backend','STR_PHP_TEMPLATE'),      
        'sortOrder'=>41,
        'icon'=>'page_white_code.png',    
        'disabled_icon'=>false,   
        'valid_children'=>'all',
        
      ),  
      'section'=> array 
      (
        'name'=>Yii::t('backend','STR_SECTION'),
        'sortOrder'=>20,        
        'icon'=>'folder.png',       
        'valid_children'=>'all',             
      ),
      'file'=> array 
      (
        'name'=>Yii::t('backend','STR_FILE'),      
        'sortOrder'=>60,
        'icon'=>'disk.png',       
        'valid_children'=>'all',
        'showOption'=>false,
      ),               
      'seperator'=> array 
      (
        'name'=>Yii::t('backend','STR_SEPERATOR'),
        'sortOrder'=>30,
        'icon'=>'text_horizontalrule.png',   
        'valid_children'=>'none',
        'showOption'=>false,     
      ),      
      'default'=> array 
      (
        'name'=>Yii::t('backend','STR_UNKNOWN'),
        'sortOrder'=>30,
        'icon'=>'page_white_error.png',   
        'showOption'=>false,  
        'valid_children'=>'js:["default"]',          
      ),    
        
    );
  }
  
  function initSectionContentData($content)
  {
    if($content && $content->var==1)
    {
      $data=$content->published()->children()->findAll();
      return array('data'=>$data,'content'=>$content); 
    }
    else
      return array('data'=>$content); 
    return false;
  }
  
  function initPhpTemplateContentData($content)
  {
    if($content && $content->external_id>=0)
    {
      $tpl=TypePhpTemplate::model()->findByPk($content->external_id);
      if($tpl)
        return array('data'=>$tpl);
    }
    return false;
  }
  
  function deletePhpTemplateData($content)
  {
    TypePhpTemplate::model()->deleteByPk($content->external_id);
    return true;
  }  
  
  function deleteFileData($content)
  {
    $filedir=Yii::getPathOfAlias('webroot').DIRECTORY_SEPARATOR.'media'.DIRECTORY_SEPARATOR.'files'.DIRECTORY_SEPARATOR;  
    @unlink($filedir.$content->var);
    return true;
  }    
  
}