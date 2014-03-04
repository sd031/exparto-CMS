<?php
class InitGalleryContent extends CApplicationComponent
{ 
  function config()
  {
    return array(
      'gallery'=> array 
      (
        'name'=>Yii::t('backend','STR_GALLERY'),
        'sortOrder'=>5,
        'icon'=>'images.png',
      ),          
    );
  }
  
  function initGalleryContentData($content)
  {
    CmsModule::loadModule('cms_gallery');
    if($content)
    {
      $gallery=TypeGallery::model()->findAll(array('order'=>'pos asc,t.rec_created DESC','condition'=>'content_id='.$content->id));
      if($gallery)
        return array('data'=>$gallery);
    } 
    return false;
  }
  
  function deleteGalleryData($content)
  {
    CmsModule::loadModule('cms_gallery');
    TypeGallery::model()->deleteAll('content_id=?',array($content->id));
    return true;    
  }
}